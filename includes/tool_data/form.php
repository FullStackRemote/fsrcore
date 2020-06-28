<?php
function form_request_personal_data( $atts, $content = null ) {
	if( isset( $_POST['submit'] ) ){
		$action_type = 'export_personal_data';
		$email_address = $_POST['user_email_gpdr'];
		$request_id = wp_create_user_request( $email_address, $action_type );
		if( $request_id &&  !is_wp_error($request_id) ){
			wp_send_user_request( $request_id );
			_e('Your request is sent.',ET_DOMAIN);
		} else {
			echo $request_id->get_error_message();
		}

	} else {
		$user_email_gpdr = '';
		if(is_user_logged_in()){
		 	$current_user = wp_get_current_user();
		 	$user_email_gpdr = $current_user->user_email;
		}
		ob_start();	?>

		<form method="post" class="wp-privacy-request-form fre-gdpr-form-js">
			<h2><?php _e( 'Submit Data Export Request',ET_DOMAIN ); ?></h2>
			<p><?php _e( 'An email will be sent to the user at this email address to verify the request.',ET_DOMAIN ); ?></p>

			<div class="wp-privacy-request-form-field" class="form-group " >
				<label for="username_or_email_for_privacy_request"><?php esc_html_e( 'Email address' ); ?></label>
				<input type="text" required class="regular-text form-control" style="height: 41px;" id="user_email_gpdr" value="<?php echo $user_email_gpdr;?>" name="user_email_gpdr" />

			</div>
			<div class="wp-privacy-request-form-field" class="form-group " ><br />
				<button type="submit" name="submit" id="submit" class="fre-btn  primary-bg-color" value="1"><?php _e('Send Request',ET_DOMAIN);?></button>
			</div>
			<?php wp_nonce_field( 'personal-data-request' ); ?>
		</form>
		<?php
		return ob_get_clean();
	}
}
add_shortcode( 'gdpr_form', 'form_request_personal_data' );