<?php

use App\Classes\LocalChallengeGenerator;
use App\Classes\RandomWord;
use App\Interfaces\ChallengeGenerator;
use Illuminate\Support\Collection;

describe('LocalChallengeGenerator', function (){
    beforeEach(function () {
        $this->generator = app(LocalChallengeGenerator::class);
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
        it('retrieves random word', function () {
            $result = $this->generator->generate(); 
            expect($result)->toBeInstanceOf(RandomWord::class)
                ->and($result->category)->not()->toBeEmpty()
                ->and($result->word)->not()->toBeEmpty();
        });
    });  
    
});
