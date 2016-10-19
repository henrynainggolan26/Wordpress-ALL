<?php
class Widget_Top_Score extends WP_Widget
{
	public function __construct(){
		$widget_ops = array( 
			'classname' => 'Widget_Top_Score',
			'description' => 'Widget Top Score',
		);
		parent::__construct( 'Widget_Top_Score', 'Widget Top Score', $widget_ops );
	}
	public function widget($args, $instance){ 
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		global $blog_id; 
		global $wpdb;
		$result = $wpdb->get_results( "SELECT * FROM players WHERE blog_id = $blog_id ORDER BY goal DESC LIMIT 1" );
		if(count($result)>0){
			echo "<table border='2' width='200px' height='70px' cellpadding='5' align='center'>"; 
			echo "<tr><th> Name </th><th> Position </th><th> Age </th> <th> Goal </th></tr>";
			foreach ( $result as $player ) 
			{
				echo '<td><a href="http://localhost/wordpress-all/barcelona-fc/top-score/">'.$player->name.'</a></td>';
				echo '<td>'.$player->position.'</td>';
				echo '<td>'.floor(((strtotime(get_the_date('Y-m-d'))-strtotime($player->birthday))/86400)/365).'</td>';
				echo '<td>'.$player->goal.'</td>';
			}
			echo'</table>';
			echo '<br />';
		}
		else{
			echo "Data empty";
		}
		echo $args['after_widget'];
	}
	public function form($instance)
	{
		if( $instance) {
			$title = esc_attr($instance['title']);
		} else {
			$title = '';
		}
		echo '<p>';
		?>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e(esc_attr('Title', 'Widget_Top_Score')); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		<?php
		echo '</p>';
	}
	public function update($new_instance, $old_instance){
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance; 
	}
}
function register_top_score_widget() {
	register_widget( 'Widget_Top_Score' );
}
add_action( 'widgets_init', 'register_top_score_widget' );