<?php

namespace App\Services;

use App\Interfaces\GameStateInterface;
use Illuminate\Support\Arr;

class GameStateService implements GameStateInterface
{

    /**
     * prepare gamedata with piece, score or victory as necessary
     * @param $gameState
     * @param $request
     * @param $piece
     * @return array
     */
    public function updateGameState($gameState, $request, $piece): array
    {
        // update board
        $board = $gameState->board;
        $board[$request->x][$request->y] = $piece;

        // check for victory
        $victory = $this->checkForVictory($board);

        // prepare update data, can also be achieved by simple save.
        $updatedBoard = [
            'board' => $board,
            'current_turn' => $gameState->current_turn === 'x' ? 'o' : 'x',
            'victory' => $victory
        ];

        if(!is_null($victory)) {
            $scoreField = 'score_'.$victory;
            $updatedBoard[$scoreField] = $gameState->$scoreField + 1;
        }

        return $updatedBoard;
    }

    /**
     * prepare game data for reset state
     * @return array
     */
    public function resetGameState(): array
    {
        $restartGame = $this->initGameData();
        unset($restartGame['score_x'], $restartGame['score_o']);
        return $restartGame;

    }

    /**
     * check if game is won or not with provided board
     * @param $board
     * @return mixed|null
     */
    public function checkForVictory($board): mixed
    {
        // all possible winning combinations
        $winningCombinations = [
            [$board[0][0], $board[0][1], $board[0][2]],
            [$board[1][0], $board[1][1], $board[1][2]],
            [$board[2][0], $board[2][1], $board[2][2]],
            [$board[0][0], $board[1][0], $board[2][0]],
            [$board[0][1], $board[1][1], $board[2][1]],
            [$board[0][2], $board[1][2], $board[2][2]],
            [$board[0][0], $board[1][1], $board[2][2]],
            [$board[0][2], $board[1][1], $board[2][0]]
        ];

        foreach($winningCombinations as $combination)
        {
            $emptyRemoved = array_filter($combination);
            // remove any array with empty slots
            if (3 !== count($emptyRemoved)) {
                continue;
            }
            // disregard any array with 2 unique items, winning array has only one unique item
            if( 1 === count(array_unique($emptyRemoved))) {
                return $emptyRemoved[0];
            };
        }
        return null;
    }

    /**
     * Initial game state data
     * @return array{board: array, score_x: int, score_o: int, current_turn: string, victory: null}
     */
    public function initGameData(): array
    {
        return [
            'board' => array_fill(0, 3, array_fill(0, 3, '')),
            'score_x' => 0,
            'score_o' => 0,
            'current_turn' => 'x',
            'victory' => null,
        ];
    }


}
