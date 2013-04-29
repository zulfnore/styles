<?php

abstract class Styles_Control {

	var $group_priority = 1;

	/**
	 * Default setting value 
	 */
	var $default = '';

	// From $element
	var $selector;
	var $type;    
	var $label;   
	var $priority;
	var $id;      
	var $setting; 
	
	/**
	 * Template CSS for $selector and $value to be filled into
	 *
	 * @var string
	 **/
	public $template;

	public function __construct( $group, $element ) {
		$this->group = $group;
		$this->element = $element;

		$this->selector = $element['selector'];
		$this->type     = $element['type'];
		$this->label    = $element['label'];
		$this->priority = $element['priority'];
		$this->id       = $this->get_element_id();
		$this->setting  = $this->get_setting_id();

		if ( !empty( $element['template'] ) ) {
			$this->template = $element['template'];
		}

		if ( empty( $this->label) ) {
			$this->label = $this->selector;
		}

		if ( !empty( $element['suffix'] ) ) {
			$this->suffix = $element['suffix'];
		}
		if ( !empty( $this->suffix ) ) {
			$this->label .=  '::' . $this->suffix;
		}

		

		if ( empty( $this->selector ) ) { return false; }

		// postMessage javascript callback
		if ( 'postMessage' == $this->get_transport() ) {
			add_filter( 'styles_customize_preview', array( $this, 'post_message' ) );
		}

	}

	/**
	 * Register control and setting with $wp_customize
	 * @return null
	 */
	abstract public function add_item();

	/**
	 * @param array $element Values related to this control, like CSS selector and control type
	 * @return string Unique, sanatized ID for this element based on label and type
	 */
	public function get_element_id() {
		$key = trim( sanitize_key( $this->element['label'] . '_' . $this->element['type'] ), '_' );
		return str_replace( '-', '_', $key );
	}

	/**
	 * @param string $group Name of Customizer group this element is in
	 * @param string $id unique element ID
	 * @return string $setting_id unique setting ID for use in form input names
	 */
	public function get_setting_id() {
		$group = $this->group;
		$id = str_replace( '-', '_', trim( $this->id, '_' ) );

		$setting_id = Styles_Helpers::get_option_key() . "[$group][$id]";

		return $setting_id;
	}

	public function get_element_setting_value() {
		$settings = get_option( Styles_Helpers::get_option_key() );

		$group_id = Styles_Helpers::get_group_id( $this->group );

		$value = $settings[ $group_id ][ $this->id ];

		if ( !empty( $value ) ) {
			return $value;
		}else {
			return false;
		}
	}

	public function get_transport() {
		$transport = 'refresh';

		if ( 
			method_exists( $this, 'post_message' ) 
			&& empty( $this->element['template'] ) // No custom CSS template set
			&& false == strpos( $this->selector, ':' ) // jQuery doesn't understand pseudo-selectors like :hover and :focus
		) {
			// postMessage supported
			$transport = 'postMessage';
		}

		return $transport;
	}

	public function apply_template( $args ) {
		$template = $args['template'];
		unset( $args['template'] );

		foreach ( $args as $key => $value ) {
			$template = str_replace( '$'.$key, $value, $template );
		}

		$template = str_replace( '$selector', $this->selector, $template );

		return $template;
	}

	/**
	 * Convert CSS selector into jQuery-compatible selector
	 */
	public function jquery_selector() {
		$selector = str_replace( "'", "\'", $this->selector );

		return $selector;
	}

}