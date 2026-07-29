<?php

namespace EO\Database;

class Pagination  implements \IteratorAggregate
{
	private int $totalRows = 1;
	private int $perPage = 2;
	private int $totalPages;
	private int $currentPage = 1;
	private int $adjacent;
	private int $itemStartingNumber;
	private string $path;
	private array $urlParameters = [];
	private array $links = [];
	private $items;

	function __construct() 
	{
		$this->path = request()->getUrl()->getPath();
		$this->urlParameters = input()->all();
	}

	function setItems($items)
	{
		$this->items = $items;

		$this->totalPages = ceil($this->totalRows / $this->perPage);
		
		if($this->totalPages > 0) {
			$this->links = $this->generateLinks();
		}

		return $this;
	}

	function getItems()
	{
		return $this->items;
	}

	public function getIterator(): \Traversable
    {
        return (function () {
			foreach($this->items as $key => $val) {
                yield $key => $val;
            }
        })();
    }

	function setTotalRows($total_rows)
	{
		$this->totalRows = $total_rows;
	}

	function getTotalRows()
	{
		return $this->totalRows;
	}

	function getPagination() 
	{
		if(!isset($this->totalRows)) {
			return false;
		}

		$this->adjacent = $this->getAdjacent();
		$this->itemStartingNumber = $this->getItemStartingNumber();

		return [
			"totalRows" => $this->totalRows,
			"perPage" => $this->perPage,
			"totalPages" => $this->totalPages,
			"currentPage" => $this->currentPage,
			"adjacent" => $this->adjacent,
			"itemStartingNumber" => $this->itemStartingNumber,
			"path" => $this->path,
			"urlParameters" => $this->urlParameters,
			"links" => $this->links
		];
	}

	function setPath(string $path) 
	{
		$this->path = $path;
		return $this;
	}

	function getPath() 
	{
		return $this->path;
	}

	function setCurrentPage(int $page)
	{
		$this->currentPage = (in_array($page, range(0, 10000)) ? $page : 10000000);
		return $this;
	}

	function getCurrentPage()
	{
		return $this->currentPage;
	}

	function setUrlParameters(array $uri = []) 
	{
		$this->urlParameters = $uri;
		return $this;
	}

	function getUrlParameters() 
	{
		return $this->urlParameters;
	}

	function setPerPage(int $limit)
	{
		$this->perPage = $limit;
		return $this;
	}

	function getPerPage() 
	{
		return intval($this->perPage) == 0 ? 20 : intval($this->perPage);
	}

	function setAdjacent(int $adjacent)
	{
		$this->adjacent = $adjacent;
		return $this;
	}

	function getAdjacent() 
	{
		return ($this->currentPage - 1) * $this->perPage;
	}

	function setItemStartingNumber(int $starting_number)
	{
		$this->itemStartingNumber = $starting_number;
		return $this;
	}

	function getItemStartingNumber() 
	{
		return $this->currentPage > 1 ? ($this->adjacent + 1) : 1;
	}

	function getTotalPages() 
	{
		return $this->totalPages;
	}

	function getLinks() 
	{
		return $this->links ?? [];
	}

	function generateLinks()
	{
		$url_parameters = $this->urlParameters;
		$target_page = $target_page ?? $this->path;
		$rows = ($this->totalRows >= 1000) ? 1000 : $this->totalRows;

		/* Setup page vars for display. */
		if ($this->currentPage == 0) $this->currentPage = 1;					//if no page var is given, default to 1.
		$prev = $this->currentPage - 1;							//previous page is page - 1
		$next = $this->currentPage + 1;							//next page is page + 1
		$lastpage = $this->totalPages;		//lastpage is = total pages / items per page, rounded up.
		$lpm1 = $lastpage - 1;						//last page minus 1
		$pagination = [];

		$url_parameters = array_merge($url_parameters,["page" => $prev]);
		$pagination[] = [
			"link" => url($target_page, null, $url_parameters)->getAbsoluteUrl(),
			"value" => "<span aria-hidden='true'>&laquo;</span> Previous"
		];

		for($i=1; $i<=$lastpage; $i++) {
			$url_parameters = array_merge($url_parameters, ["page" => $i]);
			$pagination[] = [
				"link" => url($target_page, null, $url_parameters)->getAbsoluteUrl(),
				"value" => $i
			];
		}

		$url_parameters = array_merge($url_parameters,["page" => $next]);
		$pagination[] = [
			"link" => url($target_page, null, $url_parameters)->getAbsoluteUrl(),
			"value" => "Next <span aria-hidden='true'>&raquo;</span>"
		];

		return $pagination;
	}

}