/*
    Description: 	Contact Framework
    Author: 		InsideLab
    Version: 		1.0
*/

/*	--------------------------------------------------
	:: 	Credit Card Formatter
	-------------------------------------------------- */
	
	$(document).ready(function(){
		$('#card-number').payment('formatCardNumber');
        $('#card-date').payment('formatCardExpiry');
		$('#card-cvv').payment('formatCardCVC');
		$(this).removeClass("visa"); 
		$(this).removeClass("visaelectron");
		$(this).removeClass("mastercard");
		$(this).removeClass("maestro"); 
		$(this).removeClass("discover");
		$(this).removeClass("jcb");
		$(this).removeClass("amex"); 
		$(this).removeClass("dinersclub");
		$(this).addClass($('#card-number').val());
	});
	
/*	--------------------------------------------------
	:: 	Credit Card Formatter
	-------------------------------------------------- */
	
	$(document).ready(function(){
		$('#stripe-number').payment('formatCardNumber');
		$('#stripe-month').payment('formatMonthExpiry');
		$('#stripe-year').payment('formatFourDigitYearExpiry');
		$('#stripe-cvc').payment('formatCardCVC');
		$(this).removeClass("visa"); 
		$(this).removeClass("visaelectron");
		$(this).removeClass("mastercard");
		$(this).removeClass("maestro"); 
		$(this).removeClass("discover");
		$(this).removeClass("jcb");
		$(this).removeClass("amex"); 
		$(this).removeClass("dinersclub");
		$(this).addClass($('#stripe-number').val());
	});
