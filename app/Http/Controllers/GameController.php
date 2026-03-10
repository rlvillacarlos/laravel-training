<?php

namespace App\Http\Controllers;

use App\Classes\ChallengeGenerator;
use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GameController extends Controller
{
    public function __construct(private readonly ChallengeGenerator $challengeGenerator)
    {
    }

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
    public function store(StoreGameRequest $request)
    {
        $games = $request->session()->get('games', []);

        $data = $request->safe()->only('name');

        $id = Str::uuid()->toString();

        $games[$id] = [
            'name' => $data['name'],
            'challenge' => $this->challengeGenerator->generate()
        ];

        $request->session()->put('games', $games);

        return redirect()->route('games.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $games = $request->session()->get('games', []);

        if(!array_key_exists($id, $games)){
            abort(404);
        }

        $game = $games[$id];
        $name = $game['name'];
        $challenge = $game['challenge'];
        $disabledKeys = false;
        
        if($challenge->isOver()) {
            $disabledKeys = true;
            $game['challenge'] = $this->challengeGenerator->generate();
            $games[$id] = $game;
            $request->session()->put('games', $games);          
        } else {
            $disabledKeys = $challenge->getGuesses();
        }
        
        return view('games.show', compact('id', 'name', 'challenge', 'disabledKeys'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Not Implemented
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGameRequest $request, string $id)
    {
        $games = $request->session()->get('games', []);

        if(!array_key_exists($id, $games)){
            abort(404);
        }

        $game = $games[$id];
        $challenge = $game['challenge'];

        $skip = $request->input('skip', false);
        if($skip){
            $challenge->skip();
        }else{
            $guess = $request->safe()->guess;
            $challenge->guess($guess);
        }

        return redirect()->route('games.show', compact('id'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
