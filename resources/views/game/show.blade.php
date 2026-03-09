@php
    $keygroups = [
        ['Q','W','E','R','T','Y','U','I','O','P'],
        ['A','S','D','F','G','H','J','K','L'],
        ['Z','X','C','V','B','N','M'],
    ];
@endphp

@if($game->isCompleted())
    <div>Congratulations!</div>
@elseif ($game->isFailed())
    <div>You failed! The word we are looking for is {{ $game->word }}</div>
@endif

<div>
    Category: {{ ucwords(str_replace('_',' ',$game->category)) }}
</div>
<div>
    Remaining Lives: {{ $game->lives }}
</div>
<div>
    {{ $game }}
</div>
<br />
<div>
    <form method="post" action="{{ route('game.update') }}">
        @method('put')
        @csrf

        @foreach($keygroups as $keys)
            <div>
                @foreach ($keys as $key)
                    <button type="submit" name="guess" value="{{ $key }}">
                        {{ $key }}
                    </button>
                @endforeach
            </div>
        @endforeach
    </form>
</div>
