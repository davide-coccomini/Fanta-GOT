
<div class="modal fade" id="modalCodes" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><?php echo $promoCodeTitle; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body-codes">
      	<form method="POST" action="https://fantagot.com/php/promocode-process.php">
          <p><?php echo $promoCodeDescription; ?></p><br>
      	 <div class="input-group col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
      		<center><input type="text" name="codice" id="textboxcode" <?php if(isset($promocodePlaceholder1)) echo 'placeholder="'.$promocodePlaceholder1.'"';?>>
      		<input type="submit" class="btn btn-primary" value=<?php echo '"'.$promoCodeButton1.'"'; ?>></center>
             </div>
      	</form>
      </div><br>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $closeText; ?></button>
      </div>
    </div>
  </div>
</div>
<script>

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});

</script>