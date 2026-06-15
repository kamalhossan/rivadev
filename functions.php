<?php

require_once(get_stylesheet_directory() . "/includes/custom-function.php");

add_action('wp_enqueue_scripts', 'salient_child_enqueue_styles', 100);
function salient_child_enqueue_styles()
{
	$nectar_theme_version = nectar_get_theme_version();
	wp_enqueue_style('slick', get_stylesheet_directory_uri() . '/css/slick.css');
	wp_enqueue_style('salient-child-style', get_stylesheet_directory_uri() . '/style.css', '', $nectar_theme_version);
	wp_enqueue_style('kunal-style', get_stylesheet_directory_uri() . '/kunal.css', '', $nectar_theme_version);
	wp_enqueue_style('font-abs', get_stylesheet_directory_uri() . '/fonts/abc/stylesheet.css', array(), $nectar_theme_version, 'all');
	wp_enqueue_style('font-antro', get_stylesheet_directory_uri() . '/fonts/antro/stylesheet.css', array(), $nectar_theme_version, 'all');
	wp_enqueue_style('font-archivo', get_stylesheet_directory_uri() . '/fonts/archivo/stylesheet.css', array(), $nectar_theme_version, 'all');

	if (is_rtl()) {
		wp_enqueue_style('salient-rtl',  get_template_directory_uri() . '/rtl.css', array(), '1', 'screen');
	}

	wp_enqueue_script('slick', get_stylesheet_directory_uri() . '/js/slick.js', array('jquery'), true);
	wp_enqueue_script('gsap', get_stylesheet_directory_uri() . '/js/gsap.min.js', array('jquery'), true);
	wp_enqueue_script('ScrollTrigger', get_stylesheet_directory_uri() . '/js/ScrollTrigger.min.js', array('jquery'), true);
	wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/js/custom-script.js', array('jquery'), true);

	$wp_ajx_array = array('wp_ajax_url' => admin_url('admin-ajax.php'));
	wp_localize_script('custom-script', 'admin_ajaax', $wp_ajx_array); // localize ajax url in script

	wp_localize_script('custom-script', 'ajax_params', array(
		'ajax_url' => admin_url('admin-ajax.php') // WordPress AJAX handler
	));
}

require_once(get_stylesheet_directory() . "/includes/custom-function.php");
require_once(get_stylesheet_directory() . "/includes/api-function.php");
require_once(get_stylesheet_directory() . "/includes/bison-studio.php");

//add SVG to allowed file uploads
add_action('upload_mimes', 'add_file_types_to_uploads');
function add_file_types_to_uploads($file_types)
{
	$new_filetypes = array();
	$new_filetypes['svg'] = 'image/svg+xml';
	$file_types = array_merge($file_types, $new_filetypes);
	return $file_types;
}

add_filter("redux/salient_redux/field/typography/custom_fonts", "salient_redux_custom_fonts");
function salient_redux_custom_fonts()
{
	return array(
		'Custom Fonts' => array(
			'ABC Arizona' => 'ABC Arizona',
			'Archivo' => 'Archivo',
            'Antro' => 'Antro'
		)
	);
}

add_shortcode('landing-slider', 'riva_landing_slider_callback');

function riva_landing_slider_callback($atts)
{
	ob_start();
?>
	<div class="riva_lading_slider_container">
		<div class="riva_landing_slider" id="riva_landing_slider">
			<div class="slider_item">
				<div class="slider_content">
					<div class="slider_image">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/slider-1.jpg" alt="" srcset="">
					</div>
				</div>
			</div>
			<div class="slider_item">
				<div class="slider_content">
					<div class="slider_image">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/slider-1.jpg" alt="" srcset="">
					</div>
				</div>
			</div>
			<div class="slider_item">
				<div class="slider_content">
					<div class="slider_image">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/slider-1.jpg" alt="" srcset="">
					</div>
				</div>
			</div>
			<div class="slider_item">
				<div class="slider_content">
					<div class="slider_image">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/slider-1.jpg" alt="" srcset="">
					</div>
				</div>
			</div>
			<div class="slider_item">
				<div class="slider_content">
					<div class="slider_image">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/slider-1.jpg" alt="" srcset="">
					</div>
				</div>
			</div>
			<div class="slider_item">
				<div class="slider_content">
					<div class="slider_image">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/slider-1.jpg" alt="" srcset="">
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
	return ob_get_clean();
}

function my_custom_post_list_shortcode($atts)
{
	ob_start();
?>
	<div id="my-post-list" class="my-post-list">
		<?php
		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => 7,
			'paged'          => 1,
			'order'	=> 'DESC'
		);
		$query = new WP_Query($args);
		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post(); ?>
				<div class="news_list">
					<?php if (has_post_thumbnail()) { ?>
						<div class="col-md-8">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail('large'); ?>
							</a>
						</div>
					<?php } ?>
					<div class="col-md-4">
						<h4 class="news_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
						<div class="news_excerpt"><?php the_excerpt(); ?></div>
						<a class="read_more" href="<?php the_permalink(); ?>">Read more
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="15" height="15">
								<path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z" />
							</svg>
						</a>
					</div>
				</div>
		<?php }
		} else {
			echo '<p>No posts found.</p>';
		}
		wp_reset_postdata();
		?>
	</div>
	<div class="text-center">
		<button id="load-more" data-page="1" data-max="<?php echo $query->max_num_pages; ?>">Load More</button>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode('post_list', 'my_custom_post_list_shortcode');

