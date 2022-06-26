<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Generate_mail extends CI_Model{
	
	public $mailfrom;
	public $namefrom;
	public $mailto;
	public $mailcc;
	public $mailbcc;
	public $mailsubject;
	public $mailmessage;
	
	public $mailto_list;
	
	public function __construct()
	{
		parent::__construct();		
	}
	
	public function mail_send(){
		try
		{	
			
			$mailcount = count($this->mailto_list);
			$i = 1;		
			
			foreach ($this->mailto_list as $row)
			{
				if ($i < $mailcount){
					$this->mailto .=  $row->user_email.",";
				}else{
					$this->mailto .=  $row->user_email;
				}
				
				$i++;
			}
			
			// message
			$message = '<html>
                    <head>
                    </head>
                    <body>';
			
			$message .= $this->mailmessage;
					
			$message .= '</body>
                    </html>';
					
			// To send HTML mail, the Content-type header must be set
			$headers  = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
			
			//$headers .= 'To: '.$this->mailto.'\r\n';
			$headers .= 'From: '.$this->namefrom.'<'.$this->mailfrom.'>'."\r\n";			
			
			// Mail it
			mail($this->mailto, $this->mailsubject, $message, $headers);
			
			return TRUE;
			
		}
		catch(Exception $e)
		{
			log_message('error', $e->getMessage());
			return FALSE;
		}
	}
	
}

?>