<?php

declare(strict_types=1);

namespace App\Response;

interface ResponseStrategyInterface
{
    public function render(string $view, array $data = []): string;
}
