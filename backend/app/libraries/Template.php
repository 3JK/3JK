<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *	anying: 安盈
 *
 *
 *  @package    Anying
 *  @author     Yoozi Dev Team
 *  @copyright  Copyright (c) 2012, Yoozi.cn
 *  @since      Version 1.0.0
 *	@filesource
 */

// ------------------------------------------------------------------------

/**
 *	模板类
 *
 *		使系统更好的支持面包屑效果，布局和局部页面。
 *	
 *
 *
 *	@package	Anying
 *	@subpackage Libraries
 *	@category	Template
 *	@author		Saturn <huyanggang@gmail.com>
 *	@link	
 *		
 */
 
class Template {
	/**
	 * Data to parse
	 *
	 * @access private
	 * @var array
	 */
	private $_data = array();
	
	/**
	 * Current controller
	 *
	 * @access private
	 * @var string
	 */
	private $_controller = '';

	/**
	 * Current method
	 *
	 * @access private
	 * @var string
	 */
	private $_method = '';

	/**
	 * Page title separator
	 *
	 * @access private
	 * @var string
	 */
	private $_page_title_separator = ' &ndash; ';
	
	/**
	 * Page breadcrumbs for generating navigation
	 *
	 * @access private
	 * @var array
	 */
	private $_page_breadcrumbs = array();

	/**
	 * Page title
	 *
	 * @access private
	 * @var array
	 */
	private $_page_title = '';
	
	/**
	 * Page metadata
	 *
	 * @access private
	 * @var array
	 */
	private $_page_metadata = array();

	/**
	 * Partial pages
	 *
	 * @access private
	 * @var array
	 */
	private $_partials = array();

	/**
	 * Whether or not use a layout?
	 *
	 * @access private
	 * @var bool
	 */
	private $_layout = FALSE; // By default, dont wrap the view with anything

