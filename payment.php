<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Payment</title>
    </head>
    <body>
        
        
        
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<style>
body {  background:url("my images/bg2.jpg");}

/* CSS for Credit Card Payment form */
.credit-card-box .panel-title {
    display: inline;
    font-weight: bold;
}
.credit-card-box .form-control.error {
    border-color: red;
    outline: 0;
    box-shadow: inset 0 1px 1px rgba(0,0,0,0.075),0 0 8px rgba(255,0,0,0.6);
}
.credit-card-box label.error {
  font-weight: bold;
  color: red;
  padding: 2px 8px;
 // margin-top: 2px;
}
.credit-card-box .payment-errors {
  font-weight: bold;
  color: red;
  padding: 2px 8px;
 // margin-top: 2px;
}
.credit-card-box label {
    display: block;
}
/* The old "center div vertically" hack */
.credit-card-box .display-table {
    display: table;
}
.credit-card-box .display-tr {
    display: table-row;
}
.credit-card-box .display-td {
    display: table-cell;
    vertical-align: middle;
    width: 50%;
}
/* Just looks nicer */
.credit-card-box .panel-heading img {
    min-width: 180px;
}
</style>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

<script>
var $form = $('#payment-form');
$form.find('.subscribe').on('click', payWithStripe);

/* If you're using Stripe for payments */
function payWithStripe(e) {
    e.preventDefault();
    
    /* Abort if invalid form data */
    if (!validator.form()) {
        return;
    }

    /* Visual feedback */
    $form.find('.subscribe').html('Validating <i class="fa fa-spinner fa-pulse"></i>').prop('disabled', true);

    var PublishableKey = 'pk_test_6pRNASCoBOKtIshFeQd4XMUh'; // Replace with your API publishable key
    Stripe.setPublishableKey(PublishableKey);
    
    /* Create token */
    var expiry = $form.find('[name=cardExpiry]').payment('cardExpiryVal');
    var ccData = {
        number: $form.find('[name=cardNumber]').val().replace(/\s/g,''),
        cvc: $form.find('[name=cardCVC]').val(),
        exp_month: expiry.month, 
        exp_year: expiry.year
    };
    
    Stripe.card.createToken(ccData, function stripeResponseHandler(status, response) {
        if (response.error) {
            /* Visual feedback */
            $form.find('.subscribe').html('Try again').prop('disabled', false);
            /* Show Stripe errors on the form */
            $form.find('.payment-errors').text(response.error.message);
            $form.find('.payment-errors').closest('.row').show();
        } else {
            /* Visual feedback */
            $form.find('.subscribe').html('Processing <i class="fa fa-spinner fa-pulse"></i>');
            /* Hide Stripe errors on the form */
            $form.find('.payment-errors').closest('.row').hide();
            $form.find('.payment-errors').text("");
            // response contains id and card, which contains additional card details            
            console.log(response.id);
            console.log(response.card);
            var token = response.id;
            // AJAX - you would send 'token' to your server here.
            $.post('/account/stripe_card_token', {
                    token: token
                })
                // Assign handlers immediately after making the request,
                .done(function(data, textStatus, jqXHR) {
                    $form.find('.subscribe').html('Payment successful <i class="fa fa-check"></i>');
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    $form.find('.subscribe').html('There was a problem').removeClass('success').addClass('error');
                    /* Show Stripe errors on the form */
                    $form.find('.payment-errors').text('Try refreshing the page and trying again.');
                    $form.find('.payment-errors').closest('.row').show();
                });
        }
    });
}
/* Fancy restrictive input formatting via jQuery.payment library*/
$('input[name=cardNumber]').payment('formatCardNumber');
$('input[name=cardCVC]').payment('formatCardCVC');
$('input[name=cardExpiry').payment('formatCardExpiry');

/* Form validation using Stripe client-side validation helpers */
jQuery.validator.addMethod("cardNumber", function(value, element) {
    return this.optional(element) || Stripe.card.validateCardNumber(value);
}, "Please specify a valid credit card number.");

jQuery.validator.addMethod("cardExpiry", function(value, element) {    
    /* Parsing month/year uses jQuery.payment library */
    value = $.payment.cardExpiryVal(value);
    return this.optional(element) || Stripe.card.validateExpiry(value.month, value.year);
}, "Invalid expiration date.");

jQuery.validator.addMethod("cardCVC", function(value, element) {
    return this.optional(element) || Stripe.card.validateCVC(value);
}, "Invalid CVC.");

validator = $form.validate({
    rules: {
        cardNumber: {
            required: true,
            cardNumber: true            
        },
        cardExpiry: {
            required: true,
            cardExpiry: true
        },
        cardCVC: {
            required: true,
            cardCVC: true
        }
    },
    highlight: function(element) {
        $(element).closest('.form-control').removeClass('success').addClass('error');
    },
    unhighlight: function(element) {
        $(element).closest('.form-control').removeClass('error').addClass('success');
    },
    errorPlacement: function(error, element) {
        $(element).closest('.form-group').append(error);
    }
});

paymentFormReady = function() {
    if ($form.find('[name=cardNumber]').hasClass("success") &&
        $form.find('[name=cardExpiry]').hasClass("success") &&
        $form.find('[name=cardCVC]').val().length > 1) {
        return true;
    } else {
        return false;
    }
}

$form.find('.subscribe').prop('disabled', true);
var readyInterval = setInterval(function() {
    if (paymentFormReady()) {
        $form.find('.subscribe').prop('disabled', false);
        clearInterval(readyInterval);
    }
}, 250);
</script>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/1.2.3/jquery.payment.min.js"></script>

