<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Game;

class GameControllerAPI extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'created_by' => 'required',
            'deck_used' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => $validator->errors()]);
        } else {
        	$game = new Game;
            $game->created_by = $request->get('created_by');
            $game->deck_used = $request->get('deck_used');


            $game = Game::create([
				'created_by' => $request['created_by'],
				'deck_used' => $request['deck_used']
			]);

			$game->players()->attach($request['created_by'],['team_number' => 1]);

            return response()->json(['msg' => 'Game Created', 'game' => $game], 200);
        }
    }

    public function join(Request $request) {
    	$validator = Validator::make($request->all(),[
    		'user_id' => 'required',
            'game_id' => 'required',
            'team_number' => 'required'  
    	]);

    	if ($validator->fails()) {
            return response()->json(['msg' => $validator->errors()]);
        } else {

            $game = Game::findOrFail($request->get('game_id'));

			$game->players()->attach($request['user_id'],['team_number' => $request['team_number']]);

            return response()->json(['msg' => 'Join successfull'], 200);
        }
    }

    public function start(Request $request) {
    	$validator = Validator::make($request->all(),[
            'game_id' => 'required'
    	]);

    	if ($validator->fails()) {
            return response()->json(['msg' => $validator->errors()]);
        } else {

            $game = Game::findOrFail($request->get('game_id'));
			$game->status = 'active';
			$game->save();

            return response()->json(['msg' => 'Game Started'], 200);
        }
    }
}
