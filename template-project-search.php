<?php 
/* Template Name: Advanced Project Search */
get_header(); ?>
<main class="content">
    <?php 
 
	// Get variables
	$paged = ($_GET['paged']) ? $_GET['paged'] : 1;
    $search_string = $_GET['project_search'];
	$category = $_GET['category'];
	$project_status =  $_GET['project_status'];
 
	// Search form
	global $post;
	?>
    <div class="form">
        <form method="GET" action="<?php echo get_permalink($post->ID); ?>">
            <div class="row-flex">
                <div class="column">
                    <label for="project_search"><?php _e('Search...', 'projects'); ?></label>
                    <input type="text" id="project_search" name="project_search"
                        value="<?php echo $search_string; ?>" />
                </div>
                <div class="column">
                    <label for="project-type"><?php _e('Project Type', 'projects'); ?></label>
                    <?php 
				wp_dropdown_categories([
					'taxonomy' => 'project_type',
					'name' => 'category',
					'id' => 'category',
					'value_field' => 'slug',
					'selected' => $category,
					'show_option_none' => __('All', 'projects'),
					'option_none_value' => 'All',
					'hierarchical' => true,
					'hide_if_empty' => false,
				]);
				?>
                </div>
                <div class="column">
                    <label for="project_status"><?php _e('Status', 'projects'); ?></label>
                    <select name="project_status" id="project_status">
                        <option value="All" <?php selected( $project_status, 'All' ); ?>>All</option>
                        <option value="active" <?php selected( $project_status, 'active' ); ?>>Active</option>
                        <option value="completed" <?php selected( $project_status, 'completed' ); ?>>Completed
                        </option>
                        <option value="discarded" <?php selected( $project_status, 'discarded' ); ?>>Discarded
                        </option>
                    </select>
                </div>
                <div class="column">
                    <input type="submit" value="<?php _e('Search', 'projects'); ?>" />
                </div>
            </div>
        </form>
    </div>
    <?php

	// Reset wp_query temporary
	$tmp_wpquery = $wp_query;
	$wp_query = null;
 
	// Start setting up custom query
	$args = [
		'post_type' => 'projects',
		'posts_per_page' => 16,
		'paged' => $paged
	];
 
	$meta_query = [];
	$tax_query = [];
 
	// Search post title and content
	if (!empty($search_string)) {
		$args['s'] = $search_string;
	}
 
	// Search by category
	if (!empty($category)) {

		if ( $category != "All") {

			$tax_query[] = [
				'taxonomy' => 'project_type',
				'field' => 'slug',
				'terms' => $category
			];
			
		}
	}
 
	// Search by Project Status
	if (!empty($project_status)) {
		if ( $project_status != "All") {
			$meta_query[] = [
				'key' => 'projects_meta_status',
				'value' => $project_status,
			];
		}
	}

	// Add to query arguments
	if (!empty($meta_query)) {
		$args['meta_query'] = $meta_query;
	}
	if (!empty($tax_query)) {
		$args['tax_query'] = $tax_query;
	}
 
	//print_r($args);

	// Perform query and assign it to wp_query
	$projects = new WP_Query( $args );
	$wp_query = $projects;
 
	// Loop through results
	if (have_posts()) { 
?>
    <ul class="auto-grid">
        <?php
		
		while (have_posts()) : the_post();
			get_template_part('content', 'project');
		endwhile;

	?>
    </ul>
    <?php

 		$total_pages = $projects->max_num_pages;

		if ($total_pages > 1){

			$current_page = max(1, get_query_var('paged'));

			$url_params_regex = '/\?.*?$/';
			preg_match($url_params_regex, get_pagenum_link(), $url_params);
		
			$base   = !empty($url_params[0]) ? preg_replace($url_params_regex, '', get_pagenum_link()).'%_%/'.$url_params[0] : get_pagenum_link().'%_%';

			?>
    <div class="navigation">
        <?php
						echo paginate_links(array(
							'base'      => $base,
							'format' => '?paged=%#%',
							'current' => $current_page,
							'total' => $total_pages,
							'prev_text' => __('< Prev'),
							'next_text' => __('Next >','multi'),
						));
					?>
    </div>
    <?php
		}    
 
	} else {
		?>
    <p class=" no-posts"><?php _e('No projects found.', 'txdomain'); ?></p>
    <?php
	}
 
	// Reset wp_query back to what it was
	$wp_query = null;
	$wp_query = $tmp_wpquery;
	?>
</main>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
<script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
<script>
var lightboxInlineIframe = GLightbox({
    selector: '.glightboxIline',
    keyboardNavigation: false,
    touchNavigation: false
});
</script>
<?php get_footer(); ?>