<?php

namespace App\Http\Controllers;

use App\Classes\ChallengeGenerator;
use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class GameController extends Controller
{
    public function __construct(private readonly ChallengeGenerator $challengeGenerator) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $owned = $request->query('owned', false);
        if ($owned) {
            $games = $request->user()->created_games;
        } else {
            $games = Game::get();
        }

        return view('games.index', compact('games', 'owned'));
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
        $data = $request->safe()->only('name');
        $user = $request->user();

        $user->created_games()
            ->create(['name' => $data['name']]);

        return redirect()->route('games.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Game $game)
    {
        $isCreator = ! is_null($request->user()->created_games->find($game->id));

        if (! Gate::allows('view', [$game, $isCreator])) {
            abort(403);
        }

        $stage = $game->play($request->user(), boolval($request->input('next', false)));
        $disabledKeys = $stage->isOver() ? true : $stage->getGuesses()->all();

        return view('games.show', compact('game', 'stage', 'disabledKeys'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        // Not Implemented
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGameRequest $request, Game $game)
    {
        $isCreator = ! is_null($request->user()->created_games->find($game->id));

        if (! Gate::allows('update', [$game, $isCreator])) {
            abort(403);
        }

        $stage = $game->play($request->user());

        if ($request->input('skip')) {
            $stage->skip();
        } else {
            $guess = $request->safe()->guess;
            $stage->guess($guess);
        }

        return redirect()->route('games.show', compact('game'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        //
    }
}
