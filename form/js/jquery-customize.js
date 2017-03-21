/*
    Description: 	Contact Framework
    Author: 		InsideLab
    Version: 		1.0
*/

/*	--------------------------------------------------
	:: Contact Validation
	-------------------------------------------------- */

	$(document).ready(function(){
		var bar = $('.bar');
		var percent = $('.percent');
		$('#contact').validate({
			errorClass:'error',
			validClass:'success',
			errorElement:'em',
			highlight: function(element, errorClass, validClass) {
				var elements = $(element);
				elements.closest('.group').addClass(errorClass).removeClass(validClass);
			},
			unhighlight: function(element, errorClass, validClass) {
				var elements = $(element);
				elements.closest('.group').removeClass(errorClass).addClass(validClass);
			},
			errorPlacement: function (error, element) {
				if (element.is(":checkbox")) {
					element.closest('.checkbox-group').after(error);
			    } else if (element.is(":radio")) {
					element.closest('.radio-group').after(error);
					element.closest('.rating').after(error);
			    } else {
					error.insertAfter(element.parent());
			    }
			},
			submitHandler:function(form) {
				jQuery(form).ajaxSubmit({
					target:'#contact-message',
					beforeSubmit:function(){
						var percentVal = '0%';
						bar.width(percentVal);
						percent.html(percentVal);
						$('.progress-bar-container').fadeIn();
						$('input').attr('disabled', 'disabled');
						$('textarea').attr('disabled', 'disabled');
						$('select').attr('disabled', 'disabled');
						$('#contact-button').attr('disabled', 'disabled');
						$('#reset-button').attr('disabled', 'disabled');
					},
					uploadProgress: function(event, position, total, percentComplete) {
						var percentVal = percentComplete + '%';
						bar.width(percentVal)
						percent.html(percentVal);
					},
					success:function(){
						var percentVal = '100%';
						bar.width(percentVal);
						percent.html(percentVal);
						$('.progress-bar-container').fadeIn(500).delay(4000).fadeOut();
						$('input').removeAttr('disabled');
						$('textarea').removeAttr('disabled');
						$('select').removeAttr('disabled');
						$('#contact-button').removeAttr('disabled');
						$('#reset-button').removeAttr('disabled');
						$('#contact-message').fadeIn(500).delay(4000).fadeOut();
						$(".totalservices").text('$0');
						$('#contact').each(function(){
							this.reset();
						});
					},
				});
			}
		});
	});

/*	--------------------------------------------------
	:: 	Placeholder Form
	-------------------------------------------------- */

	$(document).ready(function(){
		$('input, textarea').placeholder();
	});

/*	--------------------------------------------------
	:: 	Loader Form
	-------------------------------------------------- */

	$(window).load(function(){
		$('.loader-container').fadeOut('slow');
	});

/*	--------------------------------------------------
	:: 	Upload Left
	-------------------------------------------------- */

	$(document).on('change', '.upload-group :file', function() {
		var input = $(this),
		numFiles = input.get(0).files ? input.get(0).files.length : 1,
		label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [numFiles, label]);
	});

	$(document).ready( function() {
		$('.upload-group :file').on('fileselect', function(event, numFiles, label) {
			var input = $(this).parents('.upload-group').find(':text'),
			log = numFiles > 1 ? numFiles + ' files selected' : label;

			if(input.length) {
				input.val(log);
			}
		});
	});

/*	--------------------------------------------------
	:: 	Multiple Files Left
	-------------------------------------------------- */

	$(document).on('change', '.drop-upload-group :file', function() {
		var input = $(this),
		numFiles = input.get(0).files ? input.get(0).files.length : 1,
		label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [numFiles, label]);
	});

	$(document).ready( function() {
		$('.drop-upload-group :file').on('fileselect', function(event, numFiles, label) {
			var input = $(this).parents('.drop-upload-group').find(':text'),
			log = numFiles > 1 ? numFiles + ' files selected' : label;

			if(input.length) {
				input.val(log);
			}
		});
	});

/*	--------------------------------------------------
	:: 	Calculate Total Price
	-------------------------------------------------- */

	$(document).ready(function() {
		function checkprice() {
			var total = 0;
			$('.checkbox-slick:checked').each(function() {
				total += parseFloat($(this).attr("rel"));
			});
			$('.totalservices').text('$' + total);
		}
		$('.checkbox-slick').change(function(){
		    checkprice();
		});
	});

/*	--------------------------------------------------
	:: 	Calculate Total Price
	-------------------------------------------------- */

	$(document).ready(function() {
		function checkprice() {
			var total = 0;
			$('.radio-slick:checked').each(function() {
				total += parseFloat($(this).attr("rel"));
			});
			$('.totalservices').text('$' + total);
		}
		$('.radio-slick').change(function(){
		    checkprice();
		});
	});