function my_load_more_posts()
{
	$paged = isset($_POST['page']) ? $_POST['page'] : 1;
	$paged++; // Increment page number for next batch

	$args = array(
		'post_type'      => 'post',
		'posts_per_page' => 5, // Load more in batches of 5
		'paged'          => $paged,
		'order'	=> 'DESC'
	);
	$query = new WP_Query($args);

	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post(); ?>
			<div class="news_list">
				<?php if (has_post_thumbnail()) { ?>
					<div class="col-md-8">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail('large'); ?>
						</a>
					</div>
				<?php } ?>
				<div class="col-md-4">
					<h4 class="news_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
					<div class="news_excerpt"><?php the_excerpt(); ?></div>
					<a class="read_more" href="<?php the_permalink(); ?>">Read more
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="15" height="15">
							<path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z" />
						</svg>
					</a>
				</div>
			</div>
		<?php }
	}
	wp_reset_postdata();
	die(); // Stop execution
}
add_action('wp_ajax_load_more', 'my_load_more_posts');
add_action('wp_ajax_nopriv_load_more', 'my_load_more_posts'); // Allow non-logged-in users


add_action('vc_before_init', 'riva_custom_widgets');

function riva_custom_widgets()
{
	vc_map(array(
		"name" => __("Riva Slider", "salient"),
		"base" => "landing-slider",
		"category" => __("Riva Widgets", "salient"),
		//for slider
		"params" => array(
			array(
				"type" => "param_group",
				"heading" => __("Slider Image", "salient"),
				"param_name" => "images",
				"value" => "",
				"params" => array(
					array(
						"type" => "attach_image",
						"heading" => __("Image", "salient"),
						"param_name" => "image"
					)
				)
			)
		)
	));

	vc_map(array(
		"name" => __("Riva Availability", "salient"),
		"base" => "riva_availability",
		"category" => __("Riva Widgets", "salient"),
	    "params" => array(
            array(
                "type" => "dropdown",
                "heading" => __("Filter Text Color", "salient"),
                "param_name" => "filter_color",
                "value" => array(
                    __("Light", "salient") => "light",
                    __("Dark", "salient") => "dark"
                )
            )
        )
	));

	vc_map(array(
		"name" => __("Riva Residence Garages", "salient"),
		"base" => "riva_residence_garages",
		"category" => __("Riva Widgets", "salient"),
		"params" => array(
            array(
                "type" => "dropdown",
                "heading" => __("Filter Text Color", "salient"),
                "param_name" => "filter_color",
                "value" => array(
                    __("Light", "salient") => "light",
                    __("Dark", "salient") => "dark"
                )
            )
        )
	));

	vc_map(array(
		"name" => __("Similar Residence", "salient"),
		"base" => "riva_similar_residence",
		"category" => __("Riva Widgets", "salient"),
		"params" => array()
	));

	vc_map(array(
		"name" => __("Riva Availability Filter", "salient"),
		"base" => "riva_availability_filter",
		"category" => __("Riva Widgets", "salient"),
		"params" => array()
	));

	vc_map(array(
		"name" => __("Riva Residence Details", "salient"),
		"base" => "riva_residence_details",
		"category" => __("Riva Widgets", "salient"),
		"params" => array()
	));

	vc_map(array(
		"name" => __("Riva Residence Features", "salient"),
		"base" => "riva_residence_features",
		"category" => __("Riva Widgets", "salient"),
		"params" => array()
	));

	//News Article Shortcodes Start

	vc_map(array(
		"name" => __("Riva News Article Title", "salient"),
		"base" => "riva_news_article_title",
		"category" => __("Riva Widgets", "salient"),
		'params' => array(
			array(
				'type' => 'dropdown',
				'heading' => 'Show Date',
				'param_name' => 'show_date',
				'value' => array(
					'yes' => 'Yes',
					'no'  => 'No',
				)
			)
		)
	));

	vc_map(array(
		"name" => __("Riva News Article Single Image", "salient"),
		"base" => "riva_news_article_single_image",
		"category" => __("Riva Widgets", "salient"),
		"params" => array()
	));

	//News Article Shortcodes ENd
}

add_shortcode('riva_availability', 'riva_availability_callback');

