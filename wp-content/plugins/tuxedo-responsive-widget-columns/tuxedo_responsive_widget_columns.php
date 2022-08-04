<?php
/**
 * Plugin Name: Tuxedo Responsive Widget Columns
 * Plugin URI:  https://github.com/andtrev/Tuxedo-Responsive-Widget-Columns
 * Description: Split sidebars and widget areas into responsive columns.
 * Version:     1.1
 * Author:      Trevor Anderson
 * Author URI:  https://github.com/andtrev
 * License:     GPLv2 or later
 * Domain Path: /languages
 * Text Domain: tuxedo-rwc
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * 
 * @package TuxedoRWC
 * @version 1.0.0
 */

/**
 * Tuxedo Responsive Widget Columns manager class.
 *
 * Bootstraps the plugin.
 *
 * @since 1.0.0
 */
class TuxedoRespWidgetColumns {

	/**
	 * TuxedoRespWidgetColumns instance.
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 * @var TuxedoRespWidgetColumns
	 */
	private static $instance = false;

	/**
	 * Get the instance.
	 * 
	 * Returns the current instance, creates one if it
	 * doesn't exist. Ensures only one instance of
	 * TuxedoRespWidgetColumns is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 *
	 * @return TuxedoRespWidgetColumns
	 */
	public static function get_instance() {

		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;

	}

	/**
	 * Constructor.
	 * 
	 * Initializes and adds functions to filter and action hooks.
	 * 
	 * @since 1.0.0
	 */
	public function __construct() {

		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		add_action( 'customize_register', array( $this, 'customizer_options' ) );
		add_action( 'wp_print_styles', array( $this, 'print_css' ) );
		add_filter( 'dynamic_sidebar_params', array( $this, 'add_sidebar_row_col_divs' ) );

	}

	/**
	 * Register widgets.
	 * 
	 * Attached to the 'widgets_init' action.
	 * 
	 * @since 1.0.0
	 */
	function register_widgets() {

		register_widget( 'TuxedoWidgetRow' );
		register_widget( 'TuxedoWidgetColumn' );

	}

