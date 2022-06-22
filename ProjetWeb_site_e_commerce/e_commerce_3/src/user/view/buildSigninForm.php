<?php

function buildSigninForm($values, string $error): string
{
  $errorAlert = '';
  if ($error !== '') {
    $errorAlert = "<div class=\"alert alert-danger mb-3\">$error</div>";
  }
  $firstName = $values['firstName'];
  $lastName = $values['lastName'];
  $email = $values['email'];
  return <<<HTML
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            $errorAlert
              
              <h2 class="text-center mb-5">Inscription</h2>
              
              <form action="" method="post">
                <div class="form-group">
                  <label for="firstName">Prénom :</label>
                  <input type="text" name="firstName" id="firstName" value="$firstName" class="form-control" placeholder="Entrez votre prénom" required>
                </div>
                <div class="form-group">
                  <label for="lastName">Nom :</label>
                  <input type="text" name="lastName" id="lastName" value="$lastName" class="form-control" placeholder="Entrez votre nom" required>
                </div>
                <div class="form-group">
                  <label for="email">Adresse mail :</label>
                  <input type="email" name="email" id="email" value="$email" class="form-control" placeholder="Entrez votre adresse mail" required>
                </div>
                <div class="form-group">
                  <label for="password">Mot de passe :</label>
                  <input type="password" name="password" class="form-control" placeholder="Entrez votre mot de passe" id="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary">S'inscrire</button>
              </form>
          </div>
        </div>
        <hr>
        Vous avez déjà un compte? <a href="/e_commerce_3/src/user/login.php">connectez-vous</a>
      </div>
    </div>
HTML;
}
