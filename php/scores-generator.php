
<div class="row">
 <div class="col-md-10">
	<div id="scores-form">
	<?php
	if($_COOKIE["admin"]==0)
		controllaDonatore();

	$query="SELECT * FROM vociregolamento WHERE lingua='".$lingua."' ORDER BY id ASC";
	$result=$mysqli->query($query);
	while($row=$result->fetch_assoc()){
	 echo"<div class='row rule-box col-md-8'><div id='rule'>
	 <div class='scores-voice'>".$row['nome']."</div>
	 <div class='scores-details col-md-10'>".$row['dettagli']."</div>
	 <div class='quantity' >
	 <input type='number' onchange='aggiornaPunteggio(this);' class='scores-selector col-md-1 col-md-offset-1' min='".$row['punteggioMinimo']."' max='".$row['punteggioMassimo']."' id='pt".$row['id']."'> 
	 </div>

	 </div></div>";
	}


	?>
	</div>
 </div>
 <center><div id="scoresgeneratoralert">Lo scores-generator non è disponibile per mobile</div></center>
 <div class="col-md-1">
	<div id="scores-box" class="col-md-offset-0 col-xs-offset-1">
	 Punteggio<br>
	 	<div id="score">
		0
		</div>
	</div>
 </div>


</div>


<script>

function aggiornaPunteggio(){
 var punteggio=0;
 var t=document.getElementsByClassName("scores-selector");

	for(var i=0; i<t.length; i++){
		var target=t[i];
		var min=parseFloat(target.min);
		var max=parseFloat(target.max);
		var number=parseFloat(t[i].value);
		if(number<min || number>max){
			target.value="";
			number=0;
		}
		if(Number.isInteger(number)){
		 punteggio=punteggio+number;
		}
	}
var box=document.getElementById("scores-box");
if(punteggio>0){
	box.style.backgroundColor="rgba(0, 255, 57, 0.31)";
}else if(punteggio==0){
	box.style.backgroundColor="rgba(35, 35, 35, 0.31)";
}else{
	box.style.backgroundColor="rgba(255, 0, 0, 0.39)";
}

var scope=document.getElementById("score");
var textnode = document.createTextNode(punteggio); 
scope.removeChild(scope.childNodes[0]);
scope.appendChild(textnode);
}
 jQuery('<div class="quantity-nav"><div class="quantity-button quantity-up">+</div><div class="quantity-button quantity-down">-</div></div>').insertAfter('.quantity input');
    jQuery('.quantity').each(function() {
      var spinner = jQuery(this),
        input = spinner.find('input[type="number"]'),
        btnUp = spinner.find('.quantity-up'),
        btnDown = spinner.find('.quantity-down'),
        min = input.attr('min'),
        max = input.attr('max');

      btnUp.click(function() {
      	if(!input.val())
      		var oldValue=parseFloat(min)-1;
      	else
      		var oldValue=parseFloat(input.val());

        if (oldValue >= max) {
          var newVal = "";
        } else {
          var newVal = oldValue + 1;
        }
        spinner.find("input").val(newVal);
        spinner.find("input").trigger("change");
      });

      btnDown.click(function() {
      	if(!input.val())
      		var oldValue=parseFloat(max)+1;
      	else
      		var oldValue=parseFloat(input.val());
      	
        if (oldValue <= min) {
          var newVal = "";
        } else {
          var newVal = oldValue - 1;
        }
        spinner.find("input").val(newVal);
        spinner.find("input").trigger("change");
      });

    });

</script>