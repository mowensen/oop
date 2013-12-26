<?
	function sqlFindMissingValue($table,$field,$min=0){
		makeVar($x,"select ifnull(min(0+$field),$min),ifnull(max(0+$field)+1,1),count($field) from $tbl");list($min,$id,$n)=$x[0];
		if($n<$id-$min){
			makeVar($x,"select 2+max(a.$field +1) from $table as a , $table as b where a.$field+1=b.$field");$id=$x[0][0];
		}
		return $id;
	}


	// SPECIAL FILE FUNCTIONS ************************************************************************************************
	function phpToJsVar($fil){
		read($fil,$x);$t="";foreach($x as $y){$y=trim($y);if(substr($y,0,1)=="\$")$t.=str_replace("\$","",$y);}
		return $t;
	}
	// ramasse toutes les variables dans le fichier (où un seul "=" sur la ligne) et les crées en php
	function jsToPhpVar($fil){
		read($fil,$x);
		for($i=0;$i<count($x);$i++){
			$y=explode("=",str_replace("=>","",$x[$i]));
			if(count($y)==2){global ${str_replace(".","_",$y[0])};eval("\$".str_replace(".","_",$x[$i]));}
		}
	}
	// enregistre les infos en javascript pour la variable $var
	function savAdmMnu($var){
		global $$var,$webPath;
		$file=$webPath."javascript/adminMenu.js";
		read($file,$x);$x[]="\n";
			$j=count($x);$x[$j].="\n";for($i=0;$i<count($x);$i++)if(strstr($x[$i],$var)!="")$j=$i;
			if($$var!=strval(round($$var)))$x[$j]="$var=\"".str_replace("\\","",str_replace("'","",$$var))."\";\n";
			else$x[$j]="$var=".$$var.";\n";
			$txt="";for($i=0;$i<count($x);$i++)$txt.=$x[$i];
		save($file,str_replace("\r","",str_replace("\n\n","\n",$txt)));
	}
	// enregistre les infos en javascript pour la variable $var
	function savShwMnu($var,$fil="Show"){
		global $$var,$webPath;
		$file=$webPath."javascript/admin$fil.js";
		read($file,$x);$x[]="\n";
			$j=count($x);$x[$j]="\n";for($i=0;$i<count($x);$i++)if(strstr($x[$i],str_replace("_",".",$var))!="")$j=$i;
			if($$var!=strval(round($$var)))$x[$j]=str_replace("_",".",$var)."=\"".str_replace("\\","",str_replace("'","",$$var))."\";\n";
			else$x[$j]="$var=".$$var.";\n";
			$txt="";for($i=0;$i<count($x);$i++)$txt.=$x[$i];
		save($file,str_replace("\r","",str_replace("\n\n","\n",$txt)));
	}
	// sert à rajouter un id dans une var ",idB,id1,...,idn,"
	function mdfVarAdmMnu($x,&$mdfPag,$del=false){
		if(strstr($mdfPag,",".$x.",")=="")$mdfPag.=",".$x.",";
		if($del)$mdfPag=str_replace(",".$x.",",",",$mdfPag);
		while(strstr($mdfPag,",,")!="")$mdfPag=str_replace(",,",",",$mdfPag);
	}

	// FORMAT FUNCTIONS ************************************************************************************************
	function generatePassword($length=8){// generate a password
		$password="";
		$possible="0123456789bcdfghjkmnpqrstvwxyz"; 
		for($i=0;$i<8;$i++){
			$char=substr($possible,mt_rand(0,strlen($possible)-1),1);
			if (!strstr($password, $char))$password.= $char;
		}
		return $password;
	}
	function trunc($txt,$n,$dots="..."){ // trunc a text
		$t=substr($txt,0,$n);if($t!=$txt){$x=explode(" ",$t);$t="";for($i=0;$i<count($x)-1;$i++)$t.=$x[$i]." ";$t.=$dots;}return $t;
	}
	function more($t,$n){
		$x=array();
		if(strstr($t," ")!="")$x=explode(" ",substr($t,0,$n));else $x[0]=$t;
		if($x[0]>$n||count($x)==1)return substr($t,0,$n);
		else $y="";for($i=0;$i<count($x)-1;$i++)$y.=$x[$i]." ";return $y."...";
	}
	function dec2Alpha($number){
	}
	function dec2Roman($number){
		# Defining arrays
		$romanNumbers=array(1000,500,100,50,10,5,1);
		$romanLettersToNumbers=array("M"=>1000,"D"=>500,"C"=>100,"L"=>50,"X"=>10,"V"=>5,"I"=>1);
		$romanLetters=array_keys($romanLettersToNumbers);
		# Looping through and adding letters.
		while($number){
			for($pos=0;$pos <= 6;$pos++){
				# Dividing the remaining number with one of the roman numbers.
				$dividend=$number / $romanNumbers[$pos];
				# If that division is >= 1, round down, and add that number of letters to the string.
				if($dividend >= 1){
					$linje .= str_repeat($romanLetters[$pos],floor($dividend));
					# Reduce the number to reflect what is left to make roman of.
					$number -= floor($dividend) * $romanNumbers[$pos];
				}
			}
		}
		# If I find 4 instances of the same letter, this should be done in a different way.
		# Then, subtract instead of adding (smaller number in front of larger).
		$numberOfChanges=1;
		while($numberOfChanges){
			$numberOfChanges=0;
			for($start=0;$start < strlen($linje);$start++){
				$chunk=substr($linje,$start,1);
				if($chunk==$oldChunk&&$chunk!="M"){
					$appearance++;
				}else{
					$oldChunk=$chunk;
					$appearance=1;
				}
				# Was there found 4 instances.
				if($appearance==4){
					$firstLetter=substr($linje,$start - 4,1);
					$letter=$chunk;
					$sum=$firstNumber + $letterNumber * 4;

					$pos=array_search($letter,$romanLetters);

					if($romanLetters[$pos - 1]==$firstLetter){
						$oldString=$firstLetter.str_repeat($letter,4);
						$newString=$letter.$romanLetters[$pos - 2];
					}else{
						$oldString=str_repeat($letter,4);
						$newString=$letter.$romanLetters[$pos - 1];
					}
					$numberOfChanges++;
					$linje=str_replace($oldString,$newString,$linje);
				}
			}
		}
		return $linje;
	}



	// DATABASE FUNCTIONS ************************************************************************************
	function duplicate($tbl,$lng,$prm){
		global $dLng;
		makeVar($col,"show columns from ".$tbl);
		makeVar($val,"select * from ".$tbl.$prm." and lngId=".$dLng);
		$txt="insert into $tbl(";
		for($i=0;$i<count($col);$i++)$txt.=$col[$i][0].($i!=count($col)-1?",":"");
		$txt.=")values(";for($i=0;$i<count($col);$i++)$txt.="'".($col[$i][0]!="lngId"?$val[0][$i]:$lng)."'".($i!=count($col)-1?",":"");
		$txt.=")";
		query($txt);
	}
	function getTrace($okUpd=1){
		$ru="'".$GLOBALS["REMOTE_USER"]."'";$ip="'".$GLOBALS["REMOTE_ADDR"]."'";$dt="'".date("Y-m-d H:i:s",time())."'";
		return ($okUpd?"xtrace=$ru,xip=$ip,xdate=$dt":"$ru,$ip,$dt");
	}
	function trace($record,$op,$data="",$date=""){ // trace an operation in adminTrace table
		if(!$date)$date=date("Y-m-d H:i:s",time());
		$ru=$GLOBALS["REMOTE_USER"];$ra=$GLOBALS["REMOTE_ADDR"];
		makeVar($x,"select xtrace,xip,xdate from adminTrace where record='$record' and op='$op' and data='$data'");
		makeVar($y,"select xip from adminTrace where xtrace='$ru' and xip='$ra' and xdate='$date'");
//		if($ra=="82.228.250.68")alert(count($y)." $ra $ru $date");
		if(count($y)<1){	// on ne peut enregistrer qu'une fois par seconde pour le même utilisateur car date est une clef limitée par secondes
			if(count($x)==1){
				query("update adminTrace set xtrace='$ru',xip='$ra',xdate='$date' where record='$record' and op='$op' and data='$data'");
			}else{
				if(count($x)>1)query("delete from adminTrace where record='$record' and op='$op' and data='$data'");
				query("insert into adminTrace(record,op,data,xtrace,xip,xdate)values('$record','$op','$data','$ru','$ra','$date')");
			}
		}
	}





	function tstGetVar($strVar){
		global $HTTP_GET_VARS,$HTTP_POST_VARS,$$strVar;
		if(isset($HTTP_GET_VARS[$strVar])){
			$$strVar=$HTTP_GET_VARS[$strVar];
			session_register($strVar);
			$$strVar=$HTTP_GET_VARS[$strVar];
		}
		if(isset($HTTP_POST_VARS[$strVar])){
			$$strVar=$HTTP_POST_VARS[$strVar];
			session_register($strVar);
			$$strVar=$HTTP_POST_VARS[$strVar];
		}
	}
	function sortAry(&$ary){
		sort($ary);$ar=array();$ar[0]=$ary[0];for($i=1;$i<count($ary);$i++)if($ary[$i]!=$ary[$i-1])$ar[]=$ary[$i];$ary=$ar;
	}
	function makeAry($ary,$query=""){
		global $txtAry,$var;
		$var=array();
		if($query!=""){makeVar($var,str_replace(";;","",$query));}else{global $$ary;$var=$$ary;}
		$txtAry.=$ary."=new Array(";
		for($i=0;$i<count($var);$i++)
			for($j=0;$j<count($var[$i]);$j++)
				$txtAry.="'".$var[$i][$j]."',";
		if (strpos($txtAry,",",strlen($txtAry)-1)){$txtAry=substr($txtAry,0,strlen($txtAry)-1);}; //takes out the last comma
		$txtAry.=");";
	}
	function jsAry($x){
		global $$x;
		$txt=$x."=new Array(";
		for($i=0;$i<count($$x);$i++)$txt.=jsVar(${$x}[$i]).($i!=count($$x)-1?",":"");
		$txt.=");\n";
		return $txt;
	}
	function jsVar($var){
		return "\"".str_replace('"','\\"',str_replace("'","\\'",str_replace("\n","\\n",str_replace("\r","",$var))))."\"";
	}
	function phpAry($ary){
		global $$ary;
		$txt="\$".$ary."=array(";
			for($i=0;$i<count($$ary);$i++)$txt.="\"".${$ary}[$i]."\"".($i!=count($$ary)-1?",":"");
		$txt.=");\n";
		return $txt;
	}
