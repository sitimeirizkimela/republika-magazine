<?php
	
	if(!function_exists('writeLog')){
		function writeLog($message){
			echo now()." ~ ".$message."\n";
		}
	}
	
	if(!function_exists('now')){
		function now(){
			return date("Y-m-d H:i:s");
		}
	}


	function cronLockOpen($pathLock){		
		// analyze locking
		if( file_exists($pathLock) ){
				if (($handle = fopen($pathLock, "r")) !== FALSE) {
				$data = fgetcsv($handle, 100, ",");
				fclose($handle);
				$result = exec('ps ' . $data[0]);
				//var_dump($result);
				if( strpos($result,$data[0]) === false ) {
					@unlink($pathLock);
				}
				else{
					die('locked!');        	
				}
			}	
		}
		
		// create lock file
		file_put_contents($pathLock, getmypid().', locked on ' . date("Y-m-d H:i:s"));
		//file_put_contents($pathLock, 'locked on ' . date("Y-m-d H:i:s"));
	}

	function cronLockClose($pathLock){
		if(file_exists($pathLock)) unlink($pathLock);
	} 
