<div class="content">
<div class="container">
  <div class="row" id="pwd-container">
    <div class="alert alert-info infobox col-md-6 col-md-offset-3">
    <?php echo $groupJoinAlert1 ?><b><a href="https://www.facebook.com/groups/1881842968610054/" class="red-text" target="_blank"><?php echo $capitalClickHere; ?></a></b>
    </div>
    
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
      <section class="login-form">
        <form method="post" action="../php/group-process.php?action=2" role="login">
          <input type="text" name="gruppo" placeholder=<?php echo '"'.$groupJoinPlaceholder1.'"'; ?> required class="form-control input-lg">
          
          <input type="text" name="password" class="form-control input-lg" id="password" placeholder=<?php echo '"'.$groupJoinPlaceholder2.'"'; ?> required="" />
          
          
          <div class="pwstrength-viewport-progress"></div>
          
          
          <button type="submit" name="go" class="btn btn-lg btn-primary btn-block"><?php echo $groupJoinButton1; ?></button>
          <br>
          
        </form>
        
      
      </section>  
      </div>
    </div>
  </div>
  </div><br><br>