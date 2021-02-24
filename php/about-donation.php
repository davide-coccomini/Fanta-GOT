
<script src="https://www.paypalobjects.com/api/checkout.js"></script>

<div class="container">
<div class="col-md-12 about-donation-box">
<center><h1><?php echo $donationTitle1; ?></h1></center>
<div class="row">
<h3><?php echo $donationTitle2; ?></h3>
<p><?php echo $donationDescription1; ?></p>
</div>
<div class="row">
<h3><?php echo $donationTitle3; ?></h3>
<p><?php echo $donationDescription2; ?><br><br>

<?php echo $donationDescription3; ?></p>

</div>

<div class="row" id="scoresgenerator">
<h4><b><?php echo $donationTitle4; ?></b></h4>
<p><?php echo $donationDescription4; ?></p><br>
 <div class="row">
      <div class="col-lg-4 col-sm-4 col-6"><a href="#scoresgenerator" title="Scores generator"><img src="../img/extras/scores_generator1.jpg" class="thumbnail img-responsive"></a></div>
      <div class="col-lg-4 col-sm-4 col-6"><a href="#scoresgenerator" title="Scores generator"><img src="../img/extras/scores_generator2.jpg" class="thumbnail img-responsive"></a></div>
      <div class="col-lg-4 col-sm-4 col-6"><a href="#scoresgenerator" title="Scores generator"><img src="../img/extras/scores_generator3.jpg" class="thumbnail img-responsive"></a></div>
</div><br>
<p>
<?php echo $donationDescription5; ?>
</p><br>
</div>
<div class="row" id="analytics">
<h4><b><?php echo $donationTitle5; ?></b></h4>
<p><?php echo $donationDescription6; ?></p><br>
 <div class="row">
      <div class="col-lg-3 col-sm-3 col-6"><a href="#analytics" title="Andamento personale"><img src="../img/extras/analytics1.jpg" class="thumbnail img-responsive"></a></div>
      <div class="col-lg-3 col-sm-3 col-6"><a href="#analytics" title="Schieramenti e acquisti"><img src="../img/extras/analytics2.jpg" class="thumbnail img-responsive"></a></div>
      <div class="col-lg-3 col-sm-3 col-6"><a href="#analytics" title="Punteggi personaggi"><img src="../img/extras/analytics3.jpg" class="thumbnail img-responsive"></a></div>
      <div class="col-lg-3 col-sm-3 col-6"><a href="#analytics" title="Scommesse, gruppi e clan"><img src="../img/extras/analytics4.jpg" class="thumbnail img-responsive"></a></div>
</div><br>
<br>
<h3><?php echo $donationTitle6; ?></h3>
<p>

<?php echo $donationDescription7; ?>
</p>
</div>

<center>
  <div class="row">
    <div class="col-md-12" id="currency-box">
     <span class="input-symbol-euro">
      <input type="number" onchange="verificaImporto(this)" min="2.00" step="0.50" max="2500" id="currency" name ="currency"> 
     </span>
    </div><br>
     <div class="col-md-12" id="paypal-button-box">
      <div id="paypal-button">
      </div>
    </div>
  </div>
</center>

  <script>
        paypal.Button.render({

            env: 'production', // sandbox | production

            // PayPal Client IDs - replace with your own
            // Create a PayPal app: https://developer.paypal.com/developer/applications/create
            client: {
                sandbox:    'staff@fantavikings.it',
                production: 'AeyYLUUts1gi8o0o47WXbXpthc0Mx-wL_LLsdhUnbe3UdqctR8PD4N2OU4bcOXQHs0Z1ratLPauWWZMm'
            },

            // Show the buyer a 'Pay Now' button in the checkout flow
            commit: true,

            // payment() is called when the button is clicked
            payment: function(data, actions) {

                // Make a call to the REST api to create the payment
                return actions.payment.create({
                    payment: {
                        transactions: [
                            {
                                amount: { total: $("#currency").val(), currency: 'EUR' }
                            }
                        ]
                    }
                });
            },

            // onAuthorize() is called when the buyer approves the payment
            onAuthorize: function(data, actions) {

                // Make a call to the REST api to execute the payment
                return actions.payment.execute().then(function() {
                  $.ajax({
                     url : "/php/payment-process.php",
                     method : "POST",
                     dataType : 'json',
                     success : function (data,stato) {
                       location.href = "index/" + data.p + "/" + data.email;

                     },
                     error : function (richiesta, stato, errori) {

                     }
                  });

                });
            }

        }, '#paypal-button');
  </script>
</div>
</div>
</div>


<div id="myModalImg" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" id="modal-dialog-rules">
  <div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3 class="modal-title modal-title-img">Heading</h3>
	</div>
	<div class="modal-body modal-body-img">

	</div>
	<div class="modal-footer">
		<button class="btn btn-default" data-dismiss="modal">Close</button>
	</div>
   </div>
  </div>
</div>


<script>
function verificaImporto(scope){
  if(scope.value<2){
    scope.value=2;
  }
}
$('.thumbnail').click(function(){
  	$('.modal-body-img').empty();
  	var title = $(this).parent('a').attr("title");
  	$('.modal-title-img').html(title);
  	$($(this).parents('div').html()).appendTo('.modal-body-img');
  	$('#myModalImg').modal({show:true});
});
</script>
