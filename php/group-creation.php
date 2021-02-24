<?php
if(isset($_COOKIE["gruppo"])){
  header('location: /index/group-creation');
  nuovoMsg($generalAlert40);
  $mysqli->close();
  die();
}
 
?>
    <div class="container">
      <div class="row">
        <div class="bg-blue main-center creation-group-box">
        <center><h3><?php echo $groupCreationTitle2 ?></h3></center>
        <center><?php  echo $groupCreationDescription1; ?></center>  
        <br><br>
        <center><b><?php echo $groupCreationTitle1 ?></b></center>
          <form class="form-horizontal" method="post" action="/php/group-process.php?action=0">
          <div class="row"><br>
          <input  type="radio" name="clan"  value="Lannister"  id="Lannister" class="input-hidden" 
          <?php if(isset($_COOKIE["formClanGruppo"])){
                  if($_COOKIE["formClanGruppo"]==0){echo " checked";}
          } ?> />
          <label for="Lannister">
            <img src="/img/clanicons/Lannister.png" alt="Lannister" class="imgclan" data-toggle='tooltip' data-placement="bottom" title="Lannister" />
          </label>
          <input type="radio" name="clan" value="Stark" id="Stark" class="input-hidden" 
          <?php if(isset($_COOKIE["formClanGruppo"])){
                  if($_COOKIE["formClanGruppo"]==1){echo " checked";}
          } ?>
          />
          <label for="Stark">
            <img src="/img/clanicons/Stark.png"  alt="Stark" class="imgclan" data-toggle='tooltip' data-placement="bottom" title="Stark" />
          </label>
          <input type="radio" name="clan" value="Targaryen" id="Targaryen" class="input-hidden" 
          <?php if(isset($_COOKIE["formClanGruppo"])){
                  if($_COOKIE["formClanGruppo"]==2){echo " checked";}
          } ?>
          />
          <label for="Targaryen">
            <img src="/img/clanicons/Targaryen.png"  alt="Targaryen" class="imgclan" data-toggle='tooltip' data-placement="bottom" title="Targaryen"  />
          </label>
          </div>
         
            <div class="form-group">
              <label for="nome" class="cols-sm-2 control-label"><?php echo $groupCreationLabel1; ?></label>
              <div class="cols-sm-10">
                  <input type="text" class="form-control input-lg" name="nome" id="nome"  placeholder=<?php echo '"'.$groupCreationPlaceholder1.'"'; ?> <?php
                  	if(isset($_COOKIE['formNomeGruppo']))
                    	echo "value='".$_COOKIE['formNomeGruppo']."'";
                  ?>/>
              </div>
            </div>

               <div class="form-group">
              <label for="motto" class="cols-sm-2 control-label"><?php echo $groupCreationLabel2; ?></label>
              <div class="cols-sm-9">
                  <input type="motto" class="form-control input-lg" name="motto" id="motto"  placeholder=<?php echo '"'.$groupCreationPlaceholder2.'"'; ?> <?php
                  	if(isset($_COOKIE['formMottoGruppo']))
                    	echo "value='".$_COOKIE['formMottoGruppo']."'";
                  ?>/>
              </div>
            </div>

            <div class="form-group">
              <label for="email" class="cols-sm-2 control-label"><?php echo $groupCreationLabel3; ?></label>
              <div class="cols-sm-10">
                  <input type="text" class="form-control input-lg" name="password" id="password"  placeholder=<?php echo '"'.$groupCreationPlaceholder3.'"'; ?>/>

              </div>
            </div>

            

  

            <div class="form-group ">
              <input type="submit" class="btn btn-primary btn-lg btn-block login-button" value=<?php echo '"'.$groupCreationButton1.'"'; ?>>
            </div>
     
          </form>
        </div>
      </div>
    </div>

<script>

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});

</script>
