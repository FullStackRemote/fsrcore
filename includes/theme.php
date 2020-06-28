<?php

if ( ! function_exists( 'et_log' ) ) {

	function et_log($input, $file_store = ''){

		$file_store = WP_CONTENT_DIR.'/et_log.css';

		if( is_array( $input ) || is_object( $input ) ){
			error_log( date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ). ': '. print_r($input, TRUE), 3, $file_store );
		} else {
			error_log( date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ). ': '. $input . "\n" , 3, $file_store);
		}
	}
}
function et_track_payment(){

	if( ET_TRACK_PAYMENT ){
		$file_store = WP_CONTENT_DIR.'/et_track_payment.lock';

		if( is_array( $input ) || is_object( $input ) ){
			error_log( date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ). ': '. print_r($input, TRUE), 3, $file_store );
		} else {
			error_log( date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ). ': '. $input . "\n" , 3, $file_store);
		}
	}
}
if ( ! function_exists( 'fre_check_register' ) ) {
	/**
	 * check register
	 * @return bool|mixed|void $re
	 */
	function fre_check_register() {
		$re = false;
		if ( is_wp_error( MULTISITE ) && MULTISITE ) {
			$re = users_can_register_signup_filter();

		} else {
			$re = get_option( 'users_can_register', 0 );
		}

		return $re;
	}
}
if ( ! function_exists( 'fre_project_demonstration' ) ) {

	/**
	 * render project desmonstration settings in hompage
	 *
	 * @param bool $home if true render home page desmonstration/ false render list project demonstration
	 *
	 * @since v1.0
	 * @author Dakachi
	 */
	function fre_project_demonstration( $home = false ) {
		$project_demonstration = ae_get_option( 'project_demonstration' );
		if ( $home ) {
			echo $project_demonstration['home_page'];

			return;
		}
		echo $project_demonstration['list_project'];
	}
}

if ( ! function_exists( 'fre_profile_demonstration' ) ) {

	/**
	 * render profile desmonstration settings in header
	 *
	 * @param bool $home if true render home page desmonstration/ false render list project demonstration
	 *
	 * @since v1.0
	 * @author Dakachi
	 */
	function fre_profile_demonstration( $home = false ) {
		$project_demonstration = ae_get_option( 'profile_demonstration' );
		if ( $home ) {
			echo $project_demonstration['home_page'];

			return;
		}
		echo $project_demonstration['list_profile'];
	}
}

if ( ! function_exists( 'fre_logo' ) ) {

	/**
	 * render site logo image get from option
	 * @author tam
	 * @return void
	 */
	function fre_logo( $option_name = '' ) {
		if ( $option_name == '' ) {
			if ( is_front_page() ) {
				$option_name = 'site_logo_white';
			} else {
				$option_name = 'site_logo_black';
			}
		}
		switch ( $option_name ) {
			case 'site_logo':
				$img = get_template_directory_uri() . "/img/logo-fre.png";
				break;
			case 'site_logo_black':
				$img = get_template_directory_uri() . "/img/logo-fre-black.png";
				break;

			case 'site_logo_white':
				$img = get_template_directory_uri() . "/img/logo-fre-white.png";
				break;

			case 'site_logo_white_footer':
				$img = get_template_directory_uri() . "/img/logo-fre-white-footer.png";
				break;

			default:
				$img = get_template_directory_uri() . "/img/logo-fre-black.png";
				break;
		}
		$options = AE_Options::get_instance();

		// save this setting to theme options
		$site_logo = $options->$option_name;
		if ( ! empty( $site_logo ) ) {
			$img = $site_logo['large'][0];
		}
		echo '<img alt="' . $options->blogname . '" src="' . $img . '" />';
	}
}

if ( ! function_exists( 'fre_logo_mobile' ) ) {
	/**
	 * render site mobile logo image get from option
	 * @author Tuandq
	 * @return void
	 */
	function fre_logo_mobile() {
		$img     = get_template_directory_uri() . "/img/logo-fre-white.png";
		$options = AE_Options::get_instance();
		// save this setting to theme options
		$mobile_site_logo = $options->site_logo;
		if ( ! empty( $mobile_site_logo ) ) {
			$img = $mobile_site_logo['large'][0];
		} else {
			$img = get_template_directory_uri() . "/img/logo-fre-white.png";
		}
		echo '<img alt="' . $options->blogname . '" src="' . $img . '" />';
	}
}

/**
 * check site option shared role or not
 * @since 1.2
 * @author Dakachi
 */
if ( ! function_exists('fre_share_role')){
	function fre_share_role() {
		$options = AE_Options::get_instance();

		// save this setting to theme options
		return $options->fre_share_role;
	}
}

/**
 * allow user to upload a video file
 * @author tam
 *
 */
add_filter( 'upload_mimes', 'fre_add_mime_types' );
add_filter( 'et_upload_file_upload_mimes', 'fre_add_mime_types' );
function fre_add_mime_types( $mimes ) {
	/**
	 * admin can add more file extension
	 */
	if ( current_user_can( 'manage_options' ) ) {
		return array_merge( $mimes, array(
			'ac3'                  => 'audio/ac3',
			'mpa'                  => 'audio/MPA',
			'flv'                  => 'video/x-flv',
			'svg'                  => 'image/svg+xml',
			'mp4'                  => 'video/MP4',
			'doc|docx'             => 'application/msword',
			'pdf'                  => 'application/pdf',
			'zip'                  => 'multipart/x-zip',
			'xla|xls|xlt|xlw|xlsx' => 'application/vnd.ms-excel',
		) );
	}
	// if user is normal user
	$mimes = array_merge( $mimes, array(
		'xla|xls|xlt|xlw|xlsx' => 'application/vnd.ms-excel',
		'doc|docx'             => 'application/msword',
		'pdf'                  => 'application/pdf',
		'zip'                  => 'multipart/x-zip'
	) );

	return $mimes;
}

