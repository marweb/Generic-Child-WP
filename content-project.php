<li class="card">
    <?php if ( has_post_thumbnail() ) : ?>
    <a href="#inline-example-<?php echo get_the_id(); ?>" class="glightboxIline"
        data-glightbox="width: 700; height: auto;"
        title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( 'thumbnail', array( 'itemprop' => 'image' ) ); ?></a>
    <?php endif; ?>
    <h3><?php the_title();?></h3>
    <p><?php echo get_the_excerpt(); ?></p>
    <a href="#inline-example-<?php echo get_the_id(); ?>" class="glightboxIline view-btn"
        data-glightbox="width: 700; height: auto;">
        View Details
    </a>
</li>
<div id="inline-example-<?php echo get_the_id() ?>" style="display: none">
    <div class="inline-inner">
        <h3 class="text-center"><?php the_title();?></h3>
        <?php the_post_thumbnail( 'medium', array( 'itemprop' => 'image' ) ); ?>
        <p>
            <?php the_content(); ?>
        </p>
        <div class="info-text">Project Status:</div>
        <?php $status = get_post_meta( $post->ID, 'projects_meta_status' );
        echo $status[0];
        ?>
        <div class="info-text">Project Type:</div>
        <?php
        $terms = get_the_terms( $post->ID, 'project_type' ); 
        foreach($terms as $term) {
          echo $term->name;
        }
        ?>
        <div class="info-text">Tools Used:</div>
        <br>
        <?php 
                        $tags = wp_get_post_tags( $post->ID );
                        foreach ($tags as $t => $tag) {
                            echo '<span class="info">';
                            echo $tag->name;
                            echo '</span>';
                        }
                        echo '<div class="clear"></div>';
                        echo '</div>';
                    ?>
        <a class="gtrigger-close inline-close-btn" href="#">Close</a>
    </div>