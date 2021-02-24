<?php
//  controllaRegistrazioni($mysqli);
  if(isset($_GET["invite"]) && !isset($_COOKIE['alreadyInvited'])){
    $invitante = filtra($_GET["invite"],$mysqli);
    setcookie("invitedBy",$_GET["invite"], time() + (600), "/");
  }
?>

    <div class="container col-xs-12" id="registration-content" style="display:none">
      <?php
         if((isset($_COOKIE["invitedBy"]) || isset($_GET['invite'])) && !isset($_COOKIE['alreadyInvited'])){
            $invitedBy = (isset($_GET["invite"]))?$_GET['invite']:$_COOKIE["invitedBy"];
            echo "<br><br><div class='alert alert-info infobox col-md-4 col-md-offset-4'>".$invitedBy1."<b>".$invitedBy1."</b>".$invitedBy2."</div>";
         }
         if(isset($_COOKIE['alreadyInvited']) && isset($_GET['invite'])){
          echo "<br><br><div class='alert alert-info infobox col-md-4 col-md-offset-4'>".$invitedByError1."</div>";
         }
         $ip = ottieniIp();
         if(controllaRegistrazioniPerIp($mysqli,$ip) && isset($_GET['invite']) && !(isset($_COOKIE['alreadyInvited']))){
          echo "<br><br><div class='alert alert-info infobox col-md-4 col-md-offset-4'>".$invitedByError2."</div>";
         }
      ?>
      <div class="row">
      <div class="col-xs-12 padding-bottom-sm">

        <div class="main-login main-center gallery_product">
          <form class="form-horizontal" method="post" action="/php/registration-process.php">

       	<div class="form-group">
              <div>
                <div class="input-group">
                  <span class="input-group-addon fa-dark"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
                  <input type="text" class="form-control email" name="email" placeholder="Email" <?php
                  	if(isset($_COOKIE["formEmail"]))
                    	echo "value='".$_COOKIE['formEmail']."'";
                  ?>/>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div>
                <div class="input-group">
                  <span class="input-group-addon fa-dark"><i class="fa fa-users fa" aria-hidden="true"></i></span>
                  <input type="text" class="form-control username" name="username" placeholder="Username" data-toggle="tooltip" data-placement="bottom" title=<?php echo '"'.$registrationTooltip1.'"'; ?> <?php
                  	if(isset($_COOKIE["formEmail"]) && isset($_COOKIE['formUsername']))
                    	echo "value='".$_COOKIE['formUsername']."'";
                  ?>/>
                </div>
              </div>
            </div>




            <div class="form-group">
              <div>
                <div class="input-group">
                  <span class="input-group-addon fa-dark"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                  <input type="password" class="form-control" name="password" id="password"  placeholder=<?php echo '"'.$registrationPlaceholder1.'"'; ?>  data-toggle="tooltip" data-placement="bottom" title=<?php echo '"'.$registrationTooltip2.'"'; ?> />
                </div>
              </div>
            </div>

            <div class="form-group">
              <div>
                <div class="input-group">
                  <span class="input-group-addon fa-dark"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                  <input type="password" class="form-control" name="confirm" id="confirm"  placeholder=<?php echo '"'.$registrationPlaceholder2.'"'; ?>/>
                </div>
              </div>
            </div>
            <div class="row">
              <center>
              <div class="col-md-1">
                <input type="checkbox" required>
              </div>
              <div class="col-md-10">
              <p class="registration-policy">
               <?php echo $registrationPrivacyPolicy1 ?><a href="/privacy.htm" target="_blank"><?php echo $registrationPrivacyPolicy2; ?></a>
              </p>
              </div>
              </center>
            </div>
            <center> <div class="g-recaptcha" data-callback="attivaPulsante" data-theme="dark" data-sitekey="6LdqI4sUAAAAAMKt5iyZ1FDCn7vaHZygNhUc8nwV"></div></center>
            <div class="form-group">
              <button type="submit" id="btnreg" class="btn btn-primary btn-lg btn-block login-button col-xs-8" disabled><?php echo $registrationButton ?></button>
            </div>
    
    
            <div class="login-register">
              <a href="javascript:changeSection(0)">Login</a>
            </div>

          </form>
        </div>
      </div>
    </div>
</div>
<script>

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
function attivaPulsante()
{
  document.getElementById("btnreg").disabled=false;
}

</script>
