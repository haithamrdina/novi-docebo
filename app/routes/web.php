<?php

use App\Http\Controllers\ConfigController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WebHookController;
use App\Http\Integrations\Docebo\DoceboConnector;
use App\Http\Integrations\Docebo\Requests\GetUserfiels;
use App\Http\Integrations\Docebo\Requests\GetUsersData;
use App\Http\Integrations\Docebo\Requests\GetUsersDataFromDocebo;
use App\Http\Integrations\Docebo\Requests\UpdateUserFiledsData;
use App\Http\Integrations\Novi\NoviConnector;
use App\Http\Integrations\Novi\Requests\AddNewMember;
use App\Http\Integrations\Novi\Requests\GetMemberCustomFiels;
use App\Http\Integrations\Novi\Requests\GetMemberDetailFromNovi;
use App\Http\Integrations\Novi\Requests\GetUsersDataFromNovi;
use App\Http\Integrations\Novi\Requests\GetUsersSimpleDataFromNovi;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
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

/** tests @s */
Route::get('novi-users' , function(){
    $doceboConnector = new DoceboConnector();
    $userId = '13081';
    $data = [
        '1' => "(418)478-5418"
    ];
    $result = ($doceboConnector->send(new UpdateUserFiledsData($userId, $data)));
    dd($result->dto());
});
Route::get('/test', function () {
    $doceboConnector = new DoceboConnector();
    $result = ($doceboConnector->send(new GetUserfiels))->dto();
    $configFilePath = config_path('userfields.php');
    $configContent = '<?php' . PHP_EOL . 'return [' . PHP_EOL;

    foreach ($result as $key => $value) {
        // Écrire chaque clé avec une valeur nulle
        $configContent .= "    '$key' => null, //$value" . PHP_EOL;
    }
    $configContent .= '];' . PHP_EOL;

    // Écrire dans le fichier de configuration
    File::put($configFilePath, $configContent);
    return view('welcome');
});
Route::get('check-docebo-user', function(){
    $noviConnector = new NoviConnector;

    $noviUsersSimpleDataResponse = $noviConnector->send(new GetMemberDetailFromNovi('a1a267fb-5247-41d8-b1ab-35c7db139bf9'));
    $noviUsers = $noviUsersSimpleDataResponse->dto();
    dd($noviUsers);
});
/** tests @e */

/** webhooks @s */
Route::post('novi-listener', [WebHookController::class, 'webhookNoviHandler']);
Route::post('novi-update-listener', [WebHookController::class, 'webhookNoviUpdateHandler']);
Route::post('docebo-listener', [WebHookController::class, 'webhookDoceboHandler']);
//Route::post('novi-updated', [WebHookController::class, 'noviUpdateHandle']);

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