function riva_availability_callback($atts)
{

    $atts = shortcode_atts(
        array(
            'filter_color' => 'light',
        ),
        $atts,
        'riva_residence_garages'
    );

    $filter_color = $atts['filter_color'];

	$is_ajax = defined('DOING_AJAX') && DOING_AJAX;
	$paged = isset($_POST['page']) ? $_POST['page'] : 1;


	ob_start();

	// $paged = get_query_var('paged') ? get_query_var('paged') : 1;

	$args = array(
		'post_type' => 'residences',
		'posts_per_page' => 8,
		'paged' => $paged,
	);

	$orderby = isset($_POST['orderby']) ? $_POST['orderby'] : '';
	$order = isset($_POST['order']) ? $_POST['order'] : '';

	if (!empty($orderby)) {
		$args['meta_key'] = strtolower($orderby); // Set the meta key to order by
		$args['orderby'] = 'meta_value_num'; // Use numeric comparison for meta values
	}

	if (!empty($order)) {
		if ($order === 'Low to High') {
			$args['order'] = 'ASC'; // Ascending order
		} elseif ($order === 'High to Low') {
			$args['order'] = 'DESC'; // Descending order
		} elseif ($order === 'New Arrival') {
			$args['orderby'] = 'date'; // Order by post date
			$args['order'] = 'DESC';   // Newest first
		}
	}


	$residences = new WP_Query($args);
	$residences_count = $residences->post_count;
	$max_num_pages = $residences->max_num_pages;

	if (!$is_ajax) {
		?>
		<div class="availability-card-section-top">
			<div class="riva-availability-card-top <?php echo $filter_color; ?>">
				<div class="riva-availability-card-top-left">
					<div class="result-info">
						<span class="result-count"><?php echo $residences_count; ?></span> residences found
					</div>
					<div class="filter-erase hidden">
						<span class="clear-filter">Clear filters</span>
					</div>
				</div>
				<div class="riva-availability-card-top-right">
					<div class="availability-sorting">
						<div class="sort-by">
							<span>Sort by</span>
						</div>
						<div class="sort-options">
							<div class="filter-item">
								<div class="riva-select" data-filter="orderby"><span>Floor</span> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/avail-arrow.png" alt="" srcset=""> </div>
								<div class="filter-options">
									<div class="filter-option">Floor</div>
									<div class="filter-option">Bedrooms</div>
									<div class="filter-option">Bathrooms</div>
									<div class="filter-option">Area</div>
									<div class="filter-option">Price</div>
								</div>
							</div>
							<div class="filter-item">
								<div class="riva-select" data-filter="order"><span>New Arrival</span> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/avail-arrow.png" alt="" srcset=""> </div>
								<div class="filter-options">
									<div class="filter-option">New Arrival</div>
									<div class="filter-option">High to Low</div>
									<div class="filter-option">Low to High</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="availability-card-section" id="availability-cards">
			<?php

			if ($residences->have_posts()) {
				while ($residences->have_posts()) {
					$residences->the_post();
					riva_residence_card(get_the_ID());
				}
				wp_reset_postdata();
			} else {
				echo '<h2 class="section-title">No residences found.</h2>';
			}

			?>
		</div>
	<?php
	}

	if ($is_ajax) {
		if ($residences->have_posts()) {
			while ($residences->have_posts()) {
				$residences->the_post();
				riva_residence_card(get_the_ID());
			}
			wp_reset_postdata();
		} else {
			echo '<h2 class="section-title">No residences found.</h2>';
		}
	}

	?>

	<?php
	if (!$is_ajax) {
		if ($max_num_pages > 1) {
	?>
			<div class="card-action">
				<a class="btn-primary" href="#" id="load-more-availability">LOAD MORE</a>
			</div>
	<?php
		}
	}


	if ($is_ajax) {
		$html = ob_get_clean();
		wp_send_json_success(
			array(
				'html' => $html,
				'page' => $paged,
				'max_page' => $max_num_pages,
				'count' => $residences_count,
			)
		);
		wp_die(); // Always for AJAX responses
	} else {
		return ob_get_clean();
	}
}

add_shortcode('riva_residence_garages', 'riva_residence_garages_callback');

function riva_residence_garages_callback($atts)
{

    $atts = shortcode_atts(
        array(
            'filter_color' => 'light',
        ),
        $atts,
        'riva_residence_garages'
    );
    $filter_color = $atts['filter_color'];

	$is_ajax = defined('DOING_AJAX') && DOING_AJAX;
	$paged = isset($_POST['page']) ? $_POST['page'] : 1;


	ob_start();

	// $paged = get_query_var('paged') ? get_query_var('paged') : 1;

	$args = array(
		'post_type' => 'residences',
		'posts_per_page' => 8,
		'paged' => $paged,
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => 'garage',
                'operator' => 'IN',
            )
        )
	);

	$orderby = isset($_POST['orderby']) ? $_POST['orderby'] : '';
	$order = isset($_POST['order']) ? $_POST['order'] : '';

	if (!empty($orderby)) {
		$args['meta_key'] = strtolower($orderby); // Set the meta key to order by
		$args['orderby'] = 'meta_value_num'; // Use numeric comparison for meta values
	}

	if (!empty($order)) {
		if ($order === 'Low to High') {
			$args['order'] = 'ASC'; // Ascending order
		} elseif ($order === 'High to Low') {
			$args['order'] = 'DESC'; // Descending order
		} elseif ($order === 'New Arrival') {
			$args['orderby'] = 'date'; // Order by post date
			$args['order'] = 'DESC';   // Newest first
		}
	}


	$residences = new WP_Query($args);
	$residences_count = $residences->post_count;
	$max_num_pages = $residences->max_num_pages;

	if (!$is_ajax) {
		?>
		<div class="availability-card-section-top garage-section">
			<div class="riva-availability-card-top garages <?php echo $filter_color; ?>">
				<div class="riva-availability-card-top-left">
					<div class="result-info">
						<span class="result-count"><?php echo $residences_count; ?></span> garages found
					</div>
					<div class="filter-erase hidden">
						<span class="clear-filter">Clear filters</span>
					</div>
				</div>
				<div class="riva-availability-card-top-right">
					<div class="availability-sorting">
						<div class="sort-by">
							<span>Sort by</span>
						</div>
						<div class="sort-options">
							<div class="filter-item">
								<div class="riva-select" data-filter="orderby"><span>Floor</span> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/avail-arrow.png" alt="" srcset=""> </div>
								<div class="filter-options">
									<div class="filter-option">Floor</div>
									<div class="filter-option">Bedrooms</div>
									<div class="filter-option">Bathrooms</div>
									<div class="filter-option">Area</div>
									<div class="filter-option">Price</div>
								</div>
							</div>
							<div class="filter-item">
								<div class="riva-select" data-filter="order"><span>New Arrival</span> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/avail-arrow.png" alt="" srcset=""> </div>
								<div class="filter-options">
									<div class="filter-option">New Arrival</div>
									<div class="filter-option">High to Low</div>
									<div class="filter-option">Low to High</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="availability-card-section" id="availability-cards-garages">
			<?php

			if ($residences->have_posts()) {
				while ($residences->have_posts()) {
					$residences->the_post();
					riva_garage_card(get_the_ID());
				}
				wp_reset_postdata();
			} else {
				echo '<h2 class="section-title">No garages found.</h2>';
			}

			?>
		</div>
	<?php
	}

	if ($is_ajax) {
		if ($residences->have_posts()) {
			while ($residences->have_posts()) {
				$residences->the_post();
				riva_residence_card(get_the_ID());
			}
			wp_reset_postdata();
		} else {
			echo '<h2 class="section-title">No residences found.</h2>';
		}
	}

	?>

	<?php
	if (!$is_ajax) {
		if ($max_num_pages > 1) {
	?>
			<div class="card-action">
				<a class="btn-primary" href="#" id="load-more-availability">LOAD MORE</a>
			</div>
	<?php
		}
	}


	if ($is_ajax) {
		$html = ob_get_clean();
		wp_send_json_success(
			array(
				'html' => $html,
				'page' => $paged,
				'max_page' => $max_num_pages,
				'count' => $residences_count,
			)
		);
		wp_die(); // Always for AJAX responses
	} else {
		return ob_get_clean();
	}
}


