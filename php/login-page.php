<div class="container" id="login-content">
  <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-12 login-container">
 
   <a href="/index/home"> 
	    <center><h1 alt="Fanta-GOT">
        <div id="logoindex" title="fanta-got-logo">
          <div class="col-xs-12 col-md-10 col-md-offset-1 logoindex" title="fanta-got-logo"></div>
        </div>
      </h1></center>
    </a>
   
      <section class="login-form">

        <form method="post" action="/php/login-process.php" role="login">
        <div class="col-md-12 login-description">
          <p><?php echo $loginSidebar1."<br>".$loginSidebar2."<br>".$loginSidebar3 ?></p>
        </div>
          <input type="email" name="email" placeholder="Email" autocomplete="email" required class="form-control input-lg">

          <input type="password" name="password" class="form-control input-lg password" placeholder="Password" required="" />


          <div id="fb-root"></div>
          <div>
            <a href="javascript:accedi()"><div class="col-md-6 col-xs-6 btn btn-fb"><div class="row"><div class="col-md-12 col-xs-12 col-sm-12"><img src="https://www.facebook.com/rsrc.php/v3/yC/r/aMltqKRlCHD.png" alt="Facebook" title="Facebook"> </div></div></div></a>
            <a href="/php/libs/twitter/twitter-helper.php"><div class="col-md-6 col-xs-6 btn btn-tw"><div class="row" id="twitter-content"><div class="col-md-12 col-md-12 col-xs-12"><img src="/img/twitter.png" id="imgtwitter" alt="Twitter" title="Twitter"> </div><div class="col-md-10 col-xs-10"></div></div></a>
          </div>
          <button type="submit" name="go" class="btn btn-primary btn-block"><?php echo $loginButton ?></button>
          <div class="login-others">
            <a href="javascript:changeSection(1)"><?php echo $createAccount; ?></a> <?php echo $or ?> <a href="javascript:changeSection(2)"><?php echo $resetPassword; ?></a><br>
            <a href="/rules" target="blank_"><?php echo $howToPlay; ?></a>
          </div>

        </form>


      </section>

      </div>
  </div>



</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="../js/bootstrap.min.js"> </script>
<script>
function audio(target){
var t=document.getElementsByTagName("video")[0];
  if(target.className=="fa fa-volume-up volume-icon"){
    target.className="fa fa-volume-off volume-icon";
    t.muted=true;
  }else{
    target.className="fa fa-volume-up volume-icon";
    t.muted=false;
  }
}

</script>