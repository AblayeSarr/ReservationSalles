<?php

declare(strict_types=1);

namespace App\Response;

class HtmlResponseStrategy implements ResponseStrategyInterface
{
    public function render(string $view, array $data = []): string
    {
        extract($data);

        ob_start();

        require dirname(__DIR__, 2)
            . '/templates/'
            . $view
            . '.php';

        return ob_get_clean();
    }
}
