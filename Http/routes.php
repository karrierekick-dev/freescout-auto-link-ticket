<?php

Route::group(['middleware' => 'web', 'prefix' => \Helper::getSubdirectory(), 'namespace' => 'Modules\AutoLinkTicket\Http\Controllers'], function()
{
    Route::get('/', 'AutoLinkTicketController@index');
});
