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
        <center><h3><?php echo $groupCreationTitle3 ?></h3></center>
        <img src="/img/clanicons/White Walkers.png" alt="White Walkers" class="imground" data-toggle='tooltip' data-placement="bottom" title="White Walkers" />    
        
        <center><?php  echo $groupCreationDescription2; ?></center>
          <form class="form-horizontal" method="post" action="/php/group-process.php?action=0">
          <input type="hidden" name="clan" value="White Walkers">
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