	/**
	 * Customizer options.
	 * 
	 * Attached to the 'customize_register' action.
	 * 
	 * @since 1.0.0
	 */
	function customizer_options( $wp_customize ) {

		$wp_customize->add_setting( 'tuxrwc_gutter', array(
			'default'           => '30',
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_setting( 'tuxrwc_collapse', array(
			'default'           => '768',
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_section( 'tuxrwc_section', array(
			'title'    => __( 'Widget Columns', 'tuxedo-rwc' ),
			'priority' => 110,
		) );
		$wp_customize->add_control( 'tuxrwc_gutter_control', array(
			'label'    => __( 'Gutters (px)', 'tuxedo-rwc' ),
			'section'  => 'tuxrwc_section',
			'settings' => 'tuxrwc_gutter',
			'type'     => 'number',
		) );
		$wp_customize->add_control( 'tuxrwc_collapse_control', array(
			'label'    => __( 'Responsive Collapse at (px)', 'tuxedo-rwc' ),
			'section'  => 'tuxrwc_section',
			'settings' => 'tuxrwc_collapse',
			'type'     => 'number',
		) );

	 }

	/**
	 * Print grid CSS.
	 * 
	 * Attached to the 'wp_print_styles' action.
	 * 
	 * @since 1.0.0
	 */
	function print_css() {

		$gutter = intval( get_option( 'tuxrwc_gutter', 30 ) ) / 2;
		$collapse = intval( get_option( 'tuxrwc_collapse', 768 ) );

		echo
			'<style id="tux-grid-css" type="text/css">' .
				'.tux-row:before,.tux-row:after{' .
					'-webkit-box-sizing:border-box;' .
					'-moz-box-sizing:border-box;' .
					'box-sizing:border-box;' .
					'content:" ";' .
					'display:table;' .
				'}' .
				'.tux-row{' .
					'-webkit-box-sizing:border-box;' .
					'-moz-box-sizing:border-box;' .
					'box-sizing:border-box;' .
					'padding:0;' .
					'margin:0 -' . $gutter . 'px;' .
				'}' .
				'.tux-row:after{' .
					'clear:both;' .
				'}' .
				'.tux-col{' .
					'-webkit-box-sizing:border-box;' .
					'-moz-box-sizing:border-box;' .
					'box-sizing:border-box;' .
					'padding:0 ' . $gutter . 'px;' .
					'margin:0;' .
				'}' .
				'@media(min-width:' . $collapse . 'px){' .
					'.tux-col{' .
						'float:left;' .
					'}';
		for ( $i = 1; $i < 13; $i++ ) {
			echo
					'.tux-span-1-of-' . $i . '{' .
						'width:' . round( (1/$i)*100, 8 ) . '%;' .
					'}';
		}
		echo
				'}' .
			'</style>';

	}

	/**
	 * Add sidebar row and column divs.
	 *
	 * Attached to the 'dynamic_sidebar_params' filter.
	 *
	 * @since 1.0.0
	 */
	function add_sidebar_row_col_divs( $params ) {

		static $sidebar_id = '';           // Current sidebar id.
		static $cur_widget = 1;            // Current widget.
		static $total_widgets = 1;         // Total widgets in current sidebar.
		static $cur_row = 0;               // Current row (zero-based).
		static $cur_col_in_row = 1;        // Current column in row.
		static $cols_per_row = array( 1 ); // Columns per row.
		static $total_tux_widgets = 0;     // Total row/column widgets.
		$before = '';

		if ( $sidebar_id != $params[0]['id'] ) {

			$sidebar_widgets = wp_get_sidebars_widgets(); // Get list of sidebars and their widgets.
			$total_widgets = count( $sidebar_widgets[$params[0]['id']] );
			$cur_widget = 1;
			$sidebar_id = $params[0]['id'];
			$cur_row = 0;
			$cols_per_row = array( 1 );
			$total_tux_widgets = 0;
			foreach ( $sidebar_widgets[$sidebar_id] as $widget_id ) {
				if ( strpos( $widget_id, 'tux_col' ) !== false ) {
					if ( $cols_per_row[$cur_row] == 12 ) {
						$cur_row++;
						$cols_per_row[$cur_row] = 1;
					} else {
						$cols_per_row[$cur_row]++;
					}
					$total_tux_widgets++;
				} elseif ( strpos( $widget_id, 'tux_row' ) !== false ) {
					$cur_row++;
					$cols_per_row[$cur_row] = 1;
					$total_tux_widgets++;
				}
			}
			$cur_row = 0;
			$before = '<div class="tux-row"><div class="tux-col tux-span-1-of-' . $cols_per_row[0] . '">';

		}

		if ( $total_tux_widgets < 1 ) {
			return $params;
		}

		if ( strpos( $params[0]['widget_id'], 'tux_row' ) !== false || $cur_col_in_row > 12 ) {
			$cur_row++;
			$before = $before . '</div></div><div class="tux-row"><div class="tux-col tux-span-1-of-' . $cols_per_row[$cur_row] . '">';
			$cur_col_in_row = 1;
			$params[0]['before_widget'] = '';
			$params[0]['after_widget'] = '';
		} elseif ( strpos( $params[0]['widget_id'], 'tux_col' ) !== false ) {
			$before = $before . '</div><div class="tux-col tux-span-1-of-' . $cols_per_row[$cur_row] . '">';
			$cur_col_in_row++;
			$params[0]['before_widget'] = '';
			$params[0]['after_widget'] = '';
		}

		$params[0]['before_widget'] = $before . $params[0]['before_widget'];

		if ( $cur_widget == $total_widgets ) {
			$params[0]['after_widget'] .= '</div></div>';
		}
		$cur_widget++;

		return $params;

	}

}

/** Instantiate the plugin class. */
$tux_rwc = TuxedoRespWidgetColumns::get_instance();

/**
 * Tuxedo Widget Row widget class.
 *
 * @since 1.0.0
 */
class TuxedoWidgetRow extends WP_Widget {

	public function __construct() {

		parent::__construct( 'tux_row', __( 'Layout: New Column on New Row', 'tuxedo-rwc' ), array(
			'classname'   => 'tux_widget_row',
			'description' => __( 'Create a new row and column of widgets.', 'tuxedo-rwc' ),
		) );

	}

	public function widget( $args, $instance ) { echo $args['before_widget'] . $args['after_widget']; }

	public function update( $new_instance, $old_instance ) { return $new_instance; }

	public function form( $instance ) { ?><br/><?php }

}

/**
 * Tuxedo Widget Column widget class.
 *
 * @since 1.0.0
 */
class TuxedoWidgetColumn extends WP_Widget {

	public function __construct() {

		parent::__construct( 'tux_col', __( 'Layout: New Column', 'tuxedo-rwc' ), array(
			'classname'   => 'tux_widget_col',
			'description' => __( 'Create a new column of widgets.', 'tuxedo-rwc' ),
		) );

	}

	public function widget( $args, $instance ) { echo $args['before_widget'] . $args['after_widget']; }

	public function update( $new_instance, $old_instance ) { return $new_instance; }

	public function form( $instance ) { ?><br/><?php }

}