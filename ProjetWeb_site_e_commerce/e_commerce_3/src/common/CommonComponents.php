<?php

class CommonComponents
{
  public static function render(string $component, string $titre, bool $isConnected, bool $isAdmin = false, $withNavbar = true) {
    $head = self::htmlHeadComponent($titre);
    if ($isConnected == true && $isAdmin == false)
        $navbar = $withNavbar ? self::navbarConnected() : '';
    else if ($isConnected == true && $isAdmin == true)
        $navbar = $withNavbar ? self::navbarAdmin() : '';
    else
        $navbar = $withNavbar ? self::navbarNotConnected() : '';
    $scripts = self::scripts();
    $ajax = self::ajax_function();
    echo <<<HTML
      <!DOCTYPE HTML>
      <html lang="fr">
      <head>
        $head
      </head>

      <body>

      $navbar

      <main role="main" class="container py-4">
        $component
      </main>

      $scripts
      $ajax
      </body>
      </html>
HTML;
  }

  private static function htmlHeadComponent($titre): string
  {
    return <<<HTML
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">

      <title>$titre</title>

      <link rel="stylesheet"
            href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
            integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
            crossorigin="anonymous">
      <link rel="stylesheet" href="style.css">
HTML;
  }

  private static function navbarConnected(): string
  {
    return <<<HTML
    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
        <a class="navbar-brand mr-auto" href="../home/index.php">Site de e-commerce</a>
        <ul class="navbar-nav">
            <li class = "nav-item">
                <a href="../panier/addpanier.php" class="nav-link"> Panier</a>
            </li>
            <li class = "nav-item">
                <a href="../commande/commande.php" class="nav-link"> Commandes</a>
            </li>
            <li class = "nav-item">
                <a href="../user/logout.php" class="nav-link">Déconnexion</a>
            </li>
        </ul>
    </nav>
HTML;
  }

    private static function navbarAdmin(): string
    {
        return <<<HTML
    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
        <a class="navbar-brand mr-auto" href="../home/index.php">Site de e-commerce</a>
        <ul class="navbar-nav">
            <li class = "nav-item">
                <a href="../admin/add_article.php" class="nav-link">Ajout article</a>
            </li>
            <li class = "nav-item">
                <a href="../admin/change_order.php" class="nav-link">Définir ordre</a>
            </li>
            <li class = "nav-item">
                <a href="../user/logout.php" class="nav-link">Déconnexion</a>
            </li>
        </ul>
    </nav>
HTML;
    }

  private static function navbarNotConnected(): string
    {
        return <<<HTML
      <nav class="navbar navbar-expand-md navbar-dark bg-primary">
    <a class="navbar-brand mr-auto" href="../home/index.php">Site de e-commerce</a>
    <ul class="navbar-nav">
        <li class = "nav-item">
            <a href="../user/signin.php" class="nav-link">Inscription</a>
        </li>
            <a href="../user/login.php" class="nav-link">Connexion</a>
        <li class = "nav-item">
            <a href="../user/login.php" class="nav-link"> Panier</a>
        </li>
    </ul>
      </nav>
HTML;
    }

  private static function scripts(): string
  {
    return <<<HTML
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
      <script>
        function afficherCacher() {
         var div_a_cacher = document.getElementById('a_cacher');
         var btn = document.getElementById('btn_cacher')
         if (div_a_cacher.style.display === 'none') {
          div_a_cacher.style.display = 'block';
          btn.innerHTML='Cacher';
         } else {
                div_a_cacher.style.display = 'none';
                btn.innerHTML='Montrer';
            }
        } 
    </script>
      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
              integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
              integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
HTML;
  }

  private static function ajax_function(): string{
    return <<<HTML
    <script>
      $(function(){
        $('#add-product').on('click', function(){
          var id_film = $(this).data('id_product');
          $.ajax({
            url:"../panier/addpanier.php",
            method:"POST",
            data:{id_product:id_product},
            success:function(data){
              if(data == '    Success'){
                alert("produit ajouté dans le panier!");
              }
              else{
                alert("ça marche pas...");
              }
            }
          })
        })
      });
    </script>
HTML;
  }
}
