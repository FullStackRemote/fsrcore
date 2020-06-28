<?php
/**
 * indicator the fre_membership is activated or not.
 * @since: 1.8.9
*/
if( ! function_exists('is_acti_fre_membership') ){
	function  is_acti_fre_membership(){
		return false;
	}
}
if( ! function_exists('fre_membership_package_info') ){
	function fre_membership_package_info(){

	}
}
if( ! function_exists('is_acti_fre_credit_plus') ){
	function is_acti_fre_credit_plus(){
		return false;
	}
}
if( ! function_exists('is_active_fre_credit') ){
	function is_active_fre_credit(){
		if( defined('FRE_CREDIT_VERSION'))
			return true;
		return false;
	}
}


/**
 * check user can or can't bid a project
 *
 * @param int $user_ID the user's ID
 *
 * @return bool true if user can bid / false if user can't bid
 * @since version 1.5.4
 * @author Tambh
 *
 */
if( ! function_exists('can_user_bid') ){
	function can_user_bid( $user_ID ) {
		global $user_ID;

		if ( ae_get_option( 'pay_to_bid', false ) ) {
			// add from 1.8.9
			$free_bid_of_this_month = fre_get_free_bid_current_month();
			if($free_bid_of_this_month > 0 ){
				return true;
			}

			if( is_acti_fre_membership() ){

				$available_bid = get_number_bid_available();
				if( $available_bid ){
					return true;
				}
				// end 1.8.9

			} else {
				$user_credits = get_user_credit_number( $user_ID );
				if ( $user_credits > 0 ) {
					return true;
				}
			}
			return false;
		}

		return true;
	}
}
/**
*/
if( ! function_exists('can_post_project_free')){
	function can_post_project_free($sku){

		if( ! is_acti_fre_membership() )
			return AE_Package::can_post_free($sku);

		return apply_filters('can_post_project_free', true , $sku);
	}
}