<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *	anying: 安盈
 *
 *	
 *
 *	@package	Anying
 *  @author     Yoozi Dev Team
 *	@copyright	Copyright (c) 2012, Yoozi.cn
 *	@since		Version 1.0.0
 *	@filesource
 */

/**
 * 	权限管理
 *
 *
 * 	@package	Anying
 * 	@subpackage Libraries
 * 	@category	Authorize
 * 	@link
 *
 */

class Authorize
{
	// 权限操作选项
	public $actions = array();
	
	public function __construct($config = array())
	{
			$this->actions = $config;
	}
	
	// 获取权限操作选项
	public function get_actions()
	{
		return $this->actions;
	}
	
	/**
	 * 是否有权限
	 * 
	 * @param string $module	操作模块
	 * @param string $action	操作类型
	 * @return bool
	 */
	public function allow($module, $action)
	{
		CI()->load->model('groups_mdl');
		
		$group_id = CI()->session->userdata('group_id');
		
		$info = CI()->groups_mdl->get_by_id($group_id);
		
		// 当前管理员拥有的权限
		$allow_actions = $info['actions'];
		
		// 是超级管理员?
		if($allow_actions == 'all')
		{
			return TRUE;
		}
		
		$allow_actions = explode('|', $allow_actions);
		
		// 没有指定操作类型?
		if( ! $action )
		{
			return $this->_has_action_in($module, $allow_actions);
		}
				
		// 有指定权限?
		if(in_array(sprintf('%s_%s', $module, $action), $allow_actions))
		{
			return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	 * 是否有属于该模块的操作类型
	 * 
	 * @param string	$module			操作模块
	 * @param array		$allow_actions	管理员拥有的权限
	 * @return bool
	 */
	private function _has_action_in($module, $allow_actions)
	{
		// 没有该模块?
		if( ! isset($this->actions[$module]) )
		{
			return FALSE;
		}
		
		foreach($this->actions[$module]['actions'] as $key => $action)
		{
			if(in_array(sprintf('%s_%s', $module, $key), $allow_actions))
			{
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
}

/* End of file Authorize.php */
/* Location: ./library/Authorize.php */