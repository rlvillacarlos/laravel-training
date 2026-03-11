<x-app>
    <x-slot:title>
        {{ $stage->challenge->category }}
    </x-slot:title>
    
    <h1>{{ $game->name }}</h1>
    @if($stage->isCompleted())
        <div>Congratulations!</div>
    @elseif ($stage->isFailed())
        <div>You failed! The word we are looking for is {{ $stage->challenge->word }}</div>
    @endif

    <div>
        Score: {{ $stage->player->score }}
    </div>

    <div>
        Category: {{ $stage->challenge->category }}
    </div>
    <div>
        Remaining Lives: {{ $stage->lives }}
    </div>
    <div>
        {{ $stage }}
    </div>
    <br />
    <div>
        <form method="post" action="{{ route('games.update', compact('game')) }}">
            @method('put')
            @csrf

            @error('guess')
                <div>{{ $message }}</div>
            @enderror
            <x-keyboard :disabled-keys="$disabledKeys"/>

            @if(!$stage->isOver())
            <div>
                <button type="submit" name="skip" value=true>
                    Skip stage
                </button>
            </div>
            @else
                <a href="{{ route('games.show', ['game'=>$game, 'next'=>true]) }}">Next stage</a>
            @endif
        </form>
    </div>
    @php $topGamers = $game->getTopGamers() @endphp
    
    @if($topGamers->isNotEmpty())
    <br/>
    <hr/>
    <div>
        <h2>Top Players</h2>
        <ol>
            @foreach ($topGamers as $gamer)
            <li>
                {{ $gamer->name }} - {{ $gamer->player->score }} point(s)
            </li>
            @endforeach
        </ol>
    </div>
    @endif
</x-app>