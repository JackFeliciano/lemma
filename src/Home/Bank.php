<?php

declare(strict_types=1);

namespace Lemma\Home;

/**
 * The bank. It holds lemmas by concept, derives forms for the parts of speech a
 * language marks, and answers a lookup that arrives as a written word.
 */
final class Bank
{
    /** @var list<Lemma> */
    private array $lemmas = [];

    /** @var array<string, string> */
    private array $regular = [
        'verb' => '-ed',
        'noun' => '-s',
        'adjective' => '-er',
        'adverb' => '-ly',
    ];

    public function add(Lemma $lemma): void
    {
        $this->lemmas[] = $lemma;
    }

    /** @return list<Lemma> */
    public function lemmas(): array
    {
        return $this->lemmas;
    }

    public function regularEnding(string $feature): string
    {
        return $this->regular['verb'];
    }

    public function byFamily(string $family): array
    {
        $out = [];
        foreach ($this->lemmas as $lemma) {
            if ($lemma->family === $family) {
                $out[] = $lemma;
            }
        }

        return $out;
    }

    public function byGroup(string $group): array
    {
        $out = [];
        foreach ($this->lemmas as $lemma) {
            if ($lemma->group === $group) {
                $out[] = $lemma;
            }
        }

        return $out;
    }

    /**
     * A written word comes back as the lemma it belongs to, derived or stored.
     */
    public function lookup(string $word): ?Lemma
    {
        foreach ($this->lemmas as $lemma) {
            if ($lemma->word === $word) {
                return $lemma;
            }
            foreach ($lemma->forms() as $form) {
                if ($form->apply($lemma->stem) === $word) {
                    return $lemma;
                }
            }
        }

        return null;
    }

    /**
     * Every form a lemma takes, keyed by feature.
     *
     * @return array<string, string>
     */
    public function paradigm(Lemma $lemma): array
    {
        $out = [];
        foreach ($lemma->forms() as $form) {
            $out[$form->feature] = $form->apply($lemma->stem);
        }

        return $out;
    }

    /** @return array<string, int> */
    public function countByPos(): array
    {
        $out = [];
        foreach ($this->lemmas as $lemma) {
            $out[$lemma->partOfSpeech] = ($out[$lemma->partOfSpeech] ?? 0) + 1;
        }
        ksort($out);

        return $out;
    }
}
