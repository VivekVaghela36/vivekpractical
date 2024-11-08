<?php

use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SkillController;

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
    return view('welcome');
});

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashBoardController::class, 'index'])->name('dashboard');

    Route::get('/skills/create', [SkillController::class, 'create'])->name('skills.create');
    Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');
    Route::get('/skills/{id}/edit', [SkillController::class, 'edit'])->name('skills.edit');
    Route::put('/skills/{id}', [SkillController::class, 'update'])->name('skills.update');
    Route::delete('/skills/{id}', [SkillController::class, 'destroy'])->name('skills.destroy');

    Route::post('friend-request/send/{receiver_id}', [FriendRequestController::class, 'sendFriendRequest'])->name('sendFriendRequest');
    Route::post('friend-request/accept/{request_id}', [FriendRequestController::class, 'acceptFriendRequest'])->name('acceptFriendRequest');
    Route::post('friend-request/cancel/{request_id}', [FriendRequestController::class, 'cancelFriendRequest'])->name('cancelFriendRequest');
    Route::post('unfriend/{friend_id}', [FriendRequestController::class, 'unfriend'])->name('unfriend');

    // Friend listing and skill-based listing
    Route::get(
        'friends',
        [FriendRequestController::class, 'listFriends']
    )->name('listFriends');
    Route::get('users/same-skills', [FriendRequestController::class, 'usersWithSameSkills'])->name('usersWithSameSkills');
    Route::get('friend-requests', [FriendRequestController::class, 'viewFriendRequests'])->name('viewFriendRequests');
});
