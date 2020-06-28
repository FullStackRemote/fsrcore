<?php
/**
 * @since 1.8.8
*/
require_once dirname(__FILE__) . '/export_personal_data.php';
require_once dirname(__FILE__) . '/erase_personal_data.php';
require_once dirname(__FILE__) . '/form.php';
function ae_submit_gdpr_form(){
	$response = array('success'=> true,'msg' => __("Your request is successful.",ET_DOMAIN) );

	$action_type = 'export_personal_data';
	$data = $_POST['data'];
	$email_address = $data['user_email_gpdr'];

	$request_id = wp_create_user_request( $email_address, $action_type );

	if( $request_id &&  !is_wp_error($request_id) ){
		wp_send_user_request( $request_id );
		$response['success'] = true;
	} else {
		$response['success'] = false;
		$response['msg'] = $request_id->get_error_message();
	}

	wp_send_json($response);
}
add_action('wp_ajax_nopriv_ae-submit-gdpr','ae_submit_gdpr_form');
add_action('wp_ajax_ae-submit-gdpr','ae_submit_gdpr_form');