<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\GameRequest;
use App\Http\Resources\Api\V1\GameResource;
use App\Interfaces\GameStateInterface;
use App\Models\Game;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameController extends Controller
{
    public function __construct(
       public GameStateInterface $gameState
    ) {
    }

    /**
     * @return JsonResource
     */
    public function index(): JsonResource
    {
        $gameState = Game::firstOrCreate(
            [],
            $this->gameState->initGameData()
        );

        return (new GameResource($gameState));
    }

    /**
     * @param GameRequest $request
     * @param string $piece
     * @return JsonResponse|JsonResource
     */
    public function piece(GameRequest $request, string $piece): JsonResponse|JsonResource
    {
        // before anything else, make sure piece is either x or o
        if(!in_array($piece, ['x', 'o']))
        {
            return response()->json(['error' => 'Invalid piece'], 400);
        }

        // get the game
        $gameState = Game::firstOrFail();

        // check if it is turn for $piece
        if($piece !== $gameState->current_turn)
        {
            return response()->json(['error' => 'Piece is being placed out of turn'],406);
        }

        // check if the slot is occupied, here x is for parent array and y for child array.
        if(!empty($gameState->board[$request->x][$request->y]))
        {
            return response()->json(['error' => 'Slot has already been taken'],409);
        }

        $gameState->update($this->gameState->updateGameState($gameState, $request, $piece));

        // return updated game
        return (new GameResource($gameState));
    }

    /**
     * @return JsonResource
     */
    public function restart(): JsonResource
    {
        // get the game
        $gameState = Game::firstOrFail();
        // update the data
        $gameState->update($this->gameState->resetGameState());

        return (new GameResource($gameState));
    }

    /**
     * @return JsonResponse
     */
    public function destroy(): JsonResponse
    {
        // get the game
        $gameState = Game::firstOrFail();
        // reset the data
        $gameState->update( $this->gameState->initGameData());

        return response()->json(["currentTurn" => $gameState->current_turn], 200);
    }


}