add_shortcode("riva_availability_filter", "riva_availability_filter_callback");

function riva_availability_filter_callback($atts)
{

	ob_start();

	global $wpdb;
	$all_floor = $wpdb->get_results("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = 'floor' AND meta_value != ''");
	$all_bedrooms = $wpdb->get_results("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = 'bedrooms' AND meta_value != ''");
	$all_bathrooms = $wpdb->get_results("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = 'bathrooms' AND meta_value != ''");

	$all_floor_sort = array();
	$all_bedrooms_sort = array();
	$all_bathrooms_sort = array();

	foreach ($all_floor as $floor) {
		$all_floor_sort[] = $floor->meta_value;
	}
	foreach ($all_bedrooms as $bedrooms) {
		$all_bedrooms_sort[] = $bedrooms->meta_value;
	}
	foreach ($all_bathrooms as $bathrooms) {
		$all_bathrooms_sort[] = $bathrooms->meta_value;
	}

	//sort the arrays
	sort($all_floor_sort);
	sort($all_bedrooms_sort);
	sort($all_bathrooms_sort);

	?>

	<div class="availability-filter-section">
		<div class="availability-filter">
			<div class="filter-item-left">
				<div class="filter-item">
					<div class="riva-select"><span>All Residences</span> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/select-arrow.png" alt="" srcset=""> </div>
					<div class="filter-options">
						<div class="filter-option" data-category="residence">The Residence</div>
						<div class="filter-option" data-category="sky-villas">The Sky Villas</div>
					</div>
				</div>
			</div>
			<div class="filter-item-right">
				<div class="filter-item">
					<div class="riva-select append" data-filter="floor"><span>FLOOR</span> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/select-arrow.png" alt="" srcset=""> </div>
					<div class="filter-options">
						<?php
						foreach ($all_floor_sort as $floor) {
						?>
							<div class="filter-option" data-floor="<?php echo $floor; ?>"><?php echo $floor; ?></div>
						<?php } ?>
					</div>
				</div>
				<div class="filter-item">
					<div class="riva-select append" data-filter="bedrooms"><span>BEDROOMS</span> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/select-arrow.png" alt="" srcset=""> </div>
					<div class="filter-options">
						<?php
						foreach ($all_bedrooms_sort as $bedroom) {
						?>
							<div class="filter-option" data-bedrooms="<?php echo $bedroom; ?>"><?php echo $bedroom; ?></div>
						<?php } ?>
					</div>
				</div>
				<div class="filter-item">
					<div class="riva-select append" data-filter="bathrooms"><span>BATHROOMS</span> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/select-arrow.png" alt="" srcset=""> </div>
					<div class="filter-options">
						<?php
						foreach ($all_bathrooms_sort as $bathroom) {
						?>
							<div class="filter-option" data-bathrooms="<?php echo $bathroom; ?>"><?php echo $bathroom; ?></div>
						<?php
						} ?>
					</div>
				</div>
				<div class="filter-item">
					<div class="riva-select append" data-filter="area"><span>AREA</span> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/select-arrow.png" alt="" srcset=""> </div>
					<div class="filter-options">
						<div class="filter-option">0-50</div>
						<div class="filter-option">50-100</div>
						<div class="filter-option">100-150</div>
						<div class="filter-option">150-200</div>
					</div>
				</div>
				<div class="filter-item">
					<div class="riva-select append" data-filter="price"><span>Price</span> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/select-arrow.png" alt="" srcset=""> </div>
					<div class="filter-options">
						<div class="filter-option">1M-10M</div>
						<div class="filter-option">10M-20M</div>
						<div class="filter-option">20M-30M</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
<?php
	return ob_get_clean();
}


add_shortcode("riva_residence_details", "riva_residence_details_callback");

