var sendResetReques = function() {

  var action = $("#action").val();
  console.log($("#password-recover-box").serialize());
  if (action == 0) {
    $.ajax({
       url : "./../php/password-recover-process.php",
       method : "POST",
       data : $("#password-recover-form").serialize(),
       dataType : 'json',
       success : function (data,stato) {

         if (data.status == "ok") {
           $("#action").val(1);
           $("#user-info").slideToggle();
           $("#pass-info").slideToggle();
         }

       },
       error : function (richiesta, stato, errori) {

       }
    });
  }else{
    $.ajax({
       url : "./../php/password-recover-process.php",
       method : "POST",
       data : $("#password-recover-form").serialize(),
       dataType : 'json',
       success : function (data,stato) {

         if (data.status == "ok") {
           $("#pass-info").slideToggle();
         }

       },
       error : function (richiesta, stato, errori) {

       }
    });
  }
}
