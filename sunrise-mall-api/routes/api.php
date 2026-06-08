<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ShopController;

Route::prefix('v1')->group(function () {
    // 【追加】XML専用ルーティング
    Route::get('shops.xml', [ShopController::class, 'indexXml']);
    // 既存のルーティング（JSON用）
    Route::apiResource('shops', ShopController::class);
});
