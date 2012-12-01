<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 宽字符串截字函数
 *
 * @access public
 * @param string $str 需要截取的字符串
 * @param integer $start 开始截取的位置
 * @param integer $length 需要截取的长度
 * @param string $trim 截取后的截断标示符
 * @param string $charset 字符串编码
 * @return string
 */
if( ! function_exists('sub_str') )
{
	function sub_str($str, $start, $length, $trim = "...", $charset = 'UTF-8')
	{
		if (function_exists('mb_get_info')) 
		{
			$iLength = mb_strlen($str, $charset);
			$str = mb_substr($str, $start, $length, $charset);

			return ($length < $iLength - $start) ? $str . $trim : $str;
		} 
		else 
		{
			preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $str, $info);
			$str = join("", array_slice($info[0], $start, $length));

			return ($length < (sizeof($info[0]) - $start)) ? $str . $trim : $str;
		}
	}
}