function tmplt($tmplt){
	return "
		ob_start();ob_implicit_flush(0);
		include 'build/$tmplt.php';
		$bld.=ob_get_contents();
		$fil='buildPhp/$tmplt.php';if(file_exists($fil));include $fil;
		ob_end_clean();
	";
}
function registerCookie($cks,$var="zz%gh"){
	$x=explode(",",$cks);$y=explode(",",$var);$ok=($var!="zz%gh"?1:0);
	$dmn=$GLOBALS["HTTP_HOST"];if(substr($dmn,4)=="www.")$dmn=substr($dmn,3);if($dmn=="localhost")$dmn="";
	for($i=0;$i<count($x);$i++){
		global ${$x[$i]};
		setCookie($x[$i],($ok?$y[$i]:${$x[$i]}),0,"/",$dmn);
	}
}

	function securised(){ // returns a test on people logged in or not
		global $_SERVER,$includePath,$protect;
		if(!array_key_exists("REMOTE_USER",$_COOKIE))$_COOKIE["REMOTE_USER"]="";
		$ok=0;$user=$_COOKIE["REMOTE_USER"];
		if($user!=""){
			$groups=file($includePath.$protect."groups");
			for($i=0;$i<count($groups);$i++){
				$a=explode(":",$groups[$i]);
				if(strstr($a[1],$user)!=""){$group=$a[0];$ok=1;}
			}
		}else{
			$ok=($_SERVER["REMOTE_ADDR"]==$_SERVER["SERVER_ADDR"]&&$_SERVER["REMOTE_ADDR"]!="");
		}
		return $ok;
	}


		// Les fonctions ci-dessous peuvent maintenant être remplacée par un simple file_get_contents($url);
		
		// ouverture d'un socket ip ou url
		function sock($url,$file="",$q="",$method="POST",$secure,$split1,$split2){// socket connexion ($q = post variables)
			return sock1($url,$url,$file,$q,$method,$protocole,$port,$split1,$split2);
		}
		// ouverture d'un socket ip et url
		function sockUrlAndIp($url,$ip,$file="",$q="",$method="POST",$secure=0,$split1,$split2){// socket $url != $ip
			global $sockTxt;
				$auth=$error="";if(strstr($url,"@")!=""){
					$a=explode("@",$url);$ip=substr($ip,strpos($ip,"@")+1);
					$auth="Authorization: Basic ".base64_encode($a[0])."\r\n";
					$url=$a[1];
				}
				// !!!! mettre labonne valeur de port en secure ci-dessous: ,($secure?80:80),!!!!!
				$fp=fsockopen(($secure?'ssl://':'').$ip,($secure?80:80),$errno,$errstr,3);
				if(!$fp){
					if($GLOBALS["REMOTE_USER"]!="")$error="SOCKET ERROR : $errno - $errstr<br>\r\n";
				}else{
					fputs($fp,	"$method /$file HTTP/1.1\r\n".
							"Host: ".$url."\r\n".
							$auth.
							"Content-Length: ".strlen($q)."\r\n".
							"Content-Type: application/x-www-form-urlencoded\r\n".
							"Connection: Close\r\n\r\n".
							$q
					);
					while(!feof($fp))$t.=fgets($fp,128);
					fclose($fp);
				}
				$sockTxt=$t;
				if($split1!=""){
					$y=explode($split1,$t);$t="";for($i=1;$i<count($y);$i++)$t.=$split1.$y[$i];
					if($split2!=""){$x=explode($split2,$t);$sockTxt=$x[0];}else{$sockTxt=$t;}
				}
				return $sockTxt;
		}



?>
