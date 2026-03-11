<x-app>
    <x-slot:title>
        Create Player Account
    </x-slot:title>

    <h1>Create Player Account</h1>
    @if(session('success'))
    <div>New Account Created!</div>
    @endif
    <form method="post" action="{{ route('registration.save') }}">
        @csrf
        <div>
            <label for="name">
                Player Name:
            </label>
            <input type="text" name="name" id="name" required value="{{ old('name') }}"/>
            (Must be letters,numbers, - and _)
            @error('name')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="email">
                Email:
            </label>
            <input type="email" name="email" id="email" required value="{{ old('email') }}" />
            @error('email')
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
            <label for="password_confirmation">
                Confirm Password:
            </label>
            <input type="password" name="password_confirmation" id="password_confirmation" required />
            @error('password_confirmation')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            <button type="submit">Create Account</button>
        </div>
    </form>
</x-app>