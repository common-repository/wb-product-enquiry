<?php



/********************************************************************************** Running Modes logics  *****************************************************************************************************/



add_action( 'init', 'wb_product_enquiry_catalog_mode_enforce' );
function wb_product_enquiry_catalog_mode_enforce() {
        $wb_product_enquiry_options = get_option('wb_product_enquiry_options');
		if(isset( $wb_product_enquiry_options['wb_product_enquiry_EnableCatalogMode'] )){
			  remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			  remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			  if(isset( $wb_product_enquiry_options['wb_product_enquiry_hidePrices'] )){
					  remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
					  remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
			  }
			  add_filter( 'woocommerce_is_purchasable', '__return_false');
		}
}
 
 
 add_action( 'woocommerce_single_product_summary', 'wb_product_enquiry_product_enquiry_mode_enforce', 100 );
 function wb_product_enquiry_product_enquiry_mode_enforce() {
		$wb_product_enquiry_options = get_option('wb_product_enquiry_options');
		if(isset( $wb_product_enquiry_options['wb_product_enquiry_EnableProductEnquiryMode'] )){
			
			
			if(!wp_style_is('wb_product_enquiry_addAssets') && !wp_script_is('wb_product_enquiry_addAssets')){
				// Check if assets have been already added on a page. Add them for a front-end if they're not on there.
				wp_register_style('wb_product_enquiry_addAssets', plugins_url('../assets/css/wb_product_enquiry.css',__FILE__ ));
				wp_enqueue_style('wb_product_enquiry_addAssets');
				wp_register_script( 'wb_product_enquiry_addAssets', plugins_url('../assets/js/wb_product_enquiry.js',__FILE__ ));
				wp_enqueue_script('wb_product_enquiry_addAssets');
				wp_localize_script( 'wb_product_enquiry_addAssets', 'wb_product_enquiry_ajax', array( 'adminajaxurl' => admin_url( 'admin-ajax.php' )));        

			}
			
		
			
			global $product;
			$wb_product_enquiry_prod_id = $product->get_id();
			$wb_product_enquiry_prod_name = $product->get_name();
			
			
			
			
			if(isset($wb_product_enquiry_options['WBproductEnquiryLabels'])){
				$enquiryButton = esc_html($wb_product_enquiry_options['WBproductEnquiryLabels']['callbutton']);
				if($enquiryButton==""){$enquiryButton = "Send an Enquiry";}
				$title =  esc_html($wb_product_enquiry_options['WBproductEnquiryLabels']['title']);
				if($title==""){$title = "Enquiry about";}
				$name =  esc_html($wb_product_enquiry_options['WBproductEnquiryLabels']['name']);
				if($name==""){$name = "Name";}
				$email =  esc_html($wb_product_enquiry_options['WBproductEnquiryLabels']['email']);
				if($email==""){$email = "Email";}
				$phone =  esc_html($wb_product_enquiry_options['WBproductEnquiryLabels']['phone']);
				if($phone==""){$phone = "Phone";}
				$postcode =  esc_html($wb_product_enquiry_options['WBproductEnquiryLabels']['postcode']);
				if($postcode==""){$postcode = "Postcode";}
				$message =  esc_html($wb_product_enquiry_options['WBproductEnquiryLabels']['message']);
				if($message==""){$message = "Message";}
				$submitButton =  esc_html($wb_product_enquiry_options['WBproductEnquiryLabels']['sendButton']);
				if($submitButton==""){$submitButton = "Send";}
			}
			
			$form_output = '
			<a id="wb-enquiry-button" class="button">'.$enquiryButton.'</a>
			<form id="wb-enquiry-form" role="form" method="post" enctype="multipart/form-data" style="display:none;">
			<input type="hidden" name="wb-enquiry-product_id" id="wb-enquiry-product_id" value="'.esc_attr( $wb_product_enquiry_prod_id).'">
		
							<h2 style="font-size:20px;">'.$title.'  '.esc_html( $wb_product_enquiry_prod_name).'</h2>
							
		                    <label for="enq_user_name">'.$name.'  <span class="required">*</span></label>
		                    <input type="text" name="wb-enquiry-name" id="wb-enquiry-name" value="" required="required">
		               
		                    <label for="enq_user_email">'.$email.'  <span class="required">*</span></label>
		                    <input type="text" name="wb-enquiry-email" id="wb-enquiry-email" value="" required="required">
		           
							<label>'.$phone.' <span class="required">*</span></label>
							<input type="text" value="" name="wb-enquiry-phone" id="wb-enquiry-phone" placeholder="" required="required">
							
							<label>'. $postcode .' <span class="required">*</span></label>
							<input type="text" value="" name="wb-enquiry-postcode" id="wb-enquiry-postcode" placeholder="" required="required">
				   
							<label>'.$message.' <span class="required">*</span></label>
							<textarea name="wb-enquiry-message" id="wb-enquiry-message" placeholder=""></textarea>
    
							<a id="wb-enquiry-submit" class="button" >'.$submitButton.'</a>
					
			
		</form>
		<span id="wb_enquiry_response"></span>
		';
		if(is_product()){
			echo  $form_output."

			";
		}
		}
		
 }
 
 /********************************************************************************** END Running Modes logics  *****************************************************************************************************/


?>