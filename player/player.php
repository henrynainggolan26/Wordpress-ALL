<?php
/*
Plugin Name: Players
Description: Players Plugin
Version: 1.0
Author: Henry
*/
include 'widget_top_score.php';
include 'widget_assist.php';
include 'widget_search.php';

add_action( 'admin_menu', 'my_admin_menu' );
function my_admin_menu() {
	add_menu_page( 'Player', 'Player', 'manage_options', 'admin_player/admin_player_page.php', 'player_admin_page', 'dashicons-tickets', 6 );
}
function player_session(){
		include_once 'start_session.php';
}
add_action('init', 'player_session');
function show_player(){
	global $blog_id;
	global $wpdb;
	$result = $wpdb->get_results("SELECT * FROM players WHERE blog_id = $blog_id");
	echo "<h2><center>Show Player</center></h2>"; 
	echo "<table border='1' cellpadding='3' align='center'>"; 
	echo "<tr><th>ID</th> <th>Name</th> <th>Address</th> <th>Position</th> <th>Birthday</th> <th>Goal</th>
		<th>Assist</th> <th>In/Out</th> <th>Status</th> <th>Action</th></tr>";
	foreach ($result as $key) {
		echo '<tr><td>'.$key->id.'</td>';
		echo '<td>'.$key->name.'</td>';
		echo '<td>'.$key->address.'</td>';
		echo '<td>'.$key->position.'</td>';
		echo '<td>'.$key->birthday.'</td>';
		echo '<td>'.$key->goal.'</td>';
		echo '<td>'.$key->assist.'</td>';
		echo '<td>'.$key->in_out.'</td>';
		echo '<td>' .$key->status.'</td>';
		echo '<td><form action="" method="post"> <input type=hidden name="id_player" value="'.$key->id.'">
		<input type="submit" name="edit" value="Edit"> <input type="submit" name="delete" value="Delete"></form> </td></tr>';
	}
}
function delete_player(){
	global $blog_id;
	global $wpdb;
	if(isset($_POST['delete'])){
		$id = sanitize_text_field($_POST["id_player"]);
		$wpdb->delete('players', array('id'=>$id, 'blog_id' => $blog_id));
	}
}
function edit_player(){
	global $wpdb;
	if(isset($_POST['edit'])){
		$id = sanitize_text_field($_POST["id_player"]);
		$player = $wpdb->get_row("SELECT * FROM players WHERE id = '".$id."'");
		$name = $player->name;
		$address = $player->address;
		$position = $player->position;
		$birthday = $player->birthday;
		$goal = $player->goal;
		$assist = $player->assist;
		$in_out = $player->in_out;
		$status = $player->status;
		$arrayPosition = array('GK'=>'GK','CB'=>'CB','LB'=>'LB','RB'=>'RB','DMF'=>'DMF','AMF'=>'AMF','LMF'=>'LMF','RMF'=>'RMF','CF'=>'CF');
		$arrayStatus = array('Pending'=>'Pending', 'Accepted'=>'Accepted','Rejected'=>'Rejected'); 
		$arrayInOut = array('in'=>'In', 'out'=>'Out'); 
		echo '<h2>Edit Players</h2>';
		echo '<form id="edit_player" action="#" method="post">';
		echo 'Name (required) </br>';
		echo '<input type="text" name="player-name" pattern="[a-zA-Z0-9 ]+" value="'.$name.'"> <br /><br />';
		echo 'Address (required) </br>';
		echo '<textarea type="text" name="player-address" style="min-width: 68%" pattern="[a-zA-Z0-9 ]+" rows="4" required/>'.$address.'</textarea><br /><br />';
		echo 'Select Position (required) </br>';
		echo' <select id="player-position" name="player-position">';  
		foreach($arrayPosition as $valPosition=>$wordPosition){ 
			if($position == $wordPosition){
				echo'<option value="'.$valPosition.'" selected="selected">'.$wordPosition.'</option>'; 	
			}
    		echo'<option value="'.$valPosition.'">'.$wordPosition.'</option>'; 
		} 
		echo'</select>';
		echo '<br /><br />';
		echo 'Birthday (required) </br>'; 
		echo '<input type="date" id="player-birthday" name="player-birthday" value="'.$birthday.'"><br /><br />';
		echo 'Goal (required) </br>'; 
		echo '<input type="text" name="player-goal" pattern="[0-9]+" value="'.$goal.'"><br /><br />';
		echo 'Assist (required) </br>'; 
		echo '<input type="text" name="player-assist" pattern="[0-9]+" value="'.$assist.'"><br /><br />';
		echo 'In /Out (required) </br>'; 
		echo' <select id="player-inout" name="player-inout">';  
		foreach($arrayInOut as $valInOut=>$wordInOut){ 
			if($in_out == $valInOut){
				echo'<option value="'.$valInOut.'" selected="selected">'.$wordInOut.'</option>'; 	
			}
    		echo'<option value="'.$valInOut.'">'.$wordInOut.'</option>'; 
		} 
		echo'</select>';
		echo '<br /><br />';
		echo 'Select Status (required) </br>'; 
		echo' <select id="player-status" name="player-status">';  
		foreach($arrayStatus as $valStatus=>$wordStatus){ 
			if($status == $wordStatus){
				echo'<option value="'.$valStatus.'" selected="selected">'.$wordStatus.'</option>'; 	
			}
    		echo'<option value="'.$valStatus.'">'.$wordStatus.'</option>'; 
		} 
		echo'</select>';
		echo '<br /><br />';
		echo '<input type="submit" name="player-save" value="Save">';
		echo '<input type=hidden name="id_player_hidden" value="'.$id.'">';
		echo '</form>';
		}
	if(isset($_POST['player-save'])){ 
		$id = sanitize_text_field($_POST["id_player_hidden"]);
		$name = sanitize_text_field($_POST["player-name"]);
		$address = sanitize_text_field($_POST["player-address"]);
		$position = sanitize_text_field($_POST["player-position"]);
		$birthday = sanitize_text_field($_POST["player-birthday"]);
		$goal = sanitize_text_field($_POST["player-goal"]);
		$assist = sanitize_text_field($_POST["player-assist"]);
		$inout = sanitize_text_field($_POST["player-inout"]);
		$status = sanitize_text_field($_POST["player-status"]);
		$wpdb->update(
			'players',
			array(
				'name' => $name,
				'address' => $address,
				'position' => $position,
				'birthday' => $birthday,
				'goal' => $goal,
				'assist' => $assist,
				'in_out' => $inout,
				'status' => $status,
				),
			array('id' => $id),
			array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%d',
				'%s',
				'%s',
				),
			array('%d')
			);
	}
}
function form_input_player() {
	echo '<form id="featured_upload" action="#" method="post" enctype="multipart/form-data">';
	echo '<p>';
	echo 'Name (required) <br/>';
	echo '<input type="text" name="player-name" value="' . ( isset( $_POST["player-name"] ) ? esc_attr( $_POST["player-name"] ) : '' ) . '" size="40" required/>';
	echo '</p>';
	echo '<p>';
	echo 'Email (required) <br/>';
	echo '<input type="email" name="player-email" value="' . ( isset( $_POST["player-email"] ) ? esc_attr( $_POST["player-email"] ) : '' ) . '" size="40" required/>';
	echo '</p>';
	echo '<p>';
	echo 'Address (required) <br/>';
	echo '<textarea type="text" name="player-address" style="min-width: 68%" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["player-address"] ) ? esc_attr( $_POST["player-address"] ) : '' ) . '" rows="4" required/></textarea>' ;
	echo '</p>';
	echo 'Select Position : <select id="player-position" name="player-position" value="'.$position.'">';                      
	echo '<option value="GK">GK</option>';
	echo '<option value="CB">CB</option>';
	echo '<option value="LB">LB</option>';
	echo '<option value="RB">RB</option>';
	echo '<option value="DMF">DMF</option>';
	echo '<option value="AMF">AMF</option>';
	echo '<option value="LMF">LMF</option>';
	echo '<option value="RMF">RMF</option>';
	echo '<option value="CF">CF</option>';
	echo '</select><br /><br />';
	echo '<p>';
	echo 'Birthday (required) <br/>';
	echo '<input type="date" id="player-birthday" name="player-birthday" required/>';
	echo '</p>';
	echo '<p>';
	echo '<input type="hidden" name="post_id" id="post_id" value="55" />';
	echo 'Upload image: <input type="file" name="my_image_upload" id="my_image_upload" multiple="false" required/>';
	wp_nonce_field( 'my_image_upload', 'my_image_upload_nonce' );	
	echo '</p>';
	echo '<p><input type="submit" name="player-submitted" value="Save"></p>';
	echo '</form>';
}
function randomPassword() {
	$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); 
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 8; $i++) {
    	$n = rand(0, $alphaLength);
    	$pass[] = $alphabet[$n];
    }
    return implode($pass); 
}
function insert_player(){
	global $blog_id;
	global $wpdb;
	if(isset($_POST['player-submitted'])){
		$name = sanitize_text_field($_POST["player-name"]);
		$address = sanitize_text_field($_POST["player-address"]);
		$email = sanitize_email($_POST["player-email"]);
		$position = sanitize_text_field($_POST["player-position"]);
		$birthday = sanitize_text_field($_POST["player-birthday"]);
		$randPass = randomPassword();
		if(isset($_POST['my_image_upload_nonce'], $_POST['post_id']) && wp_verify_nonce( $_POST['my_image_upload_nonce'], 'my_image_upload')){
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );
		$attachment_id = media_handle_upload( 'my_image_upload', $_POST['post_id'] );
		if ( is_wp_error( $attachment_id ) ) {
			echo "There was an error uploading the image."; } 
		else {
			echo "Data Success Save! Please cek your email"; }
		}
		$wpdb->insert(
			'players',
			array(
				'name' => $name,
				'username' => $email,
				'password' => $randPass,
				'address' => $address,
				'email' => $email,
				'position' => $position,
				'birthday' => $birthday,
				'blog_id' => $blog_id,
				'goal' => 0,
				'assist' => 0,
				'in_out' => 'out',
				'image' => $attachment_id,
				'status' => 'Pending',
				),
			array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%s',
				'%s',
				)
			); ;
		
	}
}
add_shortcode( 'sitepoint_player_form', 'player_shortcode' );
function player_shortcode() {
	ob_start();
	insert_player();
	form_input_player();
	return ob_get_clean();
}
function player_admin_page(){
	edit_player();
	delete_player();
	show_player();
}
add_action( 'wp_enqueue_scripts', 'add_scripts' );
function add_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-autocomplete' );
	wp_register_style( 'jquery-ui-styles','http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css' );
	wp_enqueue_style( 'jquery-ui-styles' );
	wp_register_script( 'my_autocomplete', plugin_dir_url( __FILE__ ) . '/js/my-autocomplete.js', array( 'jquery', 'jquery-ui-autocomplete' ), '1.0', false );
	wp_localize_script( 'my_autocomplete', 'my_autocomplete', array( 'urlrrr' => admin_url( 'admin-ajax.php' ) ) );
	wp_enqueue_script( 'my_autocomplete' );
	wp_enqueue_style( 'prefix-style', plugins_url('style.css', __FILE__) );
}
add_action( 'wp_ajax_autocomplete_post', 'my_action_autocomplete_post_callback' );
add_action( 'wp_ajax_nopriv_autocomplete_post', 'my_action_autocomplete_post_callback' );
function my_action_autocomplete_post_callback() {
	$keyword = isset($_POST['search']) ? $_POST['search'] : null; 
	$args = array('s' => $keyword ); 
	$arrayAvailable = [];
	$the_query = new WP_Query($args);
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$title = get_the_title();
			$obj = new StdClass();
			$obj->label = $title;
			$obj->value = $title;
			$arrayAvailable[] = $obj;	
		}
		wp_reset_query();
	}
	wp_send_json($arrayAvailable);
}
add_shortcode('shortcode_display_autocomplete', 'display_autocomplete');
function display_autocomplete(){
	if($_GET['search_name'] == ""){
	}
	else{
		$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ): 1;
		$keyword = isset($_GET['search_name']) ? $_GET['search_name'] : null;
		$args = array('s' => $keyword ,
			'posts_per_page' => 5,
			'paged'         => $paged,
			);
		$the_query = new WP_Query($args);
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();?>
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"> <?php the_title(); ?></a> 
				<?php echo "</br>";
			}
			wp_reset_query();
		}
		else{ 
			echo "Sorry, no posts matched your criteria.";
		}
		$big = 999999999;

		echo paginate_links( array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $the_query->max_num_pages,
			) );
	}
}
/*function custom_excerpt_length( $length ) {
    return 15;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );*/

