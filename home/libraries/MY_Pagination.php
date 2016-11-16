<?php
	# 扩展分页类，实现用 ajax 分页

	class MY_Pagination extends CI_Pagination {
		function __construct() {			
			parent::__construct();
		}
		
		/**
	 * Generate the pagination links
	 *
	 * @access	public
	 * @return	string
	 */
	function create_links($cur_page = FALSE,$onclick="",$uid='0')
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
		
		// Set the base page index for starting page number
		if ($this->use_page_numbers)
		{
			$base_page = 1;
		}
		else
		{
			$base_page = 0;
		}
		
		// Determine the current page number.
		$CI =& get_instance();

		if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
		{
			if ($CI->input->get($this->query_string_segment) != $base_page)
			{
				if(!$cur_page) {
					$this->cur_page = $CI->input->get($this->query_string_segment);
	
					// Prep the current page - no funny business!
					$this->cur_page = (int) $this->cur_page;
				}
			}
		}
		else
		{
			if ($CI->uri->segment($this->uri_segment) != $base_page)
			{
				if(!$cur_page) {
					$this->cur_page = $CI->uri->segment($this->uri_segment);
	
					// Prep the current page - no funny business!
					$this->cur_page = (int) $this->cur_page;
				}
			}
		}
		
		// Set current page to 1 if using page numbers instead of offset
		if ($this->use_page_numbers AND $this->cur_page == 0)
		{
			if(!$cur_page) {
				$this->cur_page = $base_page;
			}
		}

		$this->num_links = (int)$this->num_links;

		if ($this->num_links < 1)
		{
			show_error('Your number of links must be a positive number.');
		}

		if ( ! is_numeric($this->cur_page))
		{
			$this->cur_page = $base_page;
		}

		// Is the page number beyond the result range?
		// If so we show the last page
		if ($this->use_page_numbers)
		{
			if ($this->cur_page > $num_pages)
			{
				$this->cur_page = $num_pages;
			}
		}
		else
		{
			if ($this->cur_page > $this->total_rows)
			{
				$this->cur_page = ($num_pages - 1) * $this->per_page;
			}
		}

		$uri_page_number = $this->cur_page;
		
		if ( ! $this->use_page_numbers)
		{
			$this->cur_page = floor(($this->cur_page/$this->per_page) + 1);
		}

		// Calculate the start and end numbers. These determine
		// which number to start and end the digit links with
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
		$end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

		// Is pagination being used over GET or POST?  If get, add a per_page query
		// string. If post, add a trailing slash to the base URL if needed
		if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
		{
			$this->base_url = rtrim($this->base_url).'&amp;'.$this->query_string_segment.'=';
		}
		else
		{
			$this->base_url = rtrim($this->base_url, '/') .'/';
		}

		// And here we go...
		$output = '';
		if($this->cur_page <=1){
			$output.="{$this->num_tag_open}<li class=\"disabled\"><a {$this->anchor_class} href='javascript:void(0)' >&laquo;</a></li>{$this->num_tag_close}";
		}
		// Render the "First" link
		if  ($this->first_link !== FALSE AND $this->cur_page > ($this->num_links + 1))
		{
			$first_url = ($this->first_url == '') ? $this->base_url : $this->first_url;
			
		}

		// Render the "previous" link
		if  ($this->prev_link !== FALSE AND $this->cur_page != 1)
		{
			if ($this->use_page_numbers)
			{
				$i = $uri_page_number - 1;
			}
			else
			{
				$i = $uri_page_number - $this->per_page;
			}

			if ($i == 0 && $this->first_url != '')
			{
				$output .= $this->prev_tag_open.'<li><a '.$this->anchor_class.'href="'.$this->first_url.'">'.$this->prev_link.'</a></li>'.$this->prev_tag_close;
			}
			else
			{
				$i = ($i == 0) ? '' : $this->prefix.$i.$this->suffix;
			
				$output.="{$this->num_tag_open}<li><a {$this->anchor_class} href='javascript:void(0)' onclick={$onclick}({$i},$uid)>{$this->prev_link}</a></li>{$this->num_tag_close}";
			}

		}

		// Render the pages
		if ($this->display_pages !== FALSE)
		{
			// Write the digit links
			for ($loop = $start -1; $loop <= $end; $loop++)
			{
				if ($this->use_page_numbers)
				{
					$i = $loop;
				}
				else
				{
					$i = ($loop * $this->per_page) - $this->per_page;
				}

				if ($i >= $base_page)
				{
					if ($this->cur_page == $loop)
					{
						$output .= $this->cur_tag_open.$loop.$this->cur_tag_close; // Current page
						
					}
					else
					{
						$n = ($i == $base_page) ? '' : $i;

						if ($n == '' && $this->first_url != '')
						{
							//$output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="'.$this->first_url.'">'.$loop.'</a>'.$this->num_tag_close;
							$output.="{$this->num_tag_open}<li><a {$this->anchor_class} href='javascript:void(0)' onclick={$onclick}({$loop},$uid)>{$loop}</a></li>{$this->num_tag_close}";
						}
						else
						{
							
							//$n = ($n == '') ? '' : $this->prefix.$n.$this->suffix;

							//$output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="#'.$n.'" onclick='.$onclick.'(>'.$loop.'</a>'.$this->num_tag_close;
							$output.="{$this->num_tag_open}<li><a {$this->anchor_class} href='javascript:void(0)' onclick={$onclick}({$loop},$uid)>{$loop}</a></li>{$this->num_tag_close}";
						}
					}
				}
			}
		}

		// Render the "next" link
		if ($this->next_link !== FALSE AND $this->cur_page < $num_pages)
		{
			if ($this->use_page_numbers)
			{
				$i = $this->cur_page + 1;
			}
			else
			{
				$i = ($this->cur_page * $this->per_page);
			}

			//$output .= $this->next_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$this->prefix.$i.$this->suffix.'">'.$this->next_link.'</a>'.$this->next_tag_close;
			$output.="{$this->num_tag_open}<li><a {$this->anchor_class} href='javascript:void(0)' onclick={$onclick}({$i},$uid)>{$this->next_link}</a></li>{$this->num_tag_close}";
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
			
			$output.="{$this->num_tag_open}<li><a {$this->anchor_class} href='javascript:void(0)' onclick={$onclick}({$i},$uid)>{$this->last_link}</a></li>{$this->num_tag_close}";
		}

		// Kill double slashes.  Note: Sometimes we can end up with a double slash
		// in the penultimate link so we'll kill all double slashes.
		
		//$output = preg_replace("#([^:])//+#", "\\1/", $output);

		// Add the wrapper HTML if exists
		$output.="<li><a class=\"current\">共{$this->total_rows}条数据</a></li>";
		$output = $this->full_tag_open.$output.$this->full_tag_close;
		
		return $output;		
	}
	
	function create_links2($cur_page = FALSE){
		
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
		
		// Set the base page index for starting page number
		if ($this->use_page_numbers)
		{
			$base_page = 1;
		}
		else
		{
			$base_page = 0;
		}
		
		// Determine the current page number.
		$CI =& get_instance();
		
		if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
		{
			if ($CI->input->get($this->query_string_segment) != $base_page)
			{
				if(!$cur_page) {
					
					$this->cur_page = $CI->input->get($this->query_string_segment);//empty($CI->input->get($this->query_string_segment))?"1":$CI->input->get($this->query_string_segment);
		
					// Prep the current page - no funny business!
					$this->cur_page = (int) $this->cur_page;
				}
			}
		}
		else
		{
			if ($CI->uri->segment($this->uri_segment) != $base_page)
			{
				if(!$cur_page) {
					$this->cur_page = $CI->uri->segment($this->uri_segment);
		
					// Prep the current page - no funny business!
					$this->cur_page = (int) $this->cur_page;
				}
			}
		}
		
		// Set current page to 1 if using page numbers instead of offset
		if ($this->use_page_numbers AND $this->cur_page == 0)
		{
			if(!$cur_page) {
				$this->cur_page = $base_page;
			}
		}
		
		$this->num_links = (int)$this->num_links;
		
		if ($this->num_links < 1)
		{
			show_error('Your number of links must be a positive number.');
		}
		
		if ( ! is_numeric($this->cur_page))
		{
			$this->cur_page = $base_page;
		}
		
		// Is the page number beyond the result range?
		// If so we show the last page
		if ($this->use_page_numbers)
		{
			if ($this->cur_page > $num_pages)
			{
				$this->cur_page = $num_pages;
			}
		}
		else
		{
			if ($this->cur_page > $this->total_rows)
			{
				$this->cur_page = ($num_pages - 1) * $this->per_page;
			}
		}
		
		$uri_page_number = $this->cur_page;
		
		if ( ! $this->use_page_numbers)
		{
			$this->cur_page = floor(($this->cur_page/$this->per_page) + 1);
		}
		
		// Calculate the start and end numbers. These determine
		// which number to start and end the digit links with
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
		$end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;
		
		// Is pagination being used over GET or POST?  If get, add a per_page query
		// string. If post, add a trailing slash to the base URL if needed
		if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
		{
			$this->base_url = rtrim($this->base_url).'&amp;'.$this->query_string_segment.'=';
		}
		else
		{
			$this->base_url = rtrim($this->base_url, '/') .'/';
		}
		
		// And here we go...
		$output = '';
		if($this->cur_page <=1){
			//$output.="{$this->num_tag_open}<li class=\"disabled\"><a {$this->anchor_class} href='javascript:void(0)' >&laquo;</a></li>{$this->num_tag_close}";
		}
		// Render the "First" link
		if  ($this->first_link !== FALSE )//AND $this->cur_page > ($this->num_links + 1)
		{
			
			$first_url = $this->base_url;//.base_url();//($this->first_url == '') ? $this->base_url+base_url() : $this->first_url;
			//echo "aaaa=".$first_url;//$this->first_url;
			$output.= $this->prev_tag_open.'<li><a '.$this->anchor_class.'href="'.$first_url.'">'.$this->first_link.'</a></li>'.$this->prev_tag_close;
				
		}
		
		// Render the "previous" link
		if  ($this->prev_link !== FALSE AND $this->cur_page != 1)
		{
			if ($this->use_page_numbers)
			{
				$i = $uri_page_number - 1;
			}
			else
			{
				$i = $uri_page_number - $this->per_page;
			}
		
			if ($i == 0 && $this->first_url != '')
			{
				$output .= $this->prev_tag_open.'<li><a '.$this->anchor_class.'href="'.$this->base_url.'">'.$this->prev_link.'</a></li>'.$this->prev_tag_close;
			}
			else
			{
				$i = ($i == 0) ? '' : $this->prefix.$i.$this->suffix;
					
				$output.="{$this->num_tag_open}<li><a {$this->anchor_class} href='".$this->base_url.$this->prefix.$i.$this->suffix."' >{$this->prev_link}</a></li>{$this->num_tag_close}";
			}
		
		}
		
		// Render the pages
		if ($this->display_pages !== FALSE)
		{
			// Write the digit links
			for ($loop = $start -1; $loop <= $end; $loop++)
			{
			if ($this->use_page_numbers)
			{
			$i = $loop;
			}
			else
			{
			$i = ($loop * $this->per_page) - $this->per_page;
			}
		
			if ($i >= $base_page)
			{
			if ($this->cur_page == $loop)
				{
				$output .= $this->cur_tag_open.$loop.$this->cur_tag_close; // Current page
		
				}
				else
				{
				$n = ($i == $base_page) ? '' : $i;
		
				if ($n == '' && $this->first_url != '')
					{
							//$output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="'.$this->first_url.'">'.$loop.'</a>'.$this->num_tag_close;
					$output.="{$this->num_tag_open}<li><a {$this->anchor_class} href='javascript:void(0)' onclick={$onclick}({$loop},$uid)>{$loop}</a></li>{$this->num_tag_close}";
				}
				else
				{
					
				//$n = ($n == '') ? '' : $this->prefix.$n.$this->suffix;
		
				//$output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="#'.$n.'" onclick='.$onclick.'(>'.$loop.'</a>'.$this->num_tag_close;
					$output.="{$this->num_tag_open}<li><a {$this->anchor_class} href='".$this->base_url.$this->prefix.$i.$this->suffix."'>{$loop}</a></li>{$this->num_tag_close}";
					}
				}
				}
				}
				}
		
				// Render the "next" link
				if ($this->next_link !== FALSE AND $this->cur_page < $num_pages)
				{
				if ($this->use_page_numbers)
				{
				$i = $this->cur_page + 1;
				}
				else
				{
					$i = ($this->cur_page * $this->per_page);
					}
		
					$output .= $this->next_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$this->prefix.$i.$this->suffix.'">'.$this->next_link.'</a>'.$this->next_tag_close;
					//$output.="{$this->num_tag_open}<li><a {$this->anchor_class} href='javascript:void(0)' onclick={$onclick}({$i},$uid)>{$this->next_link}</a></li>{$this->num_tag_close}";
					}
		
					// Render the "Last" link
					if ($this->last_link !== FALSE AND ($this->cur_page + $this->num_links) < 9999)//$num_pages
					{
					if ($this->use_page_numbers)
				{
				$i = $num_pages;
				}
				else
				{
				$i = (($num_pages * $this->per_page) - $this->per_page);
				}
					
		
				//$output.="{$this->num_tag_open}<li><a {$this->anchor_class} href='javascript:void(0)' onclick={$onclick}({$i},$uid)>{$this->last_link}</a></li>{$this->num_tag_close}";
				$output.="{$this->num_tag_open}<li><a {$this->anchor_class} href='".$this->base_url.$this->prefix.$i.$this->suffix."'>{$this->last_link}</a></li>{$this->num_tag_close}";
				}
		
				// Kill double slashes.  Note: Sometimes we can end up with a double slash
				// in the penultimate link so we'll kill all double slashes.
		
				//$output = preg_replace("#([^:])//+#", "\\1/", $output);
		
				// Add the wrapper HTML if exists
				$output.="<li><a class=\"current\">共{$this->total_rows}条数据</a></li>";
				$output = $this->full_tag_open.$output.$this->full_tag_close;
		
				return $output;
	
	}
	
	//去掉li标签   总共条数
	function create_links3($cur_page = FALSE){
		
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
		
		// Set the base page index for starting page number
		if ($this->use_page_numbers)
		{
			$base_page = 1;
		}
		else
		{
			$base_page = 0;
		}
		
		// Determine the current page number.
		$CI =& get_instance();
		
		if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
		{
			if ($CI->input->get($this->query_string_segment) != $base_page)
			{
				if(!$cur_page) {
					
					$this->cur_page = $CI->input->get($this->query_string_segment);//empty($CI->input->get($this->query_string_segment))?"1":$CI->input->get($this->query_string_segment);
		
					// Prep the current page - no funny business!
					$this->cur_page = (int) $this->cur_page;
				}
			}
		}
		else
		{
			if ($CI->uri->segment($this->uri_segment) != $base_page)
			{
				if(!$cur_page) {
					$this->cur_page = $CI->uri->segment($this->uri_segment);
		
					// Prep the current page - no funny business!
					$this->cur_page = (int) $this->cur_page;
				}
			}
		}
		
		// Set current page to 1 if using page numbers instead of offset
		if ($this->use_page_numbers AND $this->cur_page == 0)
		{
			if(!$cur_page) {
				$this->cur_page = $base_page;
			}
		}
		
		$this->num_links = (int)$this->num_links;
		
		if ($this->num_links < 1)
		{
			show_error('Your number of links must be a positive number.');
		}
		
		if ( ! is_numeric($this->cur_page))
		{
			$this->cur_page = $base_page;
		}
		
		// Is the page number beyond the result range?
		// If so we show the last page
		if ($this->use_page_numbers)
		{
			if ($this->cur_page > $num_pages)
			{
				$this->cur_page = $num_pages;
			}
		}
		else
		{
			if ($this->cur_page > $this->total_rows)
			{
				$this->cur_page = ($num_pages - 1) * $this->per_page;
			}
		}
		
		$uri_page_number = $this->cur_page;
		
		if ( ! $this->use_page_numbers)
		{
			$this->cur_page = floor(($this->cur_page/$this->per_page) + 1);
		}
		
		// Calculate the start and end numbers. These determine
		// which number to start and end the digit links with
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
		$end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;
		
		// Is pagination being used over GET or POST?  If get, add a per_page query
		// string. If post, add a trailing slash to the base URL if needed
		if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
		{
			$this->base_url = rtrim($this->base_url).'&amp;'.$this->query_string_segment.'=';
		}
		else
		{
			$this->base_url = rtrim($this->base_url, '/') .'/';
		}
		
		// And here we go...
		$output = '';
		if($this->cur_page <=1){
			//$output.="{$this->num_tag_open}<li class=\"disabled\"><a {$this->anchor_class} href='javascript:void(0)' >&laquo;</a></li>{$this->num_tag_close}";
		}
		// Render the "First" link
		if  ($this->first_link !== FALSE )//AND $this->cur_page > ($this->num_links + 1)
		{
			
			$first_url = $this->base_url;//.base_url();//($this->first_url == '') ? $this->base_url+base_url() : $this->first_url;
			//echo "aaaa=".$first_url;//$this->first_url;
			$output.= $this->prev_tag_open.'<a '.$this->anchor_class.'href="'.$first_url.'">'.$this->first_link.'</a>'.$this->prev_tag_close;
				
		}
		
		// Render the "previous" link
		if  ($this->prev_link !== FALSE AND $this->cur_page != 1)
		{
			if ($this->use_page_numbers)
			{
				$i = $uri_page_number - 1;
			}
			else
			{
				$i = $uri_page_number - $this->per_page;
			}
		
			if ($i == 0 && $this->first_url != '')
			{
				$output .= $this->prev_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
			}
			else
			{
				$i = ($i == 0) ? '' : $this->prefix.$i.$this->suffix;
					
				$output.="{$this->num_tag_open}<a {$this->anchor_class} href='".$this->base_url.$this->prefix.$i.$this->suffix."' >{$this->prev_link}</a>{$this->num_tag_close}";
			}
		
		}
		
		// Render the pages
		if ($this->display_pages !== FALSE)
		{
			// Write the digit links
			for ($loop = $start -1; $loop <= $end; $loop++)
			{
			if ($this->use_page_numbers)
			{
			$i = $loop;
			}
			else
			{
			$i = ($loop * $this->per_page) - $this->per_page;
			}
		
			if ($i >= $base_page)
			{
			if ($this->cur_page == $loop)
				{
				$output .= $this->cur_tag_open.$loop.$this->cur_tag_close; // Current page
			// echo $this->cur_tag_open;exit;
				}
				else
				{
				$n = ($i == $base_page) ? '' : $i;
		
				if ($n == '' && $this->first_url != '')
					{
							//$output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="'.$this->first_url.'">'.$loop.'</a>'.$this->num_tag_close;
					$output.="{$this->num_tag_open}<a {$this->anchor_class} href='javascript:void(0)' onclick={$onclick}({$loop},$uid)>{$loop}</a>{$this->num_tag_close}";
				}
				else
				{
					
				//$n = ($n == '') ? '' : $this->prefix.$n.$this->suffix;
		
				//$output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="#'.$n.'" onclick='.$onclick.'(>'.$loop.'</a>'.$this->num_tag_close;
					$output.="{$this->num_tag_open}<a {$this->anchor_class} href='".$this->base_url.$this->prefix.$i.$this->suffix."'>{$loop}</a>{$this->num_tag_close}";
					}
				}
				}
				}
				}
		
				// Render the "next" link
				if ($this->next_link !== FALSE AND $this->cur_page < $num_pages)
				{
				if ($this->use_page_numbers)
				{
				$i = $this->cur_page + 1;
				}
				else
				{
					$i = ($this->cur_page * $this->per_page);
					}
		
					$output .= $this->next_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$this->prefix.$i.$this->suffix.'">'.$this->next_link.'</a>'.$this->next_tag_close;
					//$output.="{$this->num_tag_open}<li><a {$this->anchor_class} href='javascript:void(0)' onclick={$onclick}({$i},$uid)>{$this->next_link}</a></li>{$this->num_tag_close}";
					}
		
					// Render the "Last" link
					if ($this->last_link !== FALSE AND ($this->cur_page + $this->num_links) < 9999)//$num_pages
					{
					if ($this->use_page_numbers)
				{
				$i = $num_pages;
				}
				else
				{
				$i = (($num_pages * $this->per_page) - $this->per_page);
				}
					
		
				//$output.="{$this->num_tag_open}<li><a {$this->anchor_class} href='javascript:void(0)' onclick={$onclick}({$i},$uid)>{$this->last_link}</a></li>{$this->num_tag_close}";
				$output.="{$this->num_tag_open}<a {$this->anchor_class} href='".$this->base_url.$this->prefix.$i.$this->suffix."'>{$this->last_link}</a>{$this->num_tag_close}";
				}
		
				// Kill double slashes.  Note: Sometimes we can end up with a double slash
				// in the penultimate link so we'll kill all double slashes.
		
				//$output = preg_replace("#([^:])//+#", "\\1/", $output);
		
				// Add the wrapper HTML if exists
				// $output.="<li><a class=\"current\">共{$this->total_rows}条数据</a></li>";
				$output = $this->full_tag_open.$output.$this->full_tag_close;
		
				return $output;
	
	}

	function create_links_ren($cur_page = FALSE){
	
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
	
		// Set the base page index for starting page number
		if ($this->use_page_numbers)
		{
			$base_page = 1;
		}
		else
		{
			$base_page = 0;
		}
	
		// Determine the current page number.
		$CI =& get_instance();
	
		if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
		{
			if ($CI->input->get($this->query_string_segment) != $base_page)
			{
				if(!$cur_page) {
						
					$this->cur_page = $CI->input->get($this->query_string_segment);//empty($CI->input->get($this->query_string_segment))?"1":$CI->input->get($this->query_string_segment);
	
					// Prep the current page - no funny business!
					$this->cur_page = (int) $this->cur_page;
				}
			}
		}
		else
		{
			if ($CI->uri->segment($this->uri_segment) != $base_page)
			{
				if(!$cur_page) {
					$this->cur_page = $CI->uri->segment($this->uri_segment);
	
					// Prep the current page - no funny business!
					$this->cur_page = (int) $this->cur_page;
				}
			}
		}
	
		// Set current page to 1 if using page numbers instead of offset
		if ($this->use_page_numbers AND $this->cur_page == 0)
		{
			if(!$cur_page) {
				$this->cur_page = $base_page;
			}
		}
	
		$this->num_links = (int)$this->num_links;
	
		if ($this->num_links < 1)
		{
			show_error('Your number of links must be a positive number.');
		}
	
		if ( ! is_numeric($this->cur_page))
		{
			$this->cur_page = $base_page;
		}
	
		// Is the page number beyond the result range?
		// If so we show the last page
		if ($this->use_page_numbers)
		{
			if ($this->cur_page > $num_pages)
			{
				$this->cur_page = $num_pages;
			}
		}
		else
		{
			if ($this->cur_page > $this->total_rows)
			{
				$this->cur_page = ($num_pages - 1) * $this->per_page;
			}
		}
	
		$uri_page_number = $this->cur_page;
	
		if ( ! $this->use_page_numbers)
		{
			$this->cur_page = floor(($this->cur_page/$this->per_page) + 1);
		}
	
		// Calculate the start and end numbers. These determine
		// which number to start and end the digit links with
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
		$end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;
	
		// Is pagination being used over GET or POST?  If get, add a per_page query
		// string. If post, add a trailing slash to the base URL if needed
		if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
		{
			$this->base_url = rtrim($this->base_url).'&amp;'.$this->query_string_segment.'=';
		}
		else
		{
			$this->base_url = rtrim($this->base_url, '/') .'/';
		}
	
		// And here we go...
		$output = '';
		if($this->cur_page <=1){
			//$output.="{$this->num_tag_open}<li class=\"disabled\"><a {$this->anchor_class} href='javascript:void(0)' >&laquo;</a></li>{$this->num_tag_close}";
		}
		// Render the "First" link
		if  ($this->first_link !== FALSE )//AND $this->cur_page > ($this->num_links + 1)
		{
				
			$first_url = $this->base_url;//.base_url();//($this->first_url == '') ? $this->base_url+base_url() : $this->first_url;
			//echo "aaaa=".$first_url;//$this->first_url;
			$output.= $this->prev_tag_open.'<a '.$this->anchor_class.'href="'.$first_url.'">'.$this->first_link.'</a>'.$this->prev_tag_close;
	
		}
	
		// Render the "previous" link
		if  ($this->prev_link !== FALSE AND $this->cur_page != 1)
		{
			if ($this->use_page_numbers)
			{
				$i = $uri_page_number - 1;
			}
			else
			{
				$i = $uri_page_number - $this->per_page;
			}
	
			if ($i == 0 && $this->first_url != '')
			{
				$output .= $this->prev_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
			}
			else
			{
				$i = ($i == 0) ? '' : $this->prefix.$i.$this->suffix;
					
				$output.="{$this->num_tag_open}<a {$this->anchor_class} href='".$this->base_url.$this->prefix.$i.$this->suffix."' >{$this->prev_link}</a>{$this->num_tag_close}";
			}
	
		}
	
		// Render the pages
		if ($this->display_pages !== FALSE)
		{
			// Write the digit links
			for ($loop = $start -1; $loop <= $end; $loop++)
			{
			if ($this->use_page_numbers)
			{
			$i = $loop;
			}
			else
			{
			$i = ($loop * $this->per_page) - $this->per_page;
			}
	
			if ($i >= $base_page)
			{
			if ($this->cur_page == $loop)
			{
			$output .= $this->cur_tag_open.$loop.$this->cur_tag_close; // Current page
	
			}
			else
				{
				$n = ($i == $base_page) ? '' : $i;
	
				if ($n == '' && $this->first_url != '')
				{
				//$output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="'.$this->first_url.'">'.$loop.'</a>'.$this->num_tag_close;
					$output.="{$this->num_tag_open}<a {$this->anchor_class} href='javascript:void(0)' onclick={$onclick}({$loop},$uid)>{$loop}</a>{$this->num_tag_close}";
				}
				else
				{
					
				//$n = ($n == '') ? '' : $this->prefix.$n.$this->suffix;
	
					//$output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="#'.$n.'" onclick='.$onclick.'(>'.$loop.'</a>'.$this->num_tag_close;
					$output.="{$this->num_tag_open}<a {$this->anchor_class} href='".$this->base_url.$this->prefix.$i.$this->suffix."'>{$loop}</a>{$this->num_tag_close}";
				}
				}
				}
				}
				}
	
				// Render the "next" link
				if ($this->next_link !== FALSE AND $this->cur_page < $num_pages)
				{
				if ($this->use_page_numbers)
				{
				$i = $this->cur_page + 1;
				}
				else
				{
				$i = ($this->cur_page * $this->per_page);
				}
	
				$output .= $this->next_tag_open.'<a '.$this->anchor_class.'href="'.$this->base_url.$this->prefix.$i.$this->suffix.'">'.$this->next_link.'</a>'.$this->next_tag_close;
				//$output.="{$this->num_tag_open}<li><a {$this->anchor_class} href='javascript:void(0)' onclick={$onclick}({$i},$uid)>{$this->next_link}</a></li>{$this->num_tag_close}";
				}
	
				// Render the "Last" link
				if ($this->last_link !== FALSE AND ($this->cur_page + $this->num_links) < 9999)//$num_pages
				{
				if ($this->use_page_numbers)
				{
				$i = $num_pages;
			}
			else
			{
				$i = (($num_pages * $this->per_page) - $this->per_page);
				}
					
	
				//$output.="{$this->num_tag_open}<li><a {$this->anchor_class} href='javascript:void(0)' onclick={$onclick}({$i},$uid)>{$this->last_link}</a></li>{$this->num_tag_close}";
				$output.="{$this->num_tag_open}<a {$this->anchor_class} href='".$this->base_url.$this->prefix.$i.$this->suffix."'>{$this->last_link}</a>{$this->num_tag_close}";
			}
	
			// Kill double slashes.  Note: Sometimes we can end up with a double slash
			// in the penultimate link so we'll kill all double slashes.
	
				//$output = preg_replace("#([^:])//+#", "\\1/", $output);
	
				// Add the wrapper HTML if exists
				//$output.="<a class=\"current\">共{$this->total_rows}条数据</a>";
                $output.="<span>共{$this->total_rows}条数据</span>";
				$output = $this->full_tag_open.$output.$this->full_tag_close;
	
				return $output;
	
		}	
	function create_links_wx($cur_page = FALSE){
	
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
	
		// Set the base page index for starting page number
		if ($this->use_page_numbers)
		{
			$base_page = 1;
		}
		else
		{
			$base_page = 0;
		}
	
		// Determine the current page number.
		$CI =& get_instance();
	
		if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
		{
			if ($CI->input->get($this->query_string_segment) != $base_page)
			{
				if(!$cur_page) {
						
					$this->cur_page = $CI->input->get($this->query_string_segment);//empty($CI->input->get($this->query_string_segment))?"1":$CI->input->get($this->query_string_segment);
	
					// Prep the current page - no funny business!
					$this->cur_page = (int) $this->cur_page;
				}
			}
		}
		else
		{
			if ($CI->uri->segment($this->uri_segment) != $base_page)
			{
				if(!$cur_page) {
					$this->cur_page = $CI->uri->segment($this->uri_segment);
	
					// Prep the current page - no funny business!
					$this->cur_page = (int) $this->cur_page;
				}
			}
		}
	
		// Set current page to 1 if using page numbers instead of offset
		if ($this->use_page_numbers AND $this->cur_page == 0)
		{
			if(!$cur_page) {
				$this->cur_page = $base_page;
			}
		}
	
		$this->num_links = (int)$this->num_links;
	
		if ($this->num_links < 1)
		{
			show_error('Your number of links must be a positive number.');
		}
	
		if ( ! is_numeric($this->cur_page))
		{
			$this->cur_page = $base_page;
		}
	
		// Is the page number beyond the result range?
		// If so we show the last page
		if ($this->use_page_numbers)
		{
			if ($this->cur_page > $num_pages)
			{
				$this->cur_page = $num_pages;
			}
		}
		else
		{
			if ($this->cur_page > $this->total_rows)
			{
				$this->cur_page = ($num_pages - 1) * $this->per_page;
			}
		}
	
		$uri_page_number = $this->cur_page;
	
		if ( ! $this->use_page_numbers)
		{
			$this->cur_page = floor(($this->cur_page/$this->per_page) + 1);
		}
	
		// Calculate the start and end numbers. These determine
		// which number to start and end the digit links with
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
		$end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;
	
		// Is pagination being used over GET or POST?  If get, add a per_page query
		// string. If post, add a trailing slash to the base URL if needed
		if ($CI->config->item('enable_query_strings') === TRUE OR $this->page_query_string === TRUE)
		{
			$this->base_url = rtrim($this->base_url).'&amp;'.$this->query_string_segment.'=';
		}
		else
		{
			$this->base_url = rtrim($this->base_url, '/') .'/';
		}
		
		//修改分页显示的标签2016年8月26日14:56:44
		// And here we go...
		$output = '';
		if($this->cur_page <=1){
			//$output.="{$this->num_tag_open}<li class=\"disabled\"><a {$this->anchor_class} href='javascript:void(0)' >&laquo;</a></li>{$this->num_tag_close}";
		}
	
		// Render the "previous" link
		if  ($this->prev_link !== FALSE AND $this->cur_page != 1)
		{
			if ($this->use_page_numbers)
			{
				$i = $uri_page_number - 1;
			}
			else
			{
				$i = $uri_page_number - $this->per_page;
			}
	
			if ($i == 0 && $this->first_url != '')
			{
				$output .= $this->prev_tag_open.'<a '.$this->prev_link_class.'href="'.$this->base_url.'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
			}
			else
			{
				$i = ($i == 0) ? '' : $this->prefix.$i.$this->suffix;
					
				$output.="{$this->num_tag_open}<a class='mob_list_pprev boradius' href='".$this->base_url.$this->prefix.$i.$this->suffix."' >{$this->prev_link}</a>{$this->num_tag_close}";
			}
	
		} else if ($this->prev_link !== FALSE AND $this->cur_page == 1) {
			//当前页为第一页上一页不能点击
			$output .= $this->prev_tag_open.'<a class="mob_list_pprev boradius" href="javascript:void(0);">'.$this->prev_link.'</a>'.$this->prev_tag_close;
		}
		//总共数据
		$output.="<p><span>{$this->cur_page}</span> / <span>{$num_pages}</span></p>";
		// Render the "next" link
		if ($this->next_link !== FALSE AND $this->cur_page < $num_pages)
		{
			if ($this->use_page_numbers)
			{
			$i = $this->cur_page + 1;
			}
			else
			{
			$i = ($this->cur_page * $this->per_page);
			}

			$output .= $this->next_tag_open.'<a class="mob_list_pnext boradius" href="'.$this->base_url.$this->prefix.$i.$this->suffix.'">'.$this->next_link.'</a>'.$this->next_tag_close;
		//$output.="{$this->num_tag_open}<li><a {$this->anchor_class} href='javascript:void(0)' onclick={$onclick}({$i},$uid)>{$this->next_link}</a></li>{$this->num_tag_close}";
		} else if ($this->next_link !== FALSE AND $this->cur_page >= $num_pages) {
			//当前页为最后一页，下一页按钮不能点击
			$output .= $this->next_tag_open.'<a class="mob_list_pnext boradius" href="javascript:void(0);">'.$this->next_link.'</a>'.$this->next_tag_close;
		}
		return $output;
	
	}
}
?>