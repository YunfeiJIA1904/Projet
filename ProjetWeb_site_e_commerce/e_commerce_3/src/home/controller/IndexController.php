<?php
    require_once __DIR__ . '/../view/buildIndexView.php';

class IndexController {

    private $authenticationService;
    private $articleRepository;

    public function __construct(AuthenticationService $authenticationService, ArticleRepository $articleRepository)
    {
        $this->authenticationService = $authenticationService;
        $this->articleRepository = $articleRepository;
    }

    public function viewAction(): string {
        return buildIndexView($this->articleRepository->getAllArticles(), $this->articleRepository->getAllCategories(), $this->authenticationService->isUserAdmin());
    }

}