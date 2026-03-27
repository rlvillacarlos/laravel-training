<?php

use App\Models\Challenge;
use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Game Model', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    describe('Relationships', function () {
        it('belongs to a user as creator', function () {
            $game = Game::factory()->for($this->user, 'creator')->create();
            expect($game->creator->is($this->user))->toBeTrue();
        });

        it('has 0 or more challenges', function () {
            $game = Game::factory()
                ->for($this->user, 'creator')
                ->create();

            expect($game->challenges)->toBeEmpty();

            $game = Game::factory()
                ->for($this->user, 'creator')
                ->has(Challenge::factory(3), 'challenges')
                ->create();
            expect($game->challenges()->count())->toBe(3);
        });

        it('has 0 or more gamers', function () {
            $game = Game::factory()
                ->for($this->user, 'creator')
                ->create();
            expect($game->gamers)->toBeEmpty();

            $game = Game::factory()
                ->for($this->user, 'creator')
                ->has(User::factory(3), 'gamers')
                ->create();
            expect($game->gamers()->count())->toBe(3);
        });
    });

    describe('Methods', function () {
        describe('getChallenge()', function () {
            it('creates and return a new challenge if none is available', function () {
                $game = Game::factory()
                    ->for($this->user, 'creator')
                    ->create();

                expect($game->challenges()->count())->toBe(0)
                    ->and($game->getChallenge())->not()->toBeNull()
                    ->and($game->challenges()->count())->toBe(1)
                    ->and($game->getChallenge())->not()->toBeNull()
                    ->and($game->challenges()->count())->toBe(2);
            });

            it('creates and return the next challenge with respect to another', function () {
                $game = Game::factory()
                    ->for($this->user, 'creator')
                    ->has(Challenge::factory(3), 'challenges')
                    ->create();

                $challenges = $game->challenges()->orderBy('id')->get();

                expect($challenges)->each(function ($challenge, $index) use ($game, $challenges) {
                    if ($index < $challenges->count() - 1) {
                        expect($game->getChallenge($challenge->value)->is($challenges[$index + 1]))->toBeTrue();
                    }
                });
            });
        });

        describe('getTopGamers()', function () {
            it('returns up to n gamers with non-zero scores', function () {
                $gamerCount = 20;
                $gamerCountHalf = intdiv($gamerCount, 2);
                $gamerCountQuarter = intdiv($gamerCount, 4);

                $game = Game::factory()
                    ->for($this->user, 'creator')
                    ->hasAttached(
                        User::factory($gamerCount),
                        new Sequence(['score' => 0], ['score' => 1]),
                        'gamers'
                    )->create();

                $moreTopGamers = $game->getTopGamers($gamerCountQuarter);
                $lessTopGamers = $game->getTopGamers($gamerCount);
                $equalTopGamers = $game->getTopGamers($gamerCountHalf);

                expect($moreTopGamers->count())->toBe($gamerCountQuarter)
                    ->and($moreTopGamers)->each(fn ($gamer) => $gamer->player->score->toBeGreaterThan(0))
                    ->and($lessTopGamers->count())->toBe($gamerCountHalf)
                    ->and($lessTopGamers)->each(fn ($gamer) => $gamer->player->score->toBeGreaterThan(0))
                    ->and($equalTopGamers->count())->toBe($gamerCountHalf)
                    ->and($equalTopGamers)->each(fn ($gamer) => $gamer->player->score->toBeGreaterThan(0));
            });
        });
    });
});
