<?php

use App\Services\RabbitMqService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('test');
});

Route::post('/send', function () {
    RabbitMqService::sendMessage(request()->input('message'), 'queue_name');

    return redirect()->back()->with('success', 'Message sent successfully');
})->name('send');