	/**
	 * Constructer
	 * 
	 * @access private
	 * @param	array  $config 
	 * @return string
	 */
	public function __construct()
	{
		// Work out the controller and method
		$this->_controller	= CI()->router->fetch_class();
		$this->_method 		= CI()->router->fetch_method();
		
		log_message('debug', 'Template class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Magic Get function to get data
	 *
	 * @access	  public
	 * @param		string
	 * @return	  mixed
	 */
	public function __get($name)
	{
		return isset($this->_data[$name]) ? $this->_data[$name] : NULL;
	}

	// --------------------------------------------------------------------

	/**
	 * Magic Set function to set data
	 *
	 * @access	  public
	 * @param		string
	 * @return	  mixed
	 */
	public function __set($name, $value)
	{
		$this->_data[$name] = $value;
	}

	// --------------------------------------------------------------------

	/**
	 * Build the template
	 *
	 * @access		public
	 * @param		string	$view Body template view of a layout (.html) OR single normal view page (.php)
	 * @param		array	$data Data required for parsing
	 * @return		bool	$return Output to the browser (FALSE) OR return a string (TRUE) 
	 * @return		void
	 */
	public function build($view = '', $data = array(), $return = FALSE)
	{
		// Set whatever values are given. These will be available to all view files
		is_array($data) OR $data = array($data);
		
		// Merge in what we already have with the specific data
		$this->_data = array_merge($this->_data, $data);

		// If there is no title, we try to guess a title for this page
		if(empty($this->_page_title))
		{
			$this->_page_title = $this->_guess_title();
		}
		
		// Output template variables to the template
		$template['page_title']	= $this->_page_title;
		$template['breadcrumbs'] = $this->_page_breadcrumbs;
		$template['metadata']	= $this->_page_metadata ? implode("\r\n", $this->_page_metadata) : '';
		$template['partials']	= array();
		
		// Assign by reference, as all loaded views will need access to partials
		$this->_data['template'] = & $template;
		
		foreach($this->_partials as $name => $partial)
		{
			// If its a view, use details to find it.
			if (isset($partial['view']))
			{
				$template['partials'][$name] = CI()->load->view($partial['view'], $this->_data, TRUE);
			}
			// Otherwise, it is just a string
			else
			{
				$template['partials'][$name] = $partial['string'];
			}
		}
		
		// Disable sodding IE7's constant cacheing!!
		CI()->output->set_header('Expires: Sat, 01 Jan 2000 00:00:01 GMT');
		CI()->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		CI()->output->set_header('Cache-Control: post-check=0, pre-check=0, max-age=0');
		CI()->output->set_header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
		CI()->output->set_header('Pragma: no-cache');
		
		// Load the requested view
		$this->_body = CI()->load->view($view, $this->_data, TRUE);
		
		// Want this file wrapped with a layout file?
		if($this->_layout)
		{
			$template['body'] = $this->_body;
			$this->_body = CI()->load->view($this->_layout, $this->_data, TRUE);
		}
		
		// Want it returned or output to browser?
		if(!$return)
		{
			// Send it to output
			CI()->output->set_output($this->_body);
		}

		return $this->_body;
	}

	// --------------------------------------------------------------------

	/**
	 * Set the title of the page
	 *
	 * @access	  public
	 * @param	 string
	 * @return	  void
	 */
	public function title()
	{
		// If we have some segments passed
		if($segments = & func_get_args())
		{
			$this->_page_title = implode($this->_page_title_separator, $segments);
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Put extra javascipt, css, meta tags, etc before all other head data
	 *
	 * @access	  public
	 * @param		 string	$line	The line being added to head
	 * @return	  void
	 */
	public function prepend_metadata($line)
	{
		array_unshift($this->_page_metadata, $line);
		return $this;
	}

	// --------------------------------------------------------------------   

	/**
	 * Put extra javascipt, css, meta tags, etc after other head data
	 *
	 * @access	  public
	 * @param		 string	$line	The line being added to head
	 * @return	  void
	 */
	public function append_metadata($line)
	{
		array_push($this->_page_metadata, $line);
		return $this;
	}

	// --------------------------------------------------------------------


	/**
	 * Set metadata for output later
	 *
	 * @access	  public
	 * @param		string	$name		keywords, description, etc
	 * @param		string	$content	The content of meta data
	 * @param		string	$type		Meta-data comes in a few types, links for example
	 * @return	  void
	 */
	public function set_metadata($name, $content, $type = 'meta')
	{
		$name = htmlspecialchars(strip_tags($name));
		$content = htmlspecialchars(strip_tags($content));

		// Keywords with no comments? ARG! comment them
		if($name == 'keywords' && !strpos($content, ','))
		{
			$content = preg_replace('/[\s]+/', ', ', trim($content));
		}

		switch($type)
		{
			case 'meta':
				$this->_page_metadata[$name] = '<meta name="'.$name.'" content="'.$content.'" />';
			break;

			case 'link':
				$this->_page_metadata[$content] = '<link rel="'.$name.'" href="'.$content.'" />';
			break;
		
			case 'js':
				$this->_page_metadata[$content] = '<script type="text/javascript" src="' . $content . '"></script>';
			break;
		}

		return $this;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Set the current layout
	 *
	 * @access	public
	 * @param	string	$view
	 * @return	void
	 */
	public function set_layout($view = '')
	{
		$this->_layout = 'layouts/' . $view;
		return $this;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Set a view partial
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	boolean
	 * @return	void
	 */
	public function set_partial($name, $view)
	{
		$this->_partials[$name] = array('view' => $view);
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Set a view partial
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	boolean
	 * @return	void
	 */
	public function inject_partial($name, $string)
	{
		$this->_partials[$name] = array('string' => $string);
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Helps build custom breadcrumb trails
	 *
	 * @access	public
	 * @param	string	$name		What will appear as the link text
	 * @param	string	$url_ref	The URL segment
	 * @return	void
	 */
	public function set_breadcrumb($name, $uri = '')
	{
		$this->_page_breadcrumbs[] = array('name' => $name, 'uri' => $uri );
		return $this;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 *	Guess page title
	 * 
	 *
	 * @access	  public
	 * @param		 string	$view
	 * @return	  array
	 */
	private function _guess_title()
	{
		CI()->load->helper('inflector');

		// Obviously no title, lets get making one
		$title_parts = array();

		// If the method is something other than index, use that
		if($this->_method != 'index')
		{
			$title_parts[] = $this->_method;
		}

		// Make sure controller name is not the same as the method name
		if( ! in_array($this->_controller, $title_parts))
		{
			$title_parts[] = $this->_controller;
		}

		// Glue the title pieces together using the title separator setting
		$title = humanize(implode($this->_page_title_separator, $title_parts));

		return $title;
	}
}

/* End of file Template.php */
/* Location: ./app/libraries/Template.php */