<?php
function buildArticleView(Article $a): string
{

    $txt = "<div class=''>";
    $id = htmlspecialchars($a->getIdArticle());
    $nom = strtoupper($a->getNom());
    $img = htmlspecialchars($a->getImage());
    $qte = htmlspecialchars($a->getQuantite());
    $prix = htmlspecialchars($a->getPrix());
    $desc = htmlspecialchars($a->getDescription());
        $txt = $txt."
            <div  class='article'> 
            <h2>" . $nom. "</h5>
            <div> <img id='image' src='../../images/".$img."'></div>
            <div> " . $prix . " €</div>
            <div> quantité restante :" . $qte . "</div>
            <h3>Description :</h4>
            <p> " . $desc . "</p>
    </div></div>";

    return $txt;
}

?>