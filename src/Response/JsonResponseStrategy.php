<?php

declare(strict_types=1);

namespace App\Response;

class JsonResponseStrategy implements ResponseStrategyInterface
{
    public function render(string $view, array $data = []): string
    {
        return json_encode($data);
    }
}
