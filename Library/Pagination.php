<?php

namespace Library;

// no direct access
defined( 'ACCESS' ) or die( 'Restricted access' );

class Pagination {

	function build(Table $table, $targetPage = "", $uriQuery = []) { 

		$rows = ($table->rows >= 1000) ? 1000 : $table->rows;

		/* Setup page vars for display. */
		if ($table->page['current'] == 0) $table->page['current'] = 1;					//if no page var is given, default to 1.
		$prev = $table->page['current'] - 1;							//previous page is page - 1
		$next = $table->page['current'] + 1;							//next page is page + 1
		$lastpage = ceil($rows/$table->page['limit']);		//lastpage is = total pages / items per page, rounded up.
		$lpm1 = $lastpage - 1;						//last page minus 1
		$pagination = "";

		if($lastpage > 1)
		{
			$pagination .= "<div class='pagination-container mt-2'>";
			$pagination .= "<nav aria-label='Page navigation'>";
			$pagination .= "<ul class=\"pagination justify-content-center d-print-none\" >";

			//previous button
			if ($table->page['current'] > 1) {
				$uriQuery = array_merge($uriQuery,["page" => $prev]);
				$pagination.= "<li class='page-item'><a class='ajax page-link' title=' Page $prev' href=\"".url($targetPage,null,$uriQuery)."\">&laquo; previous</a></li>";
				} else {
				$pagination.= "<li class='page-item disabled'><a class='page-link'>&laquo; previous</a></li>";
			}
			
			//pages
			if ($lastpage < 7 + ($table->page['adjacents'] * 2))	//not enough pages to bother breaking it up
			{
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == $table->page['current']) {
						$pagination.= "<li class='page-item disabled'><a class='page-link'>$counter</a></li>";
					} else {
						$uriQuery = array_merge($uriQuery,["page" => $counter]);
						$pagination.= "<li class='page-item'><a class='ajax page-link' title=' Page ".$counter."' href=\"".url($targetPage,null,$uriQuery)."\">$counter</a></li>";
					}
				}
			}
			elseif($lastpage > 5 + ($table->page['adjacents'] * 2))	//enough pages to hide some
			{
				//close to beginning; only hide later pages
				if($table->page['current'] < 1 + ($table->page['adjacents'] * 2))
				{
					for ($counter = 1; $counter < 2 + ($table->page['adjacents'] * 2); $counter++)
					{
						if ($counter == $table->page['current']) {
							$pagination.= "<li class='page-item disabled'><a class='page-link'>$counter</a></li>";
						}else {
							$uriQuery = array_merge($uriQuery,["page" => $counter]);
							$pagination.= "<li class='page-item'><a class='ajax page-link' title=' Page ".$counter."' href=\"".url($targetPage,null,$uriQuery)."\">$counter</a></li>";
						}
					}
					$pagination.= "<li class='page-item disabled'><a class='page-link'>...</a></li>";
					$uriQuery = array_merge($uriQuery,["page" => $lpm1]);
					$pagination.= "<li class='page-item'><a class='ajax page-link' title=' Page ".$lpm1."' href=\"".url($targetPage,null,$uriQuery)."\">$lpm1</a></li>";
					$uriQuery = array_merge($uriQuery,["page" => $lastpage]);
					$pagination.= "<li class='page-item'><a class='ajax page-link' title=' Page ".$lastpage."' href=\"".url($targetPage,null,$uriQuery)."\">$lastpage</a></li>";
				}
				//in middle; hide some front and some back
				elseif($lastpage - ($table->page['adjacents'] * 2) > $table->page['current'] && $table->page['current'] > ($table->page['adjacents'] * 2))
				{
					$uriQuery = array_merge($uriQuery,["page" => 1]);
					$pagination.= "<li class='page-item'><a class='ajax page-link' title=' Page 1' href=\"".url($targetPage,null,$uriQuery)."\">1</a></li>";

					$uriQuery = array_merge($uriQuery,["page" => 2]);
					$pagination.= "<li class='page-item'><a class='ajax page-link' title=' Page 2' href=\"".url($targetPage,null,$uriQuery)."\">2</a></li>";
					$pagination.= "<li class='page-item'><a class='page-link'>...</a></li>";
					for ($counter = $table->page['current'] - $table->page['adjacents']; $counter <= $table->page['current'] + $table->page['adjacents']; $counter++)
					{
						if ($counter == $table->page['current']) {
							$pagination.= "<li class='page-item disabled'><a class=\"page-link\">$counter</a></li>";
						}else {
							$uriQuery = array_merge($uriQuery,["page" => $counter]);
							$pagination.= "<li class='page-item'><a class='ajax page-link' title=' Page ".$counter."' href=\"".url($targetPage,null,$uriQuery)."\">$counter</a></li>";
						}
					}
					$pagination.= "<li class='page-item disabled'><a  class='page-link'>...</a></li>";
					$uriQuery = array_merge($uriQuery,["page" => $lpm1]);
					$pagination.= "<li class='page-item'><a class='ajax page-link' title=' Page ".$lpm1."' href=\"".url($targetPage,null,$uriQuery)."\">$lpm1</a></li>";

					$uriQuery = array_merge($uriQuery,["page" => $lastpage]);
					$pagination.= "<li class='page-item'><a class='ajax page-link' title=' Page ".$lastpage."' href=\"".url($targetPage,null,$uriQuery)."\">$lastpage</a></li>";
				}
				//close to end; only hide early pages
				else
				{
					$uriQuery = array_merge($uriQuery,["page" => 1]);
					$pagination.= "<li class='page-item'><a class='ajax page-link' title=' Page 1' href=\"".url($targetPage,null,$uriQuery)."\">1</a></li>";
					$uriQuery = array_merge($uriQuery,["page" => 2]);
					$pagination.= "<li class='page-item'><a class='ajax page-link' title=' Page 2' href=\"".url($targetPage,null,$uriQuery)."\">2</a></li>";
					$pagination.= "<li class='page-item disabled'><a  class='page-link'>...</a></li>";
					for ($counter = $lastpage - (($table->page['adjacents'] * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $table->page['current']) {
							$pagination.= "<li class='page-item disabled'><a class='page-link'>$counter</a></li>";
						}else {
							$uriQuery = array_merge($uriQuery,["page" => $counter]);
							$pagination.= "<li class='page-item'><a class='ajax page-link' title=' Page ".$counter."' href=\"".url($targetPage,null,$uriQuery)."\">$counter</a></li>";
						}
					}
				}
			}
			//next button
			if ($table->page['current'] < $counter - 1) {
				$uriQuery = array_merge($uriQuery,["page" => $next]);
				$pagination.= "<li class='page-item'><a class='ajax page-link' title=' Page ".($table->page['current'] + 1)."' href=\"".url($targetPage,null,$uriQuery)."\">next &raquo;</a></li>";
			} else {
				$pagination.= "<li class='page-item disabled'><a class=\"page-link\">next &raquo;</a></li>";
			}
				$pagination.= "</ul>";
			$pagination.= "</nav>";
			$pagination.= "</div>";
		}
		$pageNum = isset($_REQUEST['p']) ? "<div class='text-center'>Page <b>".$_REQUEST['p']."</b></div>" : "";

