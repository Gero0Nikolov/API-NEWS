<?php
	class RA_NEWS {
		private $debug_ = false;
		private $server_name = NULL;
		private $db_name = NULL;
		private $db_user = NULL;
		private $db_pass = NULL;
		private $stories_table = NULL;

		/*
		* 	__construct function.
		*  	Purpose:
		*  	- Creates the RA_NEWS class.
		*  	- Enables $debug_ mode.
		*  	Arguments:
		*  	- $debug: true || false. By default it is false.
		*  	- $server_name: The name of the host server. Example: localhost
        * 	- $db_name: The name of the database that we are going to use. Example: dopamine_ra_news
        * 	- $db_user: The name of the database user. Example: root
        * 	- $db_pass: The password of the database. Example: pass_5869
		*  	- $stories_table: The table where we are going to store the stories. By default it is "ra_news_stories".
		 */
		function __construct( 
			$debug = false,
			$server_name,
			$db_name, 
			$db_user, 
			$db_pass,
			$stories_table = "ra_news_stories" ) { 
			$GLOBALS[ "debug_" ] = $debug;
			$GLOBALS[ "server_name" ] = $server_name;
			$GLOBALS[ "db_name" ] = $db_name;
			$GLOBALS[ "db_user" ] = $db_user;
			$GLOBALS[ "db_pass" ] = $db_pass;
			$GLOBALS[ "stories_table" ] = $stories_table;
		}

        function __desctruct() {} // Destructor
      
      	/*
      	* 	Connect to database function.
      	*  	Purpose:
      	*  	- Connects to the database and returns the connection bridge.
      	 */
      	function connect_to_database() {
      		// Set database variables
      		$server_name = $GLOBALS[ "server_name" ];
      		$db_user = $GLOBALS[ "db_user" ];
      		$db_pass = $GLOBALS[ "db_pass" ];
      		$db_name = $GLOBALS[ "db_name" ];

      		// Connect to the database
      		$connection_ = mysqli_connect( $server_name, $db_user, $db_pass, $db_name );
			if ( $connection_->connect_error ) { die("Fatal connection pronlem: " . $conn->connect_error); }

			return $connection_;
      	}
            
        /*
        *	Initialize Database function.
        * 	Purpose:
        * 	- Tests the connection to the database.
        * 	- Creates the needed tables in the database.
         */                     	
      	function initialize_db() {
      		// Connect to the database
      		$connection_ = $this->connect_to_database();

      		$stories_table = $GLOBALS[ "stories_table" ];

      		// Build the stories table
      		$sql_ = "SELECT id FROM $stories_table LIMIT 1";
      		$catch_ = $connection_->query( $sql_ );
      		if ( isset( $catch_->num_rows ) ) { /* The table exists! */ }
  			else { // Create the table!
	       		$sql_ = "
				CREATE TABLE $stories_table (
					id int NOT NULL AUTO_INCREMENT,
					story_title LONGTEXT,
					story_publish_date DATE,
					story_text LONGTEXT,
					PRIMARY KEY (id)
				)
	       		";
	       		if ( $connection_->query( $sql_ ) === FALSE ) {
					if ( $GLOBALS[ "debug_" ] == true ) { echo "<h4>Table: <i>$stories_table</i> wasn't created.<br>Reason: $connection_->error</h4>"; }
				}
	        }

	        // Close the connection
	        $connection_->close();
      	}

      	/*
      	* 	Sanitize string function.
      	*  	Purpose:
      	*  	- Sanitizes the user input and returns safe content for the database.
      	*  	Arguments:
      	*  	- $string: This is the string that we are going to sanitize.
      	 */
      	function sanitize_string( $string_ ) {
      		$string_ = str_replace( "'", "&#39", trim( htmlentities( $string_ ) ) );
      		return $string_;
      	}

      	/*
      	* 	Add story function
      	*  	Purpose:
      	*  	- Add new story to the database.
      	*  	Arguments:
      	*  	- $title_: The title of the story. Example: Story number 1
      	*  	- $content_: The content of the story. Example: Lorem ipsum dolor grand...
      	*  	- $date_: The publish date of the story. By default it is the current date.
      	 */
      	function add_story( $title_, $content_, $date_ = "" ) {
      		// Secure the user input
      		$title_ = $this->sanitize_string( $title_ );
      		$content_ = $this->sanitize_string( $content_ );
      		if ( empty( $date_ ) ) { $date_ = date( "Y-m-d" ); }
      		else { $date_ = $this->sanitize_string( $date_ ); }

      		// Connect to the database
      		$connection_ = $this->connect_to_database();

      		$stories_table = $GLOBALS[ "stories_table" ];

      		// Add the new user into the Authors table
      		$sql_ = "INSERT INTO $stories_table (story_title, story_publish_date, story_text) VALUES ('$title_', '$date_', '$content_')";
      		if ( $connection_->query( $sql_ ) === FALSE ) {
      			if ( $GLOBALS[ "debug_" ] == true ) { echo "<h4>Couldn't add a story to: <i>$stories_table</i>.<br>Reason: $connection_->error</h4>"; }
      		}

      		// Close the connection
      		$connection_->close();
      	}

      	/*
      	* 	Edit story function
      	*   Purpose:
      	*   - Edit story that already exists in the database.
      	*   Arguments:
      	*   - $id: The ID of the story in the database.
      	*   - $title_: The new title of the story. If it is empty the title won't be updated.
      	*   - $content_: The new content of the story. If it is empty the content won't be updated.
      	*   - $date_: The new date of the story. If it is empty the date won't be updated.
      	 */
      	function edit_story( $id, $title_ = "", $content_ = "", $date_ = "" ) {
      		// Connect to the database
      		$connection_ = $this->connect_to_database();

      		$stories_table = $GLOBALS[ "stories_table" ];
      		$id = $this->sanitize_string( $id );

      		// Update story title
      		if ( !empty( $title_ ) ) {
      			$title_ = $this->sanitize_string( $title_ );
      			$sql_ = "UPDATE $stories_table SET story_title='$title_' WHERE id=$id";
      			if ( $connection_->query( $sql_ ) === FALSE ) { $title_update = $connection_->error; }
      		}

      		// Update story text
      		if ( !empty( $content_ ) ) {
      			$content_ = $this->sanitize_string( $content_ );
      			$sql_ = "UPDATE $stories_table SET story_text='$content_' WHERE id=$id";
      			$connection_->query( $sql_ );
      			if ( $connection_->query( $sql_ ) === FALSE ) { $content_update = $connection_->error; }
      		}

      		// Update story date
      		if ( !empty( $date_ ) ) {
      			$date_ = $this->sanitize_string( $date_ );
      			$sql_ = "UPDATE $stories_table SET story_publish_date='$date_' WHERE id=$id";
      			$connection_->query( $sql_ );
      			if ( $connection_->query( $sql_ ) === FALSE ) { $date_update = $connection_->error; }
      		}

      		if ( $GLOBALS[ "debug_" ] == true ) {
      			if ( !empty( $title_update ) ) { echo "<h4>Story title wasn't updated. Reason: $title_update</h4>"; }
      			if ( !empty( $content_update ) ) { echo "<h4>Story text wasn't updated. Reason: $content_update</h4>"; }
      			if ( !empty( $date_update ) ) { echo "<h4>Story date wasn't updated. Reason: $date_update</h4>"; }
      		}

      		// Close the connection
      		$connection_->close();
      	}

      	/*
      	* 	Delete story function.
      	* 	Purpose:
      	* 	- Deletes a story that already exists in the database.
      	* 	Arguments:
      	* 	- $id: The ID of the story in the database.
      	 */
      	function delete_story( $id ) {
      		// Connect to the database
      		$connection_ = $this->connect_to_database();

      		$stories_table = $GLOBALS[ "stories_table" ];
      		$id = $this->sanitize_string( $id );

      		$sql_ = "DELETE FROM $stories_table WHERE id=$id";
      		if ( $connection_->query( $sql_ ) === FALSE ) {
      			if ( $GLOBALS[ "debug_" ] == true ) {
      				echo "<h4>Story wasn't deleted. Reason: $connection_->error</h4>";
      			}
      		}

      		// Close the connection
      		$connection_->close();
      	}

      	/*
      	* 	Get story function.
      	* 	Purpose:
      	* 	- Get existing story from the database.
      	* 	Arguments:
      	* 	- $id: The ID of the story in the database.
      	 */
      	function get_story( $id ) {
      		// Connect to the database
      		$connection_ = $this->connect_to_database();

      		$stories_table = $GLOBALS[ "stories_table" ];
      		$id = $this->sanitize_string( $id );

      		$story_obj = array();

      		$sql_ = "SELECT story_title, story_publish_date, story_text FROM $stories_table WHERE id=$id";
      		$catch_ = $connection_->query( $sql_ );
      		if ( isset( $catch_->num_rows ) > 0 ) {
      			while ( $row_ = $catch_->fetch_assoc() ) {
      				$story_obj[ "story_title" ] = $row_[ "story_title" ];
      				$story_obj[ "story_date" ] = $row_[ "story_publish_date" ];
      				$story_obj[ "story_content" ] = nl2br( $row_[ "story_text" ] );

      				$story_obj = (object) $story_obj;
      				$story_obj = json_encode( $story_obj );
      			}
      		} else {
      			if ( $GLOBALS[ "debug_" ] == true ) {
      				echo "<h4>Story wasn't found.</h4>";
      			}
      		}

      		// Close the connection
      		$connection_->close();

      		// Return the story object in JSON
      		return $story_obj;
      	}
	}
?>