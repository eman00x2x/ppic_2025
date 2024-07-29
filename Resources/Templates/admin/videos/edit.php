<?php

$html[] = "<div class='page-header d-print-none'>";
    $html[] = "<div class='container-xl'>";

        $html[] = "<div class='row g-2 align-items-center'>";
            
            $html[] = "<div class='col'>";
                $html[] = "<div class='page-pretitle'></div>";
                $html[] = "<h2 class='page-title'>Video</h2>";
            $html[] = "</div>";

            $html[] = "<div class='col-auto ms-auto d-print-none'>";
                
            $html[] = "</div>";

        $html[] = "</div>";

        $html[] = "<div class='response'></div>";
        
    $html[] = "</div>";
$html[] = "</div>";

$html[] = "<div class='page-body'>";
    $html[] = "<div class='container-xl'>";

        $html[] = "<form id='form' action='".url("videos.save.update", ["id" => $data['video_id']])."' method='POST'>";

            $html[] = "<input type='hidden' name='csrf_token' value='".csrf_token()."' />";

            $html[] = "<div class='card mb-3'>";
                $html[] = "<div class='card-body'>";

                    $html[] = "<h3 class='card-title'>Video Details</h3>";

                    $html[] = "<div class='row'>";
                        $html[] = "<div class='col-md-6 col-lg-6 col-sm-12 col-12'>";

                            $html[] = "<div class='form-floating mb-3'>";
                                $html[] = "<input type='text' name='title' id='name' value='".$data['title']."' class='form-control'  />";
                                $html[] = "<label for='title'>Video Title</label>";
                            $html[] = "</div>";

                            $html[] = "<div class='mb-3'>";
                                $html[] = "<label class='form-label' for='description'>Description</label>";
                                $html[] = "<textarea name='description' id='description' class='form-control' rows='10'>".$data['description']."</textarea>";
                            $html[] = "</div>";

                        $html[] = "</div>";
                        $html[] = "<div class='col-md-6 col-lg-6 col-sm-12 col-12'>";

                            $html[] = "<div class='form-floating mb-3'>";
                                $html[] = "<input type='text' name='url' id='url' value='".$data['url']."' class='form-control'  />";
                                $html[] = "<label for='title'>Youtube URL</label>";
                            $html[] = "</div>";

                            $html[] = "<div class='mb-3'>";
                                $html[] = "<label class='form-label' for='started_at'>Thumbnail Image</label>";
                                $html[] = "<span class='avatar avatar-xxxl' style='background-image: url(".$data['thumbnail_image'].")'></span>";
                            $html[] = "</div>";
                            
                        $html[] = "</div>";
                    $html[] = "</div>";
                $html[] = "</div>";
            $html[] = "</div>";

            $html[] = "<div class='text-end'>";
                $html[] = "<span class='btn btn-primary btn-save'><i class='ti ti-device-floppy me-1'></i> Save Premium Group</span>";
            $html[] = "</div>";

        $html[] = "</form>";

    $html[] = "</div>";
$html[] = "</div>";

