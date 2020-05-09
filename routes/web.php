<?php

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

// 超级管理员添加管理员
Route::post('register', 'Auth\RegisterController@register');

// 管理员登录
Route::post('login', 'Auth\LoginController@login');
// 管理员退出登录
Route::post('logout', 'Auth\LogoutController@logout');
// 管理员重置密码
Route::post('reset', 'Auth\ResetPasswordController@resetPassword');

// 管理员发送短信
Route::post('message', 'ShortMessage\SendMessageController@sendMessage');
// 管理员上传图片
Route::post('upload', 'Upload\UploadPictureController@uploadPicture');
// 管理员添加文章
Route::post('article/add', 'Article\AddArticleController@addArticle');
// 管理员修改文章
Route::post('article/modify', 'Article\ModifyArticleController@modifyArticle');

// 按文章id查找文章
Route::get('article/find/id', 'Article\FindArticleController@findArticleById');
// 按文章id查找文章
Route::get('article/find/type', 'Article\FindArticleController@findArticleByType');
