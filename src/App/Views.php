<?php

declare(strict_types=1);

namespace Lemma\App;

use Lemma\Home\Bank;
use Lemma\Home\Lemma;

final class Views
{
    public function __construct(private readonly string $base)
    {
    }

    private function h(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public function header(string $title): string
    {
        $base = $this->h($this->base);

        return <<<HTML
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$this->h($title)}</title>
<base href="{$base}">
<link rel="stylesheet" href="assets/site.css">
</head>
<body>
<main>
HTML;
    }

    public function footer(): string
    {
        return "</main>\n</body>\n</html>\n";
    }

    public function notFound(string $path): string
    {
        return $this->header('Not found')
            . '<h1>Not found</h1>' . "\n"
            . '<p class="stage">' . $this->h($path) . ' is not a route here.</p>' . "\n"
            . $this->footer();
    }

    public function front(Bank $bank, string $today): string
    {
        $out = $this->header('Lemma 2.81');
        $out .= '<h1>Lemma 2.81. A lemma bank that derives its own word forms</h1>' . "\n";
        $out .= '<p class="lede">Lemma 2.81, a lemma-first lexical database, stores concepts and lemmas, derives the word forms, and feeds the result to a flashcard trainer. A verb yields every form it takes.</p>' . "\n";
        $out .= '<p class="stage">' . count($bank->lemmas()) . ' lemmas, as of ' . $this->h($today) . '.</p>' . "\n";

        $out .= '<h2>Parts of speech</h2>' . "\n";
        $out .= '<dl>';
        foreach ($bank->countByPos() as $pos => $n) {
            $out .= '<dt>' . $this->h($pos) . '</dt><dd>' . $n . '</dd>';
        }
        $out .= '</dl>' . "\n";

        $out .= '<h2>Entries</h2>' . "\n";
        $out .= '<table class="tiers">' . "\n";
        $out .= '<thead><tr><th>Word</th><th>Part</th><th>Group</th><th>Family</th><th>Forms</th></tr></thead>' . "\n";
        $out .= '<tbody>' . "\n";
        foreach ($bank->lemmas() as $lemma) {
            $out .= $this->row($bank, $lemma);
        }
        $out .= '</tbody>' . "\n" . '</table>' . "\n";

        $out .= '<h2>Paradigms</h2>' . "\n";
        $out .= '<div class="cards">' . "\n";
        foreach ($bank->lemmas() as $lemma) {
            $out .= $this->card($bank, $lemma);
        }
        $out .= '</div>' . "\n";
        $out .= $this->footer();

        return $out;
    }

    private function row(Bank $bank, Lemma $lemma): string
    {
        $forms = $bank->paradigm($lemma);
        $shown = [];
        foreach ($forms as $feature => $word) {
            $shown[] = $word . ' (' . $feature . ')';
        }

        return '<tr><td><strong>' . $this->h($lemma->word) . '</strong></td>'
            . '<td>' . $this->h($lemma->partOfSpeech) . '</td>'
            . '<td>' . $this->h($lemma->group) . '</td>'
            . '<td>' . $this->h($lemma->family) . '</td>'
            . '<td>' . $this->h(implode(', ', $shown)) . '</td></tr>' . "\n";
    }

    private function card(Bank $bank, Lemma $lemma): string
    {
        $out = '<article class="card">' . "\n";
        $out .= '<h3>' . $this->h($lemma->word) . '</h3>' . "\n";
        $out .= '<p>' . $this->h($lemma->sense()) . '</p>' . "\n";
        $out .= '<dl>';
        $out .= '<dt>Stem</dt><dd>' . $this->h($lemma->stem) . '</dd>';
        foreach ($bank->paradigm($lemma) as $feature => $word) {
            $out .= '<dt>' . $this->h($feature) . '</dt><dd>' . $this->h($word) . '</dd>';
        }
        $out .= '<dt>Stored</dt><dd>' . ($lemma->regular ? 'regular' : 'irregular') . '</dd>';
        $out .= '</dl>' . "\n";
        $out .= '</article>' . "\n";

        return $out;
    }
}
