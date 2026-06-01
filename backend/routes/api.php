<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

route::middleware('auth:sanctum')->group(function () {
    route::get("/user", function () {
        return auth()->user();
    });
});
