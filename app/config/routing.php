<?php

declare(strict_types=1);

use Andersma\NewsManager\Container;
use Andersma\NewsManager\Controller\LoginController;
use Andersma\NewsManager\Controller\NewsController;
use Andersma\NewsManager\Http\HttpMethod;
use Andersma\NewsManager\Http\Response\View;
use Andersma\NewsManager\Router;

Router::get('/', static fn (): View => Container::get(NewsController::class)->index());
Router::get('/login', static fn () => Container::get(LoginController::class)->login(HttpMethod::GET));
Router::post('/login', static fn () => Container::get(LoginController::class)->login(HttpMethod::POST));
Router::get('/logout', static fn () => Container::get(LoginController::class)->logout());
Router::delete('/news', static fn () => Container::get(NewsController::class)->delete());
Router::get('/news', static fn () => Container::get(NewsController::class)->byId());
Router::post('/news', static fn () => Container::get(NewsController::class)->create());
Router::post('/news/update', static fn () => Container::get(NewsController::class)->update());