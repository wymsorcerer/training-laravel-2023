<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Player;
use App\Models\PlayerItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

define("MAX_HP", 200);
define("MAX_MP", 200);
define("MAX_ITEM_COUNT", 99);

define("STATUS_NOT_FOUND", 404);
define("STATUS_COMMON_ERROR", 400);

define("GACHA_PRICE", 10);

class PlayerItemController extends Controller
{
    public function itemList($id)
    {
        $result = PlayerItem::where(['player_id' => $id])->get();
        if ($result) {
            Log::debug("result=");
        }
        return new Response(
            $result
        );
    }

    public function addItem(Request $request, $id)
    {
        if (PlayerItem::where(['player_id' => $id, "item_id" => $request->itemId])->exists()) {
            // update
            $player_items = PlayerItem::where(['player_id' => $id, "item_id" => $request->itemId])->get();
            $newCount =  $player_items[0]->count + $request->count;
            PlayerItem::where(['player_id' => $id, "item_id" => $request->itemId])->update([
                "count" => $newCount
            ]);
            return new Response([
                "itemId" => $request->itemId,
                "count" => $newCount
            ]);
        } else {
            PlayerItem::insert([
                "player_id" => $id,
                "item_id" => $request->itemId,
                "count" => $request->count
            ]);
            return new Response([
                "itemId" => $request->itemId,
                "count" => $request->count
            ]);
        }
    }


    public function useItem(Request $request, $id)
    {
        Log::debug($request);
        Log::debug($id);
        if (!PlayerItem::where(['player_id' => $id, "item_id" => $request->itemId])->exists()) {
            return new Response("アイテム存在しない", STATUS_NOT_FOUND);
        }

        $player_items = PlayerItem::where(['player_id' => $id, "item_id" => $request->itemId])->get();
        Log::debug($player_items);
        if ($player_items[0]->count < $request->count) {
            return new Response("アイテム数足りない", STATUS_COMMON_ERROR);
        }

        $player = Player::find($id);
        $item = Item::find($request->itemId);

        try { //transaction
            DB::beginTransaction();

            if ($player_items[0]->item_id == 1 && $player->hp < MAX_HP) { //hp回復			
                //上限まで使う数を計算
                $cnt = ((int)((MAX_HP - $player->hp) / $item->value)) + 1 < $request->count
                    ? ((int)((MAX_HP - $player->hp) / $item->value)) + 1 : $request->count;

                // count数更新
                PlayerItem::where(['player_id' => $id, "item_id" => $request->itemId])->update([
                    "count" => $player_items[0]->count - $cnt
                ]);

                // hp更新
                $hp = $player->hp + $item->value * $cnt > MAX_HP ? MAX_HP : $player->hp + $item->value * $cnt;
                Player::where(['id' => $id])->update(["hp" => $hp]);
            } else if ($player_items[0]->item_id == 2 && $player->mp < MAX_MP) { //mp回復
                //上限まで使う数を計算
                $cnt = ((int)((MAX_MP - $player->mp) / $item->value)) + 1 < $request->count
                    ? ((int)((MAX_MP - $player->mp) / $item->value)) + 1 : $request->count;

                // count数更新
                PlayerItem::where(['player_id' => $id, "item_id" => $request->itemId])->update([
                    "count" => $player_items[0]->count - $cnt
                ]);

                // mp更新
                $mp = $player->mp + $item->value * $cnt > MAX_MP ? MAX_MP : $player->mp + $item->value * $cnt;
                Player::where(['id' => $id])->update(["mp" => $mp]);
            } else {
                return new Response("HP/MPがMAXだったため、アイテムを使用しなかった", STATUS_COMMON_ERROR);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }


        // success response
        // 最新のデータを取得
        $player = Player::find($id);
        $player_items = PlayerItem::where(['player_id' => $id, "item_id" => $request->itemId])->get();

        return new Response([
            "itemId" => $request->itemId,
            "count" => $player_items[0]->count,
            "player" => $player
        ]);
    }
}
