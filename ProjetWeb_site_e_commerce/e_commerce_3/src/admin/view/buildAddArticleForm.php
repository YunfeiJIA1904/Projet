<?php
function buildAddArticleForm(string $error, string $success): string
{
    $errorAlert = '';
    $successAlert = '';
    if ($error !== '') {
        $errorAlert = "<div class=\"alert alert-danger mb-3\">$error</div>";
    }
    if ($success != '') {
        $successAlert = "<div class=\"alert alert-success mb-3\">$success</div>";
    }
    return <<<HTML
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            $errorAlert
            $successAlert
            
              <h2 class="text-center mb-5">Ajouter un article sur le site</h2>
              
              <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="nom_article" class="form-label">Nom de l'article :</label>
                  <input type="text" name="nom_article" id="nom_article" class="form-control" placeholder="Nom de l'article" required>
                </div>
                <div class="form-group">
                  <label for="categorie" class="form-label">Categorie :</label>
                  <input type="text" name="categorie" class="form-control" id="categorie" placeholder="Categorie" required>
                </div>
                <div class="form-group">
                  <label for="quantite" class="form-label">Quantite :</label>
                  <input type="number" name="quantite" class="form-control" id="quantite" placeholder="Quantite" required>
                </div>
                <div class="form-group">
                  <label for="prix" class="form-label">Prix :</label>
                  <input type="number" step="0.01" name="prix" class="form-control" id="prix" placeholder="Prix" required>€
                </div>
                <div class="form-group">
                  <label for="image" class="form-label">Image (facultatif) :</label>
                  <input type="file" name="image" class="form-control " id="image">
                </div>
                
                <button type="submit" class="btn btn-primary">Ajouter un article</button>
              </form>
          </div>
        </div>
      </div>
    </div>
HTML;
}
    ?>