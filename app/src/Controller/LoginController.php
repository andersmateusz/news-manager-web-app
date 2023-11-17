<?php

declare(strict_types=1);

namespace Andersma\NewsManager\Controller;

use Andersma\NewsManager\Container;
use Andersma\NewsManager\Http\HttpMethod;
use Andersma\NewsManager\Http\Response\Redirect;
use Andersma\NewsManager\Http\Response\View;
use Andersma\NewsManager\Repository\UserRepository;
use function filter_var;
use function password_verify;
use function session_destroy;
use const FILTER_NULL_ON_FAILURE;

final class LoginController
{
    public function login(HttpMethod $method): Redirect|View
    {
        if (isset($_SESSION['user'])) {
            return new Redirect('/', 301);
        }

        if (HttpMethod::GET === $method) {
            return new View('login.php');
        }

        $username = filter_var($_POST['username'] ?? null, options: FILTER_NULL_ON_FAILURE);
        $password = filter_var($_POST['password'] ?? null, options: FILTER_NULL_ON_FAILURE);

        if (!$username || !$password) {
            return new View('login.php', ['error' => 'Wrong Login Data!'], 401);
        }

        /** @var UserRepository $userRepository */
        $userRepository = Container::get(UserRepository::class);

        $user = $userRepository->find($username);

        if (!$user || !password_verify($password, $user->getHash())) {
            return new View('login.php', ['error' => 'Wrong Login Data!'], 401);

        }

        $_SESSION['user'] = $user;

        return new Redirect('/', 301);
    }

    public function logout(): Redirect
    {
        session_destroy();
        return new Redirect('/login');
    }
}