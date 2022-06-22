<?php

require_once __DIR__ . './../model/Panier.php';
require_once __DIR__ . './../view/buildPanierView.php';
require_once __DIR__ . '/../../article/model/BDDArticleRepository.php';

class AddPanierController
{

    private $authenticationService;

    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function viewAction(): string
    {

        if (!$this->authenticationService->isUserConnected()) {
            $this->redirectToLogin();
        } 
        else if ($this->authenticationService->isUserAdmin()) {
            $this->redirectToHomepage();
        }
        
        
        $panier = new Panier();
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $exist = (new BDDArticleRepository())->ExistArticle($id);
           
            
            if(isset($_SESSION['panier'][$id])){
                $quantite=$_SESSION['panier'][$id];
            }else{
                $quantite = 1;
            }

            if((new BDDArticleRepository())->Enough($id,$quantite)){
                $panier->add($id);
            }

            $ids = array_keys($_SESSION['panier']);
            if(empty($ids)){
                $products = array();
            }else{
                $products = (new BDDArticleRepository())->getIdsIn($ids);
            }
            
            
        }else if(isset($_GET['del'])){
            
            $id=$_GET['del'];
            $panier->del($id);

            if(!empty($_SESSION['panier'])){
                $ids = array_keys($_SESSION['panier']);
                $products = (new BDDArticleRepository())->getIdsIn($ids);
            }else{
                $products =array();
            }
            
        }else{
            $ids = array_keys($_SESSION['panier']);
            if(empty($ids)){
                $products = array();
            }else{
                $products = (new BDDArticleRepository())->getIdsIn($ids);
            }
            
        }
        

        return buildPanierView($products);
    }

    private function redirectToLogin(): void {
        header('Location: /e_commerce_3/src/user/login.php');
    }

    private function redirectToHomepage(): void {
        header('Location: /e_commerce_3/src/home');
    }

}
?>