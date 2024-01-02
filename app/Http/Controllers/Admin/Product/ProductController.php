<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Jobs\SendNewProductEmail;
use App\Models\Admin\Product\Category;
use App\Models\Admin\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkAdmin');
    }
    public function index()
    {
        $categories = Category::select('id', 'name')->get();
        $productLists = Product::join('categories', 'categories.id', 'products.category_id')
            ->select(
                'categories.id AS category_id',
                'categories.name AS category_name',
                'products.id AS product_id',
                'products.name AS product_name',
                'products.price',
                'products.quantity'
            )->paginate(10);

        return view('admin.product.product', compact('productLists', 'categories'));
    }

    public function store(Request $data)
    {


        $validator = Validator::make($data->all(), [
            'name' => ['required', 'string', 'max:200'],
            'category_id' => ['required'],
            'price' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric'],
        ]);
        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        $product = Product::create([
            'name' => $data->name,
            'category_id' => $data->category_id,
            'price' => $data->price,
            'quantity' => $data->quantity
        ]);

        SendNewProductEmail::dispatch($product);

        return back()->with('success', 'Successfully Saved.');
    }

    public function getProductInfo($id)
    {

        $productInfo = Product::join('categories', 'categories.id', 'products.category_id')->where('products.id', $id)
            ->select(
                'categories.id AS category_id',
                'categories.name AS category_name',
                'products.id',
                'products.name',
                'products.price',
                'products.quantity'
            )
            ->first();

        return response()->json($productInfo);
    }

    public function updateProductInfo(Request $request)
    {
        $productInfo = Product::find($request->input('id'));
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:200'],
            'category_id' => ['required'],
            'price' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric'],
        ]);
        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        $productInfo->name = $request->input('name');
        $productInfo->category_id = $request->input('category_id');
        $productInfo->price = $request->input('price');
        $productInfo->quantity = $request->input('quantity');
        $productInfo->save();

        return response()->json(['success' => 'Product info updated successfully']);
    }

    public function deleteProduct($id)
    {
        Product::where('id', $id)->delete();

        return response()->json(['success' => 'Product deleted successfully']);
    }
}
