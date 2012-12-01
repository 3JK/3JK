<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Loader extends CI_Loader {}
 
 /**
 * Global CI super object for PHP5 ONLY
 *
 * Example:
 * CI()->db->get('table');
 *
 * @staticvar	object	$ci
 * @return		object
 */
function CI()
{
    return CI_Controller::get_instance();
}
