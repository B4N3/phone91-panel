

<script src="https://checkout.stripe.com/checkout.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.8.1/jquery.validate.min.js"></script>
<button id="customButton" onclick="saveOrder();">Purchase</button>
<input type="hidden" id="orderId" name="orderId"/>
<script>
  var handler = StripeCheckout.configure({
    key: 'pk_test_XpPymVUnHZWQ0SFRyRsV4Bxm',
    image: '/square-image.png',
    currency:'INR',
    token: function(token) {
        console.log(token);
        var orderId = $('#orderId').val();
        $.ajax({
                 url: "/controller/paymentController.php?action=paymentResponse",
                 type:'POST',
                 data: {token:token,orderId:orderId},
                 dataType:'json',
                 success:function(){
                     console.log("success");
                 }
  });
      // Use the token to create the charge with a server-side script.
      // You can access the token ID with `token.id`
    }
  });

  function saveOrder(){
    // Open Checkout with further options
    var amount = 2000;
    $.ajax({
                 url: "/controller/paymentController.php?action=saveOrderDetail",
                 type:'POST',
                 data: {'talktime':amount},
                 dataType:'json',
                 success:function(text){
                     if(text.status == "success"){
                         $('#orderId').val(text.orederId);
                     handler.open({
                     name: 'Demo Site',
                     description: 'Phone91 ($20.00)',
                     amount: 2000,
                     currency:'INR'
                    });
                }
                 }
    
        });   
    //e.preventDefault();
  }
</script>