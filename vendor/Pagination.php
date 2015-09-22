<?php

namespace Donate\Vendor;

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\URL;

class Pagination {
	private static $page, $limit, $totalItems, $adjacents, $targetPage, $pageString;

	public static function init() {

	}

	public static function limit($page = 1, $limit) {
		$start = ($page - 1) * $limit;

		return ['start' => $start, 'limit' => $limit];
	}

	public static function render($page = 1, $totalitems, $limit = 15, $adjacents = 1, $targetpage = "", $pagestring = "/") {		
		//defaults
		if(!$adjacents) $adjacents = 1;
		if(!$limit) $limit = 15;
		if(!$page) $page = 1;
		if(!$targetpage) $targetpage = config('base_url');
		
		//other vars
		$prev = $page - 1;									//previous page is page - 1
		$next = $page + 1;									//next page is page + 1
		$lastpage = ceil($totalitems / $limit);				//lastpage is = total items / items per page, rounded up.
		$lpm1 = $lastpage - 1;								//last page minus 1
		
		/* 
			Now we apply our rules and draw the pagination object. 
			We're actually saving the code to a variable in case we want to draw it more than once.
		*/
		$pagination = "";
		if($lastpage > 1)
		{	
			$pagination .= "<ul class=\"pagination\">";

			//previous button
			if ($page > 1) 
				$pagination .= "<li><a href=\"$targetpage$pagestring$prev\">« prev</a></li>";
			else
				$pagination .= "<li class='disabled'><a href='#'>« prev</a></li>";	
			
			//pages	
			if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
			{	
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination .= "<li class='active'><a href='#'>$counter</a></li>";
					else
						$pagination .= "<li><a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a></li>";					
				}
			}
			elseif($lastpage >= 7 + ($adjacents * 2))	//enough pages to hide some
			{
				//close to beginning; only hide later pages
				if($page < 1 + ($adjacents * 3))		
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
							$pagination .= "<li class='active'><a href='#'>$counter</a></li>";
						else
							$pagination .= "<li><a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a></li>";					
					}
					$pagination .= "<li><span class=\"elipses\">...</span></li>";
					$pagination .= "<li><a href=\"" . $targetpage . $pagestring . $lpm1 . "\">$lpm1</a></li>";
					$pagination .= "<li><a href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a></li>";		
				}
				//in middle; hide some front and some back
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagination .= "<li><a href=\"" . $targetpage . $pagestring . "1\">1</a></li>";
					$pagination .= "<li><a href=\"" . $targetpage . $pagestring . "2\">2</a></li>";
					$pagination .= "<li><span class=\"elipses\">...</span></li>";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
							$pagination .= "<li><a href='#'>$counter</a></li>";
						else
							$pagination .= "<li><a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a></li>";					
					}
					$pagination .= "<li>...</li>";
					$pagination .= "<li><a href=\"" . $targetpage . $pagestring . $lpm1 . "\">$lpm1</a></li>";
					$pagination .= "<li><a href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a></li>";		
				}
				//close to end; only hide early pages
				else
				{
					$pagination .= "<li><a href=\"" . $targetpage . $pagestring . "1\">1</a></li>";
					$pagination .= "<li><a href=\"" . $targetpage . $pagestring . "2\">2</a></li>";
					$pagination .= "<li><span class=\"elipses\">...</span></li>";
					for ($counter = $lastpage - (1 + ($adjacents * 3)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination .= "<li><a href='#' class=\"current\">$counter</a></li>";
						else
							$pagination .= "<li><a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a></li>";					
					}
				}
			}
			
			//next button
			if ($page < $counter - 1) 
				$pagination .= "<li><a href=\"" . $targetpage . $pagestring . $next . "\">next »</a></li>";
			else
				$pagination .= "<li class='disabled'><a href='#''>next »</a></li>";

			$pagination .= "</ul>\n";
		}
		
		return $pagination;

	}
}