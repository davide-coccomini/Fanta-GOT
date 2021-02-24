<div class="row">
<div class="col-md-4">
<?php
    include("php/sidebar.php");
?>
</div>
    <div class="col-md-7 market-content">
        <?php
         $query = "SELECT status FROM aperture WHERE soggetto='mercato' AND programmato IS NULL ORDER BY timestamp DESC LIMIT 1";
         $row=estrai($query,$mysqli);
         $status=$row["status"];
         $query ="SELECT personaggiSchierati FROM users WHERE username='".$_COOKIE['username']."'";
         $row=estrai($query,$mysqli);
         if($_COOKIE["messaggi"]==0){
            echo "<div class='alert alert-info col-md-11 col-md-offset-1'><center><b>".$alertDisabledMessages."<a href='/php/unablemessage.php'>".$clickHere."</a>.</b></center><br></div>";
         }
         if(isset($_COOKIE["donazioneDisponibile"])){
             if($_COOKIE["donazioneDisponibile"]==1){
                echo "<div class='alert alert-info col-md-11 col-md-offset-1'><center><b>".$alertInvited1." ".$utentiDaInvitare." ".$alertInvited2."<a href='/php/donor-process.php'>".$clickHere."</a>.</b></center><br></div>";
             }
         }
         if($row["personaggiSchierati"]>0){
            echo "<div class='alert alert-info col-md-11 col-md-offset-1'><center><b>".$alertFormation1."<a href='/index/formation'>".$alertFormation2."</a>. ".$alertFormation3."</b></center><br></div>";
         }

            $queryDaEseguire="SELECT * FROM personaggi ORDER BY prezzo DESC";
            $result=$mysqli->query($queryDaEseguire);
            $row=$result->fetch_assoc();

        
            do{
                $id=$row["id"];
                $percorso=$row["percorso"];
                $prezzo=$row["prezzo"];
                $nome=$row["nome"];
                $cookie="personaggio".$id;
                 if(!isset($row["punteggio"]))
                $titolo=$marketTooltip1;
                 else
                $titolo=$marketTooltip2." ".$row['punteggio']." ".$marketTooltip3;

                if(!isset($_COOKIE[$cookie]) && $status==0 && $_COOKIE["personaggiAcquistati"]<$maxPersonaggiAcquistabili){
                    echo '<div class="gallery_product gallery_product_scale col-xl-2 col-xl-off-set-0 col-lg-offset-1 col-md-3 col-md-offset-1 col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1 filter hdpe" id="'.$id.'">';
                    echo '<center><img src="/img/personaggi/'.$percorso.'" class="img-responsive" id="img-not-buyed" data-toggle="tooltip" data-placement="bottom" title="'.$titolo.'"></center>';
                    echo '<div class="pgname text-center">'.$nome.'</div>';
                    echo '<div class="pgprice text-center">'.$prezzo.' '.$creditsText.'</div>';
                    echo '<a href="/php/market-process.php?action=0&pg='.$id.'"><button class="btn btn-buy btn-success">'.$buyText.'</button></a>';
                    echo '</div>';
                }else if(isset($_COOKIE[$cookie]) && $status==0){
                    echo '<div class="gallery_product gallery_product_scale col-xl-2 col-xl-off-set-0 col-lg-offset-1 col-md-3 col-md-offset-1 col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1 filter hdpe" id="'.$id.'">';
                    echo '<center><img src="/img/personaggi/'.$percorso.'" class="img-responsive" id="img-market" data-toggle="tooltip" data-placement="bottom" title="'.$titolo.'"></center>';
                    echo '<div class="pgname text-center">'.$nome.'</div>';
                    echo '<div class="pgprice text-center">'.$prezzo.' '.$creditsText.'</div>';
                    echo '<a href="/php/market-process.php?action=1&pg='.$id.'"><button class="btn btn-warning" id="btn-vendi">'.$sellText.'</button></a>';
                    echo '</div>';
                }else if($status==1){
                    if(isset($_COOKIE[$cookie])){
                        echo '<div class="gallery_product gallery_product_scale col-xl-2 col-xl-off-set-0 col-lg-offset-1 col-md-3 col-md-offset-1 col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1 filter hdpe" id="'.$id.'">';
                        echo '<center><img src="/img/personaggi/'.$percorso.'" class="img-responsive" id="img-market" data-toggle="tooltip" data-placement="bottom" title="'.$titolo.'"></center>';
                        echo '<div class="pgname text-center">'.$nome.'</div>';
                        echo '<div class="pgprice text-center">'.$prezzo.' '.$creditsText.'</div>';
                        echo '<button class="btn" id="btn-buy-disabled" disabled>'.$boughtText.'</button>';
                        echo '</div>';
                    }else{
                        echo '<div class="gallery_product gallery_product_scale col-xl-2 col-xl-off-set-0 col-lg-offset-1 col-md-3 col-md-offset-1 col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1 filter hdpe" id="'.$id.'">';
                        echo '<center><img src="/img/personaggi/'.$percorso.'" class="img-responsive"  id="img-not-buyed" data-toggle="tooltip" data-placement="bottom" title="'.$titolo.'"></center>';
                        echo '<div class="pgname text-center">'.$nome.'</div>';
                        echo '<div class="pgprice text-center">'.$prezzo.' '.$creditsText.'</div>';
                        echo '<button class="btn" id="btn-buy-disabled" disabled>'.$closedText.'</button>';
                        echo '</div>';
                    }   
                }else{
                         echo '<div class="gallery_product gallery_product_scale col-xl-2 col-xl-off-set-0 col-lg-offset-1 col-md-3 col-md-offset-1 col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1 filter hdpe" id="'.$id.'">';
                        echo '<center><img src="/img/personaggi/'.$percorso.'" class="img-responsive"  id="img-not-buyed" data-toggle="tooltip" data-placement="bottom" title="'.$titolo.'"></center>';
                        echo '<div class="pgname text-center">'.$nome.'</div>';
                        echo '<div class="pgprice text-center">'.$prezzo.' '.$creditsText.'</div>';
                        echo '<button class="btn" id="btn-buy-disabled" disabled>'.$limitReachedText.'</button>';
                        echo '</div>';
                }
            }while($row=$result->fetch_assoc());
        ?> 
    </div>      
</div> 
<?php

if(isset($_GET["pg"])){
    echo "<script> 
            var etop = $('#' + ".$_GET['pg'].").offset().top-80;
            $(window).scrollTop(etop);
          </script>";
}

?>
<script>

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});

</script>
