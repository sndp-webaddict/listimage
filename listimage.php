<?php
/*
 * Plugin Name: List Image by Sndp
 * Description: You can add your list item with image with help of this plugin.
 * Version: 1.0.0
 * Author: Sndp Rathore
 * Author URI: http://webaddict.in
*/

//register widget
function sndp_register_widget() {
	register_widget( 'listimage_widget' );
}
add_action( 'widgets_init', 'sndp_register_widget' );

//Create Class
class listimage_widget extends WP_Widget {

	//construct function
	function __construct() {
		parent::__construct(
			'listimage_widget',
			__('List Item with Image', 'listimage_widget_domain'),
			array('description' => __('List Item Image', 'listimage_widget_domain'), )
		);
	}

	public function widget($args, $instance) {

		$title = apply_filters('widget_title', $instance['title']);

		echo $args['before_widget'];

		//if title is present
		if(!empty($title))
		echo $args['before_title'] . $title . $args['after_title'];

		if( have_rows('image_lists', 'widget_' . $args['widget_id']) ):
		echo '<ul>';
		while ( have_rows('image_lists', 'widget_' . $args['widget_id']) ) : the_row();
		echo '<li class="image_list">';
		$image = get_sub_field( 'image', 'widget_' . $args['widget_id'] );
		$list = get_sub_field( 'list', 'widget_' . $args['widget_id'] );
		if( $image ) {
		echo '<img src="' . $image . '" />';
		} 
		if( $list ) {
		echo '<p>' . $list . '</p>';
		}
		echo '</li>';
		endwhile;
		echo '</ul>';
		endif;

		echo $args['after_widget'];
	}

	public function form($instance) {
		if ( isset( $instance[ 'title' ] ) )
		$title = $instance[ 'title' ];
		else
		$title = __( 'Image List Items', 'hstngr_widget_domain' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
	<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		return $instance;
	}
}


//Add ACF
define( 'ACF_LITE' , true );
if(!class_exists('acf')){
	include_once( '/acf/acf.php' );
}
include_once('fields.php');

//some styling
echo "<style>
li.image_list img {
    width: 33px;
    padding-right: 5px;
}
li.image_list img, li.image_list p {
    display: inline-block;
}

</style>";