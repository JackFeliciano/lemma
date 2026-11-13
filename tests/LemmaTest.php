<?php

declare(strict_types=1);

namespace Lemma\Tests;

use Lemma\Home\Bank;
use Lemma\Home\Form;
use Lemma\Home\Lemma;
use Lemma\Home\Sample;

return [
    ['a stored form applies its ending to the stem', static function (): void {
        $lemma = new Lemma('L-1', 'movement', 'jump', 'jump', 'verb', 'motion', 'jump-', 'to leave the ground', true);
        $lemma->add(new Form('past', 'ed'));
        Assert::same('jumped', $lemma->inflect('past', static fn (string $f): string => '-ed'));
    }],
    ['an unentered verb still takes the regular ending', static function (): void {
        $bank = new Bank();
        $lemma = new Lemma('L-2', 'movement', 'call', 'call', 'verb', 'contact', 'call-', 'to name out loud', true);
        $bank->add($lemma);
        Assert::same('call-ed', $lemma->inflect('past', $bank->regularEnding(...)));
    }],
    ['an irregular lemma refuses a form nobody stored', static function (): void {
        $lemma = new Lemma('L-3', 'movement', 'go', 'go', 'verb', 'motion', 'go-', 'to move away', false);
        try {
            $lemma->inflect('past', static fn (string $f): string => '-ed');
            throw new \RuntimeException('expected a refusal');
        } catch (\RuntimeException $e) {
            Assert::contains('no form', $e->getMessage());
        }
    }],
    ['lookup finds a stored word', static function (): void {
        $bank = Sample::bank();
        $found = $bank->lookup('walk');
        Assert::true($found !== null);
        Assert::same('LM-01', $found->id);
    }],
    ['lookup finds a derived word', static function (): void {
        $bank = Sample::bank();
        $found = $bank->lookup('ridden');
        Assert::true($found !== null);
        Assert::same('LM-02', $found->id);
    }],
    ['lookup of a word nobody entered returns nothing', static function (): void {
        Assert::true(Sample::bank()->lookup('bicycle') === null);
    }],
    ['the paradigm lists every stored form', static function (): void {
        $bank = Sample::bank();
        $lemma = $bank->lemmas()[1];
        $forms = $bank->paradigm($lemma);
        Assert::count(2, $forms);
        Assert::same('ridden', $forms['past']);
    }],
    ['a family groups its own lemmas', static function (): void {
        $walk = Sample::bank()->byFamily('walk-');
        $ride = Sample::bank()->byFamily('ride-');
        Assert::count(1, $walk);
        Assert::count(1, $ride);
        Assert::same('LM-01', $walk[0]->id);
        Assert::count(2, Sample::bank()->byGroup('motion'));
    }],
    ['the sample holds five lemmas in four parts of speech', static function (): void {
        $bank = Sample::bank();
        Assert::count(5, $bank->lemmas());
        $pos = $bank->countByPos();
        Assert::same(2, $pos['verb']);
        Assert::same(1, $pos['noun']);
        Assert::same(1, $pos['adjective']);
        Assert::same(1, $pos['adverb']);
    }],
    ['a sense names the concept before the definition', static function (): void {
        Assert::contains('progression on foot:', Sample::bank()->lemmas()[0]->sense());
    }],
];
