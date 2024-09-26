<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlayerResource;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\ErrorHandler\Debug;

class PlayersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new Response(
            Player::query()->select(['id', 'name'])->get()
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //$v = Player::where('id', $id)->get();
        // $v = Player::count();
        //Log::debug($v);

        return new Response(
            //Player::where('id', $id)->first()
            Player::find($id)
        );

        //return response()->json(["id" => $id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $v = [
            "name" => $request["name"],
            "hp" => $request["hp"],
            "mp" => $request["mp"],
            "money" => $request["money"]
        ];

        return new Response(["id" => Player::insertGetId($v)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $v = array();
        if ($request["name"]) {
            $v = $v + array("name" => $request["name"]);
        }
        if ($request["hp"]) {
            $v = $v + array("hp" => $request["hp"]);
        }
        if ($request["mp"]) {
            $v = $v + array("mp" => $request["mp"]);
        }
        if ($request["money"]) {
            $v = $v + array("money" => $request["money"]);
        }

        Player::where(['id' => $id])->update($v);


        // return new Response(
        //     Player::where(['id' => $id])->update($request->all())
        // );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return new Response(
            Player::where(['id' => $id])->delete()
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
}
