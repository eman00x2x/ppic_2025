<?php

namespace Library;

// no direct access

defined( 'ACCESS' ) or die( 'Restricted access' );

class Message {

	var $msg = '';

	var $tag = 'div';

	var $error = ' alert  alert-danger ';
	var $warning = ' alert  alert-warning ';
	var $info = ' alert  alert-primary ';
	var $done = ' alert  alert-success ';
	var $id = '';


	function setHtmlTag($tag,$error,$warning,$info,$done,$id) {

		$this->tag = $tag;
		$this->error = $error;
		$this->warning = $warning;
		$this->info = $info;
		$this->done = $done;
		$this->id = $id;

	}

	function buildErrorMsg($msg,$type) {

		$openTag = "<";
		$closeTag = "/" . $this->tag. ">";

		switch($type) {

			case 'error':
				$mes = "Error!";
				$class = $this->error;
				$icon = "ti ti-alert-triangle";
			break;

			case 'warning':
				$mes = "Warning!";
				$class = $this->warning;
				$icon = "ti ti-alert-triangle";
			break;

			case 'info':
				$mes = "";
				$class = $this->info;
				$icon = "ti ti-bell";
			break;

			case 'success':
				$mes = "Done!";
				$class = $this->done;
				$icon = "ti ti-check";
			break;

			default:
				$mes = "Error!";
                $class = $this->error;
                $icon = "ti ti-alert-triangle";
            

		}

		$this->msg = $openTag . $this->tag . " class='message " . $class . " alert-dismissible' id='" . $this->id . "'>";
			$this->msg .= "<div class='d-flex'>";
				$this->msg .= "<div class=''><i class='$icon me-2' aria-hidden='true'></i></div>";
				$this->msg .= "<div class=''>";
					$this->msg .= "<p class='p-0 m-0'>$mes $msg</p>";
				$this->msg .= "</div>";
			$this->msg .= "</div>";
			$this->msg .= "<button type='button' class='btn-close' data-bs-dismiss='alert'></button>";
		$this->msg .= " " . $openTag . $closeTag;

		return $this->msg;

	}

}