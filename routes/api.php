<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
|
| Текущий мастер приходит в заголовке X-Master-Id и уже разложен
| в атрибуты запроса middleware'ом ResolveCurrentMaster:
|
|     $master = $request->attributes->get('current_master');
|
| Здесь нужно написать три роута — см. README.md.
|
*/

Route::get('/ping', fn () => ['ok' => true]);

// TODO: POST /api/referrals/attach
Route::post('/referrals/attach', [BookingController::class, 'attach']);
Route::get('/referrals/my', [BookingController::class, 'my']);
Route::get('/referrals/earnings', [BookingController::class, 'earnings']);
// TODO: GET  /api/referrals/my
// TODO: GET  /api/referrals/earnings
