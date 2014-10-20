<?php

class acf_field_post_type_chooser extends acf_field {
	
	
	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/

    function __construct() {

        /*
		*  name (string) Single word, no spaces. Underscores allowed
		*/

        $this->name = 'post-type-chooser`';


        /*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/

        $this->label = __('Post Type Chooser', 'acf-post-type-chooser');


        /*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/

        $this->category = 'choice';


        /*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/

        $this->defaults = array(
            'choices'		=> array(),
            'allow_null' 	=> 0,
            'multiple'		=> 0,
            'ui'            => 0,
            'ajax'          => 0,
        );


        /*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('post-type-chooser', 'error');
		*/

        $this->l10n = array(
            'error'	=> __('Error! Please enter a higher value', 'acf-post-type-chooser'),
        );


        // do not delete!
        parent::__construct();

    }
	
	
	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

    function render_field_settings( $field ) {

        /*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/

        // post_type
        acf_render_field_setting( $field, array(
            'label'			=> __('Choose Allowed Post Type','post-type-chooser'),
            'instructions'	=> '',
            'type'			=> 'select',
            'name'			=> 'choices',
            'choices'		=> acf_get_pretty_post_types(),
            'multiple'		=> 1,
            'ui'			=> 1,
            'allow_null'	=> 1,
            'placeholder'	=> __("All post types",'post-type-chooser'),
        ));

        // allow_null
        acf_render_field_setting( $field, array(
            'label'			=> __('Allow Null?','post-type-chooser'),
            'instructions'	=> '',
            'type'			=> 'radio',
            'name'			=> 'allow_null',
            'choices'		=> array(
                1				=> __("Yes",'post-type-chooser'),
                0				=> __("No",'post-type-chooser'),
            ),
            'layout'	=>	'horizontal',
        ));


        // multiple
        acf_render_field_setting( $field, array(
            'label'			=> __('Select multiple values?','post-type-chooser'),
            'instructions'	=> '',
            'type'			=> 'radio',
            'name'			=> 'multiple',
            'choices'		=> array(
                1				=> __("Yes",'post-type-chooser'),
                0				=> __("No",'post-type-chooser'),
            ),
            'layout'	=>	'horizontal',
        ));
    }
	
	
	
	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

    function render_field( $field ) {


        // convert value to array
        $field['value'] = acf_force_type_array($field['value']);


        // add empty value (allows '' to be selected)
        if( empty($field['value']) ){

            $field['value'][''] = '';

        }


        // placeholder
        if( empty($field['placeholder']) ) {

            $field['placeholder'] = __("Select",'acf');

        }


        // vars
        $atts = array(
            'id'				=> $field['id'],
            'class'				=> $field['class'] . ' js-post-types-select2',
            'name'				=> $field['name'],
            'data-ui'			=> $field['ui'],
            'data-ajax'			=> $field['ajax'],
            'data-multiple'		=> $field['multiple'],
            'data-placeholder'	=> $field['placeholder'],
            'data-allow_null'	=> $field['allow_null']
        );



        // hidden input
        if( $field['ui'] ) {

            acf_hidden_input(array(
                'type'	=> 'hidden',
                'id'	=> $field['id'],
                'name'	=> $field['name'],
                'value'	=> implode(',', $field['value'])
            ));

        } elseif( $field['multiple'] ) {

            acf_hidden_input(array(
                'type'	=> 'hidden',
                'name'	=> $field['name'],
            ));

        } 


        // ui
        if( $field['ui'] ) {

            $atts['disabled'] = 'disabled';
            $atts['class'] .= ' acf-hidden';

        }


        // multiple
        if( $field['multiple'] ) {

            $atts['multiple'] = 'multiple';
            $atts['size'] = 5;
            $atts['name'] .= '[]';

        } 


        // special atts
        foreach( array( 'readonly', 'disabled' ) as $k ) {

            if( !empty($field[ $k ]) ) {

                $atts[ $k ] = $k;
            }

        }


        // vars
        $els = array();
        $choices = array();


        // loop through values and add them as options
        if( !empty($field['choices']) ) {

            foreach( $field['choices'] as $k => $v ) {

                if( is_array($v) ){

                    // optgroup
                    $els[] = array( 'type' => 'optgroup', 'label' => $k );

                    if( !empty($v) ) {

                        foreach( $v as $k2 => $v2 ) {

                            $els[] = array( 'type' => 'option', 'value' => $v2, 'label' => $v2, 'selected' => in_array($v2, $field['value']) );

                            $choices[] = $k2;
                        }

                    }

                    $els[] = array( 'type' => '/optgroup' );

                } else {

                    $els[] = array( 'type' => 'option', 'value' => $v, 'label' => $v, 'selected' => in_array($v, $field['value']) );

                    $choices[] = $k;

                }

            }

        }

        // null
        if( $field['allow_null'] ) {

            array_unshift( $els, array( 'type' => 'option', 'value' => '', 'label' => '- ' . $field['placeholder'] . ' -' ) );

        }		


        // html
        echo '<select ' . acf_esc_attr( $atts ) . '>';	


        // construct html
        if( !empty($els) ) {

            foreach( $els as $el ) {

                // extract type
                $type = acf_extract_var($el, 'type');


                if( $type == 'option' ) {

                    // get label
                    $label = acf_extract_var($el, 'label');


                    // validate selected
                    if( acf_extract_var($el, 'selected') ) {

                        $el['selected'] = 'selected';

                    }


                    // echo
                    echo '<option ' . acf_esc_attr( $el ) . '>' . $label . '</option>';

                } else {

                    // echo
                    echo '<' . $type . ' ' . acf_esc_attr( $el ) . '>';

                }


            }

        }


        echo '</select>';
    }

		
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function input_admin_enqueue_scripts() {
		
		$dir = plugin_dir_url( __FILE__ );
		
		
		// register & include JS
		wp_register_script( 'acf-input-post-type-chooser', "{$dir}js/input.js" );
		wp_enqueue_script('acf-input-post-type-chooser');
		
		
		// register & include CSS
		wp_register_style( 'acf-input-post-type-chooser', "{$dir}css/input.css" ); 
		wp_enqueue_style('acf-input-post-type-chooser');	
		
	}
	
	
	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
		
	function input_admin_head() {
	
		
		
	}
	
	*/
	
	
	/*
   	*  input_form_data()
   	*
   	*  This function is called once on the 'input' page between the head and footer
   	*  There are 2 situations where ACF did not load during the 'acf/input_admin_enqueue_scripts' and 
   	*  'acf/input_admin_head' actions because ACF did not know it was going to be used. These situations are
   	*  seen on comments / user edit forms on the front end. This function will always be called, and includes
   	*  $args that related to the current screen such as $args['post_id']
   	*
   	*  @type	function
   	*  @date	6/03/2014
   	*  @since	5.0.0
   	*
   	*  @param	$args (array)
   	*  @return	n/a
   	*/
   	
   	/*
   	
   	function input_form_data( $args ) {
	   	
		
	
   	}
   	
   	*/
	
	
	/*
	*  input_admin_footer()
	*
	*  This action is called in the admin_footer action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_footer)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
		
	function input_admin_footer() {
	
		
		
	}
	
	*/
	
	
	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
	
	function field_group_admin_enqueue_scripts() {
		
	}
	
	*/

	
	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
	
	function field_group_admin_head() {
	
	}
	
	*/


	/*
	*  load_value()
	*
	*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/
	
	/*
	
	function load_value( $value, $post_id, $field ) {
		
		return $value;
		
	}
	
	*/
	
	
	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is saved in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/
	
	/*
	
	function update_value( $value, $post_id, $field ) {
		
		return $value;
		
	}
	
	*/
	
	
	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/
		
	/*
	
	function format_value( $value, $post_id, $field ) {
		
		// bail early if no value
		if( empty($value) ) {
		
			return $value;
			
		}
		
		
		// apply setting
		if( $field['font-size'] > 12 ) { 
			
			// format the value
			// $value = 'something';
		
		}
		
		
		// return
		return $value;
	}
	
	*/
	
	
	/*
	*  validate_value()
	*
	*  This filter is used to perform validation on the value prior to saving.
	*  All values are validated regardless of the field's required setting. This allows you to validate and return
	*  messages to the user if the value is not correct
	*
	*  @type	filter
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$valid (boolean) validation status based on the value and the field's required setting
	*  @param	$value (mixed) the $_POST value
	*  @param	$field (array) the field array holding all the field options
	*  @param	$input (string) the corresponding input name for $_POST value
	*  @return	$valid
	*/
	
	/*
	
	function validate_value( $valid, $value, $field, $input ){
		
		// Basic usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = false;
		}
		
		
		// Advanced usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = __('The value is too little!','acf-post-type-chooser'),
		}
		
		
		// return
		return $valid;
		
	}
	
	*/
	
	
	/*
	*  delete_value()
	*
	*  This action is fired after a value has been deleted from the db.
	*  Please note that saving a blank value is treated as an update, not a delete
	*
	*  @type	action
	*  @date	6/03/2014
	*  @since	5.0.0
	*
	*  @param	$post_id (mixed) the $post_id from which the value was deleted
	*  @param	$key (string) the $meta_key which the value was deleted
	*  @return	n/a
	*/
	
	/*
	
	function delete_value( $post_id, $key ) {
		
		
		
	}
	
	*/
	
	
	/*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0	
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/
	
	/*
	
	function load_field( $field ) {
		
		return $field;
		
	}	
	
	*/
	
	
	/*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/
	
	/*
	
	function update_field( $field ) {
		
		return $field;
		
	}	
	
	*/
	
	
	/*
	*  delete_field()
	*
	*  This action is fired after a field is deleted from the database
	*
	*  @type	action
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	n/a
	*/
	
	/*
	
	function delete_field( $field ) {
		
		
		
	}	
	
	*/
	
	
}


// create field
new acf_field_post_type_chooser();

?>
