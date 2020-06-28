<?php
// ajax action: wp-privacy-export-personal-data  - wp_ajax_wp_privacy_export_personal_data
// child filter hook: wp_privacy_personal_data_exporters
// hook data: wp_user_personal_data_exporter
// request data:
/*
post_name: export_personal_data
post_type: user_request
Create a request: wp_create_user_request
*/
//wp_user_personal_data_exporter();
class Fre_Tool_Data{
	public static $instance;
	function __construct(){

	}

	public static function get_instance() {
		if ( self::$instance == null ) {
			self::$instance = new Fre_Tool_Data();
		}

		return self::$instance;
	}

	static function get_fre_meta_field(){
		return array(
			//'number_bid' => 'Number bid',
			//'earned' => 'Earned',
			'rating_score' => 'Rating Score',
			'project_worked' => 'Project worked',
			'experience' => 'Experiences',
			//'user_profile_id' => 'Profile ID',
			'hourly_rate_price' => 'Hour rate',
			'et_professional_title' => 'Personal Title',
		);
	}
}

function register_fre_theme_exporter( $exporters ) {
 	$exporters[] = array(
		'exporter_friendly_name' =>  'FreelanceEngine Data',
		'callback'               => 'fre_personal_data_exporter',
	);
 	$exporters[] = array(
		'exporter_friendly_name' =>  'Project Posted',
		'callback'               => 'fre_project_posted_exporter',
	);
	$exporters[] = array(
		'exporter_friendly_name' =>  'Project Posted',
		'callback'               => 'fre_bid_exporter',
	);
	return $exporters;

}

