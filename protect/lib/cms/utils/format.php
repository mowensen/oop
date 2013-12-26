<?
	class cms_utils_format{
		static function createPassword($length,$chars="234567890",$words=array()) {
			if(!count($words))$words=explode(' ',"vent sable eolienne solaire maree green kiwi photo mer hydraulique vert nature");
			$i = 0;
			$password = $words[mt_rand(0,count($words))];
			for($i=0;$i<$length;$i++)$password.=$chars{mt_rand(0,strlen($chars))};
			return $password;
		}
		static function parseTagAttributes($qs){// ici fournir la partie sans le tag
			$q=array();$x=explode("=",trim($qs));
			for($i=0;$i<count($x);$i++){
				$y=explode(' ',$x[$i]);for($j=0;$j<count($y);$j++)if($y[$j])$q[$y[$j]]='';
				if(count($x)>$i+1){
					$v=$x[$i+1];$s=substr($v,0,1);
					if($s!='"'&&$s!="'")$s=' ';else	$v=substr($v,1);
					$q[$y[count($y)-1]]=substr($v,0,strpos($v,$s));
					$v=substr($v,strpos($v,' ')+1);
					$y=explode(' ',$v);for($j=0;$j<count($y)-1;$j++)if($y[$j])$q[$y[$j]]='';
					$x[$i+1]=$y[count($y)-1];
				}
			}
			return $q;
		}
	}	
?>