function riva_residence_details_callback($atts)
{
	ob_start();
	$post_id = get_the_ID();
	$area = get_field('area', $post_id);
	if (!$area) {
		$area = 0;
	}
	$block = get_field('block_info', $post_id);
	$price = get_field('price', $post_id);
	$agent_info = get_field('agent_info', $post_id);
	$spec_sheet = get_field('spec_sheet', $post_id);
	$floor_plan = get_field('floor_plan_pdf', $post_id);
	$floor = get_field('floor', $post_id);
	$bedrooms = get_field('bedrooms', $post_id);
	$bathrooms = get_field('bathrooms', $post_id);

	$highlights = [];
	if ($floor) {
		$highlights[] = array(
			'title' => 'Floor',
			'value' => $floor
		);
	}
	if ($bedrooms) {
		$highlights[] = array(
			'title' => 'Bedrooms',
			'value' => $bedrooms
		);
	}
	if ($bathrooms) {
		$highlights[] = array(
			'title' => 'Bathrooms',
			'value' => $bathrooms
		);
	}

	$other_highlights = get_field('highlights', $post_id);
	if ($other_highlights) {
		$highlights = array_merge($highlights, $other_highlights);
	}
?>

	<div class="riva-residence-details-wrapper">
		<div class="riva-residence-details">
			<div class="residence-details-info">
				<div class="property-info">
					<h1 class="property-title"><?php echo get_the_title($post_id); ?></h1>
					<div class="property-top">
						<div class="size"><?php echo $area; ?> <span class="sqft">M<sup>2</sup></span></div>
						<?php if ($block) {
						?>
							<h4 class="block"><?php echo $block; ?></h4>
						<?php } ?>
					</div>
				</div>
				<div class="property-features">
					<div class="property-details">
						<p class="short-desc">
							<?php echo get_the_content($post_id); ?>
						</p>
						<?php if ($price) {
						?>
							<h2 class="price">€<?php echo number_format($price); ?></h2>
						<?php
						} ?>

						<?php
						echo '<div class="agent-info">';
						if ($agent_info['name'] && $agent_info['phone']) {
							echo strtoupper($agent_info['name']) . ' - <a href="tel:+' . $agent_info['phone'] . '" class="contact-agent">+' . $agent_info['phone'] . '</a>';
						} else {
							echo 'JOHN DOE POLIDANO - <a href="tel:+356 7789 3900" class="contact-agent">+356 7789 3900</a>';
						}
						echo '</div>';
						?>

					</div>
					<div class="apartment-feature">
						<?php if ($highlights && count($highlights) > 0) {
						?>
							<ul class="feature-list">
								<?php
								foreach ($highlights as $highlight) {
								?>
									<li><?php echo $highlight['title'] . ' ' . $highlight['value']; ?></li>
								<?php
								}
								?>
							</ul>
						<?php
						} ?>
					</div>
				</div>
				<div class="property-action">
					<a href="/enquire/" class="btn-primary btn-inquiry">Make an Inquiry</a>
				</div>
			</div>
			<div class="residence-details-features">
				<div class="residence-details-wrapper">
					<div class="features-action">
						<div class="share-group">
							<h2 class="feature-title">layout floor-Plan</h2>
							<a href="#" class="share-img share-link"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/share.png" alt="share property"></a>
						</div>
						<div class="button-group">
							<a href="<?php echo $spec_sheet; ?>" target="_blank" class="feature-button one"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/pdf.png" alt="" srcset=""> DOWNLOAD SPEC SHEET</a>
							<a href="<?php echo $floor_plan; ?>" target="_blank" class="feature-button two"> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/pdf.png" alt="" srcset=""> Download Plan</a>
						</div>
					</div>
					<div class="apartment-features-image">
						<img src="<?php echo get_the_post_thumbnail_url($post_id, 'full'); ?>" alt="<?php echo get_the_title($post_id); ?>">
					</div>
				</div>
			</div>
		</div>
	</div>

<?php
	return ob_get_clean();
}


add_shortcode("riva_residence_features", "riva_residence_features_callback");

function riva_residence_features_callback($atts)
{
	ob_start();
	$post_id = get_the_ID();
	$features = get_field('features', $post_id);
	$class = "";
	if (!$features) {
		$class = 'no-feature';
	}
?>
	<div class="riva-residence-features-wrapper <?php echo $class; ?>">
		<div class="riva-residence-features">
			<?php
			if ($features) {
				$count = 0;
				foreach ($features as $feature) {
					$count++;
					$feature_title = $feature['title'];
					$feature_content = $feature['description'];
					$feature_image = $feature['background'];
			?>
					<div class="riva-residence-card">
						<div class="residence-features-box" style="background-image: url('<?php echo $feature_image; ?>')">
							<div class="feature-name">
								<h2 class="feature-title"><?php echo $feature_title; ?></h2>
							</div>
							<div class="features-content">
								<?php echo $feature_content; ?>
							</div>
							<img class="feature-button abs" src="<?php echo get_stylesheet_directory_uri(); ?>/images/feature-button.png" alt="Feature Button">
						</div>
					</div>
			<?php
				}
			}
			?>
		</div>
	</div>
<?php
	return ob_get_clean();
}



// News Single page shortcodes
function riva_news_article_title_callback($atts)
{

	ob_start();

	$atts = shortcode_atts(array(
		'show_date' => 'yes',
	), $atts);

?>
	<div class="news-header">
		<a href="https://rivadev.bisontesting.com/news/" class="back_btn">Back</a>
		<button class="share_btn">SHARE</button>
		<?php if ($atts['show_date'] == 'yes') { ?>
			<p class="news-date"><?php echo get_the_date('d.m.Y'); ?></p>
		<?php } ?>
		<h2 class="news-title"><?php echo get_the_title(); ?></h2>
	</div>
<?php
	return ob_get_clean();
}
add_shortcode('riva_news_article_title', 'riva_news_article_title_callback');


