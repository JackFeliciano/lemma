<?php

declare(strict_types=1);

namespace Lemma\Home;

/**
 * One word form a lemma takes. A form carries the feature it realises, such as
 * the past tense, and the ending that produces it.
 */
final class Form
{
    public function __construct(
        public readonly string $feature,
        public readonly string $ending,
    ) {
    }

    public function apply(string $stem): string
    {
        return $stem . $this->ending;
    }
}
