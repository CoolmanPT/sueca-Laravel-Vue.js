<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Game;
use Carbon\Carbon;
use App\Http\Resources\UserStatsResource;
use App\Http\Resources\GamesPerDayResource as GamesPerDayResource;
use Validator;
use DB;

class StatisticsControllerAPI extends Controller
{
    public function statistics() {
    	$statistics = [];

    	$statistics['registedUsers'] = $this->registedUsers();
    	$statistics['activeGames'] = $this->activeGames();
    	$statistics['totalGames'] = $this->totalGames();

    	$statistics['top5MostGames'] = $this->top5MostGames();
    	$statistics['top5MostPoints'] = $this->top5MostPoints();
    	$statistics['top5BestAvg'] = $this->top5BestAvg();

    	return response()->json(['statistics' => $statistics], 200);
    }

    public function registedUsers() {
    	return User::where('admin',0)->count();
    }

    public function activeGames() {
    	return Game::where('status', 'active')->count();
    }

    public function totalGames() {
    	return Game::where('status', 'terminated')->count();
    }

    public function top5MostGames() {
    	$users = User::where('admin',0)->orderBy('total_games_played', 'desc')->take(5)->get();

    	return UserStatsResource::collection($users);
    }

    public function top5MostPoints() {
    	$users = User::where('admin',0)->orderBy('total_points', 'desc')->take(5)->get();

    	return UserStatsResource::collection($users);
    }

    public function top5BestAvg() {
    	$users = DB::table('users')->where('admin',0)
                ->orderByRaw('(total_points / total_games_played) DESC')
                ->take(5)
                ->get();

        return UserStatsResource::collection($users);
    }

    public function getUsersStatistics() {
        $arrayofUsers = array();

        $users = User::where('admin', 0)->get();
        foreach ($users as $user) {
            $userarray = array('nickname' => $user->nickname, 
                                'total_points' => $user->total_points,
                                'total_games_played' => $user->total_games_played,
                                'wins' => 0,
                                'ties' => 0,
                                'losses' => 0);
            foreach ($user->games as $game) {
                if (is_null($game->team_winner)) {
                    // tie
                    $userarray['ties']++;

                } else {
                
                    if ($game->team_winner == $game->pivot->team_number) {
                        // win
                        $userarray['wins']++;
                    } else {
                        // loss
                        $userarray['losses']++;
                    }
                }
            }
            array_push($arrayofUsers, $userarray);
        }

        return $arrayofUsers;

    }

    public function getGamesPerDate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dateStart' => 'required|date',
            'dateFinish' => 'required|date'
        ]);

        if ($request->wantsJson() && !$validator->fails()) {


            $carbonBegin = new Carbon($request->input('dateStart'));
            $carbonEnd = new Carbon($request->input('dateFinish'));

            $from = $carbonBegin->format('Y-m-d 00:00:00');
            $to = $carbonEnd->format('Y-m-d 00:00:00');

             $query = DB::table('games')->select(DB::raw('DATE(created_at) as date, count(*) as numberOfGames'))
                ->whereBetween('created_at', array($from, $to))
                ->groupBy('date')->get();

            if(count($query) == 0){
                return GamesPerDayResource::collection($query);
            } else {
                $dates = $this->generateDateRange($carbonBegin, $carbonEnd);
                $collection = collect($query);
                $merged = $collection->merge($dates);
                $unique = $merged->unique('date');
                $sorted = $unique->sortBy('date');
                $finalCollection = $sorted->flatten(1);

                return GamesPerDayResource::collection($finalCollection);
            }

        } else {
            return response()->json(['msg' => 'Invalid Request.'], 400);
        }
    }

    private function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];

        for ($date = $start_date; $date->lte($end_date); $date->addDay()) {
            $dates[] =  (object) array('date' => $date->format('Y-m-d'), 'numberOfGames' => 0);
        }

        $dates[] = (object) array('date' => $end_date->format('Y-m-d'), 'numberOfGames' => 0);

        return $dates;
    }
}
