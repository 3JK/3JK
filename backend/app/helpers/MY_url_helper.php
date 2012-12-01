<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
 *	url 拓展助手函数
 *
 *	
 *
 *	@package	Anying
 *	@subpackage Helpers
 *	@category	Helpers
 *	@author		Saturn <huyanggang@gmail.com>
 *	@link	
 *		  
 */

// ------------------------------------------------------------------------


/**
 * 重写系统的current_url函数
 *
 *
 * @access	public
 * @param	$hold_args	是否返回$_GET参数
 * @param	$unsets		需要销毁的参数
 * @return	string
 */
if ( ! function_exists('current_url'))
{
	function current_url($hold_args = FALSE, $unsets = array())
	{
		$CI =& get_instance();
		
		$url = $CI->config->site_url($CI->uri->uri_string());
		
		$args = $_GET;
		
		// 输入地址不带$_GET参数?
		if( ! $hold_args)
		{
			return $url;
		}
		
		// 没有$_GET参数?
		if( ! $args)
		{
			return $url . '?';
		}
		
		if( ! is_array($unsets))
		{
			$unsets = array($unsets);
		}
		
		foreach($unsets as $key)
		{
			if( ! isset($args[$key]))
			{
				continue;
			}
			
			unset($args[$key]);
		}
		
		return $url . '?' . http_build_query($args);
	}
}
/**
 * 返回来路
 *
 *	 仅作用在系统内部URL之间的跳转，外部跳转请用redirect()
 *
 * @access public
 * @param  string $suffix  附加后缀，比如 &key=val#fragment-1
 * @param  string $default 默认来路
 * @return void
 */
if ( ! function_exists('go_back'))
{
	define('BASE_URL', CI()->config->item('base_url'));
	
	function go_back($suffix = NULL, $default = BASE_URL)
	{
		 // 获取来源 URL
		 $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	
		 //判断来源
		 if (!empty($referer)) 
		 {
			 // 检查来源 URL 是否来自系统本身
			 if(parse_url($referer, PHP_URL_HOST) !== parse_url(BASE_URL, PHP_URL_HOST))
			 {
			 	show_error('Invalid Referrer');
			 }
				 
				 // 存在 URL 后缀
			 if (!empty($suffix)) 
			 {
				 $parts = parse_url($referer);
				 $myParts = parse_url($suffix);
				 
				 if (isset($myParts['fragment'])) 
				 {
					 $parts['fragment'] = $myParts['fragment'];
				 }
				 
				 if (isset($myParts['query'])) 
				 {
					 $args = array();
					 if (isset($parts['query'])) 
					 {
						 parse_str($parts['query'], $args);
					 }
				 
					 parse_str($myParts['query'], $currentArgs);
					 $args = array_merge($args, $currentArgs);
					 $parts['query'] = http_build_query($args);
				 }
				 
				 $referer = build_url($parts);
			 }
			 
			 redirect($referer);
		 } 
		 else if (!empty($default)) 
		 {
			 redirect($default);
		 }
	}
}

// --------------------------------------------------------------------

/**
 * 带消息flash的跳转
 *
 * 仅支持系统内部url间的跳转
 *
 * @param  string  $redir_to
 * @param  string  $msg_type
 * @param  string  $msg
 * @return boolean
 */
if ( ! function_exists('flash_redir')) 
{
	function flash_redir($redir_to, $msg_type, $msg)
	{
		CI()->session->set_flashdata($msg_type, $msg);
		redirect(site_url($redir_to));
	}
}

// --------------------------------------------------------------------

/**
 * 根据 parse_url 的结果重新组合 url
 * 
 * @access public
 * @param  array $params 解析后的参数
 * @return string
 */
if ( ! function_exists('build_url'))
{
	function build_url($params)
	{
		 return (isset($params['scheme']) ? $params['scheme'] . '://' : NULL)
		 . (isset($params['user']) ? $params['user'] . (isset($params['pass']) ? ':' . $params['pass'] : NULL) . '@' : NULL)
		 . (isset($params['host']) ? $params['host'] : NULL)
		 . (isset($params['port']) ? ':' . $params['port'] : NULL)
		 . (isset($params['path']) ? $params['path'] : NULL)
		 . (isset($params['query']) ? '?' . $params['query'] : NULL)
		 . (isset($params['fragment']) ? '#' . $params['fragment'] : NULL);
	}
}

// --------------------------------------------------------------------

/**
 * 将内容中的 URL 地址转换为超链接
 *
 * @param  string  $text
 * @param  string  $target
 * @return string
 */
if ( ! function_exists('url2link'))
{
	function url2link($text, $target = '_blank')
	{
		$pattern = '/https?:\/\/([^\["\'\s\.]+\.)+[^\["\'\s\<\>]+/i';
		preg_match_all($pattern, $text, $matches);
		
		if (isset($matches[0]) && !empty($matches[0])) 
		{
			$matches = array_unique($matches[0]);
			
			foreach ($matches as $url) 
			{
				$tmp = $url;
				$length = 65;
				
				if(strlen($tmp) > $length) 
				{
					$tmp = substr($tmp, 0, intval($length * 0.5)) . ' ... ' . substr($tmp, - intval($length * 0.3));
				}
				
				$text = str_replace($url, "<a href=\"$url\" target=\"$target\" title=\"$url\">$tmp</a>", $text);
			}
		}
		return $text;
	}
}

// --------------------------------------------------------------------

/**
 * 判断网址是否可以正常访问
 *
 * @param  string  $url
 * @return boolean
 */
if (!function_exists('url_ping')) 
{
	function url_ping($url)
	{
		$headers = @get_headers($url);
		
		if (isset($headers[0])) 
		{
			preg_match('/ (\d{3}) /', $headers[0], $matches);
			
			if (isset($matches[1]) && $matches[1] < 400) 
			{
				return TRUE;
			}
		}
		
		return FALSE;
	}
}
/* End of file ST_url_helper.php */
/* Location: ./app/helpers/ST_url_helper.php */