/**
 * get content current currency sign (icon)
 *
 * @param $echo bool
 *
 * @author Dakachi
 */
function fre_currency_sign( $echo = true ) {


	$currency = fre_get_currency();
	$icon = $currency['icon'];
	if ( $echo ){
		echo $icon;
	} else	{
		return $icon;
	}
}
function fre_get_currency_code( $echo = false ) {


	$currency = fre_get_currency();
	$code = $currency['code'];
	if ( !$echo ){
		return $code;
	} else	{
		echo $code;
	}
}
function fre_get_currency(){
	$currency = fre_get_df_currency();
	return apply_filters('fre_get_currency', $currency);
}
function fre_get_df_currency(){
	$currency = ae_get_option( 'currency', array(
		'align' => 'left',
		'code'  => 'USD',
		'icon'  => '$'
	) );
	return $currency;
}
function fre_price_format( $amount, $style = '<sup>' ) {


	$currency 		= fre_get_currency(); // check via fre_multi currencies extension
	$df_currency 	= fre_get_df_currency(); // df currency
	if( $currency['code'] != $df_currency['code'] ){
		$amount = apply_filters('fre_convert_currency_amount', $amount, $currency['code']);

	}

	$align = $currency['align'];
	// dafault = 0 == right;
	$icon     = $currency['icon'];
	$price_format = get_theme_mod( 'decimal_point', 1 );

	$format       = '%1$s';

	switch ( $style ) {
		case 'sup':
			$format = '<sup>%s</sup>';
			break;

		case 'sub':
			$format = '<sub>%s</sub>';
			break;

		default:
			$format = '%s';
			break;
	}

	$number_format = ae_get_option( 'number_format' );
	$decimal       = ( isset( $number_format['et_decimal'] ) ) ? $number_format['et_decimal'] : get_theme_mod( 'et_decimal', 2 );
	$decimal_point = ( isset( $number_format['dec_point'] ) && $number_format['dec_point'] ) ? $number_format['dec_point'] : get_theme_mod( 'et_decimal_point', '.' );
	$thousand_sep  = ( isset( $number_format['thousand_sep'] ) && $number_format['thousand_sep'] ) ? $number_format['thousand_sep'] : get_theme_mod( 'et_thousand_sep', ',' );

	if ( $align != "0" ) {
		$format = $format . '%s';

		return sprintf( $format, $icon, number_format( (double) $amount, $decimal, $decimal_point, $thousand_sep ) );
	} else {
		$format = '%s' . $format;

		return sprintf( $format, number_format( (double) $amount, $decimal, $decimal_point, $thousand_sep ), $icon );
	}
}
function fre_order_format( $amount, $currency_code , $style = '<sup>' ) {


	$currency 		= fre_get_currency(); // check via fre_multi currencies extension
	$df_currency 	= fre_get_df_currency(); // df currency


	$align = $currency['align'];
	// dafault = 0 == right;
	$icon     = ae_get_currency_symbol($currency_code);
	$price_format = get_theme_mod( 'decimal_point', 1 );

	$format       = '%1$s';

	switch ( $style ) {
		case 'sup':
			$format = '<sup>%s</sup>';
			break;

		case 'sub':
			$format = '<sub>%s</sub>';
			break;

		default:
			$format = '%s';
			break;
	}

	$number_format = ae_get_option( 'number_format' );
	$decimal       = ( isset( $number_format['et_decimal'] ) ) ? $number_format['et_decimal'] : get_theme_mod( 'et_decimal', 2 );
	$decimal_point = ( isset( $number_format['dec_point'] ) && $number_format['dec_point'] ) ? $number_format['dec_point'] : get_theme_mod( 'et_decimal_point', '.' );
	$thousand_sep  = ( isset( $number_format['thousand_sep'] ) && $number_format['thousand_sep'] ) ? $number_format['thousand_sep'] : get_theme_mod( 'et_thousand_sep', ',' );

	if ( $align != "0" ) {
		$format = $format . '%s';

		return sprintf( $format, $icon, number_format( (double) $amount, $decimal, $decimal_point, $thousand_sep ) );
	} else {
		$format = '%s' . $format;

		return sprintf( $format, number_format( (double) $amount, $decimal, $decimal_point, $thousand_sep ), $icon );
	}
}
function price_about_format( $price ) {
	$currency = ae_get_option( 'currency', array(
		'align' => 'left',
		'code'  => 'USD',
		'icon'  => '$'
	) );

	$align    = $currency['align'];
	$currency = $currency['icon'];
	$format   = '%s';

	$price_about = $price;
	if ( $price > 100 && $price <= 1000 ) {
		$price_about = '100+';
	}
	if ( $price > 1000 && $price <= 10000 ) {
		$price_about = '1k+';
	}
	if ( $price > 10000 ) {
		$price_about = '10k+';
	}

	if ( $align != "0" ) {
		$format = $format . '%s';

		return sprintf( $format, $currency, $price_about );
	} else {
		$format = '%s' . $format;

		return sprintf( $format, $price_about, $currency );
	}

}

function timeFormatRemoveDate( $date_fr_option ) {
	if ( preg_match( '/j/', $date_fr_option ) ) {
		$date_fr_option = str_replace( ' j,', '', $date_fr_option );
		$date_fr_option = str_replace( 'j,', '', $date_fr_option );
		$date_fr_option = str_replace( 'j', '', $date_fr_option );
	}

	if ( preg_match( '/d/', $date_fr_option ) ) {
		$date_fr_option = str_replace( 'd/', '', $date_fr_option );
		$date_fr_option = str_replace( '/d', '', $date_fr_option );
		$date_fr_option = str_replace( 'd-', '', $date_fr_option );
		$date_fr_option = str_replace( '-d', '', $date_fr_option );
		$date_fr_option = str_replace( 'd', '', $date_fr_option );
	}

	return $date_fr_option;
}

