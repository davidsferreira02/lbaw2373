<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FileController;
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




Route::get('/forgot-password-sent', function () {
    return view('auth.password-forgot-sent');
})->middleware('guest')->name('forgot-password-sent');


Route::get('/password/reset/{token}', function ($token) {
    return view('auth.password-reset', ['token' => $token]);
})->middleware('guest')->name('password.reset');


Route::get('/forgot-password', function () {
    return view('auth.password-forgot');
})->middleware('guest')->name('password.request');






Route::controller(MailController::class)->group(function () {

    Route::get('/resetAuthPassword', 'showChangePassword')->middleware('guest');


Route::post('reset_password_without_token', 'passwordRequest')->middleware("guest");
Route::post('reset_password_with_token', 'resetPassword')->middleware("guest");
});



// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login')->middleware('guest');
    Route::post('/login', 'authenticate')->middleware('guest');
    Route::get('/logout', 'logout')->name('logout')->middleware('auth');

    Route::get('/block', function () {
        return view('pages.block');
    })->name('blocked')->middleware('auth');;

    
   
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register')->middleware('guest');
    Route::post('/register', 'register')->middleware('guest');
});


Route::controller(ProjectController::class)->group(function () {
    
    Route::get('/create-project', 'showCreateProjectForm')->name('project.create')->middleware('auth');
    Route::post('/projects', 'store')->name('project.store')->middleware('auth');
    Route::get('/home', 'home')->name('project.home')->middleware('auth');
    Route::get('/project/{title}', 'show')->name('project.show')->middleware('auth');
    Route::get('/myprojects', 'index')->name('project.index')->middleware('auth');
    Route::get('/project/{title}/addMember', 'showaddMemberForm')->name('project.addMember')->middleware('auth');
    Route::post('/project/{title}/addMember/{username}', 'addOneMember')->name('project.Memberstore')->middleware('auth');
    Route::get('/project/{title}/addLeader', 'showaddLeaderForm')->name('project.addLeader')->middleware('auth');
    Route::post('/project/{title}/addLeader/{username}', 'addOneLeader')->name('project.Leaderstore')->middleware('auth');
    Route::get('/pending-invites', 'pendingInvite')->name('pending.invites')->middleware('auth');
    Route::post('/accept-invite/{id_user}/{id_project}', 'acceptInvite')->name('accept.invite')->middleware('auth');
    Route::post('/decline-invite/{id_user}/{id_project}', 'declineInvite')->name('decline.invite')->middleware('auth');
    Route::get('/projects/{title}/members', 'showMembers')->name("project.showMember")->middleware('auth');
    Route::get('/projects/{title}/leaders', 'showLeaders')->name("project.showLeader")->middleware('auth');
    Route::get('/project/{title}/edit', 'edit')->name('project.editProject')->middleware('auth');
   Route::put('/project/{title}/update', 'update')->name('project.update')->middleware('auth');
   Route::put('/project/{title}/favorite', 'favorite')->name('project.favorite')->middleware('auth');
   Route::put('project/{title}/archived','archived')->name('project.archived')->middleware('auth');
   Route::delete('/project/{title}/member/{id}/delete', 'deleteMember')->name('project.deleteMember')->middleware('auth');
   Route::get('/project/{title}/addMember/search-username', 'searchByUsernameAddMember')->name('search.username')->middleware('auth');
   Route::get('/project/{title}/addLeader/search-username', 'searchByUsernameAddLeader')->name('search.usernameLeader')->middleware('auth');
   Route::delete('/project/{title}/leave', 'leaveProject')->name('project.leave')->middleware('auth');

   

   



   


});

Route::controller(UsersController::class)->group(function () {
Route::get('/search/users', 'search')->name('search.users')->middleware('auth');


});

Route::controller(TaskController::class)->group(function () {
    
     Route::get('/project/{title}/createTask', 'create')->name('task.create')->middleware('auth');
    Route::post('/project/{title}/storeTask', 'store')->name('task.store')->middleware('auth');
    Route::get('/project/{title}/task', 'show')->name('task.show')->middleware('auth');
    Route::patch('/project/{title}/task/{taskId}/complete', 'isCompleted')->name('task.complete')->middleware('auth');
   Route::put('/project/{title}/task/{taskTitle}/update', 'update')->name('task.update')->middleware('auth');
   Route::delete('/project/{title}/task/{taskTitle}/delete', 'delete')->name('task.delete')->middleware('auth');
   Route::get('/project/{title}/task/search','search')->name('task.search')->middleware('auth');;
  




});

Route::controller(AdminController::class)->group(function () {
    Route::get('/admin', 'index')->name('admin.dashboard')->middleware('admin');;
    Route::patch('/block/{id}','block')->name('admin.block')->middleware('admin');;
});


Route::controller(ProfileController::class)->group(function(){
    Route::get('/profile/{id}', 'show')->name('profile')->middleware('auth');
    Route::get('/profile/{id}/edit', 'edit')->name('profile.edit')->middleware('auth');
   Route::put('/profile/{id}/update', 'update')->name('profile.update')->middleware('auth');
   Route::delete('/profile/{id}/delete', 'delete')->name('profile.delete')->middleware('auth');
  

});

Route::controller(CommentController::class)->group(function(){
    Route::get('/project/{title}/task/{taskId}/comments','show')->name('task.comment')->middleware('auth');
    Route::post('/project/{title}/task/{taskId}/comments/store', 'store')->name('comments.store')->middleware('auth');
    Route::delete('/project/{title}/task/{titleTask}/comments/{idComment}/delete', 'delete')->name('comment.delete')->middleware('auth');
    Route::get('/project/{title}/task/{titleTask}/comments/{idComment}/edit', 'edit')->name('comment.edit')->middleware('auth');
    Route::put('/project/{title}/task/{titleTask}/comments/{idComment}/update', 'commentupdate')->name('comment.update')->middleware('auth');
  

});

Route::controller(LikeController::class)->group(function(){
Route::patch('/project/{title}/task/{titleTask}/comment/{commentId}/like/store', 'store')->name('like.store')->middleware('auth');


});



Route::controller(FileController::class)->group(function(){
Route::post('/file/upload',  'upload')->name('file.upload')->middleware('auth');;
});


