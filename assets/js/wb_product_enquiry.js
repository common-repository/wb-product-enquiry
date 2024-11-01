
jQuery(document).ready(function( $ ) {

		$( "#wb-enquiry-button" ).click(function() {
		  $( "#wb-enquiry-form" ).slideDown();
		});
	
	$( "#wb-enquiry-form input" ).click(function() {
		 $(this).css('background-color', '#fff');
		 $(this).css('border', '1px solid #ddd');
		});
	

    $('body').on('click', '#wb-enquiry-submit', function() {
		 $("#wb-enquiry-submit").css({
		'opacity':'0.5',
		'pointer-events':'none',
		});
		
        if($('#wb-enquiry-name').val() === '') {
            wb_product_enquiry_form_validate($('#wb-enquiry-name'));
           
        } else if($('#wb-enquiry-email').val() === '') {            
            wb_product_enquiry_form_validate($('#wb-enquiry-email'));
           
        } else if($('#wb-enquiry-phone').val() === '') {              
            wb_product_enquiry_form_validate($('#wb-enquiry-phone'));
           
        } 
		else if($('#wb-enquiry-message').val() === '') {              
            wb_product_enquiry_form_validate($('#wb-enquiry-message'));
           
        }
		else if($('#wb-enquiry-postcode').val() === '') {              
            wb_product_enquiry_form_validate($('#wb-enquiry-postcode'));
           
        } 
		else {
            var data = {
                'action': 'wb_product_enquiry_send_message',
                'product': $('#wb-enquiry-product_id').val(), 
				'name': $('#wb-enquiry-name').val(),
                'email': $('#wb-enquiry-email').val(),
				'phone': $('#wb-enquiry-phone').val(),
				
				'postcode': $('#wb-enquiry-postcode').val(),
                'message': $('#wb-enquiry-message').val(),
            };
           
            var ajaxurl = wb_product_enquiry_ajax.adminajaxurl;
            $.post(ajaxurl, data, function(response) {
                if(response === 'success'){
                    $('#wb-enquiry-name').val(''); $('#wb-enquiry-email').val(''); $('#wb-enquiry-phone').val(''); $('#wb-enquiry-message').val(''); $('#wb-enquiry-postcode').val('');
                    $('#wb_enquiry_response').html('<span class="dashicons dashicons-yes-alt"></span> Message has been sent successfuly.'); 
                    $('#wb_enquiry_response').addClass('wb_product_enquiry_success'); 
                } else{
					$('#wb_enquiry_response').html('<span class="dashicons dashicons-dismiss"></span> There has been an error while sending your message. Please try again later'); 
					$('#wb_enquiry_response').addClass('wb_product_enquiry_error'); 
				}
				$("#wb-enquiry-submit").css({
				'opacity':'1',
				'pointer-events':'auto',
				});
            });
			
        }
    });
	
	function wb_product_enquiry_form_validate(element) {
    $('html, body').animate({scrollTop: $(element).offset().top-150}, 150);
		element.css('background-color', '#e4b6b6');
		element.css('border', '3px solid red');
	}
});