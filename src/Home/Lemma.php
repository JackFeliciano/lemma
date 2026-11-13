<?php

declare(strict_types=1);

namespace Lemma\Home;

/**
 * A lemma is the dictionary entry: a concept, a part of speech, a semantic
 * group, a morphological family and one sense definition. It derives its own
 * forms from the stem and the endings its family declares.
 */
final class Lemma
{
    /** @var list<Form> */
    private array $forms = [];

    public function __construct(
        public readonly string $id,
        public readonly string $concept,
        public readonly string $word,
        public readonly string $stem,
        public readonly string $partOfSpeech,
        public readonly string $group,
        public readonly string $family,
        public readonly string $definition,
        public readonly bool $regular,
    ) {
    }

    public function add(Form $form): void
    {
        $this->forms[] = $form;
    }

    /** @return list<Form> */
    public function forms(): array
    {
        return $this->forms;
    }

    /**
     * The shape of the word under a feature. A lemma nobody entered a form for
     * still declines, because the regular ending comes from the family.
     *
     * @param callable(string): string $regularEnding
     */
    public function inflect(string $feature, callable $regularEnding): string
    {
        foreach ($this->forms as $form) {
            if ($form->feature === $feature) {
                return $form->apply($this->stem);
            }
        }
        if (!$this->regular) {
            throw new \RuntimeException('no form for ' . $feature . ' on ' . $this->word);
        }

        return $this->stem . $regularEnding($feature);
    }

    public function sense(): string
    {
        return $this->concept . ': ' . $this->definition;
    }
}