<!-- If you're using Stripe for payments -->
<script src="Scripts/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="lib.js"></script>
<script>
            function calc(){
                const voucher = [
                    { "code":"XTX2GZAD",
                    "active":"false" },
                    { "code":"NDA7SY2V",
                    "active":"true" },
                    { "code":"RVA7DZ2D",
                    "active":"true" },
                    { "code":"DM8LEESR",
                    "active":"true" },
                    
                ]
            
                var codeenter = document.getElementById("voucherid").value;
                var cond = false
                for(let i = 0; i < voucher.length; i++) {
                    //console.log(voucher[i])
                    if (voucher[i].code === codeenter && voucher[i].active === "true"){
                        console.log("yes")
                            cond =true
                            break;
                    } 
                    else
                    {
                        console.log("no");
                        cond = false;
                    }
                }

                if (cond) {
                    fina = <?php echo $_GET['total'] ?>    
                    var amountdisplay = document.getElementById("amount");
                    console.log(fina - parseInt('100'))
                    amountdisplay.value = fina - parseInt('100');
                    alert('Hurray,you got £200 ');
                }
                else{
                    alert('Sorry, the voucher is not valid'); 
                }
                
            }
        </script>
    
    <!--Stylesheet-->
    <style media="screen">
      *,
*:before,
*:after{
    padding: 0;
    margin: 0;
    box-sizing: border-box;
}
body{
    background-color: #080710;
}
.background{
    width: 430px;
    height: 520px;
    position: absolute;
    transform: translate(-50%,-50%);
    left: 50%;
    top: 50%;
}
.background .shape{
    height: 130px;
    width: 130px;
    position: absolute;
    border-radius: 50%;
}
.shape:first-child{
    background: linear-gradient(
        #1845ad,
        #23a2f6
    );
    left: -80px;
    top: -20px;
}
.shape:last-child{
    background: linear-gradient(
        to right,
        #ff512f,
        #f09819
    );
    right: -30px;
    bottom: -90px;
}
form{
    height: 580px;
    width: 420px;
    background-color: rgba(255,255,255,0.13);
    position: absolute;
    transform: translate(-50%,-50%);
    top: 55%;
    left: 50%;
    border-radius: 10px;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255,255,255,0.1);
    box-shadow: 0 0 40px rgba(8,7,16,0.6);
    padding: 35px 30px;
}
form *{
    font-family: 'Poppins',sans-serif;
    color: #ffffff;
    letter-spacing: 0.5px;
    outline: none;
    border: none;
}
form h3{
    font-size: 32px;
    font-weight: 500;
    line-height: 22px;
    text-align: center;
}

label{
    display: block;
    margin-top: 20px;
    font-size: 16px;
    font-weight: 500;
}
input{
    display: block;
    height: 50px;
    width: 100%;
    background-color: rgba(255,255,255,0.07);
    border-radius: 3px;
    padding: 0 10px;
    margin-top: 8px;
    font-size: 14px;
    font-weight: 300;
}
::placeholder{
    color: #e5e5e5;
}
button{
    margin-top: 50px;
    width: 100%;
    background-color: #ffffff;
    color: #080710;
    padding: 15px 0;
    font-size: 18px;
    font-weight: 600;
    border-radius: 5px;
    cursor: pointer;
}
.social{
  margin-top: 30px;
  display: flex;
}
.social div{
  background: red;
  width: 150px;
  border-radius: 3px;
  padding: 5px 10px 10px 5px;
  background-color: rgba(255,255,255,0.27);
  color: #eaf0fb;
  text-align: center;
}
.social div:hover{
  background-color: rgba(255,255,255,0.47);
}
.social .fb{
  margin-left: 25px;
}
.social i{
  margin-right: 4px;
}

    </style>

<br>
<br>
<br>
<br>
<br>
<br>

                <div class="background">
                </div>
                    <form method="POST">
		
                        <h3>Payment</h3>

                <label for="username">Pay Pal Number</label>
                <input type="text" placeholder="Valid paypal Number"  name="cardnumber" required>

                <label for="voucherid">Voucher</label>
                <input type="text" id="voucherid" placeholder="Enter your Voucher code"  name="voucherid" required>
                <input type="button" onclick="calc()" value="Apply Vocuher"> 

                <label for="couponCode">Amount In £</label>
                                    <input type="text" id="amount" class="form-control" value="<?php echo $_GET['total'] ?>" name="amount" readonly >
                                </div>
                            </div>                        
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <input type="submit" class="btn btn-success btn-lg btn-block" name="payment" value="payment">
							</div>
                        </div>
                        <div class="row" style="display:none;">
                            <div class="col-xs-12">
                                <p class="payment-errors"></p>
                            </div>
                        </div>
						
                    </form>              
                </div>
            </div>            
            <!-- CREDIT CARD FORM ENDS HERE --> 
        </div>            
    </div>
</div>
    </body>
</html>



<?php

include "dbconfigure.php";

$total = $_GET['total'];
$id = $_GET['id'];


if(isset($_POST["payment"]))
{

$query="update bill set status='paid' where billid=$id";
$n = my_iud($query);
if($n==1)
{
echo '<script>alert("Bill Paid SuccessFully");
window.location = "viewmybill.php";
</script>';
}
else
{
echo '<script>alert("Something Went Wrong");
</script>';
}

}





?>

