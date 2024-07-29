<?php

$html[] = "<div class='offcanvas-header'>";
  $html[] = "<h5 class='offcanvas-title'>Delete Premium Group</h5>";
  $html[] = "<button type='button' class='btn-close text-reset' data-bs-dismiss='offcanvas' aria-label='Close'></button>";
$html[] = "</div>";
$html[] = "<div class='offcanvas-body'>";

    $html[] = "<div class='text-center'>";
        $html[] = "<i class='ti ti-alert-triangle fs-32 text-danger'></i>";
        $html[] = "<p>This Premium Group will be deleted.</p>";
    $html[] = "</div>";

    $html[] = "<div class='form-floating '>";
        $html[] = "<input type='text' name='name' id='name' value='".$data['name']."' class='form-control-plaintext' readonly />";
        $html[] = "<label for='name'>Premium Group Name</label>";
    $html[] = "</div>";

    $html[] = "<div class='form-floating '>";
        $html[] = "<input type='text' value='".date("M d, Y", $data['created_at'])."' class='form-control-plaintext' readonly />";
        $html[] = "<label for='created_at'>Created Date</label>";
    $html[] = "</div>";

    $html[] = "<p class='mt-3'>All data related to this Premium Group will be permanently deleted and this action is ireversible, Are you sure do you want to continue the deletion of this premium group?</p>";

    $html[] = "<div class='btn-delete-controls'>";
		$html[] = "<div class='btn-list'>";
			$html[] = "<span class='btn text-dark bg-transparent' data-bs-dismiss='offcanvas'><i class='ti ti-x me-2'></i> Cancel</span>";
			$html[] = "<span data-url='".url("premiumgroups.delete",["id" => $data['premium_group_id']], ["delete" => "true"])."' data-row='row_".$data['premium_group_id']."' class='btn btn-danger btn-continue-delete'><i class='ti ti-trash me-2'></i> Continue Deletion</span>";
		$html[] = "</div>";
	$html[] = "</div>";

$html[] = "</div>";