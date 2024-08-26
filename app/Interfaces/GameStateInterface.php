<?php

namespace App\Interfaces;


interface GameStateInterface
{
    public function updateGameState($gameState, $request, $piece):array;
    public function resetGameState();
    public function checkForVictory(array $board);

}
