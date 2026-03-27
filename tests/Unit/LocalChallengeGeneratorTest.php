<?php

use App\Classes\LocalChallengeGenerator;
use App\Classes\RandomWord;
use App\Interfaces\ChallengeGenerator;
use Illuminate\Support\Collection;

describe('LocalChallengeGenerator', function () {
    beforeEach(function () {
        $this->generator = app(LocalChallengeGenerator::class);
    });

    it('implements ChallengeGenerator interface', function () {
        expect($this->generator)->toBeInstanceOf(ChallengeGenerator::class);
    });

    describe('getCategories', function () {
        it('returns collection of all defined categories', function () {
            $expectedCategories = collect(['animals', 'countries', 'programming_languages']);
            $categories = $this->generator->getCategories();

            expect($categories)->toBeInstanceOf(Collection::class)
                ->and($categories->diff($expectedCategories))->toBeEmpty()
                ->and($expectedCategories->diff($categories))->toBeEmpty();
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
