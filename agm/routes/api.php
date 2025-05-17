<?php

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
Route::get('unauthorized', function () {
    return response()->json(array_merge_recursive([
        'meta' => [
            'code' => 401,
            'message' => 'UNAUTHORIZED_REQUEST'
        ]
    ]))->setStatusCode(401);
})->name('unauthorized');

Route::group(['namespace' => 'App\\Http\\Controllers\\Api\\Customer'], function () {
    Route::post('/pre-login', 'AuthController@preLogin');
    Route::post('/validate-otp', 'AuthController@validateOtp');
//    Route::post('/register', 'AuthController@register');
    Route::post('/forget-password', 'AuthController@forgetPassword');
    Route::post('/reset-password', 'AuthController@resetPassword');
    Route::post('/change-default-password', 'AuthController@changeDefaultPassword');
    Route::group(['middleware' => 'customer'], function () {
        Route::group(['prefix' => 'me'], function () {
            Route::get('/', 'CustomerController@me');
            Route::delete('/delete', 'CustomerController@delete');
        });
//        Route::post('/change-password', 'AuthController@changePassword');
        Route::post('/logout', 'AuthController@logout');
        Route::get('/list', 'CustomerController@list');
        // Documents
        Route::get('documents', 'DocumentController@index');
        Route::get('document/{id}', 'DocumentController@show');
        Route::get('document/{id}/download', 'DocumentController@download')->name('api.customer.documents.download');
        // Conversations
        Route::get('conversations', 'ConversationController@getConversations');
        Route::post('conversation/create', 'ConversationController@createConversation');
        Route::post('conversation/send-message', 'ConversationController@sendMessage');
        Route::get('conversation/get-messages/{id}', 'ConversationController@getMessages');
        Route::get('conversation-admin', 'ConversationController@getConversationWithAdmin');

        // Vote
        Route::get('votes', 'VoteController@votes');
        Route::get('vote/authorize', 'VoteController@authorizedPerson');
        Route::post('vote', 'VoteController@store');
        Route::post('re-vote', 'VoteController@reStore');
        Route::get('print-bau-cu', 'VoteController@printBauCu');
        Route::get('print-bieu-quyet', 'VoteController@printBieuQuyet');
        Route::get('print-co-dong', 'VoteController@customer');

        // Configuration
        Route::get('meeting-config', 'MeetingConfigController@getConfigByKey');
    });
});


Route::group(['namespace' => 'App\\Http\\Controllers\\Api\\Admin', 'prefix' => 'admin'], function () {
    Route::post('/login', 'AuthController@login');
    Route::group(['middleware' => 'admin'], function () {
        Route::get('/user/me', 'UserController@me');
        Route::post('/change-password', 'AuthController@changePassword');
        Route::post('/logout', 'AuthController@logout');
        Route::get('conversations', 'ConversationController@getConversations');
        Route::post('conversation/create', 'ConversationController@createConversation');
        Route::post('conversation/send-message', 'ConversationController@sendMessage');
        Route::get('conversation/get-messages/{id}', 'ConversationController@getMessages');
        Route::get('data-for-print-phieu-bau-cu', 'VoteController@getDataForPrintPhieuBauCu');
        Route::get('data-for-print-phieu-bieu-quyet', 'VoteController@getDataForPrintPhieuBieuQuyet');
        Route::get('data-for-print-bkkt', 'PrintController@getDataForBKKT');
        Route::get('data-for-print-phieu-xac-nhan-tham-du', 'PrintController@getDataForPrintPhieuXacNhanThamDu');
    });
});


