<?php
switch($_SERVER['REQUEST_METHOD']) {
	case 'GET':
	get();
	break;

	case 'POST':
	post();
	break;

	case 'DELETE':
	delete();
	break;
}

function get() {
	if (!is_user_logged_in()) {
		exit();
	}
	// The Query
	$the_query = new WP_Query( 'post_type=task' );

	// The Loop
	if ( $the_query->have_posts() ) {
	
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			
			$postId = get_the_ID();
			$post = get_post($postId);
			$cutom_fields = get_post_custom($postId);
			
			$newPost['id'] = $postId;
			$newPost['taskName'] = $cutom_fields['taskName'][0];
			$newPost['startDate'] = $cutom_fields['startDate'][0];
			print_r(json_encode($newPost));
		}
	} else {
		// no posts found
		exit;
	}
	// Restore original Post Data 
	wp_reset_postdata();
};

function post() {
	if (!is_user_logged_in()) {
		exit;
	}
	$post = $_REQUEST['task'];
	$metaData = $post['post_meta'];
	if(empty($post['post_meta'])) {
		exit("post_meta array is empty");
	};
	//$current_user = wp_get_current_user();
	
	$post_id = wp_insert_post( $post, $wp_error );		
	print_r("created post id is " . $post_id);
	if($post_id !== 0) {
		
		foreach ($metaData as $meta_key => $meta_value)
		add_post_meta($post_id, $meta_key, $meta_value, true);
	}
};

function delete() {
	if (!is_user_logged_in()) {
		exit;
	}
	$postId = $_REQUEST['task_id'];
	print_r($_REQUEST);
	if(empty($postId)) {
		exit("can't delete without post_id");
	}

	wp_delete_post( $postId, true );
	echo("Deleted");

};

?>
