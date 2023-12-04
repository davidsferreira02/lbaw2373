<?php

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\TaskController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
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
    Route::get('/forgotPassword','forgotPassword')->name('password');
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
    Route::get('/pending-invites', 'pendingInvite')->name('pending.invites');
    Route::post('/accept-invite/{id_user}/{id_project}', 'acceptInvite')->name('accept.invite');
    Route::post('/decline-invite/{id_user}/{id_project}', 'declineInvite')->name('decline.invite');
    Route::get('/projects/{title}/members', 'showMembers')->name("project.showMember");
    Route::get('/projects/{title}/leaders', 'showLeaders')->name("project.showLeader");
    Route::get('/project/{title}/edit', 'edit')->name('project.editProject');
   Route::put('/project/{title}/update', 'update')->name('project.update');
   Route::get('/project/{title}/favorite', 'favorite')->name('project.favorite');
   Route::get('/project/{title}/noFavorite', 'noFavorite')->name('project.noFavorite');
   Route::post('project/{title}/archived','archived')->name('project.archived');
   



   


});

Route::controller(UsersController::class)->group(function () {
Route::get('/search/users', 'search')->name('search.users');


});

Route::controller(TaskController::class)->group(function () {
    
     Route::get('/project/{title}/createTask', 'create')->name('task.create');
    Route::post('/project/{title}/storeTask', 'store')->name('task.store');
    Route::get('/project/{title}/task', 'show')->name('task.show');
    Route::patch('/project/{title}/task/{taskId}/complete', 'isCompleted')->name('task.complete');
    Route::delete('/project/{title}/task/{taskId}/delete', 'delete')->name('task.delete');




});

Route::controller(AdminController::class)->group(function () {
    Route::get('/admin', 'index')->name('admin.dashboard');
});


Route::controller(ProfileController::class)->group(function(){
    Route::get('/profile/{id}', 'show')->name('profile');
    Route::get('/profile/{id}/edit', 'edit')->name('profile.edit');
   Route::put('/profile/{id}/update', 'update')->name('profile.update');
   Route::delete('/profile/{id}/delete', 'delete')->name('profile.delete');
  

});

Route::controller(CommentController::class)->group(function(){
    Route::get('/project/{title}/task/{taskId}/comments','show')->name('task.comment');
    Route::post('/project/{title}/task/{taskId}/comments/store', [CommentController::class, 'store'])->name('comments.store');

});

Route::controller(LikeController::class)->group(function(){
Route::post('/api/like/store', 'store')->name('like.store');
Route::delete('/api/like/{id}', 'destroy')->name('like.destroy');

});