function fre_number_format( $amount, $echo = true ) {
	$number_format = ae_get_option( 'number_format' );
	$decimal       = ( isset( $number_format['et_decimal'] ) ) ? $number_format['et_decimal'] : get_theme_mod( 'et_decimal', 2 );
	$decimal_point = ( isset( $number_format['dec_point'] ) && $number_format['dec_point'] ) ? $number_format['dec_point'] : get_theme_mod( 'et_decimal_point', '.' );
	$thousand_sep  = ( isset( $number_format['thousand_sep'] ) && $number_format['thousand_sep'] ) ? $number_format['thousand_sep'] : get_theme_mod( 'et_thousand_sep', ',' );
	if ( $echo ) {
		return number_format( (double) $amount, $decimal, $decimal_point, $thousand_sep );
	} else {
		return number_format( (double) $amount, $decimal, $decimal_point, $thousand_sep );
	}
}

/**
 *
 * Function add filter orderby post status
 *
 *
 */
function fre_order_by_bid_status( $orderby ) {
	global $wpdb;
	$orderby = " case {$wpdb->posts}.post_status
                         when 'complete' then 0
                         when 'accept' then 1
                         when 'publish' then 2
                         when 'unaccept' then 3
                         end,
            {$wpdb->posts}.post_date DESC";

	return $orderby;
}

/**
 *
 * Function add filter orderby project post status
 *
 *
 */
function fre_order_by_project_status( $orderby ) {
	global $wpdb;
	// NEW VERSION
	$orderby = "{$wpdb->posts}.post_date DESC";
	// OLD VERSION
	/*
	$orderby = " case {$wpdb->posts}.post_status
							when 'disputing' then 0
							when 'reject' then 1
							when 'pending' then 2
							when 'publish' then 3
							when 'close' then 4
							when 'complete' then 5
							when 'draft' then 6
							when 'archive' then 7
							when 'disputed' then 8

						end,
						{$wpdb->posts}.post_date DESC";
	*/

	return $orderby;
}

/**
 * Function add filter orderby project post status
 */
function fre_reset_order_by_project_status( $orderby ) {
	global $wpdb;
	$orderby = "{$wpdb->posts}.post_date DESC";

	return $orderby;
}

