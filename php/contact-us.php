<div class="modal fade" id="modalContactUs" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"><?php echo $contactUsTitle; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-announcements-body">
        <div class="row">
            <div class="col-md-12">
                <div class="well well-sm">
                    <form action="../php/contact-us-process.php?action=0" method="POST">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="email">
                                    <?php echo $contactUsLabel1; ?></label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span>
                                    </span>
                                    <input type="email" class="form-control" id="email" name="email" required="required" /></div>
                            </div>
                            <div class="form-group">
                                <label for="subject">
                                    <?php echo $contactUsLabel2; ?></label>
                                <select id="subject" name="subject" class="form-control" required="required">
                                    <option value="na" selected=""></option>
                                    <option value="supporto"><?php echo $contactUsOption1; ?></option>
                                    <option value="suggerimento"><?php echo $contactUsOption2; ?></option>
                                    <option value="candidatura"><?php echo $contactUsOption3; ?></option>
                                    <option value="altro"><?php echo $contactUsOption4; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="name">
                                    <?php echo $contactUsLabel3; ?></label>
                                <textarea name="message" id="message" class="form-control" rows="9" cols="25" required="required"
                                  ></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                                <button type="submit" class="btn btn-primary pull-right" id="btnContactUs">
                                <?php echo $contactUsButton1; ?></button>
                        
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
          <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $closeText; ?></button>
      </div>
    </div>
  </div>
</div>
