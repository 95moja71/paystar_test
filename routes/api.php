<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$versions = config('app.versions');


Route::group([], function () use ( $versions) {
    foreach ($versions as $version) {
        if ($version['active']) {

            Route::group(['prefix' => $version['url'] . '/', 'as' => $version['version'] . '.', 'namespace' => $version['version']], function () use ($version) {
                require base_path('routes/versions/general.php');
                require base_path('routes/versions/' . $version['url'] . '.php');

            });
        }
    }
});
