<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Http\Resources\ShopResource;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * 店舗一覧を取得する（絞り込み機能付き）
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'floor' => 'nullable|string|max:10',
            'category' => 'nullable|string|max:50',
        ]);

        $query = Shop::query();

        if($request->has('floor')) {
            $query->where('floor', $validated['floor']);
        }

        if($request->has('category')) {
            $query->where('category', $validated['category']);
        }

        $shops = $query->get();

        return ShopResource::collection($shops);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $shop = Shop::findOrFail($id);

        return new ShopResource($shop);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
