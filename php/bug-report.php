<div class="modal fade" id="modalBugs" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><?php echo $bugReportTitle1 ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body-announcements">
      	<form method="POST" action="../php/bug-report-process.php">
      	 <div class="row"><br>
      		<center> <div class="input-group col-md-10"><span class="input-group-addon"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> <input type="text" class="form-control" name="titolo" id="username"  placeholder=<?php echo '"'.$bugReportPlaceholder1.'"' ?> data-placement="bottom" title=<?php echo '"'.$bugReportTooltip1.'"' ?>/></div>
      		</center>
      	 </div><br>
      	 <div class="input-group col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
      		<textarea style="resize: none;" name="descrizione" class="form-control col-md-12 col-sm-12 col-xs-12" rows="22" placeholder=<?php echo '"'.$bugReportPlaceholder2.'"' ?>></textarea><br><br>
      	</div>
      		<center><input type="submit" class="btn btn-primary" value=<?php echo '"'.$bugReportButton1.'"' ?>></center>
      	</form>
      </div><br>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $closeText ?></button>
      </div>
    </div>
  </div>
</div>
<script>

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});

</script>