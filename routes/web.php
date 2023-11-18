<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\TaskController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UsersController;
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

// Home
Route::redirect('/', '/login');




// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});


Route::controller(ProjectController::class)->group(function () {
    
    Route::get('/create-project', 'showCreateProjectForm')->name('project.create');
    Route::post('/projects', 'store')->name('project.store');
    Route::get('/home', 'home')->name('project.home');
    Route::get('/project/{title}', 'show')->name('project.show');
    Route::get('/myprojects', 'index')->name('project.index');
    Route::get('/project/{title}/addMember', 'showaddMemberForm')->name('project.addMember');
    Route::post('/project/{title}/addMember/store', 'addOneMember')->name('project.Memberstore');
    Route::get('/project/{title}/addLeader', 'showaddLeaderForm')->name('project.addLeader');
    Route::post('/project/{title}/addLeader/store', 'addOneLeader')->name('project.Leaderstore');
    Route::get('/convites-pendentes', 'pendingInvite')->name('pending.invites');
    Route::post('/accept-invite/{id_user}/{id_project}', 'acceptInvite')->name('accept.invite');
    Route::post('/decline-invite/{id_user}/{id_project}', 'declineInvite')->name('decline.invite');

   


});

Route::controller(UsersController::class)->group(function () {
Route::get('/search/users', 'search')->name('search.users');


});

Route::controller(TaskController::class)->group(function () {
    
     Route::get('/project/{title}/createTask', 'create')->name('task.create');
    Route::post('/project/{title}/storeTask', 'store')->name('task.store');
    Route::get('/project/{title}/task', 'show')->name('task.show');
    Route::patch('/project/{title}/task/{taskId}/complete', 'isCompleted')->name('task.complete');




});
