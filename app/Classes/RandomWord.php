<?php

namespace App\Classes;

class RandomWord
{
    public function __construct(
        public readonly string $category,
        public readonly string $word
    ) {/** Empty */
    }
}
