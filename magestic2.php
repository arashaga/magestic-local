<html>
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	
	
<title>Magestic Order Form</title>

<script type="text/javascript" src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.11.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.validation/1.13.1/jquery.validate.min.js"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="bootstrap/dist/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="bootstrap/dist/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="bootstrap/js/dropdown.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/css/bootstrapValidator.min.css"/>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>
<link rel="stylesheet" href="css/screen.css">
<script src="magesticvalidate.js"></script>
<link rel="stylesheet" href="magestic.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<script>
$(document).ready(function (){

//
	$('.stripe-button-el').prop("disabled", true );
	//$("#datepicker").datepicker().datepicker('disable');
	$("#datepicker").prop("disabled", true);
	$('.endis').attr("disabled", true );
	
$('#magform')
    .bootstrapValidator({
        message: 'This value is not valid',
        
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            customerEmail: {
                message: 'The email is not valid',
                validators: {
                    notEmpty: {
                        message: 'The email is required and can\'t be empty!'
                    },
                    emailAddress: {
                        message: 'The value is not a valid email address!'
                    },
                    identical: {
                        field: 'confirmEmail',
                        message: 'make sure to confirm your email.'
                    }
                }
            },
            confirmEmail: {
                message: 'The email is not valid',
                validators: {
                    notEmpty: {
                        message: 'The email confirmation is required!'
                    },
                    emailAddress: {
                        message: 'The value is not a valid email address!'
                    },
                    identical: {
                        field: 'customerEmail',
                        message: 'The emails do not match!'
                    }
                }
            },
		}
    })
	.on('keyup', function() {
	    // Get your form's validator
	    var validator = $('#magform').data('bootstrapValidator');

	    // Validate the form
	    validator.validate();

	    // Check if the form is valid
	    if (validator.isValid()) {
			
			if(!($("#datepicker").attr("disabled", 'disabled') && $('.stripe-button-el').attr("disabled", "disabled") ))
				return;
			//
			$("#datepicker").datepicker().datepicker('enable');

	    }
	});

	
	//DATEPICKER STUFF
	$( "#datepicker").datepicker({
			dateFormat: 'yy-mm-dd',
 	   		minDate: 1,
   	 		beforeShowDay: onlyWeekends,

			onSelect: function(date, instance){
				//var data = $(this).datepicker('getDate');
				var customerEmail = $('#customerEmail').val();
				//var lname = $('#lname').val();
				console.log(date);
				console.log(customerEmail);
				//console.log(lname);
			
			
				$.ajax({
				      type: "POST",
				      url: "rest2.php",
					// contentType: "application/json; charset=utf-8",
					  data: {'date': date, 'customerEmail': customerEmail },
							//'guest1': guest1, 'guest2': guest2, 'guest3': guest3, 'guest4' : guest4},
					//dataType: "json",
				      success: function(msg)
				      {
						  //var result = $.parseJSON(msg);
					 	 $( "#results" ).append( msg[0].res);
					
				      },
					 error: function(response, desc, err)
						 			      {
											  alert(response.responseText);
											  alert('error');
						 $( "#results" ).append( response.responseText);

						 			      },
				      statusCode: {
				       	       400: function() {
				       	       	       alert( "page not found" );
				       	       }
				       },
			    
				 })  
				 .done(function( msg ) {
					 var result = JSON.parse(msg);
					 if(result.res == 'yes'){
					 	$( "#results" ).html( "Now Please enter your guest list.");
						$('.endis').attr("disabled", false );
					 }else{
					 	$( "#results" ).html( "SORRY IT'S SOLD OUT!");
						$('.stripe-button-el').attr("disabled", true );
					
					 }
				 
					 console.log(result.res);
					 //alert( "Data Saved: " + result.res);
				 })
				 .fail(function() {
				 		 
				 });
			
				}
			});
			
			// checkout
			
		    var handler = StripeCheckout.configure({
		      key: "<?php require_once('config.php');  echo $stripe['publishable_key']; ?>",
			 address : true,
				email: true,
		        image: 'img/maglogo.png',
		      token: function(token) {
		        
				  var $input = $('<input type=hidden name=stripeToken id="stoken"/>').val(token.id);
				 $('#magform').append($input).submit(); 
		      }
		    });

		    $('#submitBtn').on('click', function(e) {
		      // Open Checkout with further options
				
				var selectValue =$('#bottles').val() * 5000;
		      handler.open({
		        name: 'Magestic',
		        description: 'Bottle Service',
				  amount: selectValue,
		      });
		      e.preventDefault();
		    });

		    // Close Checkout on page navigation
		    $(window).on('popstate', function() {
		      handler.close();
		    });
			//
			$("#bottles").on("change", function(){
				$('#submitBtn').attr("disabled", false );
			});
			

			//
	function onlyWeekends(date) {
			    var day = date.getDay();
			    return [(day != 2 && day != 4 && day !=3 && day !=1 && day !=5), ''];}
				


});




</script>

</head>


<body>

<form id="magform" method="post" class="form-horizontal" action="charge2.php">
    <div class="form-group" >
        <label class="col-sm-3 control-label">Email</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" name="customerEmail" placeholder="your email" id="customerEmail" />
		</div>
        <div class="col-sm-4">
            <input type="text" class="form-control" name="confirmEmail" placeholder="Confirm Email" id="confirmEmail" />
		</div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Pick a Date</label>
        <div class="col-sm-4">
            <input class="form-control" name="date" type="text" id="datepicker"/>
        </div>

    </div>
    <div class="form-group" >
        <label class="col-xs-6 .col-sm-4 control-label" id='results'></label>

    </div>
	
    <div class="form-group" >
        <label class="col-sm-3 control-label">Guest 1</label>
        <div class="col-sm-4">
            <input type="text" class="form-control  endis" name="guest1" placeholder="Guest 1" id="guest1" />
        </div>
        <div class="col-sm-4">
            <input type="text" class="form-control  endis" name="guest2" placeholder="Guest 2" id="guest2"  />
        </div>

    </div>
	
    <div class="form-group" >
        <label class="col-sm-3 control-label"></label>
        <div class="col-sm-4">
            <input type="text" class="form-control  endis" name="guest3" placeholder="Guest 3" id="guest3" />
        </div>
        <div class="col-sm-4">
            <input type="text" class="form-control  endis" name="guest4" placeholder="Guest 4" id="guest4"  />
        </div>

    </div>
	
	<div>
		<label class="col-sm-3 control-label">How Many</label>
	<div class="col-sm-4">
			<select class="form-control" name="bottles" id="bottles" >
				<option value="1">Select</option>
  			  	<option value="1">1 Bottle</option>
  			  	<option value="2">2 Bottles</option>
			 </select> 
	</div>
    <div class="col-sm-4">
        <input type="submit" class="form-control  endis" name="submitBtn" value="Pay" id="submitBtn" />
    </div>
  </div>
	<!--div class="col-sm-4">
   		 	<script id="oopp" src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key="<?php echo $stripe['publishable_key']; ?>"
            data-zip-code = "true" data-description="Magestic's VIP">
			</script>
      
	</div-->


 
</form>






  </body>

<script src="https://checkout.stripe.com/checkout.js"></script>




</html>