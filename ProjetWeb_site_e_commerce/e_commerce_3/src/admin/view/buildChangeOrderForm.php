<?php
    function buildChangeOrderForm(string $success)
    {
        $successAlert = '';

        if ($success != '') {
            $successAlert = "<div class=\"alert alert-success mb-3\">$success</div>";
        }

        return <<<HTML
        <div class='row justify-content-center'>
      <div class='col-md-6'>
        <div class='card'>
          <div class='card-body'>
            $successAlert
            <h5 class='text-center text-uppercase'>Ordonner par...</h5>
            <form action='' method='post'>
                <div class="form-group">
                <label for="champ">Champ</label>
                <select class="form-control" id="champ" name="champ">
                  <option>Nom</option>
                  <option>Prix</option>
                </select>
              </div>
                <div class='form-check form-switch'>
                    <input class='form-check-input' type='checkbox' name='croissant' id='croissant'>
                    <label class='form-check-label' for='croissant'>Ordre croissant ?</label>
                </div>
            </div>
            <button type='submit' class='btn btn-primary'>Submit</button>
          </form>
      </div>
      </div>
    </div>
HTML;
    }
?>