<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use App\Broadcasting\RecordsChanel;
use Illuminate\Support\Facades\Broadcast;

Route::post('/broadcasting/auth', function(Illuminate\Http\Request $req){
    return 'global';
});

Broadcast::channel('records',RecordsChanel::class);


