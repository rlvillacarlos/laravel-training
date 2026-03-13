<?php

namespace App\Http\Controllers;

use App\Http\Requests\Registration\SaveRegistrationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

class RegistrationController extends Controller
{
    public function show()
    {
        return view('registration.show');
    }

    public function save(SaveRegistrationRequest $request)
    {
        $data = $request->safe(['name', 'email', 'password']);
        event(new Registered(User::create($data)));

        return redirect()->route('registration.show')->with('success', true);
    }
}
