<?php

$html[] = "<div class='offcanvas-header'>";
  $html[] = "<h5 class='offcanvas-title'>Delete Account</h5>";
  $html[] = "<button type='button' class='btn-close text-reset' data-bs-dismiss='offcanvas' aria-label='Close'></button>";
$html[] = "</div>";
$html[] = "<div class='offcanvas-body'>";

    $html[] = "<div class='text-center'>";
        $html[] = "<i class='ti ti-alert-triangle fs-32 text-danger'></i>";
        $html[] = "<p>This account will be deleted.</p>";
    $html[] = "</div>";

    $html[] = "<div class='form-floating '>";
        $html[] = "<input type='text' name='account_type' id='account_type' value='".$data['account_type']."' class='form-control-plaintext' readonly />";
        $html[] = "<label for='account_type'>Account Type</label>";
    $html[] = "</div>";

    $html[] = "<div class='form-floating '>";
        $html[] = "<input type='text' name='username' id='username' value='".$data['username']."' class='form-control-plaintext' readonly />";
        $html[] = "<label for='username'>Username</label>";
    $html[] = "</div>";

    $html[] = "<div class='form-floating '>";
        $html[] = "<input type='email' name='email' id='email' value='".$data['email']."' class='form-control-plaintext' readonly />";
        $html[] = "<label for='email'>Email</label>";
    $html[] = "</div>";

    $html[] = "<div class='form-floating '>";
        $html[] = "<input type='text' value='".date("M d, Y", $data['registered_at'])."' class='form-control-plaintext' readonly />";
        $html[] = "<label for='registered_at'>Registration Date</label>";
    $html[] = "</div>";

    $html[] = "<p class='mt-3'>All data related to this account will be permanently deleted and this action is ireversible, Are you sure do you want to continue the deletion of this account?</p>";

    $html[] = "<div class='btn-delete-controls'>";
		$html[] = "<div class='btn-list'>";
			$html[] = "<span class='btn text-dark bg-transparent' data-bs-dismiss='offcanvas'><i class='ti ti-x me-2'></i> Cancel</span>";
			$html[] = "<span data-url='".url("AccountsController@delete",["id" => $data['account_id']], ["delete" => "true"])."' data-row='row_".$data['account_id']."' class='btn btn-danger btn-continue-delete'><i class='ti ti-trash me-2'></i> Continue Deletion</span>";
		$html[] = "</div>";
	$html[] = "</div>";

$html[] = "</div>";