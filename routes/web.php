<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoriesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get("author", [AuthorController::class, "index"])->name("authors");
Route::post("author-save", [AuthorController::class, "save"])->name("authors.store");
Route::delete("author-delete/{id}", [AuthorController::class, "delete"])->name("authors.destroy");
Route::get("author-detail/{id}", [AuthorController::class, "detail"])->name("authors.detail");
Route::post("author-update", [AuthorController::class, "update"])->name("authors.edit");

Route::get("categories", [CategoriesController::class, "index"])->name("categories");
Route::post("categories-save", [CategoriesController::class, "save"])->name("categories.store");
Route::get("categories-detail/{id}", [CategoriesController::class, "detail"])->name("categories.detail");
Route::post("categories-update", [CategoriesController::class, "update"])->name("categories.edit");
Route::delete("categories-delete/{id}", [CategoriesController::class, "delete"])->name("categories.destroy");

Route::get("books", [BookController::class, "index"])->name("books");
Route::delete("books-delete/{id}", [BookController::class, "delete"])->name("books.destroy");
Route::post("books-save", [BookController::class, "save"])->name("books.store");
Route::get("books-detail/{id}", [BookController::class, "detail"])->name("books.detail");
Route::post("books-update", [BookController::class, "update"])->name("books.edit");


Route::get("download-books/{id}", [BookController::class, "download"])->name("books.download");