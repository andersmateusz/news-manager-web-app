<?php

declare(strict_types=1);

namespace Andersma\NewsManager\Controller;

use Andersma\NewsManager\Container;
use Andersma\NewsManager\Entity\News;
use Andersma\NewsManager\Http\HttpException;
use Andersma\NewsManager\Http\Response\Json;
use Andersma\NewsManager\Http\Response\Redirect;
use Andersma\NewsManager\Http\Response\View;
use Andersma\NewsManager\Repository\NewsRepository;
use function filter_var;
use function strlen;
use const FILTER_DEFAULT;
use const FILTER_NULL_ON_FAILURE;
use const FILTER_VALIDATE_INT;

class NewsController
{
    private NewsRepository $newsRepository;

    public function __construct()
    {
        $this->newsRepository = Container::get(NewsRepository::class);
    }

    public function index(): View
    {
        return new View('home.php', ['news' => $this->newsRepository->list()]);
    }

    public function delete(): Json
    {
        if (!isset($_GET['id'])) {
            throw new HttpException('Id is required', 400);
        }

        if (null === $id = filter_var($_GET['id'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE)) {
            throw new HttpException('Id should be integer', 400);
        }

        if ($success = $this->newsRepository->delete($id)) {
            $_SESSION['success_flash'] = 'News was deleted!';
        }

        return new Json(['success' => $success]);
    }

    public function update(): Redirect
    {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

        if (null === $id) {
            throw new HttpException('Id is required', 400);
        }

        $news = $this->newsRepository->find($id) ?? throw new HttpException('News not found', 404);

        $title = filter_var($_POST['title'] ?? null, options: FILTER_NULL_ON_FAILURE);
        $description = filter_var($_POST['description'] ?? null, options: FILTER_NULL_ON_FAILURE);

        if (!$title || !$description || strlen($title) > 255) {
            throw new HttpException('Invalid request body', 400);
        }

        $isUpdated = $this->newsRepository->update(
            $news
                ->setTitle($title)
                ->setDescription($description)
        );

        if ($isUpdated) {
            $_SESSION['success_flash'] = 'News was successfully changed!';
        }

        return new Redirect('/');
    }

    public function create(): Redirect
    {
        $title = filter_var($_POST['title'] ?? null, FILTER_DEFAULT, FILTER_NULL_ON_FAILURE);
        $description = filter_var($_POST['description'] ?? null, FILTER_DEFAULT, FILTER_NULL_ON_FAILURE);

        if (!$description || !$title || strlen($title) > 255) {
            throw new HttpException('Invalid request body', 400);
        }

        $isCreated = $this->newsRepository->create(
            (new News())
                ->setDescription($description)
                ->setTitle($title)
        );

        if ($isCreated) {
            $_SESSION['success_flash'] = 'News was created!';
        }

        return new Redirect('/');
    }

    public function byId(): Json
    {
        if (null === $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE)) {
            throw new HttpException('Invalid id query param', 400);
        }

        $news = $this->newsRepository->find($id) ?? throw new HttpException('News not found', 404);

        return new Json(['title' => $news->getTitle(), 'description' => $news->getDescription(), 'id' => $news->getId()]);
    }
}