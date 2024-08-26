<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //        return parent::toArray($request);
        return [
            'board' => $this->board,
            'score' => [
                'x' => $this->score_x,
                'o' => $this->score_o,
            ],
            'currentTurn' => $this->current_turn,
            'victory' => $this->victory,
        ];

    }
}