add_filter('widget_text', 'do_shortcode');
function show_player_all(){
	global $blog_id;
	global $wpdb;
	$no = 1;
	$result = $wpdb->get_results("SELECT * FROM players WHERE blog_id = $blog_id"); 
	echo "<table border='2' width='100%' height='70%' cellpadding='5' align='center'>"; 
	echo "<tr><th> No </th> <th> Name </th>  <th> Address </th> <th> Position </th><th> Age </th><th> Goal </th><th> Assist </th></tr>";
	foreach ($result as $key) {
		echo '<tr><td>'.$no.'</td>';
		echo '<td>'.$key->name.'</a></td>';
		echo '<td>'.$key->address.'</td>';
		echo '<td>'.$key->position.'</td>';
		echo '<td>'.floor(((strtotime(get_the_date('Y-m-d'))-strtotime($key->birthday))/86400)/365).'</td>';
		echo '<td>'.$key->goal.'</td>';
		echo '<td>'.$key->assist.'</td></tr>';
		$no+=1;	
	}
	echo "</table>"; 
}
add_shortcode( 'shortcode_show_player_all', 'show_player_all' );
function show_player_in(){
	global $blog_id;
	global $wpdb;
	$no = 1; 
	$result = $wpdb->get_results("SELECT * FROM players WHERE blog_id = $blog_id AND in_out = 'in'"); 
	echo "<table border='1' cellpadding='5' width='100%' height='50%' align='center'>"; 
	echo "<tr><th> No </th> <th> Name </th>  <th> Address </th> <th> Position </th><th> Age </th><th> Goal </th><th>Assist</th></tr>";
	foreach ($result as $key) { 
		echo '<tr><td>'.$no.'</td>';
		echo '<td>'.$key->name.'</a></td>';
		echo '<td>'.$key->address.'</td>';
		echo '<td>'.$key->position.'</td>';
		echo '<td>'.floor(((strtotime(get_the_date('Y-m-d'))-strtotime($key->birthday))/86400)/365).'</td>';
		echo '<td>'.$key->goal.'</td>';
		echo '<td>'.$key->assist.'</td></tr>';
		$no+=1;
	}
	echo "</table>"; 
}
add_shortcode( 'shortcode_show_player_in', 'show_player_in' );

