<?php


use Illuminate\Database\Schema\IndexDefinition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MymailController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {

    Route::get('compose_mail',[MymailController::class, 'index'])->name('compose_mail');
    Route::post('send_mail',[MymailController::class, 'send_mail'])->name('sendmail');
    Route::post('upload_attachment',[MymailController::class, 'store_attachment'])->name('upload_attachment');
    Route::get('mail_histrory/{email?}',[MymailController::class, 'mail_histrory'])->name('mail_histrory');
    Route::get('mail_detail/{id}',[MymailController::class, 'mail_detail'])->name('mail_detail');
    // Route::get('mail_dashboard',[MymailController::class, 'mail_dashboard'])->name('mail_dashboard');
    Route::get('dashboard',[MymailController::class, 'mail_dashboard'])->name('dashboard');
});
Route::get('mail_tracking_img/{tracking_id?}',[MymailController::class, 'mail_tracking_img'])->name('mail_tracking_img');
require __DIR__.'/auth.php';
