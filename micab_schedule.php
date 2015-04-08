<?php
/*
Plugin Name: Micab Schedule
Description: Handle the schedule based message display
Version: 1.0
*/

function micab_custom_widget() {
    register_widget( 'widget_schedule' );
}
add_action( 'widgets_init', 'micab_custom_widget' );

class widget_schedule extends WP_Widget {
	// constructor
	public function  __construct() {
		parent::__construct(
		// Base ID of your widget
		'micab', 

		// Widget name will appear in UI
		__('Micab Schedule'), 

		// Widget description
		array( 'description' => __( 'Schedule based conditional display' ), )

		);
	}
	public function form($instance){
		 $default = isset($instance[ 'default_message']) ? esc_textarea($instance[ 'default_message']) : '';
		 $start = isset($instance[ 'datetimepicker_start']) ? esc_attr($instance[ 'datetimepicker_start']) : '';
		 $end = isset($instance[ 'datetimepicker_end']) ? esc_attr($instance[ 'datetimepicker_end']) : '';
		 $message = isset($instance[ 'display_message']) ? esc_textarea($instance[ 'display_message']) : '';
		?>
		<label for="<?php print $this->get_field_id( 'default_message' )?>">Default Message</label>
		<textarea id="<?php print $this->get_field_id( 'default_message' )?>" name="<?php print $this->get_field_name( 'default_message' )?>" cols="15" row="3" ><?php print $default; ?></textarea><br>

		<ul id="list">
			<li class="repeat">
				<div>
					<label for="<?php print $this->get_field_id( 'datetimepicker_start' )?>">Select start date</label>
					<input id="<?php print $this->get_field_id( 'datetimepicker_start' )?>" name="<?php print $this->get_field_name( 'datetimepicker_start' )?>" type="text" class="datetimepicker" value="<?php print $start; ?>"><br>

					<label for="<?php print $this->get_field_id( 'datetimepicker_end' )?>">Select end date</label>
					<input id="<?php print $this->get_field_id( 'datetimepicker_end' )?>"  name="<?php print $this->get_field_name( 'datetimepicker_end' )?>" type="text" class="datetimepicker" value="<?php print $end; ?>"><br>

					<label for="<?php print $this->get_field_id( 'display_message' )?>">Message</label>
					<textarea id="<?php print $this->get_field_id( 'display_message' )?>"  name="<?php print $this->get_field_name( 'display_message' )?>" cols="15" row="3" ><?php print $message; ?></textarea><br>

				</div>
			</li>
		</ul>
		<?php
	}
	public function update( $new_instance, $old_instance ) {		
		$instance = $old_instance;
		$instance['default_message'] = strip_tags( $new_instance['default_message'] );
		$instance['datetimepicker_start'] =  $new_instance['datetimepicker_start'] ;
		$instance['datetimepicker_end'] =  $new_instance['datetimepicker_end'] ;	
		$instance['display_message'] = strip_tags( $new_instance['display_message'] );		
		return $instance;
		
	}
	/**
	 * Front-end display of widget.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', $instance['title'] );

		print $before_widget;
 
		// Display the widget title 
		if ( $title )
		    print $before_title . $title . $after_title;

		$default = $instance['default_message'];
		$start = strtotime(str_replace('/', '-',$instance['datetimepicker_start']));
		$end = strtotime(str_replace('/', '-',$instance['datetimepicker_end']));
		$message = $instance['display_message'];


		$current_time = strtotime(str_replace('/', '-',current_time('y/m/d h:m')));

		if($current_time > $start && $current_time < $end){
			$display = $message;
		}
		else{
			$display = $default;
		}

		print '<h2>
				<div class="btn btn-over gray-btn"><span class="icons-schedule"></span></div>'.
 				$display
 			  .'</h2>';
	}	
}
	
function datetimepicker_load(){
	 wp_enqueue_script('jquery');	
	 wp_enqueue_script( 
            'datetimepicker', 
            plugins_url( '/js/jquery.simple-dtpicker.js', __FILE__ ), 
            array('jquery'), 
            false, 
            true 
    );
	 wp_enqueue_style('datetimepicker.css',plugins_url( '/css/jquery.simple-dtpicker.css', __FILE__ ));
	 wp_enqueue_script('custom',plugins_url( '/js/custom.js', __FILE__ ),array('datetimepicker'), false, true);
}
add_action( 'admin_enqueue_scripts', 'datetimepicker_load' );
?>