function riva_news_article_single_image_callback()
{
	ob_start();

	global $post;

?>
	<div class="news-featured-img">
		<?php echo get_the_post_thumbnail($post, "full"); ?>
		<!-- <img src="/wp-content/uploads/2025/04/Image-32.png" alt="Featured Image" /> -->
	</div>
<?php
	return ob_get_clean();
}
add_shortcode('riva_news_article_single_image', 'riva_news_article_single_image_callback');


function news_intro_text_shortcode()
{
	ob_start(); ?>
	<h4 class="section-heading">A Coastal Gem Waiting to Be Discovered</h4>
	<p class="section-text">Tucked along Malta’s northern coastline, Xemxija is a tranquil seaside enclave that offers the perfect balance of natural beauty, rich history, and modern convenience. While well-known among locals, it remains a hidden gem for those seeking a peaceful yet connected coastal lifestyle. With stunning sea views, scenic nature trails, and easy access to some of Malta’s finest dining and leisure spots, Xemxija is quickly becoming one of the most desirable residential locations on the island.</p>

	<h4 class="section-heading">Breathtaking Views & A Serene Atmosphere</h4>
	<p class="section-text">Unlike the busier hubs of Sliema or St. Julian’s, Xemxija offers a quieter, more relaxed environment, where life moves at an effortless pace. The bay provides panoramic Mediterranean views, making it an idyllic setting for those who appreciate waking up to the sound of the sea and enjoying sunset-lit evenings from their private terrace.</p>
<?php
	return ob_get_clean();
}
add_shortcode('news_intro_text', 'news_intro_text_shortcode');


function news_gallery_section_shortcode()
{
	ob_start(); ?>
	<div class="three-image-gallery">
		<img src="/wp-content/uploads/2025/04/Image-33.png" />
		<img src="/wp-content/uploads/2025/04/Image-33.png" />
	</div>


<?php
	return ob_get_clean();
}
add_shortcode('news_gallery_section', 'news_gallery_section_shortcode');



function news_conclusion_text_shortcode()
{
	ob_start(); ?>
	<p class="section-text">For those who love the outdoors, Xemxija is surrounded by scenic walking trails and lush countryside. The famous Xemxija Heritage Trail takes you through ancient cart ruts, Roman baths, and centuries-old stone walls, offering a deep connection to Malta’s past. Meanwhile, the coastline invites Residence to enjoy seafront walks, morning swims, and watersports right at their doorstep.</p>
	<h4 class="section-heading">A Thriving Community With Modern Conveniences</h4>
	<p class="section-text">While Xemxija is known for its tranquility, it is also conveniently close to key urban areas. A short drive connects you to Bugibba, St. Paul’s Bay, and Mellieħa, offering a variety of shopping, dining, and entertainment options. From boutique cafés and seafood restaurants to private marinas, everything you need is within easy reach.</p>
<?php
	return ob_get_clean();
}
add_shortcode('news_conclusion_text', 'news_conclusion_text_shortcode');


function news_large_image_shortcode()
{
	ob_start(); ?>
	<div class="news-large-img">
		<img src="/wp-content/uploads/2025/04/Image-32.png" />
	</div>
<?php
	return ob_get_clean();
}
add_shortcode('news_large_image', 'news_large_image_shortcode');

// register post type of Residence
function riva_custom_post_type()
{
	register_post_type('residences', array(
		'labels' => array(
			'name' => 'Residences',
			'singular_name' => 'Residence',
			'add_new' => 'Add New',
			'add_new_item' => 'Add New Residence',
			'edit_item' => 'Edit Residence',
			'new_item' => 'New Residence',
			'view_item' => 'View Residence',
			'search_items' => 'Search Residence',
			'not_found' => 'No Residence found',
			'not_found_in_trash' => 'No Residence found in Trash',
			'parent_item_colon' => '',
			'menu_name' => 'Residence',
		),
		'public' => true,
		'has_archive' => true,
		'rewrite' => array('slug' => 'residence'),
		'supports' => array('title', 'editor', 'thumbnail'),
		'menu_icon' => 'dashicons-building',
		'taxonomies' => array('category', 'post_tag'),
		'show_in_rest' => true,
	));
}
add_action('init', 'riva_custom_post_type');



