<?php

declare(strict_types=1);

namespace Lemma\Home;

final class Sample
{
    public static function bank(): Bank
    {
        $bank = new Bank();

        $walk = new Lemma('LM-01', 'progression on foot', 'walk', 'walk', 'verb', 'motion', 'walk-',
            'to move forward by putting one foot in front of the other', true);
        $walk->add(new Form('past', 'ed'));
        $walk->add(new Form('present participle', 'ing'));
        $bank->add($walk);

        $ride = new Lemma('LM-02', 'progression astride', 'ride', 'rid', 'verb', 'motion', 'ride-',
            'to travel on an animal or a vehicle astride it', false);
        $ride->add(new Form('past', 'den'));
        $ride->add(new Form('present participle', 'ing'));
        $bank->add($ride);

        $bridge = new Lemma('LM-03', 'a structure over an obstacle', 'bridge', 'bridge', 'noun', 'built landscape', 'bridge-',
            'something built to carry a way across an obstacle', true);
        $bridge->add(new Form('plural', 's'));
        $bank->add($bridge);

        $narrow = new Lemma('LM-04', 'small in width', 'narrow', 'narrow', 'adjective', 'dimension', 'narrow-',
            'of small width compared with length', true);
        $narrow->add(new Form('comparative', 'er'));
        $bank->add($narrow);

        $still = new Lemma('LM-05', 'without motion', 'still', 'still', 'adverb', 'manner', 'still-',
            'in a way that keeps motion out of it', true);
        $bank->add($still);

        return $bank;
    }
}
