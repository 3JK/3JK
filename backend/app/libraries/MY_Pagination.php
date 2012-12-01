<?php

/**
 * MY_Pagination 分页扩展类
 *
 * 拓展CI本身的分页类,实现需求的分页样式
 *
 */
class MY_Pagination extends CI_Pagination
{
	public function	 __construct($params = array())
	{
		parent::__construct($params);

		$this->num_links = 5;
		$this->next_link = "下一页";
		$this->prev_link = "上一页";
		$this->first_link = "首页";
		$this->last_link = "末页";
		$this->cur_tag_open = "&nbsp;<strong><a style='color:black;'>";
		$this->cur_tag_close = "</a></strong>";
	}

	public function create_links()
	{
		// If our item count or per-page total is zero there is no need to continue.
		if ($this->total_rows == 0 OR $this->per_page == 0)
		{
				return '';
		}

		// Calculate the total number of pages
		$num_pages = ceil($this->total_rows / $this->per_page);

		// Is there only one page? Hm... nothing more to do here then.
		if ($num_pages == 1)
		{
				return '';
		}

		// Determine the current page number.
		$CI =& get_instance();

		if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
		{
			if ($CI->input->get($this->query_string_segment) != 0)
			{
				$this->cur_page = $CI->input->get($this->query_string_segment);

				// Prep the current page - no funny business!
				$this->cur_page = (int) $this->cur_page;
			}
		}

		$this->num_links = (int)$this->num_links;

		if ($this->num_links < 1)
		{
				show_error('Your number of links must be a positive number.');
		}

		if ( ! is_numeric($this->cur_page))
		{
				$this->cur_page = 0;
		}

		// Is the page number beyond the result range?
		// If so we show the last page
		if ($this->cur_page > $this->total_rows)
		{
				$this->cur_page = ($num_pages - 1) * $this->per_page;
		}

		$uri_page_number = $this->cur_page;
		$this->cur_page = floor(($this->cur_page/$this->per_page) + 1);

		// Calculate the start and end numbers. These determine
		// which number to start and end the digit links with
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
		$end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

		// Is pagination being used over GET or POST?  If get, add a per_page query
		// string. If post, add a trailing slash to the base URL if needed


		// And here we go...
		$output = '';

		// Render the "First" link
		if  ($this->first_link !== FALSE AND $this->cur_page > ($this->num_links + 1))
		{
			$first_url = ($this->first_url == '') ? $this->base_url : $this->first_url;
			$output .= $this->first_tag_open.'<a '.$this->anchor_class.'href="'.htmlentities($this->base_url).$this->prefix. '&amp;per_page=' . $this->suffix.'">'.$this->first_link.'</a>'.$this->first_tag_close;
		}

		// Render the "previous" link
		if	($this->prev_link !== FALSE AND $this->cur_page != 1)
		{
			$i = $uri_page_number - $this->per_page;

			if ($i == 0 && $this->first_url != '')
			{
				$output .= $this->prev_tag_open.'<a '.$this->anchor_class.'href="'.$this->first_url.'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
			}
			else
			{
				$i = ($i == 0) ? '' : $this->prefix.$i.$this->suffix;
				$output .= $this->prev_tag_open.'<a '.$this->anchor_class.'href="'.htmlentities($this->base_url). '&per_page=' .$i.'" class="prev_page">'.$this->prev_link.'</a>'.$this->prev_tag_close;
			}

		}

		// Render the pages
		if ($this->display_pages !== FALSE)
		{
			$link_prefix = strpos($this->base_url, '?') === FALSE ? '?' : '&';

			// Write the digit links
			for ($loop = $start -1; $loop <= $end; $loop++)
			{
				$i = ($loop * $this->per_page) - $this->per_page;

				if ($i >= 0)
				{
					if ($this->cur_page == $loop)
					{
						$output .= $this->cur_tag_open.$loop.$this->cur_tag_close; // Current page
					}
					else
					{
						$n = ($i == 0) ? '' : $i;

						if ($n == '' && $this->first_url != '')
						{
								$output .= $this->num_tag_open.'<a clas= '.$this->anchor_class.'href="'.$this->first_url.'">'.$loop.'</a>'.$this->num_tag_close;
						}
						else
						{
								$n = ($n == '') ? '' : $this->prefix.$n.$this->suffix;

								$output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="'.htmlentities($this->base_url). $link_prefix . 'per_page=' . $n . '">'.$loop.'</a>'.$this->num_tag_close;
						}
					}
				}
			}
		}

		// Render the "next" link
		if ($this->next_link !== FALSE AND $this->cur_page < $num_pages)
		{

				$output .= $this->next_tag_open.'<a	 class="next_page"	'.$this->anchor_class.' href="'.htmlentities($this->base_url).$this->prefix. $link_prefix . 'per_page=' .($this->cur_page * $this->per_page).$this->suffix.'">'.$this->next_link.'</a>'.$this->next_tag_close;
		}

		// Render the "Last" link
		if ($this->last_link !== FALSE AND ($this->cur_page + $this->num_links) < $num_pages)
		{
			if ($this->use_page_numbers)
			{
				$i = $num_pages;
			}
			else
			{
				$i = (($num_pages * $this->per_page) - $this->per_page);
			}
			$output .= $this->last_tag_open.'<a '.$this->anchor_class.'href="'.htmlentities($this->base_url).$this->prefix. $link_prefix . 'per_page=' .(($num_pages-1) * $this->per_page).$this->suffix.'">'.$this->last_link.'</a>'.$this->last_tag_close;
		}
		
		// Kill double slashes.	 Note: Sometimes we can end up with a double slash
		// in the penultimate link so we'll kill all double slashes.'.$this->anchor_class.'href="'.htmlentities($this->base_url).$this->prefix. $link_prefix . 'per_page=' .(($num_pages-1) * $this->per_page).$this->suffix.'
		$output = preg_replace("#([^:])//+#", "\\1/", $output);

		// 加入页码跳转
		$params = $_GET;
		unset($params[$this->query_string_segment]);

		$output .= '&nbsp;&nbsp;跳转至 ';
		$output .= '<input name="pn" id="pn" size="2" value="" onkeydown="if(13==event.keyCode){var _pn=this;if(_pn.value==\'\' || (_pn.value|0)!=_pn.value ||(_pn.value|0)<1){ _pn.select();}else{ if(_pn.value*1 > ' . $num_pages .'){_pn.value=' . $num_pages . '}; window.location=\'' . current_url() . '?' . http_build_query($params) . '&' . $this->query_string_segment . '=\' + (_pn.value-1)*' . $this->per_page . '; };return false}">';

		// Add the wrapper HTML if exists
		$output = $this->full_tag_open.$output.$this->full_tag_close;


		return $output;
	}
}


/* End of file MY_Pagination.php */
/* Location: ./app/libraries/MY_Pagination.php */

