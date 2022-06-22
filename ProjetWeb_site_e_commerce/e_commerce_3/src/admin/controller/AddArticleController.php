<?php

require_once __DIR__ . '/../view/buildAddArticleForm.php';

class AddArticleController
{
    private $authenticationService;
    private $articleRepository;

    public function __construct(AuthenticationService $authenticationService, ArticleRepository $articleRepository)
    {
        $this->authenticationService = $authenticationService;
        $this->articleRepository = $articleRepository;
    }

    public function viewAction(): string
    {

        $error='';
        $success='';
        if (!$this->authenticationService->isUserAdmin()) {
            $this->redirectToHomepage();
        }

        if ($this->isAddFormFilledAndValid()) {
            $nom = htmlspecialchars(trim($_POST['nom_article']));
            $quantite = htmlspecialchars($_POST['quantite']);
            $categorie = htmlspecialchars($_POST['categorie']);
            $prix = htmlspecialchars($_POST['prix']);
            $image_name = '';
            if (!isset($_FILES['image']) && ($image_name=$this->uploadImage($_FILES['image'])) == '') {
                $image_name = $this->defaultImage();
            }
            if ($this->articleRepository->createArticle($nom, $image_name, $quantite, $prix, $categorie, '')) {
                $success = "L'article a bien été ajouté à la base de données.";
            } else {
                $error = "Erreur dans la création de l'article (erreur BDD)";
            }
        } elseif ($this->isOneOfTheFieldsMissing()) {
            $error = 'Formulaire incomplet';
        } elseif ($this->isQuantiteInferiorToOneAndEntier()) {
            $error = 'La quantité et le prix doivent être supérieurs à zéro et entiers';
        }

        return buildAddArticleForm($error, $success);
    }

    private function uploadImage($image) : String {
        $repertoire = '/images/';
        $uploadfile = $repertoire . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
            return $_FILES['image']['name'];
        } else {
            return '';
        }
    }

    private function isAddFormFilledAndValid(): bool
    {
        return isset($_POST['nom_article'], $_POST['quantite'], $_POST['categorie'], $_POST['prix']) &&
            $_POST['nom_article'] !== '' && $_POST['quantite'] !== '' && $_POST['categorie'] !== ''
            && $_POST['prix'] !== '' && intval($_POST['quantite']) > 0 && $_POST['prix'] > 0 && $_POST['prix']
            && is_int(intval($_POST['quantite']));
    }

    private function isQuantiteInferiorToOneAndEntier(): bool {
            return isset($_POST['quantite'], $_POST['prix']) &&
                is_int(intval($_POST['quantite'])) > 0 && $_POST['prix'] > 0;
    }

    private function isOneOfTheFieldsMissing(): bool
    {
        return (isset($_POST['nom_article']) && trim($_POST['nom_article']) === '')
            || (isset($_POST['quantite']) && trim($_POST['quantite']) === '')
            || (isset($_POST['categorie']) && trim($_POST['categorie']) === '')
            || (isset($_POST['prix']) && trim($_POST['prix']) === '');
    }

    private function defaultImage() : String {
        return 'default-image.jpg';
    }

}
?>