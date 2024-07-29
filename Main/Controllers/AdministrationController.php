<?php

namespace Main\Controllers;

use Ifsnop\Mysqldump as IMysqldump;
use Library\Configuration;

class AdministrationController extends \Main\Controller 
{
	
	function __construct() {

		if($this->AuthService->userHasRole("Administrator") === false) {
			$this->response(403);
		}
		
	}
	
	function index() {

		$this->document->setTitle("Database Administration");
		
		$data['tables'] = [
			"mls_accounts",
			"mls_account_subscriptions",
			"mls_articles",
			"mls_deleted_threads",
			"mls_handshakes",
			"mls_kyc",
			"mls_leads",
			"mls_license_reference",
			"mls_listings",
			"mls_listing_images",
			"mls_messages",
			"mls_notifications",
			"mls_page_ads",
			"mls_premiums",
			"mls_settings",
			"mls_threads",
			"mls_traffics",
			"mls_transactions",
			"mls_users",
			"mls_user_login"
		];

		$path = ROOT."/Admin/DATABASE_BACKUP";
		$data['backup_files'] = array_values(array_diff(scandir($path), array('.', '..')));

		$this->setTemplate("administration".DS."default.php");
		return $this->render($data);
		
	}
	
	function queryResult() {
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			if(!isset($_POST['query'])) {
				die("Invalid Request!");
			}
			
			$db = \Library\Factory::getDBO();
			
			$query = $_POST['query'];
			$result = $db->query($query);
			
			if($result) {
				$i = 0;
				echo "<div class='table-responsive'>";
				echo '<table class="table table-bordered table-striped table-sm table-hover"><thead><tr>';
				while ($i < $db->numFields($result)) {
					$meta = $db->fetchFields($result, $i);
					
					echo '<th class="text-center">' . strtoupper($meta[$i]->name) . '</th>';
					$i = $i + 1;
				}
				echo '</tr></thead>';
				
				echo '<tbody>';
				$i = 0;
				while ($row = $db->fetchRow($result)) {
					echo '<tr>';
					$count = count($row);
					$y = 0;
					while ($y < $count)
					{
						$c_row = current($row);
						echo '<td class="fs-12 align-middle text-center">' . $c_row . '</td>';
						next($row);
						$y = $y + 1;
					}
					echo '</tr>';
					$i = $i + 1;
				}
				echo '</tbody>';
				echo '</table></div>';
				$db->freeResult($result);
			}else {
				echo "<p>Executed but no results.</p>";
			}
		}
		
	}
	
	function backupDatabase() {

		require_once(ROOT."/Vendor/ifsnop/mysqldump-php/src/Ifsnop/Mysqldump/Mysqldump.php");

		$db = \Library\Factory::getDBO();
		$config = new Configuration();

		$mysql_backup_file = 'backup-'.date("Y-m-d-g-iA",strtotime("NOW")).'.sql';

		try {

			$dump = new IMysqldump\Mysqldump('mysql:host='.$config->db_host.';dbname='.$config->db_name.'', $config->db_user, $config->db_pass);
			$dump->start(ROOT."/Admin/DATABASE_BACKUP/".$mysql_backup_file);
			
			$this->getLibrary("Factory")->setMsg("Successfully save the backup <i>$mysql_backup_file</i>.","success");

			echo getMsg();

		} catch (\Exception $e) {
			echo 'mysqldump-php error: ' . $e->getMessage();
		}

		exit();

	}

	function downloadBackup() {

		$url = url("AdministrationController@downloadBackup", null, ["file" => $_GET['file']]);
		$filename = $_GET['file'];
		$local_path = ROOT."/Admin/DATABASE_BACKUP";

		header("Content-Description: File Transfer");
		header('Content-Type: application/octet-stream');
		header("Content-disposition: attachment; filename=\"" . $filename . "\""); 
		header('Expires: 0');
    	header('Cache-Control: must-revalidate');
    	header('Pragma: public');
		header("Content-length: ".filesize($local_path."/".$filename));

		readfile($local_path."/".$filename); 
		exit();

	}

	function deleteBackup() {

		if(!isset($_GET['file'])) {
			$this->getLibrary("Factory")->setMsg("Can't find file.","error");
			echo getMsg();
		}

		$local_path = ROOT."/Admin/DATABASE_BACKUP";
		unlink($local_path."/".$_GET['file']);

		$this->getLibrary("Factory")->setMsg("Database backup file deleted.","success");
		echo getMsg();
		
		exit();

	}

}