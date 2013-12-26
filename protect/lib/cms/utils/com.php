<?	// COM FUNCTIONS ************************************************************************************
	class cms_utils_com{
		// send html mails with attachment possibilities $attach = array( array(pièce1,nom1) -> array(pièceN,nomN) )
		static function sendMail($nameFrom,$mailFrom,$nameTo,$mailTo,$subject,$body,$attach=array()){
				if(!strstr($body,"<html"))$body="<html>$body</html>";
				$head=	"Date: ".date("D, d M Y h:i:s")." +0100\n".
					"From: ".$nameFrom." <".$mailFrom.">\n".
					"Reply-To: ".$mailFrom."\n".
					"Return-Path: ".$mailFrom."\n";
				if(count($attach)){
					$boundary='didondinaditondelosdudosdodudundodudindon';
					$head.=	"MIME-Version: 1.0\n".
						"Content-Type: multipart/mixed;\n".
						" boundary=\"$boundary\"\n\n";
					$body=	"This is a multi-part message in MIME format.\n".
						"Ceci est un message est au format MIME.\n".
						"--$boundary\n".
						"Content-Type: text/html; charset=\"iso-8859-1\"\n".
						"Content-Transfer-Encoding: 7bit\n\n".
						"$body\n\n";
					foreach($attach as $j)$body.="--$boundary\n".self::attachments($j[0],$j[1]);
					$body.="--$boundary--\n";
				}else $head.= "Content-Type: text/html; charset=iso-8859-1\n";
				return mail(str_replace('&','',$nameTo)."<".$mailTo.">",$subject,$body,$head,"-f $mailFrom");
		}
		// concatenate attachments
		static function attachments($j,$nm){
			$nam=array("pdf"=>"application/pdf","xls"=>"application/excel","doc"=>"application/msword");
			$fil=chunk_split( base64_encode(file_get_contents($j)));
			if(!$nm){$n=explode("/",$j);$nm=$n[count($n)-1];}
			$ext=substr($nm,strpos($nm,".")+1);$app=$nam[$ext];
			return	"Content-Type: $app; name=\"$nm\"\n".
				"Content-Transfer-Encoding: base64\n".
				"Content-Disposition: attachment; filename=\"$nm\"\n\n$fil";
		}
		// send an htmlmail without any attachment - A SUPPRIMER A TERME !!!!
		static function simpleMail($nameFrom,$mailFrom,$nameTo,$mailTo,$subject,$body){ // sends a mail
				if(!strstr($body,"<html"))$body="<html>$body</html>";
				$head=	"From: ".$nameFrom." <".$mailFrom.">\n".
					"Reply-To: ".$mailFrom."\n".
					"Return-Path: ".$mailFrom."\n".
					"Content-Type: text/html; charset=iso-8859-1\n"; // Type MIME
				return mail(str_replace('&','',$nameTo)."<".$mailTo.">",$subject,$body,$head,"-f $mailFrom");
		}
	}
?>
