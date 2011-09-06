<?php
if (!defined('IN_WPRO')) exit();
if (WPRO_SESSION_ENGINE=='PHP'&&!isset($_SESSION)) {
	
	/*
	* WysiwygPro custom session handler setup file.
	* If your application uses custom session handlers (http://www.php.net/manual/en/function.session-set-save-handler.php) 
	* then include your session handler functions into this file.
	* 
	* Or if your session requires a specific name you will need to set it here.
	*
	* If you want to add your application's user authentication routine to WysiwygPro then it should be added to this file.
	*
	* SIMPLIFIED EXAMPLE:
	
	// include custom session handler functions:
	include_once('mySessionHandlers.php');
	session_set_save_handler("myOpen", "myClose", "myRead", "myWrite", "myDestroy", "myGC");
	
	// start the session with a specific name if required:
	session_name('SessionName');
	session_start();
	
	*/
	
	@include "../../admin/common.inc.php";
	//session_start();
	
	class wproFtpinterface {
		
		function __construct () {
			
			global $a_configSite,$s_pathToRoot;

			// connect to FTP server and login
			$this->r_connection = ftp_connect((strlen($a_configSite["ftp_host"]) ? $a_configSite["ftp_host"] : "localhost"));
			$b_ftpLoggedIn = ftp_login($this->r_connection,$a_configSite["ftp_user"],$a_configSite["ftp_password"]);

			// change to ftp_path on FTP server
			ftp_chdir($this->r_connection,$a_configSite["ftp_path"]);

			// build filesystem path to ftp_path
			$this->s_ftpPath = $s_pathToRoot . "data/" . $a_configSite["upload_folder"] . "/";
		}
		
		function __destruct () {
			ftp_close($this->r_connection);
		}
		
		public function ftpPathFromServerPath ($s_path) {
			
			if (substr($s_path,0,strlen($this->s_ftpPath)) == $this->s_ftpPath) {
				return substr($s_path,strlen($this->s_ftpPath));
			} else {
				return false;
			}
		}
	}
	
}
?>