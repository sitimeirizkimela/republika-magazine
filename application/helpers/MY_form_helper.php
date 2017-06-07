<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    /**
     * Form Errors Array
     *
     * Returns the errors array for fields that did not pass form validation.
     * The return array is keyed by field names of the fields that did not pass form validation.
     * This function is very useful when validating ajax forms.
     *
     * @access    public
     * @return    array
     */

    if (!function_exists('form_errors_array')) {
        function form_errors_array()
        {
            if (FALSE === ($OBJ =& _get_validation_object())) {
                return array();
            }

            return $OBJ->_error_array;
        }
    }

/* End of file MY_form_helper.php */
/* Location: ./application/helpers/MY_form_helper.php */