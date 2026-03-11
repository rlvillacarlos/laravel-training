<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    /** @use HasFactory<\Database\Factories\GameFactory> */
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $fillable = ['name', 'starting_lives'];

    public function creator() : BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
