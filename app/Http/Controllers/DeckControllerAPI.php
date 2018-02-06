<?php

namespace App\Http\Controllers;

use App\Deck;
use App\Http\Resources\DeckResource;
use Illuminate\Http\Request;

class DeckControllerAPI extends Controller
{
	public function getDecks(Request $request)
	{
		if ($request->wantsJson()) {
			$decks = Deck::where('active', 1)->cards->get();

			return DeckResource::collection($decks);
		} else {
			return response()->json(['message' => 'Request inválido.'], 400);
		}
	}

	public function delete($id)
	{

		try {
			$deck = Deck::findOrFail($id);

			$deck->delete();
			return response()->json(null, 204);
		} catch (\Exception $e) {
			print_r($e);
			exit();
			return response()->json(['msg' => 'Problem sending email'], 400);
		}
	}
}
