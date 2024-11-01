<?php

 /***************************************************************** Register custom post type for enquiries ****************************************************************************/
 
 
 function wb_product_enquiry_post_type() {
	$wb_product_enquiry_options = get_option('wb_product_enquiry_options');
	if(!isset($wb_product_enquiry_options['wb_product_enquiry_disableEnquiriesSaving'])){
    $args = array(
        'public'    => true,
		'has_archive' => false,
        'label'     => __( 'Customer Product Enquiries', 'textdomain' ),
		'supports'           => array( 'title' ),
        'menu_icon' => 'dashicons-megaphone',
		'show_in_menu' => 'false',
		'capabilities' => array(
			'create_posts' => 'do_not_allow', 
			),
		'map_meta_cap' => true,
		'has_archive' => false, 
		'publicaly_queryable' => false, 
		'query_var' => false,
		
    );
    register_post_type( 'wb-enquiries', $args );
	}
}
add_action( 'init', 'wb_product_enquiry_post_type' );



// Redirect from post archive/single pages to avoid potential landing on its pages.
function wb_product_enquiry_safe_redirect() {
    global $wp_query;
    if ( is_archive('wb-enquiries') || is_singular('wb-enquiries') ) :
        $url   = get_bloginfo('url');

        wp_redirect( esc_url_raw( $url ), 301 );
        exit();
    endif;
}

 /***************************************************************** END Register custom post type for enquiries ****************************************************************************/

/********************************************************************Register Settings ********************************************************************************************************/
function wb_product_enquiry_register_settings_cb(){
    
    register_setting('wb_product_enquiry_settings_group', 'wb_product_enquiry_options', 'wb_product_enquiry_options_sanitize');
} 
add_action( 'admin_init', 'wb_product_enquiry_register_settings_cb' );

/******************************************************************** END Register Settings ********************************************************************************************************/


/********************************************************************* Add meta boxes for enquiry single admin page *************************************************************************/
function  wb_product_enquiry_single_admin_register_meta_boxes() {
    add_meta_box( 'wb_product_enquiry_details', __( 'Enquiry Details', 'textdomain' ), 'wb_product_enquiry_single_admin_display_callback', 'wb-enquiries' );
}
add_action( 'add_meta_boxes', 'wb_product_enquiry_single_admin_register_meta_boxes' );
function wb_product_enquiry_single_admin_display_callback( $post ) {
    
			$enquiry_object = get_post(get_the_ID());
			$enquiry_data = get_post_meta(get_the_ID(), 'wb_product_enquiry')[0];
				

				if($enquiry_object!=false){
				
				if(isset($enquiry_data['product'])) $product_name = get_the_title(esc_html($enquiry_data['product']));
				if(isset($enquiry_data['name'])) $name = $enquiry_data['name'];
				if(isset($enquiry_data['email'])) $email = $enquiry_data['email'];
				if(isset($enquiry_data['phone'])) $phone = $enquiry_data['phone'];
				if(isset($enquiry_data['postcode'])) $postcode = $enquiry_data['postcode'];
				if(isset($enquiry_data['message'])) $message = $enquiry_data['message'];
				
					echo "<b>Date:</b><br/>";
					echo $enquiry_object->post_date;
					echo "<br/><b>Product name:</b><br/>";
					echo $product_name;
					echo "<br/><b>Customer:</b><br/>";
					echo $name;
					echo "<br/><b>Email Address:</b><br/>";
					echo $email;
					echo "<br/><b>Phone number:</b><br/>";
					echo $phone;
					echo "<br/><b>Postcode:</b><br/>";
					echo $postcode;
					echo "<br/><b>Message:</b><br/>";
					echo $message;
				}
				
}
 
/********************************************************************* END Add meta boxes for enquiry single admin page *************************************************************************/

?>