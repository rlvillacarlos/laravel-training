<x-app>
    <x-slot:title>
        User Not Verified
    </x-slot:title>
    @session('message')
    <div>
        {{ session('message') }}
    </div>
    @endsession
    Please verify your email first.

    <a href="{{ route('verification.send') }}">Resend Email Validation Link</a>
</x-app>