<?php

	function dateToIndo($date,$short=false){
		$day = array('Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu');
		$month = array('','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
		
		$d = strtotime($date);
		if($short){
			return $day[date('w',$d)].', '.date('d',$d).' '.date('M',$d).' '.date('Y',$d);
		}else{
			return $day[date('w',$d)].', '.date('d',$d).' '.$month[date('n',$d)].' '.date('Y',$d);
		}
	}

    function dbDateToForm($date){
        $d = explode("-",$date);

        return $d[2] . "/" . $d[1] . "/" . $d[0];
    }
    
