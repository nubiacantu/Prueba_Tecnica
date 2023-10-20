<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PdfController;
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

Route::get('/',[PdfController::class,'index']);


Route::get('/leer-pdf',[PdfController::class,'index'])->name('pdf.index');
Route::post('/guardar-pdf',[PdfController::class,'store'])->name('pdf.store');
Route::get('/ver-pdf',[PdfController::class,'ver_red'])->name('pdf.ver');
Route::get('/eliminar-pdf/{nombreArchivo}',[PdfController::class,'eliminar'])->name('pdf.eliminar');

