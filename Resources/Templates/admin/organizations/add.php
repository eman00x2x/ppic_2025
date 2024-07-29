<?php

$html[] = "<div class='page-header d-print-none'>";
    $html[] = "<div class='container-xl'>";

        $html[] = "<div class='row g-2 align-items-center'>";
            
            $html[] = "<div class='col'>";
                $html[] = "<div class='page-pretitle'></div>";
                $html[] = "<h2 class='page-title'>Organization</h2>";
            $html[] = "</div>";

            $html[] = "<div class='col-auto ms-auto d-print-none'>";
                
            $html[] = "</div>";

        $html[] = "</div>";

        $html[] = "<div class='response'></div>";
        
    $html[] = "</div>";
$html[] = "</div>";

$html[] = "<form id='uploadForm' class='d-none' action='".url("OrganizationsController@upload")."' method='POST' enctype='multipart/form-data'>";
    $html[] = "<input type='hidden' name='csrf_token' value='".csrf_token()."' />";
    $html[] = "<input type='file' name='browseFile' id='browseFile' value='' class='form-control' />";
$html[] = "</form>";

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container-xl'>";

        $html[] = "<form id='form' action='".url("organizations.save.new")."' method='POST'>";

            $html[] = "<input type='hidden' name='csrf_token' value='".csrf_token()."' />";

            $html[] = "<div class='card mb-3'>";
                $html[] = "<div class='card-body'>";
                    $html[] = "<div class='row'>";
                        $html[] = "<div class='col-md-6 col-lg-6 col-sm-12 col-12'>";

                            $html[] = "<h3 class='card-title'>Account Details</h3>";

                            $html[] = "<div class='form-floating mb-3'>";
                                $html[] = "<input type='text' name='name' id='name' value='' class='form-control'  />";
                                $html[] = "<label for='name'>Name</label>";
                            $html[] = "</div>";

                            $html[] = "<div class='form-floating mb-3'>";
                                $html[] = "<input type='text' name='description' id='description' value='' class='form-control'  />";
                                $html[] = "<label for='description'>Description</label>";
                            $html[] = "</div>";

                        $html[] = "</div>";
                        $html[] = "<div class='col-md-6 col-lg-6 col-sm-12 col-12'>";
                            
                        $html[] = "</div>";
                    $html[] = "</div>";
                $html[] = "</div>";
            $html[] = "</div>";

            $html[] = "<div class='text-end'>";
                $html[] = "<span class='btn btn-primary btn-save'><i class='ti ti-device-floppy me-1'></i> Save Organization</span>";
            $html[] = "</div>";

        $html[] = "</form>";

    $html[] = "</div>";
$html[] = "</div>";

