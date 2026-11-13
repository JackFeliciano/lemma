<?php

declare(strict_types=1);

namespace Lemma\App;

use Lemma\Home\Sample;

final class Front
{
    public function __construct(private readonly string $base)
    {
    }

    public function run(): void
    {
        $router = new Router($this->base);
        $views = new Views($this->base);
        $bank = Sample::bank();
        $today = getenv('LEMMA_TODAY') ?: '12 November 2026';

        $router->get('/', static fn (): array => [200, [
            'html' => $views->front($bank, $today),
        ]]);

        $router->get('/status', static fn (): array => [200, ['json' => [
            'ok' => true,
            'lemmas' => count($bank->lemmas()),
            'parts' => count($bank->countByPos()),
            'derived' => $bank->lookup('ridden')?->id,
        ]]]);

        [$status, $body] = $router->handle(
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            $_SERVER['REQUEST_URI'] ?? '/',
        );

        http_response_code($status);
        if (isset($body['json'])) {
            header('Content-Type: application/json');
            echo json_encode($body['json'], JSON_UNESCAPED_SLASHES), "\n";

            return;
        }
        if (isset($body['html'])) {
            header('Content-Type: text/html; charset=utf-8');
            echo $body['html'];

            return;
        }
        header('Content-Type: text/html; charset=utf-8');
        echo $views->notFound((string) ($body['path'] ?? '/'));
    }
}