		return $pageNum.$pagination;
	}

	function list($uri="?", $targetPage = '') {
		/* Setup page vars for display. */
		if ($table->page['current'] == 0) $table->page['current'] = 1;					//if no page var is given, default to 1.
		$prev = $table->page['current'] - 1;							//previous page is page - 1
		$next = $table->page['current'] + 1;							//next page is page + 1
		$lastpage = ceil($table->rows/$table->page['limit']);		//lastpage is = total pages / items per page, rounded up.
		$lpm1 = $lastpage - 1;						//last page minus 1
		$pagination = array();
		if($lastpage > 1)
		{
			//pages
			if ($lastpage < 7 + ($table->page['adjacents'] * 2))	//not enough pages to bother breaking it up
			{
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					$pagination[] = "$counter";
				}
			}
			elseif($lastpage > 5 + ($table->page['adjacents'] * 2))	//enough pages to hide some
			{
				//close to beginning; only hide later pages
				if($table->page['current'] < 1 + ($table->page['adjacents'] * 2))
				{
					for ($counter = 1; $counter < 4 + ($table->page['adjacents'] * 2); $counter++)
					{
						$pagination[] = "$counter";
					}
					$pagination[] = "$lpm1";
					$pagination[] = "$lastpage";
				}
				//in middle; hide some front and some back
				elseif($lastpage - ($table->page['adjacents'] * 2) > $table->page['current'] && $table->page['current'] > ($table->page['adjacents'] * 2))
				{
					for ($counter = $table->page['current'] - $table->page['adjacents']; $counter <= $table->page['current'] + $table->page['adjacents']; $counter++)
					{
						$pagination[] = "$counter";
					}
					$pagination[] = "$lpm1";
					$pagination[] = "$lastpage";
				}
				//close to end; only hide early pages
				else
				{
					for ($counter = $lastpage - (2 + ($table->page['adjacents'] * 2)); $counter <= $lastpage; $counter++)
					{
						$pagination[] = "$counter";
					}
				}
			}
		}
		return $pagination;
	}
}