<?php

use App\Classes\RandomWord;
use App\Classes\RandomWordsApiChallengeGenerator;
use App\Interfaces\ChallengeGenerator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

describe('RandomWordsApiChallengeGenerator', function (){
    beforeEach(function () {
        $this->generator = app(RandomWordsApiChallengeGenerator::class);
        $this->categories = $this->generator->getCategories();
    });

    it('implements ChallengeGenerator interface', function () {
        expect($this->generator)->toBeInstanceOf(ChallengeGenerator::class);
    });

    describe('getCategories', function () {        
        it('returns collection of categories', function () {
            expect($this->categories)->toBeInstanceOf(Collection::class);
        });
    
        it('has all defined categories', function () {
            expect($this->categories->diff(['animals','countries','programming_languages']))
                ->toBeEmpty();
        });
    });

    describe('generate', function () {
        it('calls API endpoint to retrieve random word', function () {
            $randomWord = "laravel";
            Http::shouldReceive('withoutVerifying')->once()->andReturnSelf();
            Http::shouldReceive('get')->once()->andReturn([['word'=>$randomWord]]);
            $result = $this->generator->generate(); 
            expect($result)->toBeInstanceOf(RandomWord::class)
                ->and($result->category)->not()->toBeEmpty()
                ->and($result->word)->toBe($randomWord);
        });
    });    
});