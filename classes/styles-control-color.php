<?php

class Styles_Control_Color extends Styles_Control {
	var $suffix = 'text color';
	var $template = '$selector { color: $value; }';

	public function __construct( $group, $element ) {
		parent::__construct( $group, $element );
	}

	/**
	 * Register item with $wp_customize
	 */
	public function add_item() {
		global $wp_customize;

		$wp_customize->add_setting( $this->setting, $this->get_setting_args() );

		$control = new WP_Customize_Color_Control(
			$wp_customize,
			Styles_Helpers::get_control_id( $this->id ),
			$this->get_control_args()
		);
		$wp_customize->add_control( $control );
	}

	/**
	 * Return CSS based on setting value
	 */
	public function get_css(){
		$selector = $this->selector;
		$value = $this->get_element_setting_value();

		$css = '';
		if ( $value ) {
			$args = array(
				'template' => $this->template,
				'value' => $value,
			);
			$css = $this->apply_template( $args );
		}

		// Filter effects final CSS output, but not postMessage updates
		return apply_filters( 'styles_css_color', $css );
	}

	public function post_message( $js ) {
		$js .= str_replace(
			array( '@setting@', '@selector@' ),
			array( $this->setting, $this->jquery_selector() ),
			file_get_contents( STYLES_DIR . '/js/post-message-part-color.js' )
		);

		return $js . PHP_EOL;
	}

}