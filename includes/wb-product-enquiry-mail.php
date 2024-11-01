<?php

 /********************************************************************************** Mail function logics  *****************************************************************************************************/

add_action('wp_ajax_wb_product_enquiry_send_message', 'wb_product_enquiry_send_message' );
add_action('wp_ajax_nopriv_wb_product_enquiry_send_message', 'wb_product_enquiry_send_message' );
add_filter('wp_mail_content_type', 'wb_product_enquiry_mail_content_type' );
 function wb_product_enquiry_send_message() {
       
       if (isset($_POST['message'])) {
          
			$enquired_product = sanitize_text_field($_POST['product']);
			$customer_email_address = sanitize_email($_POST['email']);
			$customer_name = sanitize_text_field($_POST['name']);
			$customer_phone = sanitize_text_field($_POST['phone']);
			$customer_postcode = sanitize_text_field($_POST['postcode']);
			$customer_message = sanitize_textarea_field($_POST['message']);
			
			if(wb_product_enquiry_validate_phone_number($customer_phone)==true){
				// if number is a number do nothing
			} else{
				// strip all but numbers, to retrieve anything like phone number
				$customer_phone = preg_replace('/[^0-9]/', '', $customer_phone);
			}
			
			if ( is_email( $customer_email_address ) ) {
				
			$recipients[0] = sanitize_email( get_bloginfo('admin_email'));
			$wb_product_enquiry_options = get_option('wb_product_enquiry_options');
			if(isset($wb_product_enquiry_options['wb_product_enquiry_enquiryRecipients']) && is_array($wb_product_enquiry_options['wb_product_enquiry_enquiryRecipients'])){
					foreach($wb_product_enquiry_options['wb_product_enquiry_enquiryRecipients'] as $emailAddress){
						if(is_email($emailAddress)){
							$recipients[] = sanitize_email($emailAddress);
						}
					}
					if(isset($wb_product_enquiry_options['wb_product_enquiry_disableAdminEmail'])){
						unset($recipients[0]);
					}
			}
			//remove duplicate emails. In case custom recipient is the same as admin email address
			array_unique($recipients);
			
			$headers = 'Reply-To: '.$customer_name.' <'.$customer_email_address.'>';
            $subject = '['. get_bloginfo('name') .'] '. get_the_title($enquired_product).": enquiry from " . $customer_name;
          
            ob_start(); 
           
            echo '
			
				Product : <a href="'.get_permalink(esc_attr($enquired_product)).'">'.get_the_title( esc_html($enquired_product)).'</a><br/>
				Name : '. esc_html($customer_name).'<br/>
				Email : '. esc_html($customer_email_address).'<br/>
				Phone : '. esc_html($customer_phone).'<br/>
				Postcode : '. esc_html($customer_postcode).'<br/>
                
                
                <p>Message:</p>' .
                wpautop(esc_html($customer_message)) . '
                <br />
                
				Message sent from 
            '.esc_url(get_site_url());
           
            $message = ob_get_contents();
           
            ob_end_clean();
			
			foreach($recipients as $to){
				$mail = wp_mail($to, $subject, $message, $headers);
			}
            if($mail){
                echo esc_html('success');
				$wb_product_enquiry_db_entry = array(
				  'post_title'    => time(),
				  'post_type' 	  => 'wb-enquiries',
				  'post_status'   => 'publish',
				);
				 
				 if(!isset($wb_product_enquiry_options['wb_product_enquiry_disableEnquiriesSaving'])){
					// Insert the post into the database
					$wb_product_enquiry_entry_id = wp_insert_post( $wb_product_enquiry_db_entry );
					
					if($wb_product_enquiry_entry_id){
						  $wb_product_enquiry_db_entry = array(
							  'ID'           => $wb_product_enquiry_entry_id,
							  'post_title'	 => '#'.$wb_product_enquiry_entry_id,
						  );
						 
						// Update the post into the database
						  wp_update_post( $wb_product_enquiry_db_entry );
						  $wb_product_enquiry_details['product'] = $enquired_product;
						  $wb_product_enquiry_details['name'] = $customer_name;
						  $wb_product_enquiry_details['email'] = $customer_email_address;
						  $wb_product_enquiry_details['phone'] = $customer_phone;
						  $wb_product_enquiry_details['postcode'] = $customer_postcode;
						  $wb_product_enquiry_details['message'] = $customer_message;
						  update_post_meta( $wb_product_enquiry_entry_id, 'wb_product_enquiry',  $wb_product_enquiry_details);
					}
				}
				
            }
        }
       	}
		  
        exit();
       
    }
       
  function wb_product_enquiry_mail_content_type() {
        return "text/html";
    }
	
	function wb_product_enquiry_validate_phone_number($phone)
	{
		 $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
		 $phone_to_check = str_replace("-", "", $filtered_phone_number);
		 if (strlen($phone_to_check) < 8 || strlen($phone_to_check) > 16) {
			return false;
		 } else {
		   return true;
		 }
	}	
	
	?>