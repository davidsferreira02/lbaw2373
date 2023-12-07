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
use App\Http\Controllers\MailController;
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

Route::get('/features', function () {
    return view('pages.features');
})->name('features');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contacts', function () {
    return view('pages.contacts');
})->name('contacts');




// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
    Route::get('/forgotPassword','forgotPassword')->name('password');
    Route::get('/block', function () {
        return view('pages.block');
    })->name('blocked');
    
   
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});


Route::controller(ProjectController::class)->group(function () {
    
    Route::get('/create-project', 'showCreateProjectForm')->name('project.create')->middleware('auth');
    Route::post('/projects', 'store')->name('project.store')->middleware('auth');
    Route::get('/home', 'home')->name('project.home')->middleware('auth');
    Route::get('/project/{title}', 'show')->name('project.show')->middleware('auth');
    Route::get('/myprojects', 'index')->name('project.index')->middleware('auth');
    Route::get('/project/{title}/addMember', 'showaddMemberForm')->name('project.addMember')->middleware('auth');
    Route::post('/project/{title}/addMember/store', 'addOneMember')->name('project.Memberstore')->middleware('auth');
    Route::get('/project/{title}/addLeader', 'showaddLeaderForm')->name('project.addLeader')->middleware('auth');
    Route::post('/project/{title}/addLeader/store', 'addOneLeader')->name('project.Leaderstore')->middleware('auth');
    Route::get('/pending-invites', 'pendingInvite')->name('pending.invites')->middleware('auth');
    Route::post('/accept-invite/{id_user}/{id_project}', 'acceptInvite')->name('accept.invite')->middleware('auth');
    Route::post('/decline-invite/{id_user}/{id_project}', 'declineInvite')->name('decline.invite')->middleware('auth');
    Route::get('/projects/{title}/members', 'showMembers')->name("project.showMember")->middleware('auth');
    Route::get('/projects/{title}/leaders', 'showLeaders')->name("project.showLeader")->middleware('auth');
    Route::get('/project/{title}/edit', 'edit')->name('project.editProject')->middleware('auth');
   Route::put('/project/{title}/update', 'update')->name('project.update')->middleware('auth');
   Route::get('/project/{title}/favorite', 'favorite')->name('project.favorite')->middleware('auth');
   Route::get('/project/{title}/noFavorite', 'noFavorite')->name('project.noFavorite')->middleware('auth');
   Route::post('project/{title}/archived','archived')->name('project.archived')->middleware('auth');
   Route::delete('/project/{title}/member/{id}/delete', 'deleteMember')->name('project.deleteMember');

   



   


});

Route::controller(UsersController::class)->group(function () {
Route::get('/search/users', 'search')->name('search.users')->middleware('auth');


});

Route::controller(TaskController::class)->group(function () {
    
     Route::get('/project/{title}/createTask', 'create')->name('task.create')->middleware('auth');
    Route::post('/project/{title}/storeTask', 'store')->name('task.store')->middleware('auth');
    Route::get('/project/{title}/task', 'show')->name('task.show')->middleware('auth');
    Route::patch('/project/{title}/task/{taskId}/complete', 'isCompleted')->name('task.complete')->middleware('auth');
    Route::delete('/project/{title}/task/{taskId}/delete', 'delete')->name('task.delete')->middleware('auth');




});

Route::controller(AdminController::class)->group(function () {
    Route::get('/admin', 'index')->name('admin.dashboard');
    Route::patch('/block/{id}','block')->name('admin.block');
});


Route::controller(ProfileController::class)->group(function(){
    Route::get('/profile/{id}', 'show')->name('profile')->middleware('auth');
    Route::get('/profile/{id}/edit', 'edit')->name('profile.edit')->middleware('auth');
   Route::put('/profile/{id}/update', 'update')->name('profile.update')->middleware('auth');
   Route::delete('/profile/{id}/delete', 'delete')->name('profile.delete')->middleware('auth');
  

});

Route::controller(CommentController::class)->group(function(){
    Route::get('/project/{title}/task/{taskId}/comments','show')->name('task.comment')->middleware('auth');
    Route::post('/project/{title}/task/{taskId}/comments/store', [CommentController::class, 'store'])->name('comments.store')->middleware('auth');

});

Route::controller(LikeController::class)->group(function(){
Route::post('/api/like/store', 'store')->name('like.store')->middleware('auth');
Route::delete('/api/like/{id}', 'destroy')->name('like.destroy')->middleware('auth');

});

