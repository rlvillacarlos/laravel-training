<?php

use App\Classes\ChallengeGenerator;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/game', function (Request $request, ChallengeGenerator $challengeGenerator){
    if($request->session()->exists('game')){
        $game = $request->session()->get('game');
    }else{
        $game = $challengeGenerator->generate();
        $request->session()->put('game', $game);
    }

    if($game->isCompleted() || $game->isFailed()){
        $request->session()->forget('game');
    }

    return view('game.show', compact('game'));
})->name('game.show');

Route::put('/game', function (Request $request) {
    $guess = $request->input('guess');

    $game = $request->session()->get('game');
    $game->guess($guess);

    return redirect()->route('game.show');
})->name('game.update');