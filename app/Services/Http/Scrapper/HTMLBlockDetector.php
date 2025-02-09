<?php

namespace App\Services\Http\Scrapper;

class HTMLBlockDetector
{
    public const CLOUDFLARE = [
        'Attention Required! | Cloudflare'
    ];

    public function __invoke(string $text): bool
    {
        foreach (array_merge(self::CLOUDFLARE) as $needle) {
            if (str_contains($text, $needle)) {
                return true;
            }
        }

        return false;
    }
}
