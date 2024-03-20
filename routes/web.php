<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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

Route::get('/dashboard', [UserController::class , 'loadDashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/groups', [UserController::class , 'loadGroups'])->middleware(['auth', 'verified'])->name('groups');
Route::post('/create-group', [UserController::class , 'createGroup'])->middleware(['auth', 'verified'])->name('createGroup');

Route::get('/chat', function(){
    return view('chat');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::post('/save-chat' , [UserController::class , 'saveChat']);
Route::post('/load-chats' , [UserController::class , 'loadChats']);
Route::get('/get-user', [UserController::class , 'getUser']);
Route::post('/delete-chat' , [UserController::class , 'deleteChat']);
Route::put('/update-chat' , [UserController::class , 'updateChat']);

