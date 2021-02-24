<?php
 ob_start();
  $query="SELECT *, timestamp - INTERVAL ".$timezone." HOUR as timestamp FROM annunci WHERE lingua='".$lingua."' ORDER BY timestamp DESC LIMIT 10";
  $result=$mysqli->query($query);
?>
<div class="modal fade" id="modalAnnouncements" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><?php echo $announcementsTitle1; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-announcements-body">
        <?php
            while($row=$result->fetch_assoc()){
              echo "<div class='announcement-block'>
                      <div class='col-md-12 col-xs-12 col-sm-12 announcement-title'>".$row['titolo']."<div style='float:right'>".$row['timestamp'].$timezoneLabel."</div></div>
                      <div class='col-md-12 col-xs-12 col-sm-12 announcement-text'>".$row['contenuto']."<br><br>
                      </div><div class='announcement-sign'>".$row['username']."</div>
                    </div>";
            }
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $closeText; ?></button>
      </div>
    </div>
  </div>
</div>

