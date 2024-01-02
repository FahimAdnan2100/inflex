<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['checkAdmin']], function () {
    Route::get('/product', [App\Http\Controllers\Admin\Product\ProductController::class, 'index'])->name('product');
    Route::post('/product-store', [App\Http\Controllers\Admin\Product\ProductController::class, 'store'])->name('product.store');
    Route::get('/get-product-info/{id}', [App\Http\Controllers\Admin\Product\ProductController::class, 'getProductInfo'])->name('get.product.info');
    Route::post('/update-product-info', [App\Http\Controllers\Admin\Product\ProductController::class, 'updateProductInfo'])->name('update.product.info');
    Route::get('/delete-product/{id}', [App\Http\Controllers\Admin\Product\ProductController::class, 'deleteProduct'])->name('delete.product');
    Route::get('/category-dropdown', [App\Http\Controllers\Admin\Product\ProductController::class, 'categoryDropdown'])->name('category.dropdown');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified','shareUserInfo'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/purchase/{id}', [App\Http\Controllers\Site\DashboardController::class, 'purchase'])->name('purchase');


// web.php

Route::post('/import', [App\Http\Controllers\Site\DashboardController::class, 'import'])->name('import.store');
Route::get('/export', [App\Http\Controllers\Site\DashboardController::class, 'export'])->name('export.product');

require __DIR__ . '/auth.php';
