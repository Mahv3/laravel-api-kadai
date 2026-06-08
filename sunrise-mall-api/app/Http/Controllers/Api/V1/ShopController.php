<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Http\Resources\ShopResource;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use SimpleXMLElement;

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
        $shops = $this->buildShopQuery($request)->get();

        return ShopResource::collection($shops);
    }

    /**
     * 【追加】店舗一覧をXML形式で取得（絞り込み機能付き）
     */
    public function indexXml(Request $request) 
    {
        $shops = $this->buildShopQuery($request)->get();

        $xml = $this->createShopXml($shops);

        return response($xml->asXML(), 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShopRequest $request)
    {
        $validated = $request->validated();

        $shop = Shop::create($validated);

        return (new ShopResource($shop))
            ->response()
            ->setStatusCode(201);
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
    public function update(UpdateShopRequest $request, int $id)
    {
        $shop = Shop::findOrFail($id);

        $validated = $request->validated();

        $shop->update($validated);

        return new ShopResource($shop);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $shop = Shop::findOrFail($id);

        $shop->delete();

        return response()->noContent();
    }

    /**
     * 店舗一覧のクエリを組み立て
     */
    private function buildShopQuery(Request $request): Builder 
    {
        $validated = $request->validate([
            'floor' => 'nullable|string|max:10',
            'category' => 'nullable|string|max:50',
        ]);

        $query = Shop::query();

        if($request->filled('floor')) {
            $query->where('floor', $validated['floor']);
        }

        if($request->filled('category')) {
            $query->where('category', $validated['category']);
        }

        return $query;
    }

    /**
     * XML生成処理
     */
    private function createShopXml(Collection $shops): SimpleXMLElement 
    {
        $xml = new SimpleXMLElement('<shops/>');

        foreach($shops as $shop) {
            $node = $xml->addChild('shop');

            $node->addChild('id', (string) $shop->id);

            $node->addChild('name', htmlspecialchars($shop->name));

            $node->addChild('floor', htmlspecialchars($shop->floor));

            $node->addChild('category', htmlspecialchars($shop->category));

            $node->addChild('open_time', Carbon::parse($shop->open_time)->format('H:i'));

            $node->addChild('close_time', Carbon::parse($shop->close_time)->format('H:i'));

            $node->addChild('tel', htmlspecialchars($shop->tel ?? ''));

            $node->addChild('description', htmlspecialchars($shop->description ?? ''));

            $node->addChild('is_temporarily_closed', $shop->is_temporarily_closed ? 'true' : 'false');
        }

        return $xml;
    }
}
