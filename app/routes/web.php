<?php

use App\Http\Controllers\ConfigController;
use App\Http\Controllers\WebHookController;
use App\Http\Controllers\WebhookDoceboController;
use App\Http\Controllers\WebHookNoviController;
use App\Http\Integrations\Docebo\DoceboConnector;
use App\Http\Integrations\Docebo\Requests\GetTransactionAddress;
use App\Http\Integrations\Docebo\Requests\GetUserDataByUserId;
use App\Http\Integrations\Docebo\Requests\GetUserfiels;
use App\Http\Integrations\Docebo\Requests\UpdateUserFiledsData;
use App\Http\Integrations\Novi\NoviConnector;
use App\Http\Integrations\Novi\Requests\GetMemberDetailFromNovi;
use Illuminate\Support\Facades\File;
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

Route::get('transaction', function(){
    $doceboConnector = new DoceboConnector;
    $result =$doceboConnector->send(new GetTransactionAddress(20))->dto();
    dd($result);
});

/** webhooks @s */
//Route::post('novi-listener', [WebHookController::class, 'webhookNoviHandler']);
Route::post('novi-update-listener', [WebHookNoviController::class, 'noviUpdateHandle']);
Route::post('novi-remove-listener', [WebHookNoviController::class, 'noviRemoveHandle']);
Route::post('docebo-listener', [WebHookController::class, 'webhookDoceboHandler']);
Route::post('docebo-create-listener', [WebhookDoceboController::class, 'doceboCreateHandle']);
Route::post('docebo-transaction-listener', [WebhookDoceboController::class, 'doceboTransactionHandle']);
/** webhooks @e */

/** app @s */
require __DIR__.'/auth.php';
Route::middleware('auth')->group(function () {

    /*Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/home/verify', [HomeController::class, 'verify'])->name('home.verify');
    Route::get('/home/sync', [HomeController::class, 'sync'])->name('home.sync');
    Route::get('/home/empty', [HomeController::class, 'empty'])->name('home.empty');*/
    Route::prefix('settings')->name('settings.')->group(function(){
        Route::get('userfields', [ConfigController::class, 'index'])->name('index');
        Route::post('userfields', [ConfigController::class, 'update'])->name('update');
        Route::get('docebo', [ConfigController::class, 'docebo'])->name('docebo.index');
        Route::post('docebo', [ConfigController::class, 'doceboupdate'])->name('docebo.update');
        Route::get('novi', [ConfigController::class, 'novi'])->name('novi.index');
        Route::post('novi', [ConfigController::class, 'noviupdate'])->name('novi.update');
    });
});
/** app @e */
