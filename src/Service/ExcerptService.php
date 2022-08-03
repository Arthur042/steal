<?php

namespace App\Service;

class ExcerptService
{
public function excerpt(string $value, int $length = 50): string
    {
        return (strlen($value) <= $length) ? $value : substr($value, 0, $length) . '...';
    }
}