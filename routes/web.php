<?php

declare(strict_types=1);

use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    // home routes
    $r->get('/', ['HomeController@show']);
    $r->get('/fonts/{font}', ['AssetController@fonts']);
    $r->get('/posts/img/{img}', ['AssetController@postImg']);

    // blog routes
    $r->addGroup('/blog', function (RouteCollector $r) {
        $r->addGroup('/posts', function (RouteCollector $r) {
            $r->get('', ['PostController@index']);
            $r->post('', ['PostController@save']);
            $r->get('/create', ['PostController@create']);
            $r->get('/s', ['PostController@find']);
            $r->get('/{slug}', ['PostController@show']);
        });
    });

    // api routes
    $r->post('/api/sendMail', [
        'HomeController@saveMail',
        'middlewares' => ['CsrfVerify']
    ]);
};
