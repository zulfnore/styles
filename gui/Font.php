<?php

/**
 * Attaches a font picker to variables with a hex font format
 * 
 * @since 0.1
 * @package StormStyles
 * @author pdclark
 **/
class StormStyles_Extension_Font extends StormStyles_Extension_Observer {
	
	var $families = array(
		'delete'			=>	'',
		'Arial'				=>	'Arial, Helvetica, sans-serif',
		'Times'				=>	'Times, Georgia, serif',
		'Verdana'			=>	'Verdana, Tahoma, sans-serif',
		'Century Gothic'	=>	'"Century Gothic", Helvetica, Arial, sans-serif',
		'Helvetica'			=>	'Helvetica, Arial, sans-serif',
		'Georgia'			=>	'Georgia, Times, serif',
		'Lucida Grande'		=>	'"Lucida Grande","Lucida Sans Unicode",Tahoma,Verdana,sans-serif',
		'Palatino'			=>	'Palatino, Georgia, serif',
		'Garamond' 			=>	'Garamond, Palatino, Georgia, serif',
		'Tahoma'   			=>	'Tahoma, Verdana, Helvetica, sans-serif',
		'Courier'  			=>	'Courier, monospace',
		'Trebuchet MS'		=>	'"Trebuchet MS", Tahoma, Helvetica, sans-serif',
		'Comic Sans MS'		=>	'"Comic Sans MS", Arial, sans-serif',
		'Bookman'			=>	'Bookman, Palatino, Georgia, serif',
	);
	
	var $weights = array(
		'bold',
		'normal',
	);
	
	var $styles = array(
		'italic',
		'normal',
	);
	
	var $transforms = array(
		'uppercase',
		'lowercase',
		'none',
	);
	
	var $line_heights = array(
		'1',
		'1.25',
		'1.5',
		'1.75',
		'2',
	);
	
	function __construct( $args = array(), Scaffold_Extension_Observable $observable = null ) {
		parent::__construct( $args, $observable );
	}
	

	/**
	 * Set variables with correct formatting
	 * 
	 * @since 0.1
	 * @return string
	 **/
	function set( $variable, $input, $context = 'default' ) {
		if ( empty( $input ) ) {
			$this->values = array();
			return;
		}
		
		$this->values['font_size'] = preg_replace('/[^0-9\.]/', '', $input['font_size'] ); // Numbers only
		
		if ( array_key_exists( $input['font_family'], $this->families ) ) {
			$this->values['font_family'] = $input['font_family'];
		}
		
		if ( in_array( $input['font_weight'], $this->weights ) ) {
			$this->values['font_weight'] = $input['font_weight'];
		}
		
		if ( in_array( $input['font_style'], $this->styles ) ) {
			$this->values['font_style'] = $input['font_style'];
		}
		
		if ( in_array( $input['text_transform'], $this->transforms ) ) {
			$this->values['text_transform'] = $input['text_transform'];
		}
		
		if ( in_array( $input['line_height'], $this->line_heights ) ) {
			$this->values['line_height'] = $input['line_height'];
		}
	}
	
	function output() {
		$font_family = $this->value('form', 'font_family');
		?>
			<input name="<?php echo $this->form_name ?>[font_size]" class="pds_font_input" type="text" id="<?php echo $this->form_id ?>_font_size" value="<?php echo $this->value('form', 'font_size'); ?>" size="2" maxlength="4" />px
			
			<select name="<?php echo $this->form_name ?>[font_family]" class="pds_font_select">
				<option value="delete">Font Family</option>
				<?php foreach ($this->families as $name => $value ) : if (empty($value)) continue; ?>
				<option value='<?php echo $name ?>' <?php if ( $name == $font_family ) echo 'selected'; ?> ><?php echo $name ?></option>
				<?php endforeach; ?>
			</select>
			
			<a href="#" title="Bold" class="value-toggle font-weight font-weight-<?php echo $this->value('form', 'font_weight'); ?>" data-type="font-weight" data-options='<?php echo json_encode( $this->weights ) ?>' >Weight</a>
			<input name="<?php echo $this->form_name ?>[font_weight]" class="pds_font_input" type="hidden" id="<?php echo $this->form_id ?>_font_weight" value="<?php echo $this->value('form', 'font_weight'); ?>" />
			
			<a href="#" title="Italic" class="value-toggle font-style font-style-<?php echo $this->value('form', 'font_style'); ?>" data-type="font-style" data-options='<?php echo json_encode( $this->styles ) ?>' >Style</a>
			<input name="<?php echo $this->form_name ?>[font_style]" class="pds_font_input" type="hidden" id="<?php echo $this->form_id ?>_font_style" value="<?php echo $this->value('form', 'font_style'); ?>" />
			
			<a href="#" title="Case" class="value-toggle text-transform text-transform-<?php echo $this->value('form', 'text_transform'); ?>" data-type="text-transform" data-options='<?php echo json_encode( $this->transforms ) ?>' >Case</a>
			<input name="<?php echo $this->form_name ?>[text_transform]" class="pds_font_input" type="hidden" id="<?php echo $this->form_id ?>_text_transform" value="<?php echo $this->value('form', 'text_transform'); ?>" />
			
			<a href="#" title="Leading" class="value-toggle line-height line-height-<?php echo str_replace('.', '', $this->value('form', 'line_height') ); ?>" data-type="line-height" data-options='<?php echo json_encode( $this->line_heights ) ?>' >Leading</a>
			<input name="<?php echo $this->form_name ?>[line_height]" class="pds_font_input" type="hidden" id="<?php echo $this->form_id ?>_line_height" value="<?php echo $this->value('form', 'line_height'); ?>" />
		<?php
	}
	
} // END class