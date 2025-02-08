<?php

namespace App\Services\Http\Scrapper\Browser;

class BrowserOptions
{
    public function __invoke(): array
    {
        return [
            'userAgent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36',
            'noSandbox' => true,
            'disableGpu' => true,
            'headless' => true
        ];
    }
}
