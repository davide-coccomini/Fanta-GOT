

<div class="container">

  <div class="row" id="pwd-container">
    <div class="col-md-4"></div>
    
    <div class="col-md-4">
      <section class="login-form">
        <form method="post" action="/php/change-password-process.php" role="login">
          <input type="password" name="oldpassword" class="form-control input-lg" placeholder=<?php echo '"'.$changePasswordPlaceholder1.'"'; ?> required="" />
          
          <input type="password" name="newpassword" class="form-control input-lg" placeholder=<?php echo '"'.$changePasswordPlaceholder2.'"'; ?> required="" />
          <input type="password" name="newpasswordconfirm" class="form-control input-lg" placeholder=<?php echo '"'.$changePasswordPlaceholder3.'"'; ?> required="" />
          
          
          <div class="pwstrength_viewport_progress"></div>
          
          
          <button type="submit" name="go" class="btn btn-lg btn-primary btn-block"><?php echo $changePasswordButton1; ?></button>
      
          
        </form>
        
      
      </section>  
      </div>
  </div>
</div>

<br><br><br>
