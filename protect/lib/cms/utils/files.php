<?
	// FILE FUNCTIONS ************************************************************************************
	class cms_utils_files{
		static function filSiz($f){ // size of a file in adequat dimension Mo, Ko, bytes
			return file_exists($f)?self::prettyBytes(filesize($f)):0;
		}
		static function prettyBytes($size,$precision=1){
			global $lng;
			$range=array('b','Kb','Mb','Gb','Tb','Pb','Eb');
			if($lng=="fr")$range=array('b','Ko','Mo','Go','To','Po','Eo');
			for($i=0;$size>=1024&&$i<count($range);$i++)$size/=1024;
			return ($size<1&&$i==0?"0b":round($size,$precision).$range[$i]);
		}
		static function copyFile($fil,$dest){
			if(file_exists($fil)){	// copy a file and keep it's time stamp
				copy($fil,$dest);chmod($dest,0755);touch($dest,filemtime($fil));
			}
		}
		static function moveFile($fil,$dest){	// move a file and keep it's timestamp
			self::copyFile($fil,$dest);if(file_exists($fil))unlink($fil);
		}
		static function save($fil,$txt){	// Save a file protecting with lock/unlock
			file_put_contents($fil,$txt,LOCK_EX);
			@chmod($fil,0777);
		}
		static function http($pag){
			$car=array("a"=>array("â","ä","à"),"e"=>array("é","è","ë","ê"),"u"=>array("ï","ö","û","ù","ü"),""=>array(">",",","'",'"'));
			foreach($car as $v=>$a)$pag=str_replace($a,$v,$pag);
			$pag=explode(" ",$pag);$p=strtolower($pag[0]);
			for($i=1;$i<count($pag);$i++)$p.=strtoupper(substr($pag[$i],0,1)).substr(strtolower($pag[$i]),1);
			if(strstr($p,".")){$x=explode(".",$p);$p=$x[0].".".strtolower($x[1]);}
			return $p;
		}

		function read($fil,&$x){	// Read a file protecting with lock/unlock
			lock($fil);
			$x=file($fil);
			unlock($fil);
		}
		function lock($fil){
			$k=0;while(file_exists("$fil.lck")&&$k<3){
				clearstatcache();
				$k++;if($k<3)sleep(1);
			}
			if($k<3){$n=@fopen("$fil.lck","x");@fclose($n);}
		}
		function unlock($fil){@unlink("$fil.lck");}
		function tgz($src,$tar="",$rel=""){
			if($tar=="")$tar=$src;
			//exec("tar -cpf $tar.tar".($rel!=""?" -C $rel":"").($src!=""?" $src":""));
			exec("tar -zcpf $tar.tar.gz".($rel!=""?" -C $rel":"").($src!=""?" $src":""));
	//		exec("tar -jcpf $tar.tar.bz2".($rel!=""?" -C $rel":"").($src!=""?" $src":""));
			exec("zip -9rq $tar ".($rel!=""?"$rel/":"").$src);
			rename("$tar.tar.gz","$tar.tgz");
	//		rename("$tar.tar.bz2","$tar.tbz2");
		}
		function checkSum($f){return DecHex(crc32(implode("",file($f)))*1);}
	}
?>
