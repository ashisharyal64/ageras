<?php

namespace Tests\Feature;

use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GameControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Game::create([
            'board' => array_fill(0, 3, array_fill(0, 3, '')),
            'score_x' => 0,
            'score_o' => 0,
            'current_turn' => 'x',
            'victory' => null
        ]);
    }

    public function test_game_can_get_initial_state_on_index()
    {
        $response = $this->get('/api');
        $response->assertStatus(200)
        ->assertJsonStructure([
            'board',
            'score' => ['x', 'o'],
            'currentTurn',
            'victory'
        ]);
    }

    public function test_game_can_place_a_piece()
    {
        $response = $this->postJson('/api/x', ['x' => 0, 'y' => 0]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'board',
                'score' => ['x', 'o'],
                'currentTurn',
                'victory'
            ])
        ->assertJsonPath('board.0.0', 'x')
        ->assertJsonPath('currentTurn', 'o');
    }

    public function test_game_can_be_restarted()
    {
        //place a piece first
        $this->postJson('/api/x', ['x' => 0, 'y' => 0]);

        //call restart
        $response = $this->postJson('/api/restart');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'board',
                'score' => ['x', 'o'],
                'currentTurn',
                'victory'
            ])
            ->assertJsonPath('board.0.0', '')
            ->assertJsonPath('currentTurn', 'x');

    }

    public function test_game_can_reset()
    {
        // place first piece
        $this->postJson('/api/x', ['x' => 0, 'y' => 0]);

        // call reset
        $response = $this->deleteJson('/api');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'currentTurn'
            ])
            ->assertJsonPath('currentTurn', 'x');


    }

    public function test_game_can_detect_piece_out_of_turn()
    {
        // place first piece by x
        $this->postJson('/api/x', ['x' => 0, 'y' => 0]);

        // place second piece by x again
        $response = $this->postJson('/api/x', ['x' => 0, 'y' => 1]);

        $response->assertStatus(406);

    }

    public function test_game_can_detect_invalid_piece()
    {
        $response = $this->postJson('/api/z', ['x' => 0, 'y' => 1]);

        $response->assertStatus(400);
    }

    public function test_game_can_detect_occupied_piece_placement()
    {
        // place first piece
        $this->postJson('/api/x', ['x' => 0, 'y' => 0]);

        // place another piece on same place
        $response = $this->postJson('/api/o', ['x' => 0, 'y' => 0]);

        $response->assertStatus(409);
    }
}
