<?php
class twbsCarousel extends WP_Widget {
	function twbsCarousel () {
		$widget_options = array(
			'classname' => '', // css
			'description' => 'Bootstrap carousel plugin'
		);
		$this->WP_Widget('twbs_carousel', 'Bootstrap carousel', $widget_options);
	}

	// Show form
	function form($instance) {
		global $wpdb;
		global $slides;
		$datas = $wpdb->get_results("SELECT * FROM $slides");
		$options = '<option value="">No sliders found.</option>';
		if (count($datas) > 0)
			$options = '<option value=""></option>';
		$defaults = array('slider' => '');
		$instance = wp_parse_args( (array) $instance, $defaults );
		$slider = esc_attr($instance['slider']);

		foreach ($datas as $key => $data) {
			$options .= '<option '.( ($slider == $data->slide_name) ? 'selected' : '' ).'>'.$data->slide_name.'</option>';
		}
		echo '<p>Slider : <select class="widefat" name="'.$this->get_field_name('slider').'">'.$options.'</select></p>';
	}

	// Save form
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['slider'] = strip_tags($new_instance['slider']);
		return $instance;
	}

	// Show widget in page
	function widget($args, $instance) {
		global $wpdb;
		global $photos;
		extract($args);
		$slider = apply_filters('widget_title', $instance['slider']);

		/* widget content */
		twbsCarouselView::viewCarousel($slider); // outputs carousel
	}
}