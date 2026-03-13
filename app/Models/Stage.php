<?php

namespace App\Models;

use Database\Factories\StageFactory;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Stage extends Pivot
{
    /** @use HasFactory<StageFactory> */
    use HasFactory;

    protected $table = 'stages';

    public $incrementing = true;

    protected $fillable = [
        'player_id', 'challenge_id',
        'guesses', 'correct_guesses',
        'is_skipped',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }

    public function lives(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->challenge->lives
                - $this->guesses->count()
                + $this->correct_guesses->count()
        );
    }

    public function guess(string $guess)
    {
        DB::transaction(function () use ($guess) {
            $this->guesses->push($guess);
            if ($this->challenge->contains($guess)) {
                $this->correct_guesses->push($guess);
            }
            $this->save();

            $this->player->increment('score', $this->isCompleted() ? 1 : 0);
        });

    }

    public function skip()
    {
        $this->update(['is_skipped' => true]);
    }

    public function isCompleted(): bool
    {
        return ! str_contains($this, '_');
    }

    public function isFailed(): bool
    {
        return $this->lives <= 0 || $this->is_skipped;
    }

    public function isCritical(): bool
    {
        return $this->lives < $this->challenge->lives / 2;
    }

    public function isOver(): bool
    {
        return $this->isCompleted() || $this->isFailed();
    }

    public function getGuesses(): Collection
    {
        return $this->guesses;
    }

    public function isAlreadyUsed(string $guess): bool
    {
        return $this->guesses->contains($guess);
    }

    public function __toString(): string
    {
        return collect(mb_str_split($this->challenge->word))
            ->map(fn ($char) => $this->correct_guesses->contains($char) ? $char : '_')
            ->implode(' ');
    }

    public function next(): Stage
    {
        if ($this->isOver()) {
            return Stage::create([
                'player_id' => $this->player->id,
                'challenge_id' => $this->challenge->next()->id,
                'guesses' => [],
                'correct_guesses' => [],
            ]);
        }

        return $this;
    }

    protected function casts(): array
    {
        return [
            'guesses' => AsCollection::class,
            'correct_guesses' => AsCollection::class,
            'is_skipped' => 'boolean',
        ];
    }
}