function riva_residence_card($post_id)
{
	$area = get_field('area', $post_id);
	if (!$area) {
		$area = 0;
	}
	$price = get_field('price', $post_id);
	if (!$price) {
		$price = 0;
	}
	$floor = get_field('floor', $post_id);
	$bedrooms = get_field('bedrooms', $post_id);
	$bathrooms = get_field('bathrooms', $post_id);

	$highlights = [];
	if ($floor) {
		$highlights[] = array(
			'title' => 'Floor',
			'value' => $floor
		);
	}
	if ($bedrooms) {
		$highlights[] = array(
			'title' => 'Bedrooms',
			'value' => $bedrooms
		);
	}
	if ($bathrooms) {
		$highlights[] = array(
			'title' => 'Bathrooms',
			'value' => $bathrooms
		);
	}

	$floor_plan = get_field('floor_plan_pdf', $post_id);

?>
	<div class="availability-card residences">
		<div class="property-icon">
			<img src="<?php echo get_the_post_thumbnail_url($post_id, 'full'); ?>" alt="<?php echo get_the_title($post_id); ?>">
		</div>
		<div class="apartment-number">
			<h4>Apartment no.</h4>
			<div class="apartment-info">
				<div class="number">
					<?php echo get_the_title($post_id); ?>
				</div>
				<span class="apartment-divider"></span>
				<div class="size"><?php echo $area; ?> <span class="sqft">M<sup>2</sup></span></div>
			</div>
		</div>
		<div class="apartment-feature">
			<?php

			if ($highlights && count($highlights) > 0) {
			?>
				<ul class="feature-list">
					<?php
					foreach ($highlights as $highlight) {
						echo '<li class="feature-item '. strtolower($highlight['title']). '">' . $highlight['title'] . ' ' . $highlight['value'] . '</li>';
					}
					?>
				</ul>
			<?php
			}
			?>
		</div>
		<div class="apartment-price">
			<?php
			echo '<h2 class="price">€' . number_format($price) . '</h2>';
			?>
		</div>
		<div class="property-action">
			<a class="btn-primary" href="<?php echo get_the_permalink($post_id); ?>">View property</a>
			<?php
			//if ($floor_plan) {
			?>
				<!-- <a class="btn-secondary" target="_blank" href="<?php // echo $floor_plan; ?>">Download Floor Plan</a> -->
			<?php
			//} ?>
            <a class="btn-secondary" target="_blank" href="<?php echo "#"; ?>">Locate on Visual</a>
		</div>
	</div>
<?php
}
function riva_garage_card($post_id)
{
	$area = get_field('area', $post_id);
	if (!$area) {
		$area = 0;
	}
	$price = get_field('price', $post_id);
	if (!$price) {
		$price = 0;
	}
	$floor = get_field('floor', $post_id);
	$bedrooms = get_field('bedrooms', $post_id);
	$bathrooms = get_field('bathrooms', $post_id);
	$level = get_field('level', $post_id);
	$cars = get_field('cars', $post_id);

	$highlights = [];
	if ($floor) {
		$highlights[] = array(
			'title' => 'Floor',
			'value' => $floor
		);
	}
	if ($bedrooms) {
		$highlights[] = array(
			'title' => 'Bedrooms',
			'value' => $bedrooms
		);
	}
	if ($bathrooms) {
		$highlights[] = array(
			'title' => 'Bathrooms',
			'value' => $bathrooms
		);
	}
	if ($level) {
		$highlights[] = array(
			'title' => 'Level',
			'value' => $level
		);
	}
	if ($cars) {
		$highlights[] = array(
			'title' => 'Cars',
			'cars' => $cars
		);
	}

	$floor_plan = get_field('floor_plan_pdf', $post_id);

?>
	<div class="availability-card garage">
		<div class="property-icon">
			<img src="<?php echo get_the_post_thumbnail_url($post_id, 'full'); ?>" alt="<?php echo get_the_title($post_id); ?>">
		</div>
		<div class="apartment-number">
			<h4>Garage no.</h4>
			<div class="apartment-info">
				<div class="number">
					<?php echo get_the_title($post_id); ?>
				</div>
				<span class="apartment-divider"></span>
				<div class="size"><?php echo $area; ?> <span class="sqft">M<sup>2</sup></span></div>
			</div>
		</div>
		<div class="apartment-feature">
			<?php

			if ($highlights && count($highlights) > 0) {
			?>
				<ul class="feature-list">
					<?php
					foreach ($highlights as $highlight) {
						echo '<li class="feature-item '. strtolower($highlight['title']). '">' . $highlight['title'] . ' ' . $highlight['value'] . '</li>';
					}
					?>
				</ul>
			<?php
			}
			?>
		</div>
		<div class="apartment-price">
			<?php
			echo '<h2 class="price">€' . number_format($price) . '</h2>';
			?>
		</div>
		<div class="property-action">
			<a class="btn-primary" href="<?php echo get_the_permalink($post_id); ?>">Inquire</a>
			<?php
			//if ($floor_plan) {
			?>
				<!-- <a class="btn-secondary" target="_blank" href="<?php // echo $floor_plan; ?>">Download Floor Plan</a> -->
			<?php
			//} 
            ?>
		</div>
	</div>
<?php
}


add_shortcode('riva_similar_residence', 'riva_similar_residence_callback');

function riva_similar_residence_callback($atts)
{
	ob_start();

	$post_id = get_the_ID();

	$query = new WP_Query(array(
		'post_type' => 'residences',
		'posts_per_page' => 4,
		'post__not_in' => array($post_id),
	));
	$similar_residences = $query->posts;
	$similar_residences_count = count($similar_residences);


?>
	<div class="riva_similar_residence">
		<?php
		if ($similar_residences_count > 0) {
		?>
			<h2 class="section-title">SIMILAR RESIDENCES</h2>
			<div class="availability-card-section">
				<?php
				foreach ($similar_residences as $residence) {
					riva_residence_card($residence->ID);
				}
				?>
			</div>
		<?php
		} else {
			echo '<h2 class="section-title">No similar residences found.</h2>';
		}
		?>

	</div>
<?php

	return ob_get_clean();
}


add_action('wp_ajax_filter_availability_cards', 'filter_availability_cards');
add_action('wp_ajax_nopriv_filter_availability_cards', 'filter_availability_cards');

