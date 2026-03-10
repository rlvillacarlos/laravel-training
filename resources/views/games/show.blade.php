<x-app>
    <x-slot:title>
        {{ ucwords(str_replace('_',' ',$challenge->category)) }}
    </x-slot:title>
    
    <h1>{{ $name }}</h1>
    @if($challenge->isCompleted())
        <div>Congratulations!</div>
    @elseif ($challenge->isFailed())
        <div>You failed! The word we are looking for is {{ $challenge->word }}</div>
    @endif

    <div>
        Category: {{ ucwords(str_replace('_',' ',$challenge->category)) }}
    </div>
    <div>
        Remaining Lives: {{ $challenge->lives }}
    </div>
    <div>
        {{ $challenge }}
    </div>
    <br />
    <div>
        <form method="post" action="{{ route('games.update', compact('id')) }}">
            @method('put')
            @csrf

            @error('guess')
                <div>{{ $message }}</div>
            @enderror
            <x-keyboard :disabled-keys="$disabledKeys"/>

            @if(!$challenge->isOver())
            <div>
                <button type="submit" name="skip" value=true>
                    Skip Challenge
                </button>
            </div>
            @else
                <a href="{{ route('games.show', compact('id')) }}">Next Challenge</a>
            @endif
        </form>
    </div>
</x-app>