add_filter('wp_privacy_personal_data_exporters','register_fre_theme_exporter');
function fre_bid_exporter($email_address, $page = 1){

	$number = 300; $found_posts= 0;
	$bid_to_export = $data_to_export = array();
	$user = get_user_by( 'email', $email_address );

	$role = ae_user_role($user->ID);

	$args = array(
		'post_type' => 'bid',
		'author' => $user->ID,
		'posts_per_page' => $number,
		'post_status' => 'any',
	);
	$the_query = new WP_Query( $args );

	global $wp_query, $ae_post_factory, $post;
	$post_object = $ae_post_factory->get( BID );
	// The Loop
	if ( $the_query->have_posts() ) {

		$found_posts = $the_query->found_posts ;
		while ( $the_query->have_posts() ) {

			$bid_data_to_export = array();
			$the_query->the_post();
			global $post;
			$convert   = $post_object->convert( $post );
			$project = get_post($convert->post_parent);
			$project_id = $project->ID;
			$bid_id = $convert->ID;
			$bid_data_to_export[] = array(
				'name'  => 'Bid ID',
				'value' => $bid_id
			);

			$bid_data_to_export[] = array(
				'name'  => 'Project Name',
				'value' => '<a href="'.get_permalink($project_id).'">'.$project->post_title.'</a>'
			);

			$bid_data_to_export[] = array(
				'name'  => 'Bid Date',
				'value' => get_the_date()
			);
			$bid_data_to_export[] = array(
				'name'  => 'Bid Time',
				'value' => $convert->bid_time_text
			);
			$bid_data_to_export[] = array(
				'name'  => 'Bid Content',
				'value' => $convert->post_content
			);
			$is_assign = 'No';
			$bid_id_win =  get_post_meta($project_id, 'accepted', true);

			if( $bid_id_win ){
				$bid_accept = get_post($bid_id_win);
				if( $bid_accept && ! is_wp_error( $bid_accept ) ){

					$winner = get_userdata($bid_accept->post_author);
					if( ! is_wp_error( $winner ) && $winner->ID == $user->ID ){
						$is_assign = 'Yes';
					}
				}
			}
			$bid_data_to_export[] = array(
				'name'  => 'Is Assigned',
				'value' => $is_assign
			);

			$bid_data_to_export[] = array(
				'name'  => 'Bid Budget',
				'value' => $convert->bid_budget_text
			);

			$item_id = "bid-{$bid_id}";

			$data_to_export[] = array(
				'group_id'    => 'bids',
				'group_label' => __( 'List Bids' ),
				'item_id'     => $item_id,
				'data'        => $bid_data_to_export,
			);
		} // end while;


		wp_reset_postdata();
	}

	$done = $found_posts < $number;

	return array(
		'data' => $data_to_export,
		'done' => $done,
	);
}
function fre_project_posted_exporter($email_address, $page = 1){

	$user = get_user_by( 'email', $email_address );
	$number = 300; $found_posts = 0;
	$role = ae_user_role($user->ID);
	$group_id = 'list_project';
	$project_to_export = $data_to_export = array();
	$item_id = "project-of-{$user->ID}";

	//if( $role == EMPLOYER || $role == 'administrator' ) {
		$args = array(
			'post_type' => 'project',
			'author' => $user->ID,
			'posts_per_page' => $number,
			'post_status' => 'any',
		);
		$the_query = new WP_Query( $args );


		// The Loop
		if ( $the_query->have_posts() ) {
			$found_posts = $the_query->found_posts;
			while ( $the_query->have_posts() ) {
				$project_data_to_export = array();
				$the_query->the_post();
				global $post;

				$project_data_to_export[] = array(
					'name'  => 'Project ID',
					'value' => $post->ID,
				);
				$project_data_to_export[] = array(
					'name'  => 'Project Name',
					'value' => '<a href="'.get_permalink().'">'.get_the_title().'</a>',
				);
				$project_data_to_export[] = array(
					'name'  => 'Posted Date',
					'value' => get_the_date(),
				);

				$project_data_to_export[] = array(
					'name'  => 'Project Budget',
					'value' => fre_price_format( get_post_meta($post->ID,'et_budget', true) )
				);
				$item_id = "project-{$post->ID}";


				$data_to_export[] = array(
					'group_id'    => 'bids',
					'group_label' => __( 'List Projects' ),
					'item_id'     => $item_id,
					'data'        => $project_data_to_export,
				);
			}
			wp_reset_postdata();
		}
		$done = $found_posts < $number;

	//}
	return array(
		'data' => $data_to_export,
		'done' => $done,
	);

}
function fre_personal_data_exporter( $email_address, $page = 1 ) {
	$export_items = array();
	$user = get_user_by( 'email', $email_address );
	if ( $user && $user->ID ) {

		$group_label = __( 'FreelanceEngine Data' );
		// Plugins can add as many items in the item data array as they want
		$data = array();


		$role = ae_user_role($user->ID);

		if( $role == FREELANCER ) {
			$metas = Fre_Tool_Data::get_instance()->get_fre_meta_field();


			global $wp_query, $ae_post_factory, $post;
			$post_object = $ae_post_factory->get( PROFILE );
			$profile_id = get_user_meta( $user->ID, 'user_profile_id', true );
			$profile = get_post($profile_id);
			$convert    = $post_object->convert( $profile );

			foreach ($metas as $key => $title) {
				$data[] = array(
					'name' => $title,
					'value' =>$convert->{$key},
				);
			}
			if ( ! empty( $convert->tax_input['country'] ) ) {
				$data[] = array(
					'name'  => __( 'Country:', ET_DOMAIN ),
					'value' => $convert->tax_input['country']['0']->name
				);
			}
			$data[] = array(
				'name'  => __( 'Profile ID', ET_DOMAIN ),
				'value' => $profile_id
			);
			$data[] = array(
				'name'  => __( 'URL', ET_DOMAIN ),
				'value' =>get_author_posts_url($user->ID)
			);
			$data[] = array(
				'name'  => __( 'Overview:', ET_DOMAIN ),
				'value' => $convert->post_content
			);
		} else if( $role == EMPLOYER ){
			$data[] = array(
				'name'  => __( 'Project Posted', ET_DOMAIN ),
				'value' => fre_count_user_posts_by_type( $user->ID, 'project', '"publish","complete","close","disputing","disputed", "archive" ', true )
			);
			$data[] = array(
				'name'  => __( 'Hired freelancer', ET_DOMAIN ),
				'value' =>fre_count_hire_freelancer( $user->ID )
			);
			$rating      = Fre_Review::employer_rating_score( $user->ID  );
			$data[] = array(
				'name'  => __( 'Rating Score', ET_DOMAIN ),
				'value' =>  $rating['rating_score'],
			);
		}

		$data[] = array(
			'name'  => __( 'Earned', ET_DOMAIN ),
			'value' => fre_count_total_user_earned( $user->ID ),
		);


		$user_available = get_user_meta($user->ID,'user_available', true);
		$value = ($user_available == 'on') ? 'Yes':'No';
		$data[] = array(
			'name'  => __( 'Available for hire', ET_DOMAIN ),
			'value' => $value,
		);
		// Add this group of items to the exporters data array.
		$item_id = "fre-info-{$user->ID}";
		$export_items[] = array(
			'group_id'    => 'fre_data',
			'group_label' => 'FreelanceEngine Data',
			'item_id'     => $item_id,
			'data'        => $data,
		);


	}
	// Returns an array of exported items for this pass, but also a boolean whether this exporter is finished.
	//If not it will be called again with $page increased by 1.
	return array(
		'data' => $export_items,
		'done' => true,
	);
}
?>