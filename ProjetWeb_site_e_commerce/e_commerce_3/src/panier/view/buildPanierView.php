<?php

function buildPanierView($products): string
{

    $itemCount = count($products);
    //var_dump($itemCount);
    $txt='';
    
    $total=0;
    $txt = "
        <div class='panier'> 
            <div class='d-flex justify-content-between'>
            <h4> Votre panier :  </h4>
            
            </div>
            <table class='table table-striped'>
                <thead>
                    <tr>
                        <th scope='col'>NOM DE L'ARTICLE</th>
                        <th scope='col'>PRIX</th>
                        <th scope='col'>QUANTITE</th>
                        <th scope='col'>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>";

    for ($i=0; $i <$itemCount ; $i++) {
        $id =$products[$i]["id_article"];
        $nom = $products[$i]["nom"];
        $prix =$products[$i]["prix"];
        $quantite =$_SESSION['panier'][$id];
        $total += $prix*$quantite;

        $txt = $txt." 
            <div class ='row'>
                <tr class = 'article $i'>
                    <th> $nom </th>
                    <td> $prix € </td>
                    <td> $quantite </td>
                    <td> <a href='addpanier.php?del=$id' class = 'del'>Supprimer</a> </td>
                </tr>
            </div>
        ";
    }
    $txt = $txt." <div class='font-weight-bold'> Total panier : $total €</div>";
    $txt = $txt . "</tbody></table>";
    $txt = $txt . " <a href='../home/payment.php' class='btn btn-primary'>Valider le panier</a>";
    
    $_SESSION['total']=$total;
    return $txt;

}