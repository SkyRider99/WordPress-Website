<?php
    /*
        * Plugin Name: 5-Hassan Widget Hello
        * Plugin URI: http//example.com/plugins/5_hassan_widget_hello
        * Description: Skriver ut lite text i en widget
        * Version: 1.0
        * Author: Hassan El Alaoui
        * Author URI: http//example.com
        * License: GNU GPL v2 or later
    */

    add_action('widgets_init', function(){register_widget('Hassan_Widget_Hello');});

    class Hassan_Widget_Hello extends WP_Widget {
        public function __construct(){
            parent::WP_Widget(false, 'Hassan Widget Hello', 'description=Skriver ut en liten Hello WOrld-text i en sidebar');
        }

        function widget($args, $instance){
            extract($args);
            echo $before_widget;
            echo $before_title . $instance['title'] . $after_title;
            echo '<p class="hassan-widget-hello">Well hello there. This is Fun!</p>';
            echo $after_widget;
        }

        function update($new_instance, $old_instance){
            return $new_instance;
        }

        function form($instance){
            $title = esc_attr ($instance['title']);
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('title');?>">
                Title: <input class="widefat" 
                id="<?php echo $this->get_field_id('title');?>"
                name="<?php echo $this->get_field_id('title');?>"
                type="text" value="<?php echo $title; ?>" />
            </p>

                </label>
            <?php
        }
    }


?>