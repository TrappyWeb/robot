<?php

use App\Services\Http\Scrapper\HTML;
use App\Services\Http\Scrapper\HTMLBlockDetector;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

Route::get('/', function () {
    return view('welcome');
});

Route::get('scrape', function (Request $request, Response $response) {
    $validator = Validator::make($request->all(), [
        'uri' => 'required|string|active_url'
    ]);

    if ($validator->stopOnFirstFailure()->fails()) {
        return $response
            ->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setContent($validator->errors());
    }

    if ($request->wantsJson()) {
        return Http::get($request->uri);
    }

    $callable = fn() => (fn(): HTML => app()->make(HTML::class))()
        ->getRawHTML($request->uri);

    for ($i = 0; $i < 3; $i++) {
        $text = $callable();

        $blocked = (new HTMLBlockDetector())($text);

        if ($blocked === false) {
            return $text;
        }
    }

    return $response
        ->setStatusCode(Response::HTTP_SERVICE_UNAVAILABLE)
        ->setContent($text);
});
