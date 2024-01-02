<?php

namespace App\Http\Controllers\Site;

use App\Events\ProductPurchased;
use App\Http\Controllers\Controller;
use App\Jobs\SendNewProductEmail;
use App\Models\Admin\Product\Category;
use App\Models\Admin\Product\Product;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    public function purchase($id)
    {
        $product = Product::find($id);
        event(new ProductPurchased($product));
        $quantity = Product::where('id', $id)->select('quantity')->first();
        return response()->json($quantity);
    }

    public function import(Request $request)
    {
        $errorData = [];
        try {
            if ($request->hasFile('file')) {
                $path = $request->file('file')->getRealPath();
                $rows = file($path, FILE_IGNORE_NEW_LINES);
                $objProduct = '';
                $successImport = 0;
                $failedImport = 0;
                foreach ($rows as $index => $row) {
                    $product = new Product();
                    try {
                        $data = explode(',', $row);
                        if ($index > 0) {

                            $product->name = $data[0];
                            $product->price = $data[1];
                            $product->quantity = $data[2];

                            $category = Category::where('name', $data[3])->select('id')->first();

                            if ($category != null) {
                                $product->category_id = $category->id;
                            } else {
                                $cat = Category::create([
                                    'name' => $data[3]
                                ]);
                                $product->category_id = $cat->id;
                            }

                            $product->save();
                            $successImport += 1;
                            
                            //SendNewProductEmail::dispatch($product)->delay(now()->addSeconds(3));
                        }
                    } catch (Exception $ex) {
                        
                        $objProduct = $product;
                        $failedImport += 1;
                        $errorData[] = $objProduct;
                    }
                    
                }
                
                $rtr = [
                    'status' => '1',
                    'message' => 'Successfully imported ' . $successImport .' records'
                    //. ' records. Failed records is:' . $failedImport,
                    //'failedImportData' => $errorData
                ];

                return view('dashboard', compact('rtr'));
            }
        } catch (Exception $ex) {
            $rtr = [
                'status' => '501',
                'message' => 'CSV file imported failed.',
            ];

            return view('dashboard', compact('rtr'));
        }
    }
    public function export()
    {
        try {
            $products = Product::with('category')->get();
       // Eager load the 'category' relationship
            if ($products->count()>0) {
                $data = $products->map(function ($product) {
                    return [
                        'name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $product->quantity,
                        'category_id' => $product->category->name ?? '', // Access the category's name
                    ];
                });
                $csvFileName = 'products' . date('Y-m-d') . '.csv';

                // Set headers for CSV response
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
                ];
               
                // Create a response object with headers
                $response = new Response();

                // Set headers for the response
                foreach ($headers as $key => $value) {
                    $response->header($key, $value);
                }

                $output = fopen('php://output', 'w');

                // Write headers to the CSV file
                fputcsv($output, array_keys($data->first()));

                // Write data rows to the CSV file
                foreach ($data as $row) {
                    fputcsv($output, $row);
                }

                fclose($output);
                return $response;
                
            }else  {
          
             
                // Returning JSON response
                return response()->json(['error' => 'No products found'], 500)
                                // Printing error within a tag
                                ->setContent('<p>Error: ' . 'No products found' . '</p>');
            };
        } catch (Exception $e) {
          
            $errorMessage = $e->getMessage();
            // Returning JSON response
            return response()->json(['error' => $errorMessage], 500)
                            // Printing error within a tag
                            ->setContent('<p>Error: ' . $errorMessage . '</p>');
        }
    }
}
