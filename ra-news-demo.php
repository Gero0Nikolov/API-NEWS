<?php 
	/*
	*	Demo on: Localhost
	*	Live demo can be found at: http://dopamine.blogy.co
	 */

	include "ra-news-class.php";

	// Set the database variables
	$server_name = "localhost";
	$db_name = "dopamine_ra_news";
	$db_user = "root";
	$db_pass = "";

	// Call the class
	$ra_news_ = new RA_NEWS( true, $server_name, $db_name, $db_user, $db_pass );

	// Initialize the database
	$ra_news_->initialize_db();

	// Add new story
	$title = "My first post with RA_NEWS API";
	$content = "Lorem Ipsum dolor grandum spark...";
	$ra_news_->add_story( $title, $content );

	// Add story with code in the content
	$title = "<script>My first post with RA_NEWS API</script>";
	$content = "<?php echo 'Lorem Ipsum dolor grandum spark...'; die(); ?>";
	$ra_news_->add_story( $title, $content );

	// Update story
	$id = 2;
	$title = "Story 2";
	$content = "Was UPDATED!";
	$ra_news_->edit_story( $id, $title, $content );

	// Delete story
	$id = 2;
	$ra_news_->delete_story( $id );

	// Get story
	$id = 1;
	$story_json_obj = $ra_news_->get_story( $id );
	var_dump( $story_json_obj );
?>