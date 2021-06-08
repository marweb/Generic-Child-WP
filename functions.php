<?php
function enqueue_parent_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}

add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

/* projects custom post type start*/
function post_type_projects() {
    $supports = array(
        'title', // post title
        'editor', // post content
        'thumbnail', // featured images
        'excerpt' // post excerpt
    );
    $labels = array(
        'name' => _x('Projects', 'plural'),
        'singular_name' => _x('Project', 'singular'),
        'menu_name' => _x('Projects', 'admin menu'),
        'name_admin_bar' => _x('Projects', 'admin bar'),
        'add_new' => _x('Add New', 'add new'),
        'add_new_item' => __('Add New Project'),
        'new_item' => __('New Project'),
        'edit_item' => __('Edit Project'),
        'view_item' => __('View Project'),
        'all_items' => __('All Projects'),
        'search_items' => __('Search Projects'),
        'not_found' => __('No Projects found.'),
    );
    $args = array(
        'supports' => $supports,
        'labels' => $labels,
        'public' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'projects'),
        'has_archive' => true,
        'hierarchical' => false,
        'taxonomies'   => array('post_tag'),
    );
    register_post_type('projects', $args);
    }
    add_action('init', 'post_type_projects');


    add_action( 'init', 'create_projects_taxonomies', 0 );

    function create_projects_taxonomies()
    {
    $labels = array(
        'name' => _x( 'Project Type', 'taxonomy general name' ),
        'singular_name' => _x( 'Project Type', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Project Type' ),
        'popular_items' => __( 'Popular Project Type' ),
        'all_items' => __( 'All Project Types' ),
        'parent_item' => __( 'Parent Recording' ),
        'parent_item_colon' => __( 'Parent Project Type:' ),
        'edit_item' => __( 'Edit Project Type' ),
        'update_item' => __( 'Update Project Type' ),
        'add_new_item' => __( 'Add New Project Type' ),
        'new_item_name' => __( 'New Project Type Name' ),
    );
    register_taxonomy('project_type',array('projects'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'project-type' ),
    ));
    }

    /*projects custom post type end*/
    
    function create_custom_meta(){

        /*
            Projects Meta Box
        
        */

        add_meta_box("projects", "Project Information", "projects_meta", "projects", "normal", "low");
        
        function projects_meta(){

            global $post;
            $values = get_post_custom( $post->ID );

            $selected = isset( $values['projects_meta_status'][0] ) ? esc_attr( $values['projects_meta_status'][0] ) : '';
            $check = isset( $values['my_meta_box_check'] ) ? esc_attr( $values['my_meta_box_check'] ) : '';
        
?>
<p>
    <label for="projects_meta_status">Status</label>
    <select name="projects_meta_status" id="projects_meta_status">
        <option value="active" <?php selected( $selected, 'active' ); ?>>Active</option>
        <option value="completed" <?php selected( $selected, 'completed' ); ?>>Completed</option>
        <option value="discarded" <?php selected( $selected, 'discarded' ); ?>>Discarded </option>
    </select>
</p>

<?php 
        }
    
        /*
            Save all post data!!
        */
        add_action("save_post", "save_details");
        function save_details( $post_id ){

            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
            if ( $parent_id = wp_is_post_revision( $post_id ) ) {
                $post_id = $parent_id;
            }
            $fields = [
                'projects_meta_status'
            ];
            foreach ( $fields as $field ) {
                if ( array_key_exists( $field, $_POST ) ) {
                    update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
                }
             }
             
        }
    
    }
    
    add_action('admin_menu', 'create_custom_meta');