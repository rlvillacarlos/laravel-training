<x-app>
    <x-slot:title>
        New Game
    </x-slot:title>
    <h1>New Game</h1>
    <form method="post" action="{{ route('games.store') }}">
        @csrf
        <div>
            <label for="name">Name:</label>
            <input id="name" name="name" type="name" value="{{ old('name') }}" required >
            @error('name')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            <button type="submit">Create</button>
        </div>
    </form>
</x-app>