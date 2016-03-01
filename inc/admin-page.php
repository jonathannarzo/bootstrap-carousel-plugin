<?php
class twbsAdminUI {
	public static function twbsCarouselMenu() {
		add_options_page('Bootstrap Carousel Options', 'Bootstrap carousel', 'manage_options', 'twbscarousel', 'twbsAdminUI::twbsCarouselOptions');
	}

	public static function twbsCarouselOptions() {
		$slidermain = (isset($_GET['page']) && ($_GET['page'] == 'twbscarousel'));
		$sliderphotos = (isset($_GET['page']) && ($_GET['page'] == 'twbscarousel') && isset($_GET['viewslider']));
		if ($sliderphotos) twbsAdminUI::twbsSliderPhotos(); // View selected slider
		else if ($slidermain) twbsAdminUI::twbsSliders(); // View main options page
	}

	private static function twbsSliders() {
		global $wpdb;
		global $slides; // slides table
		global $photos; // slides photos table
		if (isset($_POST['submit'])) twbsAdminUI::saveTwbsCarouselOption();
		echo '
		<div class="wrap">
			<h2>Bootstrap Carousel</h2>
			<form name="twbs_carousel_option" method="post">
				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							<label for="twbs_carousel_slider">Slider Name</label>
						</th>
						<td>
							<input type="text" id="twbs_carousel_slider" name="twbs_carousel_slider" />
						</td>
					</tr>
				</table>';
				@submit_button();
		echo '
			</form>
		</div>
		<hr/>';

		$datas = $wpdb->get_results("SELECT * FROM $slides");
		$slider_rows = '<tr><td colspan="3">No slider found.</td></tr>';
		if (count($datas))
			$slider_rows = '';
		foreach ($datas as $key => $data) {
			$url = '?page='.$_GET['page'].'&viewslider='.$data->id.'&slidename='.$data->slide_name;
			$slider_rows .= '
			<tr>
				<td class="column-columnname">'.$data->id.'</td>
				<td class="column-columnname">'.$data->slide_name.'</td>
				<td class="column-columnname">
					<div class="row-actions visible">
						<span><a href="'.$url.'">View</a> |</span>
						<span class="delete"><a href="#" class="delete">Remove</a></span>
					</div>
				</td>
			</tr>';
		}
		echo '
		<h3>Sliders</h3>
		<table class="widefat fixed striped" cellspacing="0">
			<thead>
				<tr>
					<th>ID</th>
					<th>Slider Name</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody id="the-list">'.$slider_rows.'</tbody>
		</table>';
	}

	private static function twbsSliderPhotos() {
		global $wpdb;
		global $photos; // slide photos table
		if (isset($_POST['submit_photos'])) twbsAdminUI::uploadTwbsCarouselPhotos();

		$datas = $wpdb->get_results($wpdb->prepare("SELECT * FROM $photos WHERE slide_id=%s", array($_GET['viewslider'])));

		$photo_rows = '<tr><td colspan="3">No photo/s found.</td></tr>';
		if (count($datas))
			$photo_rows = '';

		foreach ($datas as $key => $data) {
			$photo_rows .= '
			<tr>
				<td>'.$data->id.'</td>
				<td><img src="'.$data->photo_path.'" style="height:60px;"></td>
				<td>
					<div class="row-actions visible">
						<span class="delete"><a href="#" class="delete">Remove</a></span>
					</div>
				</td>
			</tr>';
		}
		echo '
		<div class="wrap">
			<h2><a href="?page='.$_GET['page'].'">Bootstrap Carousel</a> ('.$_GET['slidename'].')</h2>
			<p></p>
			<form name="twbs_carousel_photos" method="post">
				<input type="hidden" name="slider_id" value="'.$_GET['viewslider'].'" />
				<input type="button" class="button button-secondary" value="Upload Image" id="upload-button" /> <b id="twbs_count_selection"></b>
				<input type="hidden" name="image_url" id="images-selected" />
				<p></p>
				<div id="twbs_photos_to_upload">
					<i>No selected photo/s.</i>
				</div>
				<input type="submit" disabled id="save_selected_photos" class="button button-primary" name="submit_photos" value="Save Photos" />
			</form>
			<hr/>
			<h3>'.$_GET['slidename'].' Photos</h3>
			<table class="widefat fixed striped" cellspacing="0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Photo</th>
						<th></th>
					</tr>
				</thead>
				<tbody id="the-list">'.$photo_rows.'</tbody>
			</table>
		</div>';
	}

	private static function saveTwbsCarouselOption() {
		global $wpdb;
		global $slides; // slides table
		if (!empty($_POST['twbs_carousel_slider'])) {
			$datas = array('slide_name' => $_POST['twbs_carousel_slider']);
			$wpdb->insert($slides, $datas);
		}
	}

	private static function uploadTwbsCarouselPhotos() {
		global $wpdb;
		global $photos; // slides table
		if (!empty($_POST['slider_id'])) {
			$images = explode(',', $_POST['image_url']);
			foreach ($images as $key => $image) {
				$datas = array('slide_id' => $_POST['slider_id'], 'photo_path' => $image);
				$wpdb->insert($photos, $datas);
			}
		}
	}
}