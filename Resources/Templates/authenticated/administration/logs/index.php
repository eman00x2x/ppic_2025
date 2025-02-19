<?php

use EO\View;

View::setMasterTemplate(path: "/authenticated/template.php");

/** Document Header Configuration */
View::setDocumentHeader(
	data: [
		'title' => 'Logs',
		'description' => 'List of all logs',
		'styles' => [
			CDN . "/js/vendor/hljs/styles/sunburst.min.css"
		],
		'scripts' => [
			CDN . "/js/vendor/hljs/highlight.min.js",
			CDN . "/js/main/app/logs.js"
		]
	]
);

/** Document Top Configuration */
View::define(
	name: "document_top",
	path: "/authenticated/includes/document_top.php", 
	data: [
		"title" => "<i class='ti ti-book me-2  fs-32'></i> Logs",
		"description" => "List of all logs"
	]
);

View::define(
	name: "toolbar",
	path: "/authenticated/includes/toolbar.php",
	data: [
		"controller" => "LogsController",
		"toolbar" => [
			"actions" => "logs_toolbar_actions",
			"filter" => "logs_toolbar_filter",
			"components" => ["sort", "limit"]
		],
		"url" => "LogsController@index",
		"sort_by" => ["time"],
		"rows" => [20, 50, 80, 100, 200, 500, 1000]
	]
);

	View::define(
		name: "logs_toolbar_actions",
		path: "/authenticated/includes/toolbar_actions/logs_toolbar_actions.php",
		data: []
	);

	View::define(
		name: "logs_toolbar_filter",
		path: "/authenticated/includes/toolbar_filters/logs_toolbar_filter.php",
		data: []
	);

$html[] = View::include("document_top");

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container-xl'>";

        $html[] = "<div class='card'>";
			
			$html[] = "<div class='card-body border-bottom py-3'>";

				$html[] = View::include("toolbar");

				$html[] = "<div class='accordion accordion-flush' id='logs-accordion'>";
					foreach($data as $log) {
						$html[] = "<div class='accordion-item row_".$log['log_id']."'>";
							$html[] = "<h2 class='accordion-header' id='".$log['log_id']."'>";
								$html[] = "<div class='d-flex align-items-center'>";
									$html[] = "<div class=''>";
										$html[] = "<input type='checkbox' class='form-check-input form-check-input-selection m-0 align-middle cursor-pointer log_id' data-uuid='".$log['log_id']."' value='".$log['log_id']."' />";
									$html[] = "</div>";
									$html[] = "<div class='flex-grow-1'>";
										$html[] = "<button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#logs-".$log['log_id']."' aria-expanded='false'>";
											$html[] = "<div class=''>".$log['main_message']." > [".($log['context']['route'] ?? "")."]</div>";
										$html[] = "</button>";
									$html[] = "</div>";
								$html[] = "</div>";
							$html[] = "</h2>";
							$html[] = "<div class='accordion-collapse collapse ".(($log['log_id'] == $data[0]['log_id']) ? "show" : "")."' data-bs-parent='#logs-accordion'  id='logs-".$log['log_id']."' aria-labelledby='".$log['log_id']."'>";
								$html[] = "<div class='accordion-body pt-0'>";
									$html[] = "<pre><code class='language-JavaScript'>".json_encode($log['context']['data'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)."</code></pre>";

									if(isset($log['context']['data']['trace'])) {
										$html[] = "<h3>Stack Trace</h3>";
										$html[] = "<pre>";
											$html[] = $log['context']['data']['trace'];
										$html[] = "</pre>";
									}

								$html[] = "</div>";
							$html[] = "</div>";
						$html[] = "</div>";
					}
				$html[] = "</div>";

				$html[] = View::getPaginationTemplate();
				
			$html[] = "</div>";
        $html[] = "</div>";
    
    $html[] = "</div>";
$html[] = "</div>";