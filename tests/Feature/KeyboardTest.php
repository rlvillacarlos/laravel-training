<?php

use App\View\Components\Keyboard;
use Sinnbeck\DomAssertions\Asserts\AssertElement;

describe('Keyboard Component', function () {
    beforeEach(function () {
        $this->letters = collect([
            'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P',
            'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L',
            'Z', 'X', 'C', 'V', 'B', 'N', 'M',
        ]);
    });
    
    it('shows all letters in QWERTY layout', function () {       
        $keyboard = $this->component(Keyboard::class, ['disabledKeys' => []]);
        $keyboard->assertElementExists('button', function (AssertElement $assertElement) {
            $assertElement->each('button', function (AssertElement $assertElement, $index) {
                $assertElement->has('value', $this->letters[$index])
                    ->containsText($this->letters[$index]);
            });
        });      
    });

    it('allows disabling all letters', function () {
        $keyboard = $this->component(Keyboard::class, ['disabledKeys' => true]);
        $this->letters->each(function ($letter) use($keyboard){
            $keyboard->assertElementExists("button:disabled[value='$letter']");
            $keyboard->assertDoesntExist("button:enabled[value='$letter']");
        });        
    });

    it('only disables selected letters', function () {
        $toDisable = ['Q','A','Z'];
        $keyboard = $this->component(Keyboard::class, ['disabledKeys' => $toDisable]);
        $this->letters->each(function ($letter) use($keyboard, $toDisable){
            if(in_array($letter, $toDisable)){
                $keyboard->assertElementExists("button:disabled[value='$letter']");
                $keyboard->assertDoesntExist("button:enabled[value='$letter']");
            }else{
                $keyboard->assertElementExists("button:enabled[value='$letter']");
                $keyboard->assertDoesntExist("button:disabled[value='$letter']");
            }
        });  
    });
});
