<?php

function buildCommandeView(Commande $c): string
{
    if(is_null($c))return "";

    $id_command = htmlspecialchars($c->getId());
    $id_user = htmlspecialchars($c->getIdUser());

    $nb_article = htmlspecialchars($c->getNb_article());
    //$qte = htmlspecialchars($c->getQuantite());
    $total = htmlspecialchars($c->getTotal());


    $txt = "
        <div class='commande'> 
            <div class='d-flex justify-content-between'>
            <h4> Commande numéro : $id_command </h4>
            <div class='font-weight-bold'> Total commande : $total €</div>
            </div>
            <table class='table table-striped'>
                <thead>
                    <tr>
                        <th scope='col'>NOM DE L'ARTICLE</th>
                        <th scope='col'>PRIX</th>
                        <th scope='col'>QUANTITE</th>
                    </tr>
                </thead>
                <tbody>";

    $i = 0;
    $j = 0;
    while ($i < $nb_article){
        $article_id= $c->getArticle_IdByIndex($j);
        $article = (new BDDArticleRepository())->getArticleById($article_id);
        $quantite = $c->getQuantiteByIndex($j);
        $nom = $article->getNom();
        $prix= $article->getPrix();

        $txt = $txt .   "<tr class = 'article $i'>
                            <th> $nom </th>
                            <td> $prix € </td>
                            <td> $quantite </td>
                        </tr>";
        $i += $quantite;
        $j++;
    }
    $txt = $txt . "</tbody></table>";

    return $txt;
    
}

?>



