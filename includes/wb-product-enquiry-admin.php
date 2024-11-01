<?php


/******************************************************************Settings sanitize**********************************************************************************************************/

function wb_product_enquiry_options_sanitize($options){
	  if( !is_array( $options ) || empty( $options ) || ( false === $options ) )
        return array();

    $valid_names = array_keys( $options );
    $clean_options = array();

	// Validate checkboxes
    foreach( $valid_names as $option_name ) {
        if( isset( $options[$option_name] ) && ( 1 == $options[$option_name] ) )
            $clean_options[$option_name] = 1;
        continue;
    }
	
	// Validate email recipients
	if (isset($options['wb_product_enquiry_enquiryRecipients'])){
		$recipients = explode(',', $options['wb_product_enquiry_enquiryRecipients']);
		foreach($recipients as $emailAddress){
			if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
				// email validation failed
			} else 	$clean_recipients[] = $emailAddress;
		}
		$clean_options['wb_product_enquiry_enquiryRecipients'] = $clean_recipients;
	}
	
	if (isset($options['WBproductEnquiryLabels'])){

	foreach( $options['WBproductEnquiryLabels'] as $key => $option_name ) {
		if( !empty($options['WBproductEnquiryLabels'][$key] )){
            $clean_options['WBproductEnquiryLabels'][$key] = sanitize_text_field( $options['WBproductEnquiryLabels'][$key]);
		} else {
			$defaultLabel = ucfirst($key);
			switch($key){
				case "callbutton":
					$defaultLabel = "Send an Enquiry";
				break;
				case "sendButton":
					$defaultLabel = "Send";
				break;
				case "title":
					$defaultLabel = "Enquiry about";
				break;
				default:
					$defaultLabel = ucfirst($key);
			}
			$clean_options['WBproductEnquiryLabels'][$key] = $defaultLabel;
		}
        continue;
    }
	
	
	}
	
    unset( $options );
    return $clean_options;
}
/******************************************************************Settings sanitize**********************************************************************************************************/

/******************************************************************Settings page content**********************************************************************************************************/


