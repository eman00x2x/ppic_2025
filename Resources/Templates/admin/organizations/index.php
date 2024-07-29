<?php

$html[] = "<div class='page-header d-print-none'>";
    $html[] = "<div class='container-xl'>";
        $html[] = "<div class='row g-2 align-items-center'>";
            
            $html[] = "<div class='col'>";
                $html[] = "<div class='page-pretitle'></div>";
                $html[] = "<h2 class='page-title'>Organizations</h2>";
            $html[] = "</div>";

            $html[] = "<div class='col-auto ms-auto d-print-none'>";
                $html[] = "<div class='btn-list'>";
                    $html[] = "<a href='".url("OrganizationsController@add")."' class='btn btn-primary'><i class='ti ti-plus me-1'></i> New Organization</a>";
                $html[] = "</div>";
            $html[] = "</div>";

        $html[] = "</div>";

        $html[] = "<div class='response'></div>";
        
    $html[] = "</div>";
$html[] = "</div>";

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container-xl'>";

        $html[] = "<div class='d-flex flex-wrap flex-sm-no-wrap mb-2'>";

            $html[] = "<div class='flex-grow-1 '>";
                $html[] = "<div class='bg-white border d-flex gap-2 align-items-center mb-1'>";

                    $html[] = "<div class='input-icon w-100 ms-2'>";
                        $html[] = "<span class='input-icon-addon'><i class='ti ti-search'></i></span>";
                        $html[] = "<input type='text' id='search' class='form-control border-0 p-3 ms-4' placeholder='Search' data-url='".url("OrganizationsController@index")."' />";
                    $html[] = "</div>";
                    
                    $html[] = "<span class='mx-3 cursor-pointer'>";
                        $html[] = "<i class='ti ti-adjustments-horizontal me-1'></i> ";
                    $html[] = "</span>";
                $html[] = "</div>";
            $html[] = "</div>";

            $html[] = "<div class='ms-1 cursor-pointer mb-1'>";
                $html[] = "<span class='btn dropdown-toggle rounded-0 p-3' data-bs-toggle='dropdown'><i class='ti ti-arrows-up-down me-1'></i> <span class='d-none d-sm-block'>Sort by</span></span>";
                $html[] = "<div class='dropdown-menu'>";

                    $request = $model->page['uri'];
                    foreach(["name", "created_at"] as $field) {
                        $name = ucwords(str_replace("_"," ", $field));

                        $direction = "ASC";
                        $sorting = $field."|".$direction;
                        if(isset($_GET['sort'])) {
                            $sort = explode("|", $_GET['sort']);
                            if($field === $sort[0]) {
                                if($sort[1] === "ASC") {
                                    $direction = "DESC";
                                }else {$direction = "ASC";}
                                $sorting = $field."|".$direction;
                            }
                        }

                        $request["sort"] = $sorting;
                        $html[] = "<a class='dropdown-item d-flex justify-content-between' href='".url("OrganizationsController@index", null, $request)."'><span>".$name."</span> <span class='text-muted fs-11'>$direction</span></a>";
                    }
                $html[] = "</div>";
            $html[] = "</div>";

            $html[] = "<div class='ms-1 cursor-pointer mb-1'>";
                $html[] = "<span class='btn dropdown-toggle rounded-0 p-3' data-bs-toggle='dropdown'><i class='ti ti-table-down me-1'></i> <span class='d-none d-sm-block'>Rows</span></span>";
                $html[] = "<div class='dropdown-menu'>";
                    $limit = $model->page['uri'];
                    foreach([20, 50, 80, 100, 200, 500, 1000] as $rows) {                        
                        $limit["rows"] = $rows;
                        $html[] = "<a class='dropdown-item d-flex justify-content-between' href='".url('OrganizationsController@index', null, $limit)."'><span>Show $rows rows</span></a>";
                    }
                $html[] = "</div>";
            $html[] = "</div>";

            $html[] = "<a href='".url("OrganizationsController@index")."' class='btn rounded-0 ms-1 mb-1'>Clear filter</a>";

        $html[] = "</div>";

        $html[] = "<div class='list'>";
            $html[] = "<div class='table-responsive'>";
                $html[] = "<table class='table table-striped accounts border border-1'>";
                    $html[] = "<thead>";
                        $html[] = "<tr>";
                            $html[] = "<th class='align-middle text-center'>#</th>";
                            $html[] = "<th class='align-middle'>Name</th>";
                            $html[] = "<th class='align-middle'>Description</th>";
                            $html[] = "<th class='align-middle'>Created Date</th>";
                            $html[] = "<th class='align-middle'>Actions</th>";
                        $html[] = "</tr>";
                    $html[] = "</thead>";
                    $html[] = "<tbody class='data-container'>";
                        if($data) {
                            for($i=0; $i<count($data); $i++) {
                                
                                $model->page['starting_number']++;

                                $html[] = "<tr class='row_".$data[$i]['organization_id']."'>";
                                    $html[] = "<td class='text-center'>".$model->page['starting_number']."</td>";
                                    $html[] = "<td>".$data[$i]['name']."</td>";
                                    $html[] = "<td>".$data[$i]['description']."</td>";
                                    $html[] = "<td>".$data[$i]['created_at']."</td>";
                                    $html[] = "<td class='d-flex gap-2'>";
                                        $html[] = "<span><a href='".url("organizations.edit", ["id" => $data[$i]['organization_id']])."' class='btn btn-sm btn-primary'><i class='ti ti-edit fs-14 me-1'></i>Edit</a></span>";
                                        $html[] = "<span><span class='btn btn-sm btn-danger btn-show-offconvas' data-url='".url("organizations.delete", ["id" => $data[$i]['organization_id']])."' data-bs-toggle='offcanvas' data-bs-target='#offcanvasEnd' role='button' aria-controls='offcanvasEnd'><i class='ti ti-trash fs-14 me-1'></i>Delete</span></span>";
                                    $html[] = "</td>";
                                $html[] = "</tr>";

                            }
                        }
                    $html[] = "</tbody>";
                $html[] = "</table>";
            $html[] = "</div>";

            $html[] = "<div class='d-flex flex-column flex-md-row justify-content-center align-items-center justify-content-md-between mt-3 '>";
                $html[] = "<p id='page-numbers'></p>";
                $html[] = "<div class='page-buttons btn-group'>";
                    $html[] = $model->pagination;
                $html[] = "</div>";
            $html[] = "</div>";

        $html[] = "</div>";
    
    $html[] = "</div>";
$html[] = "</div>";