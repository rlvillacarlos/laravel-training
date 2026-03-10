<x-app>
    <x-slot:title>
        My Games
    </x-slot:title>

    <h1>My Games</h1>

    <a href="{{ route('games.create') }}">New Game</a>

    @forelse ($games as $id => $game)
        <div>
            {{ $loop->iteration }}. <a href="{{ route('games.show', compact('id')) }}">{{ $game['name'] }}</a>
        </div>
    @empty
        <div>No Games</div>
    @endforelse
 
</x-app>
