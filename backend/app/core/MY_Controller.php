<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	public $controller;
	public $method;

	// data shared within a controller
	protected $data;

	/**
	 * Construct
	 *
	 * @access public
	 * @var		array
	 */
	public function __construct()
	{
		parent::__construct();

		// Make module, controller and methods accessible throughout the CI instance
        $this->controller	= $this->router->fetch_class();
        $this->method		= $this->router->fetch_method();

		$this->data['controller']	= $this->controller;
		$this->data['method']		= $this->method;

		// Set main layout
		$this->template->set_layout('default');

		// Enable profiler for optimization purpose if we are in 'local' OR 'dev' environment
		if(ENVIRONMENT == 'development' AND ! $this->input->is_ajax_request())
		{
			$this->output->enable_profiler(TRUE);
		}
	}
}

class MY_ADM_Contorller extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		/** 当前是否登录 */
		$this->_check_login();
		
		// Set main layout
		$this->template->set_layout('adm_default');
	}

	/**
	 *
	 *	登录检查
	 *
	 * @access public
	 * @var		array
	 */
	private function _check_login()
	{
		// 当前模块是否不需要登录
		if(in_array($this->method, array('login')))
		{
			return TRUE;
		}
		// 用户未登录？
		if( ! $this->session->userdata('userid'))
		{
			redirect('admin/home/login');
		}

		// Pass, Here we go...
		return TRUE;
	}

	/**
	 * 设置flash msg
	 *
	 * @param string $type  信息类型:[ok,error,info]
	 * @param string $msg
	 *
	 */
	protected function set_msg($type,$msg)
	{
		$msg = array(
				'type' => $type,
				'msg' => $msg
		);

		$this->session->set_flashdata('flash_msg',serialize($msg));
	}
}