function fre_where_current_bid( $where ) {
	global $wpdb;
	$result = $wpdb->get_col( "SELECT * FROM $wpdb->posts
        WHERE 1=1
        AND post_type = 'project'
        AND post_status IN ('publish', 'close', 'archive', 'disputing' )" );
	if ( ! empty( $result ) ) {
		$where .= "AND {$wpdb->posts}.post_parent IN (" . implode( ',', $result ) . ")";
	} else {
		$where .= "AND {$wpdb->posts}.post_parent";
	}

	return $where;
}

/**
 * Function add filter where project post status
 * Work history and review of freelance
 */
function fre_filter_where_bid( $WHERE ) {
	global $wpdb;
	$result = $wpdb->get_col( "SELECT * FROM $wpdb->posts
            WHERE 1=1
            AND post_type = 'project'
            AND post_status IN ('complete', 'disputed')" );
	if ( ! empty( $result ) ) {
		$WHERE .= "AND {$wpdb->posts}.post_parent IN (" . implode( ',', $result ) . ")";
	} else {
		$WHERE .= "AND {$wpdb->posts}.post_parent";
	}

	return $WHERE;
}

add_action( 'wp_ajax_ae_upload_files', 'fre_upload_file' );
function fre_upload_file() {
	$res = array(
		'success' => false,
		'msg'     => __( 'There is an error occurred', ET_DOMAIN ),
		'code'    => 400,
	);

	// check fileID
	if ( ! isset( $_POST['fileID'] ) || empty( $_POST['fileID'] ) ) {
		$res['msg'] = __( 'Missing image ID', ET_DOMAIN );
	} else {
		$fileID     = $_POST["fileID"];
		$imgType    = $_POST['imgType'];
		$project_id = $_POST['project_id'];
		$author_id  = $_POST['author_id'];

		$lock_status = get_post_meta( $project_id, 'lock_file', true );

		if ( $imgType == 'file' && $lock_status == 'lock' ) {
			$res['msg'] = __( 'You cannot upload a new file since partner locked this section. Please refresh the page.', ET_DOMAIN );
		} else {
			// check ajax nonce
			if ( ! de_check_ajax_referer( 'file_et_uploader', false, false ) && ! check_ajax_referer( 'file_et_uploader', false, false ) ) {
				$res['msg'] = __( 'Security error!', ET_DOMAIN );
			} elseif ( isset( $_FILES[ $fileID ] ) ) {

				// handle file upload
				$attach_id = et_process_file_upload( $_FILES[ $fileID ], 0, 0, array(
					'jpg|jpeg|jpe'     => 'image/jpeg',
					'gif'              => 'image/gif',
					'png'              => 'image/png',
					'bmp'              => 'image/bmp',
					'tif|tiff'         => 'image/tiff',
					'pdf'              => 'application/pdf',
					'doc'              => 'application/msword',
					'docx'             => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
					'odt'              => 'application/vnd.oasis.opendocument.text',
					'zip'              => 'application/zip',
					'rar'              => 'application/rar',
					'xla|xls|xlt|xlw|' => 'application/vnd.ms-excel',
					'xlsx'             => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
					'gz|gzip'          => 'application/x-gzip',
				) );

				if ( ! is_wp_error( $attach_id ) ) {

					try {
						$attach_data = et_get_attachment_data( $attach_id );

						$options = AE_Options::get_instance();
						global $current_user;
						$comment_id = wp_insert_comment( array(
							'comment_post_ID'      => $project_id,
							'comment_author'       => $current_user->data->user_login,
							'comment_author_email' => $current_user->data->user_email,
							'comment_content'      => sprintf( __( "%s has successfully uploaded a file", ET_DOMAIN ), $current_user->data->display_name ),
							'comment_type'         => 'message',
							'user_id'              => $current_user->data->ID,
							'comment_approved'     => 1
						) );
						$file_arr   = array( $attach_id );
						if ( $imgType == 'file' ) {
							update_comment_meta( $comment_id, 'fre_comment_file', $file_arr );
						} else if ( $imgType == 'attach' ) {
							update_comment_meta( $comment_id, 'fre_comment_file_attach', $file_arr );
						}
						update_post_meta( $attach_id, 'comment_file_id', $comment_id );
						$project = get_post( $project_id );
						Fre_MessageAction::fre_update_project_meta( $project );
						// save this setting to theme options
						// $options->$imgType = $attach_data;
						// $options->save();
						/**
						 * do action to control how to store data
						 *
						 * @param $attach_data the array of image data
						 * @param $request ['data']
						 * @param $attach_id the uploaded file id
						 */

						//do_action('ae_upload_image' , $attach_data , $_POST['data'], $attach_id );
						$attachment             = get_post( $attach_id );
						$attachment->post_date  = get_the_date( 'F j, Y g:i A', $attachment->ID );
						$attachment->project_id = $project_id;
						$attachment->comment_id = $comment_id;
						$attachment->avatar     = get_avatar( $author_id );
						$attachment->file_size  = size_format( filesize( get_attached_file( $attachment->ID ) ) );
						$file_type              = wp_check_filetype( get_attached_file( $attachment->ID ) );
						$attachment->file_type  = $file_type['ext'];
						$res                    = array(
							'success'    => true,
							'msg'        => __( 'File has been uploaded successfully', ET_DOMAIN ),
							'data'       => $attach_data,
							'attachment' => $attachment
						);
					} catch ( Exception $e ) {
						$res['msg'] = __( 'Error when updating settings.', ET_DOMAIN );
					}
				} else {
					$res['msg'] = $attach_id->get_error_message();
				}
			} else {
				$res['msg'] = __( 'Uploaded file not found', ET_DOMAIN );
			}
		}
	}

	// send json to client
	wp_send_json( $res );
}

/**
 * Check post type to use pending post
 *
 * @since 1.5.2
 *
 * @author Tambh
 */
add_filter( 'use_pending', 'filter_post_type_use_pending', 10, 2 );
function filter_post_type_use_pending( $pending, $post_type ) {
	if ( $post_type == PROFILE || $post_type == PORTFOLIO ) {
		$pending = false;
	}

	return $pending;
}

function mail_logo( $logo ) {
	if ( empty( $logo ) ) {
		$logo = get_template_directory_uri() . "/img/logo-fre-black.png";
	}

	return $logo;

}

add_filter( 'ae_mail_logo_url', 'mail_logo' );

if ( ! function_exists( 'fre_show_credit' ) ):

	/**
	 * conver credit number of curent user to number can bid and display as html.
	 * @since   1.7.9
	 * @author danng
	 * @return  void
	 */
	function fre_show_credit( $user_role ) {
		global $user_ID, $ae_post_factory, $post;
		/*
		* only show credit number if current user is freelancer or share role and employer role
		 */
		if ( ( $user_role == FREELANCER || ( fre_share_role() && in_array( $user_role, array(
						FREELANCER,
						EMPLOYER
					) ) ) ) && ae_get_option( 'pay_to_bid', false )
		) {
			$credits         = get_user_credit_number( $user_ID );
			$credits_pending = get_user_credit_number_pending( $user_ID );
			// Check user profile
			$profile_id = get_user_meta( $user_ID, 'user_profile_id', true );
			$profile    = get_post( $profile_id );
			?>

            <div class="fre-work-package-wrap">
                <div class="fre-work-package">
					<?php
					$free_bid_in_month = fre_get_free_bid_current_month();
					$total_bids = $credits + $free_bid_in_month;
					if( is_acti_fre_membership() ){
						$total_bids = get_number_bid_available();
						$credits_pending  = get_number_bid_pending();
					}


					if ( $total_bids > 0 ) {
						printf( __( '<p>You have <span class="number"><b>%s</b></span> available bid(s).</p>', ET_DOMAIN ), $total_bids );
						if ( $credits_pending > 0 ) {
							printf( __( '<p><span class="number">%s</span> pending bid(s) are under admin review.</p>', ET_DOMAIN ), $credits_pending );
						}
					} else {
						if ( $credits_pending > 0 ) {
							printf( __( '<p>You have <span class="number"><b>0</b></span> available bid(s).</p>', ET_DOMAIN ) );
							printf( __( '<p><span class="number">%s</span> pending bid(s) are under admin review.</p>', ET_DOMAIN ), $credits_pending );
						} else {
							if ( ! ( ! $profile || ! is_numeric( $profile_id ) ) ) {
								printf( __( '<p>You have <span class="number"><b>0</b></span> available bid(s).</p>', ET_DOMAIN ) );
							} else {
								if ( ae_get_option( 'pay_to_bid', false ) ) {
									printf( __( '<p>You have <span class="number"><b>0</b></span> available bid(s).</p>', ET_DOMAIN ) );
								}
							}
						}
					}

					printf( __( '<p>If you want to get more bids, you can directly move to purchase page by clicking the next button.</p>', ET_DOMAIN ) );
					?>
                    <a class="fre-normal-btn-o" href="<?php echo et_get_page_link( 'upgrade-account' ); ?>"><?php _e( 'Purchase more bids', ET_DOMAIN ); ?></a>
                </div>
            </div>

			<?php
		}
	}
endif;

/**
 * [fre_trim_words description]
 * This is a cool function
 * @author danng
 * @version 1.8.3.1
 * @param   string  $text      text input
 * @param   integer $num_words limit of the string result
 * @param   [type]  $more      [description]
 * @return  [type]             [description]
 */
function fre_trim_words( $text, $num_words = 55, $more = null ) {
	if ( null === $more ) {
		$more = __( '&hellip;' );
	}

	$original_text = $text;

	$text = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $text );
	$text = strip_tags($text,'<p>');

	$text = trim( $text );


	/*
	 * translators: If your word count is based on single characters (e.g. East Asian characters),
	 * enter 'characters_excluding_spaces' or 'characters_including_spaces'. Otherwise, enter 'words'.
	 * Do not translate into your own language.
	 */
	if ( strpos( _x( 'words', 'Word count type. Do not translate!' ), 'characters' ) === 0 && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) {
		$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
		preg_match_all( '/./u', $text, $words_array );
		$words_array = array_slice( $words_array[0], 0, $num_words + 1 );
		$sep = '';
	} else {
		$words_array = preg_split( "/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY );
		$sep = ' ';
	}

	if ( count( $words_array ) > $num_words ) {
		array_pop( $words_array );
		$text = implode( $sep, $words_array );
		$text = $text . $more;
	} else {
		$text = implode( $sep, $words_array );
	}

	/**
	 * Filters the text content after words have been trimmed.
	 *
	 * @since 3.3.0
	 *
	 * @param string $text          The trimmed text.
	 * @param int    $num_words     The number of words to trim the text to. Default 55.
	 * @param string $more          An optional string to append to the end of the trimmed text, e.g. &hellip;.
	 * @param string $original_text The text before it was trimmed.
	 */
	return apply_filters( 'wp_trim_words', $text, $num_words, $more, $original_text );
}
/**
 * Limit bid infor with some accounts level.
 * @author danng
 * @version 1.8.5
 * @param   array $bid           post object
 * @param   object $project      projectobject
 * @param   boolean $hide_bid_info value get from setting.
 * @return  [type]                [description]
 */
function can_see_bid_info( $bid, $project ){

	$hide_bid_info = ae_get_option('hide_bid_info', false);

	if( ! $hide_bid_info ){
		return true;
	}
	global $user_ID;
	if( current_user_can( 'manage_options' ) || $user_ID == $project->post_author ) // admin and employer can see bid infor
		return true;

	if($user_ID == $bid->post_author )
		return true;

	return false;
}

/**
 * show employer name and link to employer profile.
 * @since 1.8.5
 * @author: danng
*/
function fre_show_emp_link( $user_data ){ ?>
	<a class="emp-author-link" href="<?php echo get_author_posts_url( $user_data->ID ); ?>">
		<span class="avatar-profile"> <?php echo $user_data->display_name; ?></span>
	</a> <?php
}
/**
 * get free bib on this month of current freelancer.
 * @since: 1.8.9
*/
function fre_get_free_bid_current_month(){
	global $user_ID;
	$user_meta = 'bidded_on_'.date('m').date('Y');
	$bidded_this_month = (int) get_user_meta($user_ID, $user_meta, true);
	return (int) ae_get_option( 'fre_free_bid', 10 ) - $bidded_this_month;


}
function fre_update_free_bid(){
	global $user_ID;
	$user_meta = 'bidded_on_'.date('m').date('Y');
	et_log('user_meta: '.$user_meta);
	$bidded_this_month = (int) get_user_meta($user_ID, $user_meta, true);
	et_log('bidded_this_month: '. $bidded_this_month);
	$bidded = (int) $bidded_this_month + 1;
	update_user_meta($user_ID, $user_meta, $bidded);
}
function fre_get_pack_description($package, $number_of_post =0){

	$price = fre_price_format($package->et_price);
	$description =  sprintf(__("%s for %s project(s), displaying in %s day(s).", ET_DOMAIN) , $price, $number_of_post, $package->et_duration);;
	return $description.wp_strip_all_tags( $package->post_content );

}
function fre_pack_bid_description($package){
	$price = __("Free", ET_DOMAIN);

	if( $package->et_price ) {
        $price = fre_price_format($package->et_price);
    }

	$description = sprintf(__("%s for %s bid(s).", ET_DOMAIN) , $price, $package->et_number_posts);
	return $description.wp_strip_all_tags( $package->post_content );

}
function ae_get_currencies() {


	if ( ! isset( $currencies ) ) {
		$currencies = array_unique(
							array(
					'AED' => __( 'United Arab Emirates dirham', 'woocommerce' ),
					'AFN' => __( 'Afghan afghani', 'woocommerce' ),
					'ALL' => __( 'Albanian lek', 'woocommerce' ),
					'AMD' => __( 'Armenian dram', 'woocommerce' ),
					'ANG' => __( 'Netherlands Antillean guilder', 'woocommerce' ),
					'AOA' => __( 'Angolan kwanza', 'woocommerce' ),
					'ARS' => __( 'Argentine peso', 'woocommerce' ),
					'AUD' => __( 'Australian dollar', 'woocommerce' ),
					'AWG' => __( 'Aruban florin', 'woocommerce' ),
					'AZN' => __( 'Azerbaijani manat', 'woocommerce' ),
					'BAM' => __( 'Bosnia and Herzegovina convertible mark', 'woocommerce' ),
					'BBD' => __( 'Barbadian dollar', 'woocommerce' ),
					'BDT' => __( 'Bangladeshi taka', 'woocommerce' ),
					'BGN' => __( 'Bulgarian lev', 'woocommerce' ),
					'BHD' => __( 'Bahraini dinar', 'woocommerce' ),
					'BIF' => __( 'Burundian franc', 'woocommerce' ),
					'BMD' => __( 'Bermudian dollar', 'woocommerce' ),
					'BND' => __( 'Brunei dollar', 'woocommerce' ),
					'BOB' => __( 'Bolivian boliviano', 'woocommerce' ),
					'BRL' => __( 'Brazilian real', 'woocommerce' ),
					'BSD' => __( 'Bahamian dollar', 'woocommerce' ),
					'BTC' => __( 'Bitcoin', 'woocommerce' ),
					'BTN' => __( 'Bhutanese ngultrum', 'woocommerce' ),
					'BWP' => __( 'Botswana pula', 'woocommerce' ),
					'BYR' => __( 'Belarusian ruble (old)', 'woocommerce' ),
					'BYN' => __( 'Belarusian ruble', 'woocommerce' ),
					'BZD' => __( 'Belize dollar', 'woocommerce' ),
					'CAD' => __( 'Canadian dollar', 'woocommerce' ),
					'CDF' => __( 'Congolese franc', 'woocommerce' ),
					'CHF' => __( 'Swiss franc', 'woocommerce' ),
					'CLP' => __( 'Chilean peso', 'woocommerce' ),
					'CNY' => __( 'Chinese yuan', 'woocommerce' ),
					'COP' => __( 'Colombian peso', 'woocommerce' ),
					'CRC' => __( 'Costa Rican col&oacute;n', 'woocommerce' ),
					'CUC' => __( 'Cuban convertible peso', 'woocommerce' ),
					'CUP' => __( 'Cuban peso', 'woocommerce' ),
					'CVE' => __( 'Cape Verdean escudo', 'woocommerce' ),
					'CZK' => __( 'Czech koruna', 'woocommerce' ),
					'DJF' => __( 'Djiboutian franc', 'woocommerce' ),
					'DKK' => __( 'Danish krone', 'woocommerce' ),
					'DOP' => __( 'Dominican peso', 'woocommerce' ),
					'DZD' => __( 'Algerian dinar', 'woocommerce' ),
					'EGP' => __( 'Egyptian pound', 'woocommerce' ),
					'ERN' => __( 'Eritrean nakfa', 'woocommerce' ),
					'ETB' => __( 'Ethiopian birr', 'woocommerce' ),
					'EUR' => __( 'Euro', 'woocommerce' ),
					'FJD' => __( 'Fijian dollar', 'woocommerce' ),
					'FKP' => __( 'Falkland Islands pound', 'woocommerce' ),
					'GBP' => __( 'Pound sterling', 'woocommerce' ),
					'GEL' => __( 'Georgian lari', 'woocommerce' ),
					'GGP' => __( 'Guernsey pound', 'woocommerce' ),
					'GHS' => __( 'Ghana cedi', 'woocommerce' ),
					'GIP' => __( 'Gibraltar pound', 'woocommerce' ),
					'GMD' => __( 'Gambian dalasi', 'woocommerce' ),
					'GNF' => __( 'Guinean franc', 'woocommerce' ),
					'GTQ' => __( 'Guatemalan quetzal', 'woocommerce' ),
					'GYD' => __( 'Guyanese dollar', 'woocommerce' ),
					'HKD' => __( 'Hong Kong dollar', 'woocommerce' ),
					'HNL' => __( 'Honduran lempira', 'woocommerce' ),
					'HRK' => __( 'Croatian kuna', 'woocommerce' ),
					'HTG' => __( 'Haitian gourde', 'woocommerce' ),
					'HUF' => __( 'Hungarian forint', 'woocommerce' ),
					'IDR' => __( 'Indonesian rupiah', 'woocommerce' ),
					'ILS' => __( 'Israeli new shekel', 'woocommerce' ),
					'IMP' => __( 'Manx pound', 'woocommerce' ),
					'INR' => __( 'Indian rupee', 'woocommerce' ),
					'IQD' => __( 'Iraqi dinar', 'woocommerce' ),
					'IRR' => __( 'Iranian rial', 'woocommerce' ),
					'IRT' => __( 'Iranian toman', 'woocommerce' ),
					'ISK' => __( 'Icelandic kr&oacute;na', 'woocommerce' ),
					'JEP' => __( 'Jersey pound', 'woocommerce' ),
					'JMD' => __( 'Jamaican dollar', 'woocommerce' ),
					'JOD' => __( 'Jordanian dinar', 'woocommerce' ),
					'JPY' => __( 'Japanese yen', 'woocommerce' ),
					'KES' => __( 'Kenyan shilling', 'woocommerce' ),
					'KGS' => __( 'Kyrgyzstani som', 'woocommerce' ),
					'KHR' => __( 'Cambodian riel', 'woocommerce' ),
					'KMF' => __( 'Comorian franc', 'woocommerce' ),
					'KPW' => __( 'North Korean won', 'woocommerce' ),
					'KRW' => __( 'South Korean won', 'woocommerce' ),
					'KWD' => __( 'Kuwaiti dinar', 'woocommerce' ),
					'KYD' => __( 'Cayman Islands dollar', 'woocommerce' ),
					'KZT' => __( 'Kazakhstani tenge', 'woocommerce' ),
					'LAK' => __( 'Lao kip', 'woocommerce' ),
					'LBP' => __( 'Lebanese pound', 'woocommerce' ),
					'LKR' => __( 'Sri Lankan rupee', 'woocommerce' ),
					'LRD' => __( 'Liberian dollar', 'woocommerce' ),
					'LSL' => __( 'Lesotho loti', 'woocommerce' ),
					'LYD' => __( 'Libyan dinar', 'woocommerce' ),
					'MAD' => __( 'Moroccan dirham', 'woocommerce' ),
					'MDL' => __( 'Moldovan leu', 'woocommerce' ),
					'MGA' => __( 'Malagasy ariary', 'woocommerce' ),
					'MKD' => __( 'Macedonian denar', 'woocommerce' ),
					'MMK' => __( 'Burmese kyat', 'woocommerce' ),
					'MNT' => __( 'Mongolian t&ouml;gr&ouml;g', 'woocommerce' ),
					'MOP' => __( 'Macanese pataca', 'woocommerce' ),
					'MRO' => __( 'Mauritanian ouguiya', 'woocommerce' ),
					'MUR' => __( 'Mauritian rupee', 'woocommerce' ),
					'MVR' => __( 'Maldivian rufiyaa', 'woocommerce' ),
					'MWK' => __( 'Malawian kwacha', 'woocommerce' ),
					'MXN' => __( 'Mexican peso', 'woocommerce' ),
					'MYR' => __( 'Malaysian ringgit', 'woocommerce' ),
					'MZN' => __( 'Mozambican metical', 'woocommerce' ),
					'NAD' => __( 'Namibian dollar', 'woocommerce' ),
					'NGN' => __( 'Nigerian naira', 'woocommerce' ),
					'NIO' => __( 'Nicaraguan c&oacute;rdoba', 'woocommerce' ),
					'NOK' => __( 'Norwegian krone', 'woocommerce' ),
					'NPR' => __( 'Nepalese rupee', 'woocommerce' ),
					'NZD' => __( 'New Zealand dollar', 'woocommerce' ),
					'OMR' => __( 'Omani rial', 'woocommerce' ),
					'PAB' => __( 'Panamanian balboa', 'woocommerce' ),
					'PEN' => __( 'Peruvian nuevo sol', 'woocommerce' ),
					'PGK' => __( 'Papua New Guinean kina', 'woocommerce' ),
					'PHP' => __( 'Philippine peso', 'woocommerce' ),
					'PKR' => __( 'Pakistani rupee', 'woocommerce' ),
					'PLN' => __( 'Polish z&#x142;oty', 'woocommerce' ),
					'PRB' => __( 'Transnistrian ruble', 'woocommerce' ),
					'PYG' => __( 'Paraguayan guaran&iacute;', 'woocommerce' ),
					'QAR' => __( 'Qatari riyal', 'woocommerce' ),
					'RON' => __( 'Romanian leu', 'woocommerce' ),
					'RSD' => __( 'Serbian dinar', 'woocommerce' ),
					'RUB' => __( 'Russian ruble', 'woocommerce' ),
					'RWF' => __( 'Rwandan franc', 'woocommerce' ),
					'SAR' => __( 'Saudi riyal', 'woocommerce' ),
					'SBD' => __( 'Solomon Islands dollar', 'woocommerce' ),
					'SCR' => __( 'Seychellois rupee', 'woocommerce' ),
					'SDG' => __( 'Sudanese pound', 'woocommerce' ),
					'SEK' => __( 'Swedish krona', 'woocommerce' ),
					'SGD' => __( 'Singapore dollar', 'woocommerce' ),
					'SHP' => __( 'Saint Helena pound', 'woocommerce' ),
					'SLL' => __( 'Sierra Leonean leone', 'woocommerce' ),
					'SOS' => __( 'Somali shilling', 'woocommerce' ),
					'SRD' => __( 'Surinamese dollar', 'woocommerce' ),
					'SSP' => __( 'South Sudanese pound', 'woocommerce' ),
					'STD' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'woocommerce' ),
					'SYP' => __( 'Syrian pound', 'woocommerce' ),
					'SZL' => __( 'Swazi lilangeni', 'woocommerce' ),
					'THB' => __( 'Thai baht', 'woocommerce' ),
					'TJS' => __( 'Tajikistani somoni', 'woocommerce' ),
					'TMT' => __( 'Turkmenistan manat', 'woocommerce' ),
					'TND' => __( 'Tunisian dinar', 'woocommerce' ),
					'TOP' => __( 'Tongan pa&#x2bb;anga', 'woocommerce' ),
					'TRY' => __( 'Turkish lira', 'woocommerce' ),
					'TTD' => __( 'Trinidad and Tobago dollar', 'woocommerce' ),
					'TWD' => __( 'New Taiwan dollar', 'woocommerce' ),
					'TZS' => __( 'Tanzanian shilling', 'woocommerce' ),
					'UAH' => __( 'Ukrainian hryvnia', 'woocommerce' ),
					'UGX' => __( 'Ugandan shilling', 'woocommerce' ),
					'USD' => __( 'United States (US) dollar', 'woocommerce' ),
					'UYU' => __( 'Uruguayan peso', 'woocommerce' ),
					'UZS' => __( 'Uzbekistani som', 'woocommerce' ),
					'VEF' => __( 'Venezuelan bol&iacute;var', 'woocommerce' ),
					'VND' => __( 'Vietnamese &#x111;&#x1ed3;ng', 'woocommerce' ),
					'VUV' => __( 'Vanuatu vatu', 'woocommerce' ),
					'WST' => __( 'Samoan t&#x101;l&#x101;', 'woocommerce' ),
					'XAF' => __( 'Central African CFA franc', 'woocommerce' ),
					'XCD' => __( 'East Caribbean dollar', 'woocommerce' ),
					'XOF' => __( 'West African CFA franc', 'woocommerce' ),
					'XPF' => __( 'CFP franc', 'woocommerce' ),
					'YER' => __( 'Yemeni rial', 'woocommerce' ),
					'ZAR' => __( 'South African rand', 'woocommerce' ),
					'ZMW' => __( 'Zambian kwacha', 'woocommerce' ),
				)

		);
	}

	return $currencies;
}


/**
 * Get Currency symbol.
 *
 * @param string $currency Currency. (default: '').
 * @return string
 */
function ae_get_currency_symbol( $currency = '' ) {
	if ( ! $currency ) {
		// $currency = get_woocommerce_currency();
	}

	$symbols         = array(
			'AED' => '&#x62f;.&#x625;',
			'AFN' => '&#x60b;',
			'ALL' => 'L',
			'AMD' => 'AMD',
			'ANG' => '&fnof;',
			'AOA' => 'Kz',
			'ARS' => '&#36;',
			'AUD' => '&#36;',
			'AWG' => 'Afl.',
			'AZN' => 'AZN',
			'BAM' => 'KM',
			'BBD' => '&#36;',
			'BDT' => '&#2547;&nbsp;',
			'BGN' => '&#1083;&#1074;.',
			'BHD' => '.&#x62f;.&#x628;',
			'BIF' => 'Fr',
			'BMD' => '&#36;',
			'BND' => '&#36;',
			'BOB' => 'Bs.',
			'BRL' => '&#82;&#36;',
			'BSD' => '&#36;',
			'BTC' => '&#3647;',
			'BTN' => 'Nu.',
			'BWP' => 'P',
			'BYR' => 'Br',
			'BYN' => 'Br',
			'BZD' => '&#36;',
			'CAD' => '&#36;',
			'CDF' => 'Fr',
			'CHF' => '&#67;&#72;&#70;',
			'CLP' => '&#36;',
			'CNY' => '&yen;',
			'COP' => '&#36;',
			'CRC' => '&#x20a1;',
			'CUC' => '&#36;',
			'CUP' => '&#36;',
			'CVE' => '&#36;',
			'CZK' => '&#75;&#269;',
			'DJF' => 'Fr',
			'DKK' => 'DKK',
			'DOP' => 'RD&#36;',
			'DZD' => '&#x62f;.&#x62c;',
			'EGP' => 'EGP',
			'ERN' => 'Nfk',
			'ETB' => 'Br',
			'EUR' => '&euro;',
			'FJD' => '&#36;',
			'FKP' => '&pound;',
			'GBP' => '&pound;',
			'GEL' => '&#x20be;',
			'GGP' => '&pound;',
			'GHS' => '&#x20b5;',
			'GIP' => '&pound;',
			'GMD' => 'D',
			'GNF' => 'Fr',
			'GTQ' => 'Q',
			'GYD' => '&#36;',
			'HKD' => '&#36;',
			'HNL' => 'L',
			'HRK' => 'Kn',
			'HTG' => 'G',
			'HUF' => '&#70;&#116;',
			'IDR' => 'Rp',
			'ILS' => '&#8362;',
			'IMP' => '&pound;',
			'INR' => '&#8377;',
			'IQD' => '&#x639;.&#x62f;',
			'IRR' => '&#xfdfc;',
			'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
			'ISK' => 'kr.',
			'JEP' => '&pound;',
			'JMD' => '&#36;',
			'JOD' => '&#x62f;.&#x627;',
			'JPY' => '&yen;',
			'KES' => 'KSh',
			'KGS' => '&#x441;&#x43e;&#x43c;',
			'KHR' => '&#x17db;',
			'KMF' => 'Fr',
			'KPW' => '&#x20a9;',
			'KRW' => '&#8361;',
			'KWD' => '&#x62f;.&#x643;',
			'KYD' => '&#36;',
			'KZT' => 'KZT',
			'LAK' => '&#8365;',
			'LBP' => '&#x644;.&#x644;',
			'LKR' => '&#xdbb;&#xdd4;',
			'LRD' => '&#36;',
			'LSL' => 'L',
			'LYD' => '&#x644;.&#x62f;',
			'MAD' => '&#x62f;.&#x645;.',
			'MDL' => 'MDL',
			'MGA' => 'Ar',
			'MKD' => '&#x434;&#x435;&#x43d;',
			'MMK' => 'Ks',
			'MNT' => '&#x20ae;',
			'MOP' => 'P',
			'MRO' => 'UM',
			'MUR' => '&#x20a8;',
			'MVR' => '.&#x783;',
			'MWK' => 'MK',
			'MXN' => '&#36;',
			'MYR' => '&#82;&#77;',
			'MZN' => 'MT',
			'NAD' => '&#36;',
			'NGN' => '&#8358;',
			'NIO' => 'C&#36;',
			'NOK' => '&#107;&#114;',
			'NPR' => '&#8360;',
			'NZD' => '&#36;',
			'OMR' => '&#x631;.&#x639;.',
			'PAB' => 'B/.',
			'PEN' => 'S/.',
			'PGK' => 'K',
			'PHP' => '&#8369;',
			'PKR' => '&#8360;',
			'PLN' => '&#122;&#322;',
			'PRB' => '&#x440;.',
			'PYG' => '&#8370;',
			'QAR' => '&#x631;.&#x642;',
			'RMB' => '&yen;',
			'RON' => 'lei',
			'RSD' => '&#x434;&#x438;&#x43d;.',
			'RUB' => '&#8381;',
			'RWF' => 'Fr',
			'SAR' => '&#x631;.&#x633;',
			'SBD' => '&#36;',
			'SCR' => '&#x20a8;',
			'SDG' => '&#x62c;.&#x633;.',
			'SEK' => '&#107;&#114;',
			'SGD' => '&#36;',
			'SHP' => '&pound;',
			'SLL' => 'Le',
			'SOS' => 'Sh',
			'SRD' => '&#36;',
			'SSP' => '&pound;',
			'STD' => 'Db',
			'SYP' => '&#x644;.&#x633;',
			'SZL' => 'L',
			'THB' => '&#3647;',
			'TJS' => '&#x405;&#x41c;',
			'TMT' => 'm',
			'TND' => '&#x62f;.&#x62a;',
			'TOP' => 'T&#36;',
			'TRY' => '&#8378;',
			'TTD' => '&#36;',
			'TWD' => '&#78;&#84;&#36;',
			'TZS' => 'Sh',
			'UAH' => '&#8372;',
			'UGX' => 'UGX',
			'USD' => '&#36;',
			'UYU' => '&#36;',
			'UZS' => 'UZS',
			'VEF' => 'Bs F',
			'VND' => '&#8363;',
			'VUV' => 'Vt',
			'WST' => 'T',
			'XAF' => 'CFA',
			'XCD' => '&#36;',
			'XOF' => 'CFA',
			'XPF' => 'Fr',
			'YER' => '&#xfdfc;',
			'ZAR' => '&#82;',
			'ZMW' => 'ZK',
	);
	$currency_symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';

	return $currency_symbol;
}