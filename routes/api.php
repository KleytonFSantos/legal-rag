<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatAction;

Route::get('/chat', ChatAction::class);
