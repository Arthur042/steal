<?php

namespace App\Twig;

use App\Service\FormatHour;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class FormatHourExtension extends AbstractExtension
{
    public function __construct(
        private FormatHour $formatHour
    ) {}
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('formatHour', [$this, 'formatHour']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [$this, 'doSomething']),
        ];
    }

    public function formatHour(int $value): string
    {
        return $this->formatHour->format($value);
    }
}
