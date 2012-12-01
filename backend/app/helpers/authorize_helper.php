<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *	管理员权限 辅助函数集
 *
 *
 *
 *
 *	@package	Anying
 *	@subpackage Helpers
 *	@category	authorize_helper
 *	@link
 *
 */

// ---------------------------------------------------------------------------


if( ! function_exists('check_acl') )
{
	/**
	 * 检查是否有权限
	 * 
	 * @param string $type		操作类型
	 * @param string $action	操作
	 * @param string $redirect	是否跳转到错误页
	 * @param string $msg		提示信息
	 * @return bool|void
	 */
    function check_acl($type, $action = NULL, $redirect = TRUE, $msg = NULL)
    {
       $CI = &get_instance();
	   
	   if( ! $redirect )
	   {
		   return $CI->authorize->allow($type, $action);
	   }
	   
	   if( ! $CI->authorize->allow($type, $action) )
	   {	   
			$CI->session->set_flashdata('flash_msg',serialize(array(
				'type'	=> 'error',
				'msg'	=> $msg ? $msg : '您没有该操作权限，如有需要请联系管理员'
			)));	   

			go_back();	  
	   }
 
    }

}

/* End of file authorize_helper.php */

/* Location: ./helpers/authorize_helper.php */