/*
    Description: 	Form Framework Pro
    Author: 		InsideLab
    Version: 		1.0
*/

/*	--------------------------------------------------
	:: Contact Form
	-------------------------------------------------- */
	
	jQuery.noConflict()(function($){
	    $(document).ready(function(){
	        $('#contact input, textarea, select').tooltipster({
                trigger: 'custom',
                onlyOne: true,
                position: 'right'
            });
			
		    var bar = $('.bar');
			var percent = $('.percent');
            $('#contact').validate({	
      			rules:{
					firstname:{
					    required:true,
				    },
					lastname:{
					    required:true,
				    },
					useremail:{
						required:false,
						email:false,
					},
					usersubject:{
						required:false,
					},
					usermessage:{
						required:true,
						minlength:20
					},
					department:{
						required:true,
					},
					userfile1:{
						required:true,
                        extension:'jpg|png|gif|jpeg|pjpeg|psd|doc|docx|pdf|xls|rar|zip',
						filesize:2097152
					},
					userfile2:{
						required:true,
                        extension:'jpg|png|gif|jpeg|pjpeg|psd|doc|docx|pdf|xls|rar|zip',
						filesize:2097152
					},
					userfile3:{
						required:true,
                        extension:'jpg|png|gif|jpeg|pjpeg|psd|doc|docx|pdf|xls|rar|zip',
						filesize:2097152
					},
					captcha:{
						required:true,
						remote:'php/captcha/processor-captcha.php'
					}
				},	
				errorPlacement: function (error, element) {
                    $(element).tooltipster('update', $(error).text());
                    $(element).tooltipster('show');
                },
                success: function (label, element) {
                    $(element).tooltipster('hide');
                },
				submitHandler:function(form) {
				    jQuery(form).ajaxSubmit({
					    target:'#contact-message',
                        beforeSubmit:function(){ 
						    var percentVal = '0%';
							bar.width(percentVal);
							percent.html(percentVal);
							$('.progress-bar-container').fadeIn();
							$('#contact-button').attr('disabled', 'disabled');
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
							$('#contact-button').removeAttr('disabled'); 
							$('#contact-message').fadeIn(500).delay(10000).fadeOut();
							$('#contact').each(function(){
                                this.reset();
                            });
						},
				    });
			    }
	        });
        });
	});