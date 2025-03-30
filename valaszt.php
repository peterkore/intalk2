<?php
include_once 'header.php';
include_once('header.php');
include_once('body.php');
?>
<strong>Termék választó</strong>
<form action="termekValaszto.php" method="post">
<!--select class="form-select multiple form-select-lg mb-3" aria-label=".form-select-lg example">
  <option selected>Termék </option>
  <option value="1">Kutya</option>
  <option value="2">Macska</option>
  <option value="3">Egyéb</option>
</select-->

<select id="cid" name="cid" class="form-select form-select-sm" aria-label=".form-select-sm example">
  
  <option value="1">Kutyaeledel</option>
  <option value="2">Macskaeledel</option>
  <option value="3">Felszerelések</option>
  <option value="4">Higiénia</option>
  <option value="5">Kiegészítők</option>

</select>
<br>
<button type="submit" name="submit" value="send" class="btn btn-secondary">OK</button>
</form>

<?php
 include_once('footer.php')
?>