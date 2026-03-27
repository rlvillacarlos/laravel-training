<?php

use App\Classes\LocalChallengeGenerator;
use App\Classes\RandomWord;
use App\Models\Challenge;
use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Challenge Model', function () {
    beforeEach(function () {
        $this->game = Game::factory()
            ->for(User::factory(), 'creator')
            ->has(User::factory(3), 'gamers')
            ->create(['starting_lives' => 10]);
    });

    describe('Relationships', function () {
        it('belongs to a game', function () {
            $challenge = Challenge::factory()
                ->for($this->game)
                ->create();
            expect($challenge->game->is($this->game))->toBeTrue();
        });

        it('belongs to 0 or more players', function () {
            $challenge = Challenge::factory()
                ->for($this->game)
                ->create();
            expect($challenge->challengers)->toBeEmpty();

            $players = $this->game->gamers->map->player;
            $challenge = Challenge::factory()
                ->for($this->game)
                ->hasAttached($players, [], 'challengers')
                ->create();
            expect($challenge->challengers()->count())->not()->toBe(0)
                ->and($challenge->challengers->diff($players))->toBeEmpty();
        });
    });

    describe('Accessor/Mutators', function () {
        describe('contains', function () {
            beforeEach(function () {
                $this->mock(LocalChallengeGenerator::class)
                    ->shouldReceive('generate')
                    ->andReturn(new RandomWord('animals', 'HORSE'));
                $this->challenge = Challenge::factory()
                    ->for($this->game)
                    ->create();
            });

            it('returns true if the challenge word contains the given guess', function () {
                expect($this->challenge->contains('H'))->toBeTrue();
            });

            it('returns false if the challenge word does not contains the given guess', function () {
                expect($this->challenge->contains('X'))->toBeFalse();
            });
        });

        describe('category', function () {
            beforeEach(function () {
                $this->mock(LocalChallengeGenerator::class)
                    ->shouldReceive('generate')
                    ->andReturn(new RandomWord('a_category', 'WORD'));
                $this->challenge = Challenge::factory()
                    ->for($this->game)
                    ->create();
            });

            it('creates an attribute that returns the formatted category', function () {
                expect($this->challenge->category)->toBe('A Category');
            });
        });

        describe('lives', function () {
            beforeEach(function () {
                $this->challenge = Challenge::factory()
                    ->for($this->game)
                    ->create();
            });

            it('creates an attribute that returns the starting lives set in the game', function () {
                expect($this->challenge->lives)
                    ->toBe($this->game->starting_lives);
            });
        });
    });

    describe('Methods', function () {
        describe('next()', function () {
            it('returns the challenge that immediately follows base on order of creation', function () {
                $challenges = Challenge::factory(5)
                    ->for($this->game)
                    ->create()
                    ->sortBy('created_at');

                expect($challenges)->each(function ($challenge, $index) use ($challenges) {
                    $next = $challenge->next();
                    $expected = $challenges->get($index + 1);
                    if ($expected) {
                        $next->is($expected)->toBeTrue();
                    }
                });
            });

            it('returns null if nothing follows', function () {
                $challenge = Challenge::factory()
                    ->for($this->game)
                    ->create();

                expect($challenge->next())->toBeNull();
            });
        });
    });
});
