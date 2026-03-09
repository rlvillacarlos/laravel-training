<?php

namespace App\Classes;

class Challenge
{
    private const MAX_LIVES = 6;

    public int $lives {
        get {
            return self::MAX_LIVES - count($this->usedLetters) + count($this->foundLetters);
        }
    }

    private array $foundLetters = [];

    private array $usedLetters = [];

    public function __construct(
        public readonly string $category,
        public readonly string $word 
    )
    {/** Empty */}

    public function getUsedLetters() : array {
        return $this->usedLetters;
    }

    public function guess(string $guess): bool {
        $this->usedLetters[] = $guess;

        if(str_contains($this->word, $guess)){
            $this->foundLetters[] = $guess;
            return true;
        }

        return false;
    }

    public  function isCompleted() : bool {
        return !str_contains($this, '_');
    }

    public  function isFailed() : bool {
        return $this->lives <= 0;
    }

    public function __tostring() : string {
        return implode(
            " ",
            array_map(
                fn ($char) => in_array($char, $this->foundLetters) ? $char : "_",
                mb_str_split($this->word)
            )
        );
    }
}
