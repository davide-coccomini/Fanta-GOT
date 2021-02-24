<?php


if(!verificaAdmin($mysqli)){
    forzaLogout("");
}
controllaRank(3);

?>

<div class='content col-md-offset-2'>
<div class="col-md-12 market-content">
        <?php

         $query = "SELECT status FROM aperture WHERE soggetto='mercato' ORDER BY timestamp DESC LIMIT 1";
         $row=estrai($query,$mysqli);
         $status=$row["status"]; // necessario per capire se è possibile o meno effettuare l'eliminazione di un personaggio
         
        $query="SELECT * FROM personaggi ORDER BY prezzo DESC";
        $result=$mysqli->query($query);
        $row=$result->fetch_assoc();

        
            do{
                $id=$row["id"];
                $percorso=$row["percorso"];
                $prezzo=$row["prezzo"];
                $nome=$row["nome"];

                echo '<div class="gallery_product gallery_product_scale col-xl-2 col-md-3 col-sm-4 col-xs-12 filter hdpe" id="'.$id.'">';
                echo '<form action="php/panel-characters-process.php?action=0&id='.$id.'" method="POST" enctype="multipart/form-data">';
                echo '<center><img src="img/personaggi/'.$percorso.'" class="img-responsive">';
                echo '<input type="file" name="userfile"></center><input type="hidden" name="MAX_FILE_SIZE" value="5242880">';
                echo '<div class="pgname text-center"><input type="text" name="nome" class="pgname text-center" value="'.$nome.'"></div>';
                echo '<div class="pgprice text-center"><input type="number" name="prezzo" class="pgprice text-center" value="'.$prezzo.'"></div>';
                echo '<center><input type="submit" class="btn btn-success" value="Aggiorna"></center>';
                echo '</form>';

                if($status==0){
                    $delPg = "'del".$id."'";
                    echo'<center><i class="fa fa-lock" onclick="unlock(this,'.$delPg.');"  aria-hidden="true"></i><br>';
                    echo '<a href="php/panel-characters-process.php?action=1&id='.$id.'"><input type="button" id="del'.$id.'" value="Elimina" class="btn btn-danger" disabled></a></center>';
                }
                echo'</div>';
               
            }while($row=$result->fetch_assoc());

            echo '<div class="gallery_product gallery_product_scale new_character col-xl-2 col-md-3 col-sm-4 col-xs-12 filter hdpe" id="'.$id.'">';
            echo '<form action="php/panel-characters-process.php?action=2" method="POST" enctype="multipart/form-data">';
            echo '<center><img src="img/newchar.png" class="img-responsive">';
            echo '<input type="file" name="userfile"></center><input type="hidden" name="MAX_FILE_SIZE" value="5242880"></center>';
            echo '<div class="pgname text-center"><input type="text" name="nome" class="pgname text-center" placeholder="Nome del personaggio"></div>';
            echo '<div class="pgprice text-center"><input type="number" name="prezzo" class="pgprice text-center" placeholder="Prezzo del personaggio"></div>';
            echo '<center><input type="submit" class="btn btn-success" value="Crea"></center>';
            echo '</form>';
        ?> 

</div>
</div>      

<script>

function unlock(locker,id){
    var target=document.getElementById(id);
    target.disabled=!target.disabled;
    if(locker.className=="fa fa-lock"){
        locker.className="fa fa-unlock-alt";
    }else{
        locker.className="fa fa-lock";
    }
}

</script>