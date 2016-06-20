# REST-API-NEWS
Is a class that can simplify your work to create your custom blog page or a news website.
<h4>How it works?</h4>
<ol>
  <li>Include the class in your PHP script. Example: <i>include "ra-news-class.php";</i></li>
  <li>Make sure that you have properly created a database or atleast you have existing one.
    <ul>After that you have to set the database variables in the following way:
      <li>$server_name = $_YOUR_SERVER_NAME;</li>
      <li>$db_name = $_YOUR_DATABASE_NAME;</li>
      <li>$db_user = $_THE_USER_OF_YOUR_DATABASE;</li>
      <li>$db_pass = $_THE_PASSWORD_OF_YOUR_DATABASE;</li>
      <li>$stories_table: The table where we are going to store the stories. By default it is "ra_news_stories".</li>
    </ul>
  </li>
  <li>Once you've set the Database & the DB variables you are ready to call the RA_NEWS class. Example: <i>$news_demo = new RA_NEWS( true, $server_name, $db_name, $db_user, $db_pass );</i>
    <ul>The class takes 6 arguments:
      <li>$debug - By default it is set to false but if it's true the class will return the errors if any occurs.</li>
      <li>$server_name - The name of the HOST SERVER.</li>
      <li>$db_name - The name of the Database.</li>
      <li>$db_user - The Database user.</li>
      <li>$db_pass - The password of the Database user.</li>
      <li>$stories_table - The table which to be created / used for the stories.
        <ul>Structure of the table stories:
          	<div>id int NOT NULL AUTO_INCREMENT,</div>
		<div>story_title LONGTEXT,</div>
		<div>story_publish_date DATE,</div>
		<div>story_text LONGTEXT,</div>
		<div>PRIMARY KEY (id)</div>
        </ul>
      </li>
    </ul>
  </li>
  <li>Now you are ready to play around with the class methods.
    <ul>The methods are:
      <li>initialize_db() - Is used to create the table for the news in the database if we already don't have it.</li>
      <li>add_story( $title_, $content_, $date_ = "" ) - Is used to create a new story in the News table. A specific $title_, $content_ & $date_ can be provided as arguments.</li>
      <li>edit_story( $id, $title_ = "", $content_ = "", $date_ = "" ) - Is used to edit a story that already exists in the DB. The ID selector of the specific story can be provided by the $id var and the new title, content & date can be provided by $title_, $content_ & $date_. Note: If they are empty the specific field won't be updated in the DB.</li>
      <li>delete_story( $id ) - Is used to delete a story from the DB. The ID selector of the specific story can be provided by the $id var.</li>
      <li>get_story( $id ) - Is used to return story from the DB. The ID selector of the specific story can be provided by the $id var. Ther returned result is in JSON object.</li>
    </ul>
  </li>
</ol>

<h4>Live demo can be found <a href='http://dopamine.blogy.co' target='_blank'>here</a>.</h4>
