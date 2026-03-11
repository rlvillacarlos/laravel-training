<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Challenge extends Model
{
    /** @use HasFactory<\Database\Factories\ChallengeFactory> */
    use HasFactory;

    protected $fillable = ['category', 'word'];

    public function game() : BelongsTo {
        return $this->belongsTo(Game::class);
    }

    public function challengers() : BelongsToMany {
        return $this->belongsToMany(
            Player::class,
            'stages',
            'challenge_id',
            'player_id'
        )->withPivot(['guesses', 'correct_guesses'])
        ->withTimestamps()
        ->using(Stage::class)
        ->as('stage');
    }

    public function lives() : Attribute
    {
        return Attribute::make(
            get: fn() => $this->game->starting_lives
        );
    }

    public function contains(string $guess){
        return str_contains($this->word, $guess);
    }

    public function next() : Challenge {
        return $this->game->getChallenge($this);
    }

    public function category() : Attribute {
        return Attribute::make(
            get: fn(string $category) =>  ucwords(str_replace('_',' ',$category))
        );
    }
}
