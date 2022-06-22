<?php
function buildPaymentView($id_user, $amount): string
{
    $txt = '';
    $txt= $txt.'<form>
    <div class="form-row">
      <h1>Payment amount </h1>
      <div class="form-group col-md-12">
        <input type="" class="form-control" readonly id="amount_id" placeholder="'.$amount.' €">
      </div>
    </div>

    <div class="form-group">
      <label for="Amount-to-pay">Name on card</label>
      <input type="text" class="form-control" id="inputAmount-to-pay" required>
    </div>
    <h6>Card Number </h6>

    <div class="form-row">  
      <div class="form-group col-md-3">
        <input type="number" class="form-control" id="iban minlength="4" maxlength="4"" required >
      </div>

      <div class="form-group col-md-3">
        <input type="number" class="form-control" id="iban2" required >
      </div>

      <div class="form-group col-md-3">
        <input type="number" class="form-control" id="iban3" required >
      </div>

      <div class="form-group col-md-3">
        <input type="number" class="form-control" id="iban4" required >
      </div>

     
    </div>
    
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="inputExpDate">Expiry date</label>
        <input type="text" class="form-control" id="inputExpDate" placeholder="MM/YY" required>
      </div>
      <div class="form-group col-md-6">
        <label for="inputSecurityCode">Security Code</label>
        <input type="text" class="form-control" id="inputSecurityCode" required>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group col-md-12">
        <label for="inputZip">Zip/Postal Code</label>
        <input type="text" class="form-control" id="inputZip" required>
      </div>
    </div>

    <div class="form-group">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="RememberMe">
        <label class="form-check-label" for="RememberMe">
          Remember Me
        </label>
      </div>
    </div>

   <a href="../home/pay_fct.php" class="btn btn-primary"> Payer ! </a>
  </form>'; 
 
  return $txt;
}

?>