function filter_availability_cards()
{

	ob_start();
	// $paged = isset($_POST['page']) ? $_POST['page'] : 1;

	$args = array(
		'post_type'      => 'residences',
		'posts_per_page' => -1, // Load more in batches of 5
		// 'paged'          => $paged,
	);

	$orderby = isset($_POST['orderby']) ? $_POST['orderby'] : '';
	$order = isset($_POST['order']) ? $_POST['order'] : '';
	$args['orderby'] = 'meta_value_num'; // Default order by meta value (e.g., area)

	if (!empty($orderby)) {
		$args['meta_key'] = strtolower($orderby); // Set the meta key to order by
		$args['orderby'] = 'meta_value_num'; // Use numeric comparison for meta values

	}

	if (!empty($order)) {
		if ($order === 'Low to High') {
			$args['order'] = 'ASC'; // Ascending order
		} elseif ($order === 'High to Low') {
			$args['order'] = 'DESC'; // Descending order
		} elseif ($order === 'New Arrival') {
			$args['orderby'] = 'date'; // Order by post date
			$args['order'] = 'DESC';   // Newest first
		}
	}

	$category = isset($_POST['category']) ? $_POST['category'] : '';
	if (!empty($category)) {
		$args['tax_query'][] = array(
			array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => $category,
			),
		);
	}


	$floor = isset($_POST['floor']) ? $_POST['floor'] : '';
	if (!empty($floor)) {
		$args['meta_query'][] = array(
			'relation' => 'AND',
			array(
				'key'     => 'floor',
				'value'   => $floor,
				'compare' => 'LIKE',
			),
		);
	}


	$bedrooms = isset($_POST['bedrooms']) ? $_POST['bedrooms'] : '';
	if (!empty($bedrooms)) {
		$args['meta_query'][] = array(
			'relation' => 'AND',
			array(
				'key'     => 'bedrooms',
				'value'   => $bedrooms,
				'compare' => 'LIKE',
			),
		);
	}
	$bathrooms = isset($_POST['bathrooms']) ? $_POST['bathrooms'] : '';
	if (!empty($bathrooms)) {
		$args['meta_query'][] = array(
			'relation' => 'AND',
			array(
				'key'     => 'bathrooms',
				'value'   => $bathrooms,
				'compare' => 'LIKE',
			),
		);
	}
	$area = isset($_POST['area']) ? $_POST['area'] : '';
	if (!empty($area)) {
		// Split the range into minimum and maximum values
		$area_range = explode('-', $area);
		if (count($area_range) === 2) {
			$args['meta_query'][] = array(
				'key'     => 'area',
				'value'   => array((int)$area_range[0], (int)$area_range[1]),
				'type'    => 'NUMERIC',
				'compare' => 'BETWEEN',
			);
		}
	}

	$price = isset($_POST['price']) ? $_POST['price'] : '';
	if (!empty($price)) {
		// Remove "M" and split the range into minimum and maximum values
		$price_range = str_replace('M', '', $price); // Remove "M"
		$price_range = explode('-', $price_range); // Split the range

		if (count($price_range) === 2) {
			$args['meta_query'][] = array(
				'key'     => 'price',
				'value'   => array((int)$price_range[0] * 100000, (int)$price_range[1] * 100000), // Convert to numeric values
				'type'    => 'NUMERIC',
				'compare' => 'BETWEEN',
			);
		}
	}


	$result = new WP_Query($args);
	$post_count = $result->post_count;
	if ($result->have_posts()) {
		while ($result->have_posts()) {
			$result->the_post();
			riva_residence_card(get_the_ID());
			wp_reset_postdata();
		}
	} else {
		echo '<h2 class="section-title">No residences found.</h2>';
	}

	$html = ob_get_clean();

	wp_send_json_success(
		array(
			'html' => $html,
			'count' => $post_count,
			'page' => $paged,
			'max_page' => $result->max_num_pages,
		)
	);

	die();
}


add_action('wp_ajax_load_more_cards', 'riva_availability_callback');
add_action('wp_ajax_nopriv_load_more_cards', 'riva_availability_callback');

// function load_more_cards()
// {

// 	echo true;
// 	die();
// }



// build a global popup with wp_footer

add_action('wp_footer', 'riva_inquire_now_popup');

function riva_inquire_now_popup()
{
    ?>

    <div class="riva-inquire-now-popup-wrapper">
        <div class="riva-inquire-now-popup-container">
            <div class="popup-left-content" style="background: url(/wp-content/themes/salient-child/images/inquire-bg.png)">
                <div class="popup-image">
                    <img src="/wp-content/themes/salient-child/images/popup-logo.png" alt="Riva Residences logo">
                </div>
                <div class="popup-banner-text">
                    <h2>A New Standard In Coastal Living</h2>
                </div>
            </div>
            <div class="popup-right-content">
                <div class="popup-close-icon">
                    <img src="/wp-content/themes/salient-child/images/popup-close.png" alt="close-icon" class="close-icon">
                </div>
                <div class="popup-form">
                    <div class="form-top-info">
                        <div class="contact-info">
                           <h4>John Doe Polidano</h4>
                           <a href="#">+35677893900</a>
                        </div>
                        <img src="/wp-content/themes/salient-child/images/call-icon.png" alt="" class="top-icon">
                    </div>
                    <?php echo do_shortcode('[forminator_form id="42"]'); 
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}


add_action('wp_head', 'add_google_site_verification_meta_tag');
function add_google_site_verification_meta_tag()
{
    echo '<meta name="google-site-verification" content="iuH0a_ediC6IeGYinnJ3rjo2CsW4WjCl4IDsUy6DFh0" />';
}


// Change the CSS and JS version for caching
add_filter('style_loader_src', 'custom_remove_version_css_js', 9999, 2);
add_filter('script_loader_src', 'custom_remove_version_css_js', 9999, 2);

function custom_remove_version_css_js($src, $handle)
{
    $rand_no = rand(10, 1000);
    $handles_with_version = ['style'];
    // Add other script handles here if needed

    if (strpos($src, 'ver=') && !in_array($handle, $handles_with_version, true)) {
        $src = preg_replace('/(ver=)[0-9]+\.[0-9]+\.[0-9]+/', '$1' . '5.2.' . $rand_no, $src);
    }

    return $src;
}