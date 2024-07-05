<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['namespace' => 'App\Http\Controllers\Users', 'prefix' => 'user',], function () {
    Route::post('change_password', 'UserController@changePassword')->name('changePassword');

    #linkscans
    Route::group(['prefix' => 'linkscans', 'as' => 'linkscans.'], function () {
        Route::get('/getAll', 'LinkScanController@getAll')->name('getAll');
        Route::delete('/{id}/destroy', 'LinkScanController@destroy')->name('destroy');
    });

    #links
    Route::group(['prefix' => 'links', 'as' => 'links.'], function () {
        Route::get('/getAll', 'LinkController@getAll')->name('getAll');
        Route::post('/update', 'LinkController@update')->name('update');
    });

    #comments
    Route::group(['prefix' => 'comments', 'as' => 'comments.'], function () {
        Route::get('/getAll', 'CommentController@getAll')->name('getAll');
    });

    #reactions
    Route::group(['prefix' => 'reactions', 'as' => 'reactions.'], function () {
        Route::get('/', 'ReactionController@index')->name('index');
        Route::delete('/{id}/destroy', 'ReactionController@destroy')->name('destroy');
        Route::post('/create', 'ReactionController@store')->name('store');
        Route::get('/getAll', 'ReactionController@getAll')->name('getAll');
    });
});

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    #excel export
    Route::group(['prefix' => 'exports', 'as' => 'exports.'], function () {
        Route::post('/linkfollow', 'ExportExcelController@linkfollow')->name('linkfollow');
        Route::post('/linkscan', 'ExportExcelController@linkscan')->name('linkscan');
        Route::post('/linkrunning', 'ExportExcelController@linkrunning')->name('linkrunning');
    });

    #recover password
    Route::get('recover', 'UploadController@recover')->name('recover');

    #upload uid
    //Route::post('uploadUid', 'UidController@uploadUid')->name('uploadUid');

    #reactions
    Route::group(['prefix' => 'reactions', 'as' => 'reactions.'], function () {
        Route::get('/', 'ReactionController@index')->name('index');
        Route::delete('/{id}/destroy', 'ReactionController@destroy')->name('destroy');
        Route::post('/create', 'ReactionController@store')->name('store');
        Route::get('/getAll', 'ReactionController@getAll')->name('getAll');
        Route::post('/deleteAll', 'ReactionController@deleteAll')->name('deleteAll');
        Route::get('/getAllReactionUser', 'ReactionController@getAllReactionUser')->name('getAllReactionUser');
    });

    #upload
    Route::post('/upload', 'UploadController@upload')->name('upload');
    Route::post('/restore', 'UploadController@restore')->name('restore');

    #linkHistories
    Route::group(['prefix' => 'linkHistories', 'as' => 'linkHistories.'], function () {
        Route::get('/getAll', 'LinkHistoryController@getAll')->name('getAll');
    });

    #comments
    Route::group(['prefix' => 'comments', 'as' => 'comments.'], function () {
        Route::get('/', 'CommentController@index')->name('index');
        Route::delete('/{id}/destroy', 'CommentController@destroy')->name('destroy');
        Route::post('/create', 'CommentController@store')->name('store');
        Route::get('/getAll', 'CommentController@getAll')->name('getAll');
        Route::post('/deleteAll', 'CommentController@deleteAll')->name('deleteAll');
        Route::post('/updateById', 'CommentController@updateById')->name('updateById');
        Route::get('/getAllByUser', 'CommentController@getAllByUser')->name('getAllByUser');
        Route::get('/getAllCommentNew', 'CommentController@getAllCommentNew')->name('getAllCommentNew');
        Route::get('/getAllByUserClone', 'CommentController@getAllByUserClone')->name('getAllByUserClone');
        Route::get('/getAllCommentNewPagination', 'CommentController@getAllCommentNewPagination')->name('getAllCommentNewPagination');
        Route::get('/getAllCommentNewPaginationParam', 'CommentController@getAllCommentNewPaginationParam')->name('getAllCommentNewPaginationParam');
        Route::get('/getAllCommentNewPaginationByUser', 'CommentController@getAllCommentNewPaginationByUser')->name('getAllCommentNewPaginationByUser');
        Route::get('/getAllCommentNewPaginationParamByUser', 'CommentController@getAllCommentNewPaginationParamByUser')->name('getAllCommentNewPaginationParamByUser');
        Route::get('/getTestAPI', 'CommentController@getTestAPI')->name('getTestAPI');
    });

    #links
    Route::group(['prefix' => 'links', 'as' => 'links.'], function () {
        Route::get('/', 'LinkController@index')->name('index');
        Route::get('/getByType', 'LinkController@getByType')->name('getByType');
        Route::get('/getAll', 'LinkController@getAll')->name('getAll');
        Route::get('/getAllLink', 'LinkController@getAllLink')->name('getAllLink');
        Route::post('/create', 'LinkController@store')->name('store');
        Route::post('/update', 'LinkController@update')->name('update');
        Route::post('/updateLinkByLinkOrPostId', 'LinkController@updateLinkByLinkOrPostId')->name('updateLinkByLinkOrPostId');
        Route::post('/updateLinkByListLinkId', 'LinkController@updateLinkByListLinkId')->name('updateLinkByListLinkId');
        Route::post('/updateLinkByLinkOrPostIdAndUserId', 'LinkController@updateLinkByLinkOrPostIdAndUserId')->name('updateLinkByLinkOrPostIdAndUserId');
        Route::delete('/{id}/destroy', 'LinkController@destroy')->name('destroy');
        Route::post('/deleteAll', 'LinkController@deleteAll')->name('deleteAll');
        Route::post('/deleteAllUserLink', 'LinkController@deleteAllUserLink')->name('deleteAllUserLink');
        Route::post('/deleteAllByListLinkOrPostId', 'LinkController@deleteAllByListLinkOrPostId')->name('deleteAllByListLinkOrPostId');
        //Quang
        Route::get('/getAllNewForUI', 'LinkController@getAllNewForUI')->name('getAllNewForUI');
        Route::get('/getAllNewAPI', 'LinkController@getAllNewAPI')->name('getAllNewAPI');
        Route::post('/updateParentID', 'LinkController@updateParentID')->name('updateParentID');
        Route::post('/updateLinkDie', 'LinkController@updateLinkDie')->name('updateLinkDie');
        Route::post('/updateStatusByParentID', 'LinkController@updateStatusByParentID')->name('updateStatusByParentID');
        Route::post('/updateStatusLink', 'LinkController@updateStatusLink')->name('updateStatusLink');
        Route::post('/updateDataCuoiLink', 'LinkController@updateDataCuoiLink')->name('updateDataCuoiLink');
        Route::post('/updateCount', 'LinkController@updateCount')->name('updateCount');
        Route::post('/checkTimeZone', 'LinkController@checkTimeZone')->name('checkTimeZone');
        Route::post('/updateDelayLink', 'LinkController@updateDelayLink')->name('updateDelayLink');
        Route::post('/uploadUid', 'UidController@uploadUid')->name('uploadUid');
        Route::get('/getAllNewForUI_V2', 'LinkController@getAllNewForUI_V2')->name('getAllNewForUI_V2');
    });

    #userlinks
    Route::group(['prefix' => 'userlinks', 'as' => 'userlinks.'], function () {
        Route::get('/getAll', 'UserLinkController@getAll')->name('getAll');
        Route::post('/deleteAll', 'UserLinkController@deleteAll')->name('deleteAll');
        Route::post('/update', 'UserLinkController@update')->name('update');
        Route::post('/updateLinkByLinkOrPostId', 'UserLinkController@updateLinkByLinkOrPostId')->name('updateLinkByLinkOrPostId');
        Route::post('/updateLinkByListLinkId', 'UserLinkController@updateLinkByListLinkId')->name('updateLinkByListLinkId');

        //Quang
        Route::get('/getAllLinkScan', 'UserLinkController@getAllLinkScan')->name('getAllLinkScan');
        Route::get('/getAllLinkScan_V2', 'UserLinkController@getAllLinkScan_V2')->name('getAllLinkScan_V2');
    });

    #settings
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::post('/uploadmap', 'SettingController@uploadmap')->name('uploadmap');
        Route::post('/create', 'SettingController@store')->name('store');
        Route::get('/getAll', 'SettingController@getAll')->name('getAll');
        Route::post('/delete', 'SettingController@delete')->name('delete');
    });
});

Route::group(['namespace' => 'App\Http\Controllers\Admin'], function () {
    #accounts
    Route::group(['prefix' => 'accounts', 'as' => 'accounts.'], function () {
        Route::delete('/{id}/destroy', 'AccountController@destroy')->name('destroy');
    });

    #linkscans
    Route::group(['prefix' => 'linkscans', 'as' => 'linkscans.'], function () {
        Route::get('/getAll', 'LinkScanController@getAll')->name('getAll');
        Route::delete('/{id}/destroy', 'LinkScanController@destroy')->name('destroy');
    });

    #linkfollows
    Route::group(['prefix' => 'linkfollows', 'as' => 'linkfollows.'], function () {
        Route::get('/getAll', 'LinkFollowController@getAll')->name('getAll');
        Route::delete('/{id}/destroy', 'LinkFollowController@destroy')->name('destroy');
    });
});
