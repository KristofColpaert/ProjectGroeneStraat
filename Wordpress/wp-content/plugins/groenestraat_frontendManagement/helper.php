<?php

	/*
		Helper methods
	*/

	function insert_featured_image($file_handler,$post_id,$setthumb='false') 
	{
	    if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();
	 
	    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	    require_once(ABSPATH . "wp-admin" . '/includes/media.php');
	 
	    $attach_id = media_handle_upload( $file_handler, $post_id );
	 
	    if ($setthumb) update_post_meta($post_id,'_thumbnail_id',$attach_id);
	    return $attach_id;
	}
?>