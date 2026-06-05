<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// окно входа — первое, что видит пользователь
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::get('/guest', [AuthController::class, 'guest'])->name('guest');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// список товаров доступен всем, включая гостя
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// добавление/редактирование/удаление товаров — только администратор
Route::middleware('role:Администратор')->group(function () {
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

// просмотр заказов — менеджер и администратор
Route::middleware('role:Менеджер,Администратор')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});

// добавление/редактирование/удаление заказов — только администратор
Route::middleware('role:Администратор')->group(function () {
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
});