function wb_product_enquiry_settings_callback() { 
	?>
    <div class="wrap postbox"  style='padding-left:1em;'>
        <h1><?php _e( 'Product Enquiry Settings', 'textdomain' ); ?></h1>
		
		<form method="post" action="options.php">
	<?php 
		settings_fields('wb_product_enquiry_settings_group'); 
        $wb_product_enquiry_options = get_option('wb_product_enquiry_options');
	?>
	<h3>Catalog mode options</h3>
		<p>
			<label> 
				<input type="checkbox" name="wb_product_enquiry_options[wb_product_enquiry_EnableCatalogMode]" value="1" <?php checked( isset( $wb_product_enquiry_options['wb_product_enquiry_EnableCatalogMode'] ) ); ?>  "/>
				<?php 
					_e( '<b>Enable catalog mode</b>', 'textdomain' ); 
				?>
			</label>
		</p>
		<p>
			<label> 
				<input type="checkbox" name="wb_product_enquiry_options[wb_product_enquiry_hidePrices]" value="1" <?php checked( isset( $wb_product_enquiry_options['wb_product_enquiry_hidePrices'] ) ); ?>  "/>
				<?php 
					_e( '<b>Hide product prices</b>', 'textdomain' ); 
				?>
			</label>
		</p>
		<hr/>
		<h3>Product Enquiry mode options</h3>
		<p>
			<label> 
				<input type="checkbox" name="wb_product_enquiry_options[wb_product_enquiry_EnableProductEnquiryMode]" value="1" <?php checked( isset( $wb_product_enquiry_options['wb_product_enquiry_EnableProductEnquiryMode'] ) ); ?>  "/>
				<?php 
					_e( '<b>Enable product enquiry mode</b>', 'textdomain' ); 
				?>
			</label>
		</p>
		<p>
			<label> 
			<?php 
					_e( '<b>Enquiry recipients:</b><br/>', 'textdomain' ); 
					if(isset($wb_product_enquiry_options['wb_product_enquiry_enquiryRecipients']) && is_array($wb_product_enquiry_options['wb_product_enquiry_enquiryRecipients'])){
						$recipients = implode(",", $wb_product_enquiry_options['wb_product_enquiry_enquiryRecipients']);
						$recipients = esc_html($recipients);
					}
				?>
				<input type="text" name="wb_product_enquiry_options[wb_product_enquiry_enquiryRecipients]" value="<?php  echo $recipients  ?>"  />
				<?php 
					_e( '<br/><small>If you wish to add more than one recipient, separate addresses using a comma. (,) </small>', 'textdomain' ); 
				?>
			</label>
		</p>
		<p>
			<label> 
				<input type="checkbox" name="wb_product_enquiry_options[wb_product_enquiry_disableAdminEmail]" value="1" <?php checked( isset( $wb_product_enquiry_options['wb_product_enquiry_disableAdminEmail'] ) ); ?>  "/>
				<?php 
					_e( '<b>Remove admin email address from product enquiry recipients</b><br/><small><b>Please note</b>: If recipients above are not set up, emails will be sent to administrator address regardless of this setting.</small>', 'textdomain' ); 
				?>
			</label>
		</p>
		<p>
			<label> 
				<input type="checkbox" name="wb_product_enquiry_options[wb_product_enquiry_disableEnquiriesSaving]" value="1" <?php checked( isset( $wb_product_enquiry_options['wb_product_enquiry_disableEnquiriesSaving'] ) ); ?>  "/>
				<?php 
					_e( '<b>DO NOT save the enquiries in the backend</b>', 'textdomain' ); 
				?>
			</label>
		</p>
		<h4>Product Enquiry Form Labels:<br/>
		<small style='margin-top:5px;display:block'>(Replace form's wording on a product page)</small><br/></h4>
		
		
							<input type="text" name="wb_product_enquiry_options[WBproductEnquiryLabels][callbutton]"  value="<?php  if(isset($wb_product_enquiry_options['WBproductEnquiryLabels']['callbutton'])) echo  $wb_product_enquiry_options['WBproductEnquiryLabels']['callbutton'];  ?>" >
		                    <label >Label for <b><i>Send an Enquiry </i></b>button </label><br/>
							
							<input type="text" name="wb_product_enquiry_options[WBproductEnquiryLabels][title]" value="<?php  if(isset($wb_product_enquiry_options['WBproductEnquiryLabels']['title'])) echo  $wb_product_enquiry_options['WBproductEnquiryLabels']['title'];  ?>" >
		                    <label >Label for <b><i>Enquiry About {product_name}</i></b> title  </label><br/>
							
		                    <input type="text" name="wb_product_enquiry_options[WBproductEnquiryLabels][name]"  value="<?php  if(isset($wb_product_enquiry_options['WBproductEnquiryLabels']['name'])) echo  $wb_product_enquiry_options['WBproductEnquiryLabels']['name'];  ?>" >
		                    <label>Label for <b><i>Name </i></b><br/>
		               
		                    <input type="text" name="wb_product_enquiry_options[WBproductEnquiryLabels][email]" value="<?php  if(isset($wb_product_enquiry_options['WBproductEnquiryLabels']['email'])) echo  $wb_product_enquiry_options['WBproductEnquiryLabels']['email'];  ?>" >
		                    <label>Label for <b><i>Email</i></b>  </label><br/>
		           
							<input type="text" name="wb_product_enquiry_options[WBproductEnquiryLabels][phone]"   value="<?php  if(isset($wb_product_enquiry_options['WBproductEnquiryLabels']['phone'])) echo  $wb_product_enquiry_options['WBproductEnquiryLabels']['phone'];  ?>">
							<label>Label for <b><i>Phone </i></b><br/>
							
							<input type="text" name="wb_product_enquiry_options[WBproductEnquiryLabels][postcode]"   value="<?php  if(isset($wb_product_enquiry_options['WBproductEnquiryLabels']['postcode'])) echo  $wb_product_enquiry_options['WBproductEnquiryLabels']['postcode'];  ?>">
							<label>Label for <b><i>Postcode </i></b><br/>
				   
							<input type="text" name="wb_product_enquiry_options[WBproductEnquiryLabels][message]"   value="<?php  if(isset($wb_product_enquiry_options['WBproductEnquiryLabels']['message'])) echo  $wb_product_enquiry_options['WBproductEnquiryLabels']['message'];  ?>"/>
							<label>Label for <b><i>Message</i></b> <br/>
    
							<input type="text" name="wb_product_enquiry_options[WBproductEnquiryLabels][sendButton]" value="<?php  if(isset($wb_product_enquiry_options['WBproductEnquiryLabels']['sendButton'])) echo  $wb_product_enquiry_options['WBproductEnquiryLabels']['sendButton'];  ?>" />
		                    <label>Label for <b><i>Send</i></b> button  </label><br/>
	
		
	<?php
            submit_button();
    ?>
		 </form>
    </div>
    <?php
}

/****************************************************************** END Settings page content**********************************************************************************************************/
/********************************************************************************** Add menu pages to wp-admin *****************************************************************************************************/

add_action( 'admin_menu', 'wb_product_enquiry_add_menu_pages' );
function wb_product_enquiry_add_menu_pages() {
// To be uncommented in 1.2 - with dashboard page and charts.
  add_menu_page(
        __('Dashboard'),// the page title
        __('Product Enquiry'),//menu title
        'manage_options',//capability 
        'wb-product-enquiry',//menu slug
        'wb_product_enquiry_dashboard_callback',//callback function
        'dashicons-products',//icon_url,
        '50'//position
    );
	//   Dashboard page has the same slug as the main page, to override the title in submenu.
	add_submenu_page(
		'wb-product-enquiry',  // main page slug
		'Dashboard', //page title
		'Dashboard', //menu title
		'manage_options', // capability
		'wb-product-enquiry', // menu slug (same as main page to turn it to default page)
		'wb_product_enquiry_dashboard_callback' ); // callback function (which function to render page content)
    add_submenu_page(
        'wb-product-enquiry', // main page slug
        'Product Enquiry Settings', //page title
        'Settings', //menu title
        'manage_options', //capability,
        'wb-product-enquiry-settings',//menu slug
        'wb_product_enquiry_settings_callback' // callback function (which function to render page content)
    );
	$wb_product_enquiry_options = get_option('wb_product_enquiry_options');
	if(!isset($wb_product_enquiry_options['wb_product_enquiry_disableEnquiriesSaving'])){
	// Customer enquiries page
	   add_submenu_page(
	   'wb-product-enquiry', 
	   'Product Enquiries', 
	   'Product Enquiries', 
	   'manage_options', 
	   'edit.php?post_type=wb-enquiries'
	   ); 
	}
	

/* This to be commented out in 1.2
	 add_menu_page(
        __('Product Enquiry Settings'),// the page title
        __('Product Enquiry'),//menu title
        'manage_options',//capability 
        'wb-product-enquiry',//menu slug
        'wb_product_enquiry_settings_callback',//callback function
        'dashicons-products',//icon_url,
        '50'//position
    );
	//   Dashboard page has the same slug as the main page, to override the title in submenu.
    add_submenu_page(
        'wb-product-enquiry', // main page slug
        'Product Enquiry Settings', //page title
        'Settings', //menu title
        'manage_options', //capability,
        'wb-product-enquiry',//menu slug
        'wb_product_enquiry_settings_callback' // callback function (which function to render page content)
    );
	// Customer enquiries page
	$wb_product_enquiry_options = get_option('wb_product_enquiry_options');
	if(!isset($wb_product_enquiry_options['wb_product_enquiry_disableEnquiriesSaving'])){
	   add_submenu_page(
	   'wb-product-enquiry', 
	   'Product Enquiries', 
	   'Product Enquiries', 
	   'manage_options', 
	   'edit.php?post_type=wb-enquiries'
	   ); 
	}*/
}

/********************************************************************************** END add menu pages *****************************************************************************************************/
/********************************************************************************** END Render customer enquiries page *****************************************************************************************************/

// Register new custom columns for wb-enquiries post type
add_filter( 'manage_edit-wb-enquiries_columns', 'wb_product_enquiry_columns_register' ) ;
function wb_product_enquiry_columns_register( $columns ) {

	$columns = array(
		'cb' => '&lt;input type="checkbox" />',
		'title' => __( 'Enquiry Number' ),
		'product' => __( 'Product' ),
		'name' => __( 'Name' ),
		'email' => __( 'Email' ),
		'phone' => __( 'Phone' ),		
		'postcode' => __( 'Postcode' ),
		'message' => __( 'Message' ),
		'date' => __( 'Date' ),
	);

	return $columns;
}

// Render content for columns from function above
add_action( 'manage_wb-enquiries_posts_custom_column' , 'wb_product_enquiry_column_render', 10, 2 );
function wb_product_enquiry_column_render( $column, $post_id ) {
    $meta = get_post_meta( $post_id , 'wb_product_enquiry' , true ); 

	switch ( $column ) {
		case 'product' :
			if(isset($meta['product'])){
				echo  '<a href="'.get_permalink(esc_html($meta['product'])).'">'.get_the_title(esc_html($meta['product'])).'</a>';
			}
            break;
		case 'name' :
			if(isset($meta['name'])){
				echo esc_html( $meta['name']); 
			}
            break;
		case 'email' :
			if(isset($meta['email'])){
			echo esc_html( $meta['email']);
			}
            break;
		case 'phone' :
			if(isset($meta['phone'])){
			echo esc_html( $meta['phone']);
			}
            break;
		case 'postcode' :
			if(isset($meta['postcode'])){
			echo esc_html( $meta['postcode']);
			}
            break;
		case 'message' :
			if(isset($meta['message'])){
			echo esc_html( $meta['message']);
			}
            break;

    }
}

// Remove "hover" options, edit/trash/view etc. for wb-enquiries post type
function wb_product_enquiry_remove_quick_edit( $actions ) {    
 $currentScreen = get_current_screen();
    if( $currentScreen->id === "edit-wb-enquiries" ) {
     unset($actions['edit']);
     unset($actions['trash']);
     unset($actions['view']);
     unset($actions['inline hide-if-no-js']);
     
	}
	return $actions;
}
add_filter('post_row_actions','wb_product_enquiry_remove_quick_edit',10,1);

// Remove bulk edit option for wb-enquiries post type
function wb_product_enquiry_remove_bulk_actions( $actions ){
     unset( $actions['edit'] );
     return $actions;
}
add_filter('bulk_actions-edit-wb-enquiries','wb_product_enquiry_remove_bulk_actions');

/********************************************************************************** END Render customer enquiries page *****************************************************************************************************/

/********************************************************************************** Render dashboard page *****************************************************************************************************/

// Dashboard page

function wb_product_enquiry_dashboard_callback(){
		wp_register_style('wb_product_enquiry_admin_styles', plugins_url('../assets/css/wb_product_enquiry_admin.css',__FILE__ ));
		wp_enqueue_style('wb_product_enquiry_admin_styles');
	$dashboardOutput = "		<div class='postbox' style='padding-left:1em;margin-top:.5em'>
	
						<h3>Product Enquiry Dashboard</h3>
					<div class='wbEnquiriesSidebar' >
						<p style='display:none;'>Enquiries today:</p>
						<p style='display:none;'>Last 7 days today:</p>
						<p style='display:none;'>Total this month:</p>
					</div>
					<div class='wbEnquiriesChart'>
						<div class='wbEnquiriesChartCanvas'>
					";
					
					$wb_enquiries_days = array();
					for($i = 0; $i < 14; $i++) {
						$wb_enquiries_days[] = date("Y/m/d", strtotime('-'. $i .' days'));
					}
					$wb_enquiries_days = array_reverse ($wb_enquiries_days);
					$maximumEnquiriesPerDay = 0;
					
					
					// Count maximum for chart
					foreach($wb_enquiries_days as $oneDay){
						
						$oneDaySplit = explode('/', $oneDay);
						$args = array(
						'post_type' => 'wb-enquiries',
						'date_query' => array(
							array(
								'year'  => $oneDaySplit[0],
								'month' => $oneDaySplit[1],
								'day'   => $oneDaySplit[2],
							),
							'inclusive' => true
						)
						);
						$query = new WP_Query( $args );
					
					if($maximumEnquiriesPerDay<$query->post_count){
						$maximumEnquiriesPerDay = $query->post_count;
					}
					}
					// Count maximum for chart
					
					foreach($wb_enquiries_days as $oneDay){
						
						$oneDaySplit = explode('/', $oneDay);
						$args = array(
						'post_type' => 'wb-enquiries',
						'date_query' => array(
							array(
								'year'  => $oneDaySplit[0],
								'month' => $oneDaySplit[1],
								'day'   => $oneDaySplit[2],
							),
							'inclusive' => true
						)
						);
						$query = new WP_Query( $args );
					
					
					$dashboardOutput.="<div class='wbEnquiriesSingleDay' style='height:".(($query->post_count/$maximumEnquiriesPerDay)*100)."%'><div class='wbEnquiriesStats'> <span style='display:block;text-align:center'>(".$query->post_count.")</span>".$oneDay." </div></div>";}
					
					
					
	$dashboardOutput .= "</div></div></div>";
	$dashboardOutput .= "</div></div>";
echo $dashboardOutput;
}

 
/********************************************************************************** END Render dashboard pages *****************************************************************************************************/

/*************************************************************************** Bulk Export Enquiries **************************************************************************/

add_filter( 'bulk_actions-edit-wb-enquiries', function( $actions ) {
	$actions['wb_product_enquiry_csv_download'] = 'Download CSV';
	return $actions;
}, 20 );
add_filter( 'handle_bulk_actions-edit-wb-enquiries', function( $redirect_to, $action, $post_ids ) {
	if( $action !== 'wb_product_enquiry_csv_download' ) { 	return $redirect_to; }
	$args = [
		'wb_product_enquiry_csv_download' => 1,
		'post_ids' => implode( ',', $post_ids ),
	];

	
	return add_query_arg( $args, $redirect_to );
}, 10, 3 );
add_action( 'admin_init', function() {
	if( empty( $_REQUEST['wb_product_enquiry_csv_download'] ) ) { return; }

	$header = [
		'Date', 'EnquiryID', 'Product', 'Name', 'Email', 'Phone', 'Postcode', 'Message'
	];
	$data = [ $header ];
	$post_ids = explode( ',', $_REQUEST['post_ids'] );
	foreach( $post_ids as $enquiry_id ) {
			$enquiry_object = get_post($enquiry_id);
			$enquiry_data = get_post_meta($enquiry_id, 'wb_product_enquiry')[0];
				

				if($enquiry_object!=false){
				
				if(isset($enquiry_data['product'])) $product_name = get_the_title(esc_html($enquiry_data['product']));
				if(isset($enquiry_data['name'])) $name = $enquiry_data['name'];
				if(isset($enquiry_data['email'])) $email = $enquiry_data['email'];
				if(isset($enquiry_data['phone'])) $phone = $enquiry_data['phone'];
				if(isset($enquiry_data['postcode'])) $postcode = $enquiry_data['postcode'];
				if(isset($enquiry_data['message'])) $message = $enquiry_data['message'];
				$data[] = [
					$enquiry_object->post_date,
					$enquiry_id,
					$product_name,
					$name,
					$email,
					$phone,
					$postcode,
					$message
				];
				}	
	}
 
	// Output
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=webEnquiries.csv' );
	$out = fopen( 'php://output', 'w' );
	foreach( $data as $row ) { fputcsv( $out, $row ); }
	fclose( $out );
	exit;
} );

/*************************************************************************** END Bulk Export Enquiries **************************************************************************/




?>