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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([
    'prefix' => '/v1',
    'namespace' => 'App\Http\Controllers\Api\V1'
], function () {
    Route::group(['prefix' => '/auth'], function () {
        Route::post('/login', ['uses' => 'AuthController@login',  'as' => 'api.v1.auth.login']);
        Route::post('/register', ['uses' => 'AuthController@register',  'as' => 'api.v1.auth.register']);
        Route::post('/logout', ['uses' => 'AuthController@logout',  'as' => 'api.v1.auth.logout']);
    });
    Route::group(['middleware' => 'auth:api'], function () {
        Route::group(['prefix' => '/auth'], function () {
            Route::post('/refresh', ['uses' => 'AuthController@refresh',  'as' => 'api.v1.auth.refresh']);
            Route::get('/user-profile', ['uses' => 'AuthController@userProfile',  'as' => 'api.v1.auth.userProfile']);
        });
        Route::group(['prefix' => '/exchange'], function () {
            Route::match(['put', 'patch'], '/set-base-currency', ['uses' => 'ExchangeController@setBaseCurrency',  'as' => 'api.v1.exchange.setBaseCurrency']);
            Route::get('/rates', ['uses' => 'ExchangeController@listCurrenciesWithExchangeRate',  'as' => 'api.v1.exchange.rates']);
            Route::post('/set-alert-threshold', ['uses' => 'ExchangeController@setAlertThreshold',  'as' => 'api.v1.exchange.setAlertThreshold']);
        });
        Route::post('/calculate-repayment', ['uses' => 'ExchangeController@calculateRepayment',  'as' => 'api.v1.calculateRepayment']);
    });
});
