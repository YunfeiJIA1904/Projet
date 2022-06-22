<?php

require_once __DIR__ . '/buildCommandeView.php';

function buildAllCommandeView(array $commandes): string
{

    $itemCount = count($commandes);
    /*
    $pagination=10;
    if($pagination>$itemCount) $pagination=$itemCount;
    */
    $txt = "<div class='commandes'>";

    if($itemCount <= 1) $txt = $txt. "<div class='nb_commande'> Vous avez fait ". $itemCount . " commande ! <br>";
    if($itemCount > 1)  $txt = $txt. "<div class='nb_commande'> Vous avez fait ". $itemCount . " commandes ! <br>";

    //
   // $txt = $txt . "<div class=''>";
    for ($i=0; $i <$itemCount ; $i++) {
        //foreach ($commandes as &$cmd) {
            $txt = $txt . buildCommandeView($commandes[$i]);
        //}
    }

    //


    //$txt = $txt."</div>";
    return $txt;
}
?>
