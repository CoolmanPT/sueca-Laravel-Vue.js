<?php

namespace App\Http\Controllers;

use App\Deck;
use App\Http\Resources\DeckResource;
use Illuminate\Http\Request;
use Validator;
use App\Card;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class DeckControllerAPI extends Controller
{
    public function getDecks(Request $request)
    {
        if ($request->wantsJson()) {
            $decks = Deck::where('active', 1)->with('cards')->get();

            return DeckResource::collection($decks);
        } else {
            return response()->json(['message' => 'Request inválido.'], 400);
        }
    }

    public function deleteDeck($id)
    {
        try {
            $deck = Deck::findOrFail($id);
            $deleted = Card::where('deck_id', $deck->id)->delete();
            File::deleteDirectory('img/decks/' . $deck->name);
            $deck->delete();
            return response()->json("Success", 204);
        } catch (\Exception $e) {
            print_r($e);
            exit();
            return response()->json(['msg' => 'Problem sending email'], 400);
        }
    }


    public function newDeck(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'image' => 'required|image64:jpeg,jpg,png',
            'name' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(['msg' => $validator->errors()]);
        } else {

            $fileName = 'semFace.png';
            File::makeDirectory(public_path('img/decks/') . $request->get('name'), $mode = 0777, true, true);
            $path = public_path('img/decks/') . $request->get('name');
            
            
            
            Image::make($request->get('image'))->resize(75, 125)->save($path . '/' . $fileName);
            $deck = new Deck;
            $deck->name = $request->get('name');
            $deck->hidden_face_image_path = 'img/decks/' . $request->get('name') . '/' . $fileName;
            $deck->save();
            return response()->json(['user' => $request->user, 'error' => false]);
        }
    }


    public function addAllCards(Request $request)
    {

        try {
            $deck = Deck::findOrFail($request->get('deck')['id']);
            Card::where('deck_id', $deck->id)->delete();
            $this->fillHeart($deck);
            $this->fillSpade($deck);
            $this->fillDiamond($deck);
            $this->fillClub($deck);
            $deck->complete = 1;
            $deck->save();

            return response()->json(['msg' => 'Cards added with success'], 200);

        }catch (\Exception $ex) {
            return response()->json(['msg' => 'Error filling the deck.', 'exception' => $ex->getMessage()], 400);
        }
    }

    private function fillHeart(Object $deck)
    {
        $values = ['Ace', '2', '3', '4', '5', '6', '7', 'Jack', 'Queen', 'King'];
        $imgs = ['c1.png', 'c2.png', 'c3.png', 'c4.png', 'c5.png', 'c6.png', 'c7.png', 'c11.png', 'c12.png', 'c13.png'];
        $i = 0;
        $path = public_path('img/decks/') . $deck->name;
        foreach ($values as $value) {
            $card = New Card;
            $card->value = $value;
            $card->suite = 'Heart';
            $card->deck_id = $deck->id;
            Image::make(public_path('img/cards/'). $imgs[$i])->resize(75, 125)->save($path . '/' . $imgs[$i]);
            $card->path = 'img/decks/'. $deck->name . '/' . $imgs[$i];
            $card->save();
            $i++;
        }

    }

    private function fillSpade(Object $deck)
    {
        $values = ['Ace', '2', '3', '4', '5', '6', '7', 'Jack', 'Queen', 'King'];
        $imgs = ['e1.png', 'e2.png', 'e3.png', 'e4.png', 'e5.png', 'e6.png', 'e7.png', 'e11.png', 'e12.png', 'e13.png'];
        $i = 0;
        $path = public_path('img/decks/') . $deck->name;
        foreach ($values as $value) {
            $card = New Card;
            $card->value = $value;
            $card->suite = 'Spade';
            $card->deck_id = $deck->id;
            Image::make(public_path('img/cards/'). $imgs[$i])->resize(75, 125)->save($path . '/' . $imgs[$i]);
            $card->path = 'img/decks/'. $deck->name . '/' . $imgs[$i];
            $card->save();
            $i++;
        }

    }

    private function fillDiamond(Object $deck)
    {
        $values = ['Ace', '2', '3', '4', '5', '6', '7', 'Jack', 'Queen', 'King'];
        $imgs = ['o1.png', 'o2.png', 'o3.png', 'o4.png', 'o5.png', 'o6.png', 'o7.png', 'o11.png', 'o12.png', 'o13.png'];
        $i = 0;
        $path = public_path('img/decks/') . $deck->name;
        foreach ($values as $value) {
            $card = New Card;
            $card->value = $value;
            $card->suite = 'Diamond';
            $card->deck_id = $deck->id;
            Image::make(public_path('img/cards/'). $imgs[$i])->resize(75, 125)->save($path . '/' . $imgs[$i]);
            $card->path = 'img/decks/'. $deck->name . '/' . $imgs[$i];
            $card->save();
            $i++;
        }

    }

    private function fillClub(Object $deck)
    {
        $values = ['Ace', '2', '3', '4', '5', '6', '7', 'Jack', 'Queen', 'King'];
        $imgs = ['p1.png', 'p2.png', 'p3.png', 'p4.png', 'p5.png', 'p6.png', 'p7.png', 'p11.png', 'p12.png', 'p13.png'];

        $i = 0;
        $path = public_path('img/decks/') . $deck->name;
        foreach ($values as $value) {
            $card = New Card;
            $card->value = $value;
            $card->suite = 'Club';
            $card->deck_id = $deck->id;
            Image::make(public_path('img/cards/'). $imgs[$i])->resize(75, 125)->save($path . '/' . $imgs[$i]);
            $card->path = 'img/decks/'. $deck->name . '/' . $imgs[$i];
            $card->save();
            $i++;
        }

    }

    public function getCurrentDeck(Request $request) {
        $deck = Deck::with('cards')->findOrFail($request->id);
        return $deck;
    }

    public function editCardImage (int $id, Request $request) {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image64:jpeg,jpg,png',
		]);
		if ($validator->fails()) {
			return response()->json(['msg' => $validator->errors()]);
		} else {
            $card = Card::findOrFail($id);
            $deck = Deck::with('cards')->findOrFail($card->deck_id);
            $imageData = $request->get('image');
            $suite = '';
            $value = '';
            switch (strtolower($card->suite[0])) {
                case 'h':
                    $suite = 'c';
                    break;
                case 's':
                    $suite = 'e';
                    break;
                case 'c':
                    $suite = 'p';
                    break;
                case 'd':
                    $suite = 'o';
                    break;
            }

            switch ($card->value) {
                case 'Ace':
                    $value = 1;
                    break;
                case 'Jack':
                    $suite = 11;
                    break;
                case 'Queen':
                    $suite = 12;
                    break;
                case 'King':
                    $suite = 13;
                    break;
            }


            Image::make($imageData)->resize(75, 125)->save(public_path('img/decks/') . $deck->name . '/'  . $suite . $value .  '.png');
            $card->path = 'img/decks/' . $deck->name . '/' . $suite . $value . '.png';
            $card->save();
            $deck->save();
			return response()->json(['deck' => $deck, 'card' => $card], 200);
		}
    }

    public function editDeckImg (int $id, Request $request) {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image64:jpeg,jpg,png',
		]);
		if ($validator->fails()) {
			return response()->json(['msg' => $validator->errors()]);
		} else {
            
            $deck = Deck::with('cards')->findOrFail($id);
            $imageData = $request->get('image');
            Image::make($imageData)->resize(75, 125)->save(public_path('img/decks/') . $deck->name . '/' . 'semFace.png');
            $deck->hidden_face_image_path = 'img/decks/' . $deck->name . '/' . 'semFace.png';;
            $deck->save();
			return response()->json(['deck' => $deck], 200);
		}
    }
}
