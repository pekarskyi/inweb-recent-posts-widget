<?php
class Last_Five_Posts_Widget extends WP_Widget {

    // Constructor
    function __construct() {
        parent::__construct(
            'last_five_posts_widget', // Base ID
            '[InwebPress] Recent posts', // Name
            array( 'description' => 'Displays recent posts with thumbnails')
        );
    }

    // Front-end display of widget
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        $count = isset($instance['count']) ? (int)$instance['count'] : 5;

        echo $args['before_widget'];

        if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];

        // Query to get last N posts
        $query_args = array(
            'post_type'      => 'post',
            'posts_per_page' => $count
        );
        $last_n_posts = new WP_Query( $query_args );

        // Loop through posts and display them with thumbnails
        if ( $last_n_posts->have_posts() ) :
            while ( $last_n_posts->have_posts() ) : $last_n_posts->the_post(); ?>
                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="inweb-recent-posts">
                        <?php if ( has_post_thumbnail() ): ?>
                            <div class="inweb-recent-post-thumbnail">
                                <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a>
                            </div>
                        <?php endif; ?>
                        <div class="inweb-entry-summary">
                            <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title( '<div class="inweb-recent-title-post">', '</div>' ); ?></a>
                        </div>
                    </div>
                </div>
            <?php endwhile;
        endif;

        wp_reset_postdata();

        echo $args['after_widget'];
    }

    // Back-end widget form
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $count = ! empty( $instance['count'] ) ? $instance['count'] : 5;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="number" min="1" step="1" value="<?php echo esc_attr( $count ); ?>">
        </p>
        <?php
    }

    // Sanitize widget form values as they are saved
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['count'] = ( ! empty( $new_instance['count'] ) ) ? absint( $new_instance['count'] ) : 5;

        return $instance;
    }
}