<?php
	
	class SendEmail{
		private $CI;
		private $email_method = 'smtp';
		private $email_auth;
		private $email_host;
		private $email_user;
		private $email_pass;
		private $email_port;
		private $email_template_folder;
		
		private $ccEmails = false;
		private $emailContent;
		
		function __construct($config = array()){
			$this->CI = & get_instance();
			$this->CI->load->config('email');
			$configs = $this->CI->config->item('email');
			#print_r($configs);exit;
			foreach($configs as $c=>$v){
				$key = 'email_'.$c;
				if(property_exists($this,$key)){
					$this->$key = $v;
				}
			}
		}
		
		function setTemplate($file,$replacer=array()){
			if(is_file($this->email_template_folder.$file)){
				if(count($replacer) > 0){
					$content = file_get_contents($this->email_template_folder.$file);
					foreach($replacer as $rep=>$v){
						$content = str_replace('#'.$rep.'#',$v,$content);
					}
					$this->emailContent = $content;
				}
			}
		}
		
		function getEmailContent(){
			return $this->emailContent;
		}
		
		function setCC($cc){
			$this->ccEmails = $cc;
		}
		
		function sendMail($sender,$receiver,$title,$html){
			if($this->email_method == 'smtp'){
				require_once "Mail.php";
				require_once "Mail/mime.php";
				$headers = array (
					'From' => $sender,
					'To' => $receiver,
					'Subject' => $title
				);
				
				$config = array (
						'host' => $this->email_host,
						'auth' => $this->email_auth,
						'port' => $this->email_port
						);
				if($this->email_auth){
					$config['username'] = $this->email_user;
					$config['password'] = $this->email_pass;
				}		
				#var_dump($config);exit;
				$smtp = Mail::factory('smtp',$config);
				 
				$mime = new Mail_mime("\n");
				$mime->setHTMLBody($html);
				$newbody = $mime->get();
				$headers = $mime->headers($headers);
				@ $mail = $smtp->send($receiver, $headers, $newbody);
				#print_r($mail);exit;
				if (PEAR::isError($mail)) {
				  #echo($mail->getMessage());
				  error_log("Email Error: ".$mail->getMessage()."\n",3,'/tmp/email.log');
				  return false;
				} else {
				  return true;
				}
			}else{
				return false;
			}
		}
	}
