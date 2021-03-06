<?php
/**
 * Controller that processes cron jobs 
 *
 */
class CronController extends IndexController   {
	
	/**
	 * Backs up the database with an option of sending the backup via email 
	 *
	 */
	function backupAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$session = SessionWrapper::getInstance();
		$config = Zend_Registry::get('config'); 
		$formvalues = $this->_getAllParams();
		$result = array();
		$showverbose = true;
		$detect = '';
		if(!isEmptyString($this->_getParam('triggered'))){
			$showverbose = false;
		}
		if($this->_getParam('autocron') == 'yes'){
			$detect = '_cron';
		}
		
		# get the database connection parameters 
		$db_params = Zend_Controller_Front::getInstance()->getParam("bootstrap")->getPluginResource('db')->getParams(); debugMessage($db_params);
		
		#  configure your database variables below:
		$host_array = explode(":", $db_params['host']);
		$dbhost = $host_array[0]; #  Server address of your MySQL Server
		$dbuser = 'admin'; #  Username to access MySQL database
		$dbpass = '1nf0trade'; #  Password to access MySQL database
		$dbname = 'farmis'; #  Database Name
		$dbport = '3306'; 
		// exit();
		
		# Optional Options You May Optionally Configure
		$use_gzip = $config->backup->usegzip;  #  Set to No if you don't want the files sent in .gz format
		$remove_sql_file = $config->backup->removesqlfile; #  Set this to yes if you want to remove the .sql file after gzipping. Yes is recommended.
		$remove_gzip_file = $config->backup->removegzipfile; #  Set this to yes if you want to delete the gzip file also. I recommend leaving it to "no"
		
		# Configure the path that this script resides on your server.
		// $savepath = APPLICATION_PATH.$config->backup->scriptfolder; #  Full path to this directory. Do not use trailing slash!
		$savepath = BASE_PATH.DIRECTORY_SEPARATOR.'backup'; // debugMessage($savepath); 
		$send_email = $config->backup->sendemail;  #  Do you want this database backup sent to your email? Fill out the next 2 lines
		# email address
		$backupemail = $config->backup->backupemail;
		if(!isEmptyString($this->_getParam('email'))){
			$backupemail = $this->_getParam('email');
		}
		# attachment mime type - default for a text attachment 
		$attachment_mime_type = "text/plain"; 
		
		# set the maximum execution time to ensure that the backup is completed 
		ini_set("max_execution_time", 600);
		
		$date = date("dMy_Hi");
		# sql backup filename
		$sqlattachmentname = $dbname.$detect."_".$date.".sql";
		# zipped backup filename
		$gzipattachmentname = $dbname."_".$date.".tar.gz";
		# sql backup path
		$sqlscriptpath = $savepath.DIRECTORY_SEPARATOR.$sqlattachmentname;
		# zipped backup path
		$zipfilepath = $savepath.DIRECTORY_SEPARATOR.$gzipattachmentname;
		
		# before backingup, move all current files at root to the archive folder
		$sqlfiles = glob($savepath.DIRECTORY_SEPARATOR.'*.sql'); 
		$tarfiles = glob($savepath.DIRECTORY_SEPARATOR.'*.tar.gz'); 
		$archivefiles = glob($savepath.DIRECTORY_SEPARATOR.'archive'.DIRECTORY_SEPARATOR.'*');
		 
		// debugMessage($sqlfiles);
		foreach ($sqlfiles as $afile){
			$afile_filename = basename($afile);
			rename($afile, $savepath.DIRECTORY_SEPARATOR.'archive'.DIRECTORY_SEPARATOR.$afile_filename);
		}
		foreach ($tarfiles as $afile){
			$afile_filename = basename($afile);
			rename($afile, $savepath.DIRECTORY_SEPARATOR.'archive'.DIRECTORY_SEPARATOR.$afile_filename);
		}
		
		$time  = time();
		foreach($archivefiles as $file){
			if(is_file($file)){
				$seconds = 60*60*24*7;
				// $seconds = 60; // debugMessage($time - filemtime($file));
				if($time - filemtime($file) >= $seconds){ // 2 days
					// debugMessage($file);
					unlink($file);
				}
			}
		}
		
		$backupcommand = "mysqldump -R --add-drop-table --complete-insert --add-locks --quote-names --lock-tables -h ".$dbhost." -P ".$dbport." -u ".$dbuser." -p".$dbpass." ".$dbname.' -q > "'.$sqlscriptpath.'"'; debugMessage($backupcommand); // exit();
		passthru($backupcommand);
		
		if($showverbose){
			debugMessage("FARMIS Database backup completed to ".$sqlscriptpath);	
		}
		
		# create tar archive
		if($use_gzip=="yes"){		
			$zipline = "tar -czf ".$zipfilepath." ".$sqlscriptpath;
			shell_exec($zipline); debugMessage($zipline);
			debugMessage("Gzip of backup completed");
		}
		// exit();
		# set email attachment name and path depending on weather to form zip or not
		if($use_gzip=="yes"){
			$attachmentpath = $zipfilepath;
			$attachmentname = $gzipattachmentname;
			$attachment_mime_type = "application/gzip"; 
		} else {
			$attachmentpath = $sqlscriptpath;
			$attachmentname = $sqlattachmentname;
		}
		
		# send an email with a copy of the backup	
		if($send_email == "yes" ){
			$mail = Zend_Registry::get('mail');
			# build the mailer class 
			// $mail->addTo($config->get(APPLICATION_ENV)->get("databasebackupemail"));
			$mail->addTo($backupemail);
			$mail->setFrom($config->notification->emailmessagesender, $config->notification->notificationsendername);
			$mail->setSubject(sprintf($this->_translate->_("database_backup_subject"), getAppName(), date("j F Y h:iA"))); #  Subject in the email to be sent.
			$mail->setBodyHtml(sprintf($this->_translate->_("database_backup_body"), getAppName())); #  Brief Message.
			
			# attachmentpath is the full path to the file and attachmentname is the name of the file
			$at = new Zend_Mime_Part(file_get_contents($attachmentpath));
			$at->filename = $attachmentname; 
			$at->disposition = Zend_Mime::DISPOSITION_INLINE;
			$at->encoding = Zend_Mime::ENCODING_BASE64;
			$at->type = $attachment_mime_type; 
			$mail->addAttachment($at);
			// $mail->send(); 
			
			try {
				$mail->send();
				$message = getAppName()." Database backup sent to ".$backupemail;
				if($showverbose){
					debugMessage($message);
				} else {
					$result['message'] = $message;
					$result['result'] = 1;
				}
			} catch (Exception $e) {
				$message = 'Email notification not sent! '.$e->getMessage();
				if($showverbose){
					debugMessage($message);
				} else {
					debugMessage($message);
					$result['message'] = $message;
					$result['result'] = 0;
				}
			}
			
			$mail->clearRecipients();
			$mail->clearSubject();
			$mail->setBodyHtml('');
			$mail->clearFrom();
		}
		
		# remove sql file if condition is set
		if($remove_sql_file=="yes"){
			exec("rm -rf ".$sqlscriptpath);
		}
		# remove tar file if condition is set
		if($remove_gzip_file=="yes"){
			exec("rm -rf ".$attachmentpath);
		}
		
		if(!$showverbose){
			echo json_encode($result);
		}
	}
}

