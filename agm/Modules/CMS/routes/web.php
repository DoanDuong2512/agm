<?php

use Illuminate\Support\Facades\Route;
use Modules\CMS\App\Http\Controllers\AuthController;
use Modules\CMS\App\Http\Controllers\CMSController;
use Modules\CMS\App\Http\Controllers\CustomerController;
use Modules\CMS\App\Http\Controllers\AuthorityController;
use Modules\CMS\App\Http\Controllers\UserController;
use Modules\CMS\App\Http\Controllers\DocumentController;
use Modules\CMS\App\Http\Controllers\ConversationController;
use Modules\CMS\App\Http\Controllers\MeetingConfigController;
use Modules\CMS\App\Http\Controllers\PrintController;
use Modules\CMS\App\Http\Controllers\VoteCmsController;
use Modules\CMS\App\Http\Controllers\VotesCmsController;
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

Route::prefix('admin')->name('cms.')->group(function () {
    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit');

    // Authenticated routes
    Route::middleware('cms')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [CMSController::class, 'index'])->name('index');
        Route::get('/show', [CMSController::class, 'show'])->name('show');
        Route::resource('users', UserController::class);
        Route::resource('customers', CustomerController::class);
        Route::post('/customers/{customer}/toggle-checkin', [CustomerController::class, 'toggleCheckin'])->name('customers.toggle-checkin');
        Route::get('documents', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('documents/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
        Route::get('documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
        Route::put('documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
        Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
        Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
        Route::get('conversations', [ConversationController::class, 'index'])->name('conversations.index');
        Route::get('vote', [VoteCmsController::class, 'index'])->name('vote.index');

        Route::get('votes', [VotesCmsController::class, 'index'])->name('votes.index');

        Route::get('print', [VoteCmsController::class, 'printResult'])->name('vote.print');
        Route::resource('meeting-configs', MeetingConfigController::class);
        Route::get('/print/bbkt', [PrintController::class, 'showBBKT'])->name('print.bbkt');
        Route::get('/print/bien-ban-dai-hoi', [PrintController::class, 'showBBDH'])->name('print.bbdh');
        Route::prefix('authority')->name('authority.')->group(function () {
            Route::get('/', [AuthorityController::class, 'index'])->name('index');
            Route::post('/', [AuthorityController::class, 'store'])->name('store');
            Route::get('/{authority}/edit', [AuthorityController::class, 'edit'])->name('edit');
            Route::put('/{authority}', [AuthorityController::class, 'update'])->name('update');
            Route::delete('/{authority}', [AuthorityController::class, 'destroy'])->name('delete');
            Route::get('/search-customers', [AuthorityController::class, 'searchCustomers'])->name('search-customers');
            Route::get('/get-available-shares', [AuthorityController::class, 'getAvailableShares'])->name('get-available-shares');
        });
        // API cms
        Route::get('api-customer', [VoteCmsController::class, 'customer'])->name('vote.customer');
        Route::get('api-bau-cu', [VoteCmsController::class, 'baucu'])->name('vote.list');
        Route::post('api-import-customer', [VoteCmsController::class, 'importCustomer'])->name('vote.import');
        Route::post('api-agm_info', [VoteCmsController::class, 'storeAgmInfo'])->name('vote.agm_info_store');
        Route::get('api-agm_info', [VoteCmsController::class, 'getAgmInfo'])->name('vote.agm_info');

        // Quản lý phiếu biểu quyết (CRUD)
        // Route::prefix('votes')->name('votes.')->group(function () {
        //     Route::get('/', [VoteCmsController::class, 'list'])->name('list');
        //     Route::get('/create', [VoteCmsController::class, 'create'])->name('create');
        //     Route::post('/', [VoteCmsController::class, 'store'])->name('store');
        //     Route::get('/{vote}/edit', [VoteCmsController::class, 'edit'])->name('edit');
        //     Route::put('/{vote}', [VoteCmsController::class, 'update'])->name('update');
        //     Route::delete('/{vote}', [VoteCmsController::class, 'destroy'])->name('destroy');
    // Route::get('/', [\App\Http\Controllers\Cms\VotesCmsController::class, 'index'])->name('index');

            // Quản lý mục phiếu biểu quyết
        //     Route::get('/{vote}/items', [VoteCmsController::class, 'listItems'])->name('items.list');
        //     Route::get('/{vote}/items/create', [VoteCmsController::class, 'createItem'])->name('items.create');
        //     Route::post('/{vote}/items', [VoteCmsController::class, 'storeItem'])->name('items.store');
        //     Route::get('/{vote}/items/{item}/edit', [VoteCmsController::class, 'editItem'])->name('items.edit');
        //     Route::put('/{vote}/items/{item}', [VoteCmsController::class, 'updateItem'])->name('items.update');
        //     Route::delete('/{vote}/items/{item}', [VoteCmsController::class, 'destroyItem'])->name('items.destroy');
        // });
    });
});