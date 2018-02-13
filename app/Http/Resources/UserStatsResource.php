<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class UserStatsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'nickname' => $this->nickname,
            'total_points' => $this->total_points,
            'total_games_played' => $this->total_games_played,
            'wins' => $this->wins,
            'ties' => $this->ties,
            'losses' => $this->losses
        ];
    }
}