function cek_login($username, $password){
	global $wpdb;
	$result = $wpdb->get_results("SELECT username,password FROM players WHERE username = '".$username."' AND password = '".$password."'");
	return $result;
}
function get_id_player($username, $password){
	global $wpdb;
	$temp_id = $wpdb->get_var("SELECT id FROM players WHERE username = '".$username."' AND password = '".$password."'");
	$result = $temp_id;
	return $result;	
}
function login_player(){
	player_session();
	if(isset($_POST['login'])){
		$username = sanitize_text_field($_POST['username']);
		$password = sanitize_text_field($_POST['password']);
		$query = cek_login($username,$password);
		$id = get_id_player($username, $password); 
		if (!$query) {
			echo "Error: Data not found..";
			$_SESSION['userLoggedIn'] = false;
		}
		else{
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $password;
			$_SESSION['id'] = $id;			
			$_SESSION['userLoggedIn'] = true;
			wp_redirect('/wordpress-all/barcelona-fc/home/');
			exit();
		}
	}
	echo '<form action="" method="post">';
	echo '<table>';
	echo '<tr>';
	echo '<td valign="top"><label>Username</label></td>';
	echo '<td valign="top">:</td>';
	echo '<td><input placeholder="Username" type="text" name="username" required></td>';
	echo '</tr>';
	echo '<tr>'; 
	echo '<td valign="top"><label>Password</label></td>';
	echo '<td valign="top">:</td>';
	echo '<td><input placeholder="Password" type="password" name="password" required></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td colspan="3" align="right"><button type="submit" name="login" value="1">Login</button></td>';
	echo '</tr>';
	echo '</table>';
	echo '</form>';
	return $items;
}
add_shortcode( 'shortcode_login_player', 'login_player' );
function logout(){
	wp_redirect('/wordpress-all/barcelona-fc/home/');
	session_destroy();
	exit();
}
add_shortcode('shortcode_logout', 'logout');
function edit_profile_player(){
	global $wpdb;
	if($_SESSION['username'] == ""){
		wp_redirect('/wordpress-all/barcelona-fc/login/');
		exit();
	}
	$idplayer = get_id_player($_SESSION['username'], $_SESSION['password']);
	$player = $wpdb->get_row("SELECT * FROM players WHERE id = '".$idplayer."'"); 
	$name = $player->name;
	$email = $player->email;
	$username = $player->username;
	$birthday = $player->birthday;
	$address = $player->address; 
	$image = $player->image; 
    $temp_image = wp_get_attachment_image_src( $image, array('200', '200') );
    if(isset($_POST['player-save'])){ 
		$id = get_id_player($_SESSION['username'], $_SESSION['password']);
		$name = sanitize_text_field($_POST["player-name"]);
		$email = sanitize_text_field($_POST["player-email"]);
		$username = sanitize_text_field($_POST["player-username"]);
		$birthday = sanitize_text_field($_POST["player-birthday"]);
		$address = sanitize_text_field($_POST["player-address"]);
		$filename = sanitize_file_name($_POST['my_image_upload']); 
		if(isset($_POST['my_image_upload_nonce'], $_POST['post_id']) && wp_verify_nonce( $_POST['my_image_upload_nonce'], 'my_image_upload')){
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );
		$attachment_id = media_handle_upload( 'my_image_upload', $_POST['post_id'] );
		if ( is_wp_error( $attachment_id ) ) {
			echo "There was an error uploading the image.";
			$wpdb->update(
			'players',
			array(
				'name' => $name,
				'email' => $email,
				'username' => $username,
				'birthday' => $birthday,
				'address' => $address,
				),
			array('id' => $id),
			array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				),
			array('%d')
			); } 
		else {
			$wpdb->update(
			'players',
			array(
				'name' => $name,
				'email' => $email,
				'username' => $username,
				'birthday' => $birthday,
				'address' => $address,
				'image' => $attachment_id,
				),
			array('id' => $id),
			array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				),
			array('%d')
			);
		}
	}
	} 
	echo '<form id="featured_upload" action="#" method="post" enctype="multipart/form-data">';
	echo '<p>';
	echo '<img src="'.$temp_image[0].'" class="pictures">';
	echo '</p>';
	echo '<p>';
	echo 'Name (required) <br/>';
	echo '<input type="text" name="player-name" value="'.$name.'" size="40" required/>';
	echo '</p>';
	echo '<p>';
	echo 'Email (required) <br/>';
	echo '<input type="email" name="player-email" value="'.$email.'" size="40" required/>';
	echo '</p>';
	echo '<p>';
	echo 'Username (required) <br/>';
	echo '<input type="text" name="player-username" value="'.$username.'" size="40" required/>';
	echo '</p>';
	echo '<p>';
	echo 'Birthday (required) <br/>';
	echo '<input type="date" id="player-birthday" name="player-birthday" value="'.$birthday.'"/>';
	echo '</p>';
	echo '<p>';
	echo 'Address (required) <br/>';
	echo '<textarea type="text" name="player-address" style="min-width: 68%" pattern="[a-zA-Z0-9 ]+" value="" rows="4" required/>'.$address.'</textarea>' ;
	echo '</p>';
	echo '<p>';
	echo '<input type="hidden" name="post_id" id="post_id" value="55" />';
	echo 'Upload image: <input type="file" name="my_image_upload" id="my_image_upload" multiple="false" />'; wp_nonce_field( 'my_image_upload', 'my_image_upload_nonce' );
	echo '</p>';	
	echo '<p><input type="submit" name="player-save" value="Save"></p>';
	echo '</form>';
}
add_shortcode('shortcode_edit_profile_player', 'edit_profile_player');
function change_password(){
	global $wpdb;
	if($_SESSION['username'] == ""){
		wp_redirect('/wordpress-all/barcelona-fc/login/');
		exit();
	}
	$id = $_SESSION['id'];
	
	echo '<form id="change_pass" action="#" method="post" enctype="multipart/form-data">';
	echo '<p>';
	echo 'Old Password (required) <br/>';
	echo '<input type="password" name="player-oldPass" value="" size="40" required/>';
	echo '</p>';
	echo '<p>';
	echo 'New Password (required) <br/>';
	echo '<input type="password" name="player-newPass" value="" size="40" required/>';
	echo '</p>';
	echo '<p>';
	echo 'Confirm New Password (required) <br/>';
	echo '<input type="password" name="player-confirmNewPass" value="" size="40" required/>';
	echo '</p>';
	echo '<p><input type="submit" name="player-save" value="Save"></p>';
	echo '</form>';
	if(isset($_POST['player-save'])){
		$getPass = $wpdb->get_var("SELECT password FROM players WHERE id = '".$id."'"); 
		$oldPass = sanitize_text_field($_POST['player-oldPass']);
		$newPass = sanitize_text_field($_POST['player-newPass']); 
		$confNewPass = sanitize_text_field($_POST['player-confirmNewPass']);
		if($getPass != $oldPass){
			echo "Correct your old password, try again!!!";
		}
		else { 
			if($newPass == $confNewPass){
				$wpdb->update(
				'players',
				array(
					'password' => $newPass
					),
				array('id' => $id),
				array(
					'%s'
					),
				array('%d')	
				);
			}
			else {
				echo "Correct your new password and confirm new password";
			}
			
		}
		
	}
}
add_shortcode('shortcode_change_password','change_password');
function status_player(){
	global $wpdb;
	$id = $_SESSION['id'];
	$status = $wpdb->get_var("SELECT status FROM players WHERE id = '".$id."'");
	if($status == "Rejected"){
		echo "Sorry for the moment you can not join us. Thank you for your participation";
	}
	else if($status == "Accepted"){
		echo "Congratulations you have successfully joined";
	}
	else if($status == "Accepted"){
		echo "Please wait. Your status is '".$status."'";
	}
}
add_shortcode('shortcode_status_player','status_player');