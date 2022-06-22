<?php
require_once __DIR__ . '/../view/buildArticleView.php';

class ArticleViewController
{

    private $articleRepository;
    private $authenticationService;

    public function __construct(AuthenticationService $authenticationService, ArticleRepository $articleRepository)
    {
        $this->authenticationService = $authenticationService;
        $this->articleRepository = $articleRepository;
    }

    public function viewAction(): string
    {
        if ($this->authenticationService->isUserAdmin()) {
            $this->redirectToHomepage();
        }
        return buildArticleView($this->articleRepository->getArticleById($_GET['id_article']));
    }

    private function redirectToHomepage(): void {
        header('Location: /e_commerce_3/src/home');
    }
}
?>