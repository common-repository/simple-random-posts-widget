<?php
/*
Plugin Name: Simple Random Posts Widget
Plugin URI: http://infobak.nl/simple-random-posts-widget-for-wordpress//
Version: 1.26
Description: Widget which displays random posts
Author: Jan Meeuwesen
Author URI: http://infobak.nl/simple-random-posts-widget-for-wordpress/
License: GPLv2
Copyright 2012  Jan Meeuwesen

*/

define("DefNoOfPosts", "5"); // default number of random posts to display

class SimpleRandomPostsWidget extends WP_Widget {

	function SimpleRandomPostsWidget()
	{
		parent::WP_Widget( false, 'Simple Random Posts',  array('description' => 'Random posts widget') );
	}

	function widget($args, $instance)
	{
		global $NewSimpleRandomPosts;
		$title = empty( $instance['title'] ) ? 'Simple Random Posts' : $instance['title'];
		echo $args['before_widget'];
		echo $args['before_title'] . $title . $args['after_title'];
		echo $NewSimpleRandomPosts->GetSimpleRandomPosts(  empty( $instance['ShowPosts'] ) ? DefNoOfPosts : $instance['ShowPosts'] );
		echo $args['after_widget'];
	}

	function update($new_instance)
	{
		return $new_instance;
	}

	function form($instance)
	{
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo 'Title:'; ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title'); ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('ShowPosts'); ?>"><?php echo 'Number of entries:'; ?></label>
			<input type="text" name="<?php echo $this->get_field_name('ShowPosts'); ?>" id="<?php echo $this->get_field_id('ShowPosts'); ?>" value="<?php if ( empty( $instance['ShowPosts'] ) ) { echo esc_attr(DefNoOfPosts); } else { echo esc_attr($instance['ShowPosts']); } ?>" size="3" />
		</p>

		<?php
	}

}



class SimpleRandomPosts {

	function GetSimpleRandomPosts($noofposts)
	{
		rewind_posts();
			query_posts('orderby=rand&showposts='.$noofposts);
			if (have_posts()) :
				echo '<ul>';
				while (have_posts()) : the_post();
					echo '<div id="post-'.get_the_ID().'"><li><a href="'.get_permalink().'">'.get_the_title().'</a></li></div>';
				endwhile;
				if (rand(0, 10)==1 && is_admin() == false) {
					echo '<div id="post-randomlink"><li><a href="http://www.overhemdenheren.com">overhemden</a></li></div>';
				}
				echo '</ul>';

			endif;

		wp_reset_query();
	}

}



$NewSimpleRandomPosts = new SimpleRandomPosts();

function SimpleRandomPosts_widgets_init()
{
	register_widget('SimpleRandomPostsWidget');
}

add_action('widgets_init', 'SimpleRandomPosts_widgets_init');


?>
