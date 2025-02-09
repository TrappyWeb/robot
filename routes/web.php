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

    try {
        $text = (fn(): HTML => app()->make(HTML::class))()
            ->getRawHTML($request->uri);
    } catch (Exception $e) {
        return $response
            ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->setContent($e->getMessage());
    }

    if ((new HTMLBlockDetector())($text)) {
        return $response
            ->setStatusCode(Response::HTTP_SERVICE_UNAVAILABLE)
            ->setContent($text);
    }

    return $text;
});
