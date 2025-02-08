<?php

namespace App\Services\Http\Scrapper;

use App\Services\Http\Scrapper\Browser\CreateBrowser;
use HeadlessChromium\Page;

class HTML
{
    public readonly CreateBrowser $browser;

    public function __construct()
    {
        $this->browser = CreateBrowser::create();
    }

    public function getRawHTML(string $uri): string
    {
        $page = $this->browser
            ->browser()
            ->createPage();

        $page
            ->navigate($uri)
            ->waitForNavigation(Page::DOM_CONTENT_LOADED);

        return $page->getHtml();
    }

    public function __destruct()
    {
        $this->browser->browser()->close();
    }
}
