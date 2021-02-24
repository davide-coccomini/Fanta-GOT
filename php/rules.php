<?php
 ob_start();
  $query="SELECT contenuto FROM docs WHERE tipo='regolamento' AND lingua ='".$lingua."' ORDER BY timestamp DESC LIMIT 1";
  $ris=estrai($query,$mysqli);
?>
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog" id="modal-dialog-rules" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><?php echo $rulesTitle1; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
          echo $ris["contenuto"];
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $closeText; ?></button>
      </div>
    </div>
  </div>
</div>