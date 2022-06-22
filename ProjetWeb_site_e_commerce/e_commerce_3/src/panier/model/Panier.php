<?php

class Panier
{

    public function __construct(){
        if(!isset($_SESSION)){
            session_start();
        }
        if(!isset($_SESSION['panier'])){
            $_SESSION['panier'] = array();
        }
    }

    public function add($id){
        if(isset($_SESSION['panier'][$id])){
            $_SESSION['panier'][$id]++;
        }else{
            $_SESSION['panier'][$id]=1;
        }
    }

    public function del($id){
        if($_SESSION['panier'][$id]==1){
            unset($_SESSION['panier'][$id]);
        }else{
            $_SESSION['panier'][$id]--;
        }
        
    }
}

?>