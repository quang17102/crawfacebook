<?php

use Illuminate\Support\Facades\Artisan;
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

Route::get('/schedule', function () {
    return Artisan::call('schedule:run');
});

Route::get('/link', function () {
    return Artisan::call('storage:link');
});

Route::get('/optimize', function () {
    return Artisan::call('optimize:clear');
});

Route::get('/migrate', function () {
    return Artisan::call('migrate:refresh --seed');
});

Route::get('/', function () {
    return view('user.home.index', [
        'title' => 'Giới thiệu'
    ]);
});

Route::get('/register', function () {
    return view('user.register.index', [
        'title' => 'Đăng ký'
    ]);
});

Route::get('/download/{filename}', function () {
    return view('user.login.index', [
        'title' => 'Đăng nhập'
    ]);
});

#user
Route::group(['prefix' => 'user', 'namespace' => 'App\Http\Controllers\Users', 'as' => 'user.'], function () {
    Route::get('/', 'UserController@index')->name('home')->middleware('auth');
    Route::get('login', 'UserController@login')->name('login');
    Route::get('forgot', 'UserController@forgot')->name('forgot');
    Route::post('recover', 'UserController@recover')->name('recover');
    Route::post('login', 'UserController@checkLogin')->name('checkLogin');
    Route::get('register', 'UserController@register')->name('register');
    Route::post('register', 'UserController@checkRegister')->name('checkRegister');
    // Route::post('change_password', 'UserController@changePassword')->name('changePassword');
    Route::get('logout', 'UserController@logout')->name('logout');
    Route::get('me', 'UserController@me')->name('me');
    Route::post('me/update', 'UserController@update')->name('update');

    #comments
    Route::group(['prefix' => 'comments', 'as' => 'comments.'], function () {
        Route::get('/', 'CommentController@index')->name('index');
        Route::post('/create', 'CommentController@store')->name('store');
        Route::get('/update/{id}', 'CommentController@show')->name('show');
        Route::post('/update', 'CommentController@update')->name('update');
    });

    #linkfollows
    Route::group(['prefix' => 'linkfollows', 'as' => 'linkfollows.'], function () {
        Route::get('/', 'LinkFollowController@index')->name('index');
        Route::post('/create', 'LinkFollowController@store')->name('store');
        Route::get('/update/{id}', 'LinkFollowController@show')->name('show');
        Route::post('/update', 'LinkFollowController@update')->name('update');
    });

    #linkscans
    Route::group(['prefix' => 'linkscans', 'as' => 'linkscans.'], function () {
        Route::get('/', 'LinkScanController@index')->name('index');
        Route::post('/create', 'LinkScanController@store')->name('store');
        Route::get('/update/{id}', 'LinkScanController@show')->name('show');
        Route::post('/update', 'LinkScanController@update')->name('update');
    });

    #reactions
    Route::group(['prefix' => 'reactions', 'as' => 'reactions.'], function () {
        Route::get('/', 'ReactionController@index')->name('index');
        Route::post('/create', 'ReactionController@store')->name('store');
        Route::get('/update/{id}', 'ReactionController@show')->name('show');
        Route::post('/update', 'ReactionController@update')->name('update');
    });
});

#admin
Route::group([
    'prefix' => '/admin', 'namespace' => 'App\Http\Controllers\Admin',
    'as' => 'admin.', 'middleware' => 'admin'
], function () {
    Route::get('/', 'AdminController@index')->name('index');

    #reactions
    Route::group(['prefix' => 'reactions', 'as' => 'reactions.'], function () {
        Route::get('/', 'ReactionController@index')->name('index');
        Route::post('/create', 'ReactionController@store')->name('store');
        Route::get('/update/{id}', 'ReactionController@show')->name('show');
        Route::post('/update', 'ReactionController@update')->name('update');
    });

    #comments
    Route::group(['prefix' => 'comments', 'as' => 'comments.'], function () {
        Route::get('/', 'CommentController@index')->name('index');
        Route::post('/create', 'CommentController@store')->name('store');
        Route::get('/update/{id}', 'CommentController@show')->name('show');
        Route::post('/update', 'CommentController@update')->name('update');
    });

    #accounts
    Route::group(['prefix' => 'accounts', 'as' => 'accounts.'], function () {
        Route::get('/', 'AccountController@index')->name('index');
        Route::post('/create', 'AccountController@store')->name('store');
        Route::get('/update/{id}', 'AccountController@show')->name('show');
        Route::post('/update', 'AccountController@update')->name('update');
    });

    #linkrunnings
    Route::group(['prefix' => 'linkrunnings', 'as' => 'linkrunnings.'], function () {
        Route::get('/', 'LinkRunningController@index')->name('index');
        Route::post('/create', 'LinkRunningController@store')->name('store');
        Route::get('/update/{id}', 'LinkRunningController@show')->name('show');
        Route::post('/update', 'LinkRunningController@update')->name('update');
        Route::post('/update_delay', 'LinkRunningController@update_delay')->name('update_delay');
    });

    #linkfollows
    Route::group(['prefix' => 'linkfollows', 'as' => 'linkfollows.'], function () {
        Route::get('/', 'LinkFollowController@index')->name('index');
        Route::post('/create', 'LinkFollowController@store')->name('store');
        Route::get('/update/{id}', 'LinkFollowController@show')->name('show');
        Route::post('/update', 'LinkFollowController@update')->name('update');
    });

    #linkscans
    Route::group(['prefix' => 'linkscans', 'as' => 'linkscans.'], function () {
        Route::get('/', 'LinkScanController@index')->name('index');
        Route::post('/create', 'LinkScanController@store')->name('store');
        Route::get('/update/{id}', 'LinkScanController@show')->name('show');
        Route::post('/update', 'LinkScanController@update')->name('update');
    });
    
    Route::group(['prefix' => 'settingfilters', 'as' => 'settingfilters.'], function () {
        Route::get('/getAll', 'SettingFilterController@getAll')->name('getAll');
        Route::post('/store', 'SettingFilterController@store')->name('store');
        Route::get('/update/{id}', 'SettingFilterController@show')->name('show');
        Route::post('/update', 'SettingFilterController@update')->name('update');
        Route::get('/', 'SettingController@index_2')->name('index');
    });
});

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    #settings
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/', 'SettingController@index')->name('index');
        Route::post('update', 'SettingController@update')->name('update');
        Route::get('backup', 'SettingController@backup')->name('backup');
    });

    Route::group(['prefix' => 'settings_admin_1', 'as' => 'settings_admin_1.'], function () {
        Route::get('/', 'SettingController@index_1')->name('index');
        Route::post('update', 'SettingController@update_1')->name('update');
        Route::get('backup', 'SettingController@backup')->name('backup');
    });

    Route::group(['prefix' => 'settings_admin_2', 'as' => 'settings_admin_2.'], function () {
        Route::get('/', 'SettingController@index_2')->name('index');
        Route::post('update', 'SettingController@update_2')->name('update');
        Route::get('backup', 'SettingController@backup')->name('backup');
    });
});
