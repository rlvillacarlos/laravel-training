<x-app>
    <x-slot:title>
        Log-in
    </x-slot:title>
    <h1>Log-in to Hangman</h1>
    <form method="post" action="{{ route('auth.login') }}">
        @csrf
        <div>
            <label for="name">
                Name:
            </label>
            <input type="text" name="name" id="name" required value="{{ old('name') }}" />
            @error('name')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="password">
                Password:
            </label>
            <input type="password" name="password" id="password" required />
            @error('password')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            <button type="submit">Log-in</button>
        </div>
        <hr/>
        @error('oauth')
            <div>{{ $message }}</div>
        @enderror
        <a href="{{ route('registration.show') }}">[Register Account]</a> | 
        <a href="{{ route('oauth.show') }}">[Login via Google]</a>
    </form>
</x-app>