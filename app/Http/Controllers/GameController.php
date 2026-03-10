<?php

namespace App\Http\Controllers;

use App\Classes\ChallengeGenerator;
use App\Http\Requests\StoreGameRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $games = $request->session()->get('games', []);

        return view('games.index', compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('games.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGameRequest $request, ChallengeGenerator $challengeGenerator)
    {
        $games = $request->session()->get('games', []);

        $data = $request->safe()->only('name');

        $id = Str::uuid()->toString();

        $games[$id] = [
            'name' => $data['name'],
            'challenge' => $challengeGenerator->generate()
        ];

        $request->session()->put('games', $games);

        return redirect()->route('games.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
