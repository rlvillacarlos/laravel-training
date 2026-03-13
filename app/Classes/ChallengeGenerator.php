<?php

namespace App\Classes;

use Illuminate\Support\Facades\Http;

class ChallengeGenerator
{
    private array $categories = ['animals', 'countries', 'programming_languages'];

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function generate(): RandomWord
    {
        $category = $this->categories[array_rand($this->categories)];

        $response = Http::withoutVerifying()
            ->get('https://random-words-api.kushcreates.com/api', [
                'language' => 'en',
                'category' => $category,
                'length' => 8,
                'type' => 'uppercase',
                'words' => 1,
            ]);

        $word = $response[0]['word'];

        return new RandomWord($category, $word);
    }
}
