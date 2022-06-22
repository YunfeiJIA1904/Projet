<?php

function buildIndexView(PDOStatement $articles, array $categories, bool $isAdmin): string
{
    $txt = '';
    if (!$isAdmin) {
        // Si pas administrateur
        $itemCount = $articles->rowCount();
        $pagination = 10;
        if ($pagination > $itemCount) $pagination = $itemCount;
        $txt = "<style type='text/css'>
			.container.py-4{
				display: inline-flex;
			}
		</style>";

        $txt = $txt . "<div style = 'width:25%'>
        <strong>Il y a $itemCount articles</strong><br/><br/>
        <button onclick='afficherCacher()' id='btn_cacher' class='btn btn-secondary'>Cacher</button>
        <span id='a_cacher' style='display:block'>
        <form action='index.php' method='get'>
        <label for='categorie'><strong>Categorie :</strong> </label></br>
        <select id='categorie' name='categorie'>
            <option value='all'>Toutes nos catégories</option>";
            foreach ($categories as &$cat) {
                $txt = $txt . "<option value='$cat'>$cat</option>";
            }
            $txt = $txt . "
        </select></br></br>
        <label for='trie'><strong>Trie : </strong></label></br>
        <select id='trie' name='trie'>
            <option value='default'>Par défaults</option>
            <option value='ORDER BY prix'>Prix croissant</option>
            <option value='ORDER BY prix DESC'>Prix décroissant</option>
            <option value='ORDER BY nom'>Ordre A-Z</option>
            <option value='ORDER by nom DESC'>Ordre Z-A</option>
        </select></br></br>
        <label for='prix'><strong>Tranche prix : </strong></label></br>
        <input type='number' id='min' name='min'  value ='' style='width: 5em'>
        -
        <input type='number' id='max' name='max'  value ='' style='width: 5em'></br></br>
        <input type='submit' value='Filtrer'>
    </form>
    </span>
    </div>";
        $txt = $txt . "<div tyle='margin-left:10%'>";
        for ($i = 0; $i < $pagination; $i++) {
            $row = $articles->fetch(PDO::FETCH_ASSOC);
            $id = htmlspecialchars($row['id_article']);
            $nom = strtoupper(htmlspecialchars($row['nom']));
            $img = htmlspecialchars($row['image']);
            $qte = htmlspecialchars($row['quantite']);
            $prix = htmlspecialchars($row['prix']);
            $txt = $txt . "

        <div class='card bg-light article' style='max-width: 25rem;'>
            <a href='../article/article.php?id_article=" . $id . "' style='text-decoration: none;'>
                <img id='image' class='card-img-top' width='100%' src='../../images/" . $img . "'>
            </a>
                <div class='card-body'>
                <h5 class='card-title'>" . $nom . "</h5>
                <p class='card-text'> " . $prix . " €</p>
                <div class='blockquote-footer'> Quantité en stock : " . $qte . "</div>
                </div>
                <a href='../panier/addpanier.php?id=".$id."' class='btn btn-primary' data-id_product=".$id.">Ajouter au Panier</a>

        </div>

        ";
        }
        $txt = $txt . "</div>";
    } else {
        // Administrateur
        $txt = $txt . "
        <div class =''><h5 class='text-center text-uppercase'>Bienvenue, administrateur</h5>
            <div class='list-group'>
                <a href='../admin/add_article.php' class='list-group-item list-group-item-action list-group-item-secondary'>Ajouter un article au site</a>
                <a href='../admin/change_order.php' class='list-group-item list-group-item-action list-group-item-secondary'>Définir l'ordre d'affichage de la liste d'article</a>
                <a href='../user/logout.php' class='list-group-item list-group-item-action list-group-item-secondary'>Se déconnecter</a>
            </div>
        </div>
        ";
    }
    return $txt;
}

?>
