<?php
require_once get_stylesheet_directory() . '/inc/widgets/recent-posts/class.php';

// Register the widget
function register_last_five_posts_widget() {
    register_widget( 'Last_Five_Posts_Widget' );
}
add_action( 'widgets_init', 'register_last_five_posts_widget' );