<x-app>
    <x-slot:title>
        Available Games
    </x-slot:title>

    <h1>My Games</h1>

    <div>
        <a href="{{ route('games.create') }}">New Game</a> | 
        @if($owned)
        <a href="{{ route('games.index') }}">Show All Games</a>
        @else
        <a href="{{ route('games.index', ['owned'=>true]) }}">Show My Games Only</a>        
        @endif
    </div>
    <br/>
    @forelse ($games as $game)
        <div>
            {{ $loop->iteration }}. 
            <a href="{{ route('games.show', compact('game')) }}">
                {{ $game->name }} {{ $game->creator->is(auth()->user()) ? '*' : '' }}
            </a>
        </div>
    @empty
        <div>No Games</div>
    @endforelse
 
</x-app>
