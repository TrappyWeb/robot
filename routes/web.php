<?php

use App\Services\Http\Scrapper\HTML;
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

    return (fn(): HTML => app()->make(HTML::class))()
        ->getRawHTML($request->uri);
});
