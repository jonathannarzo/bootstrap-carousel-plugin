<?php
class twbsCarouselView {
	public static function viewCarousel($slider) {
		global $wpdb;
		global $slides;
		global $photos;
		$datas = $wpdb->get_results($wpdb->prepare("SELECT p.* FROM $slides AS s INNER JOIN $photos AS p ON s.id=p.slide_id WHERE s.slide_name=%s", array($slider)));
		$slide_photos = '';
		$slide_list = '';
		$i = 0;
		if (count($datas)) {
			foreach ($datas as $key => $data) {
				$slide_list .= '<li data-target="#carousel-example-generic" data-slide-to="'.$i.'" class="'.( ($i == 0) ? 'active' : '' ).'"></li>';
				$slide_photos .= '
				<div class="item '.( ($i == 0) ? 'active' : '' ).'">
					<center>
						<img src="'.$data->photo_path.'" alt="...">
					</center>
					<div class="carousel-caption">
						<h3>Photo Title '.$i.'</h3>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo</p>
					</div>
				</div>';
				$i++;
			}
			echo '
			<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
				<ol class="carousel-indicators">
					'.$slide_list.'
				</ol>
				<div class="carousel-inner" role="listbox">
					'.$slide_photos.'
					<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
			</div>';
		} else {
			echo '<pre>Bootstrap carousel ERROR: <b>';
			echo (empty($slider)) ? "Please provide a slider!" : "$slider doesn't contain a photo!";
			echo '</b></pre>';
		}
	}
}