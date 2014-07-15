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
	// The Query
  $the_query = new WP_Query( 'post_type=task' );

  // The Loop
  if ( $the_query->have_posts() ) {
    
    while ( $the_query->have_posts() ) {
	    $the_query->the_post();
				
				$postId = get_the_ID();
				$post = get_post($postId);
				$cutom_fields = get_post_custom($postId);
				
				$newPost;
				foreach ($post as $key => $value) {
					$newPost[$key] = $value;
				};
				$newPost['cutom_fields'] = $cutom_fields;
				print_r($newPost);
    }
  } else {
    // no posts found
  	echo("no posts found");
  }
  // Restore original Post Data 
  wp_reset_postdata();
};

function post() {
	$post = $_REQUEST;
	$metaData = $post['post_meta'];
	if(empty($post['post_meta'])) {
		exit("post_meta array is empty");
	};
	
	$post_id = wp_insert_post( $post, $wp_error );		
	print_r("created post id is " . $post_id);
	if($post_id !== 0) {
		
		foreach ($metaData as $meta_key => $meta_value)
		add_post_meta($post_id, $meta_key, $meta_value, true);
	}
};

function delete() {
	$postId = $_REQUEST['post_id'];
	print_r($_REQUEST);
	if(empty($postId)) {
		exit("can't delete without post_id");
	}

	wp_delete_post( $postId, true );
	echo("Deleted");

};

?>
