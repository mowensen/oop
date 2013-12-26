<?
	class cms_login{

		/*static function securised(){
			if(self::checkSecure())return true;else self::login();
		}*/
		
		static function setHtConst(){ // check htaccess file to set constants
			if(file_exists(DEV_PATH.'.htaccess')){
				$ht=file_get_contents(DEV_PATH.'.htaccess');
				preg_match("/AuthName(.*?)\n/i",$ht,$x);$r=trim(str_replace('"','',$x[1]));
				preg_match("/AuthType(.*?)\n/i",$ht,$x);$a=trim(str_replace('"','',$x[1]));
				preg_match("/AuthUserFile(.*?)\n/i",$ht,$x);$u=trim(str_replace('"','',$x[1]));
				preg_match("/AuthGroupFile(.*?)\n/i",$ht,$x);$g=trim(str_replace('"','',$x[1]));
			}else list($r,$a,$u,$g)=array('Restricted area','Form',APP_PATH.'users.Digest',APP_PATH.'groups');
			define('REALM',$r);define('AUTYP',$a);define('HTUF',$u);define('HTGF',$g);
		}

		static function checkSecure(){
			if(array_key_exists("logout",$_GET))if($_GET["logout"]==1){
				setCookie("logout",1);
				header("Location: index.php");
			}
			if(array_key_exists("logout",$_COOKIE))if($_COOKIE["logout"]){
				setCookie("logout","");
				self::login();
			}
			
			$chk='check'.AUTYP;$sec=self::$chk();setCOOKIE("sec",$sec);
			if(!$sec)self::login();
			return $sec;
		}

		static function checkForm(){
			//$ok=false;
			if(array_key_exists("A",$_POST))$_COOKIE["PHP_AUTH_DIGEST"]=$_POST["A"].":".REALM.":".md5($_POST["A"].":".REALM.":".$_POST["B"]);
			if(array_key_exists("PHP_AUTH_DIGEST",$_COOKIE)){
				$t=file_get_contents(HTUF);$PAD=$_COOKIE["PHP_AUTH_DIGEST"];
				$x=explode($PAD,"\n$t");
				if(count($x)>1){
					if(!defined('USR'))define('USR',substr($PAD,0,strpos($PAD,":")));
					if(!defined('GRP'))define('GRP',self::getGroup());
					setCookie("PHP_AUTH_DIGEST",$PAD);
					return true;
				}
			}
			//if(!$ok)self::login();
			return false;
		}

		static function checkDigest(){ //The htdigest formula for the hash is: md5("$username:$realm:$password")
			if(!array_key_exists("PHP_AUTH_DIGEST",$_SERVER))self::login();
			$needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
			$data = array();
			preg_match_all('@(' . implode('|', array_keys($needed_parts)) . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@',$_SERVER['PHP_AUTH_DIGEST'],$matches, PREG_SET_ORDER);
			foreach ($matches as $m) {
				$data[$m[1]] = $m[3] ? $m[3] : $m[4];
				unset($needed_parts[$m[1]]);
			}

			define('USR',$data['username']);define('GRP',self::getGroup());
			$t=file_get_contents(HTUF);$x=explode(USR.":".REALM.":","\n$t");
			if(count($x)>1){
				$A1 = substr($x[1],0,strpos($x[1],"\n"));
				$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
				$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);
			}else{
				$valid_response='';
				echo 'Invalid account';
			}
			return ($data['response']==$valid_response);
		}

		static function getGroup(){
			$x=file(HTGF);
			$group="";if(count($x))foreach($x as $y)if(strstr(trim($y)." "," ".USR." "))$group=substr($y,0,strpos($y,":"));
			return $group;
		}

		static function login(){
			if(AUTYP=="Form"){
				setCookie("PHP_AUTH_DIGEST",'');
			?>
			<style>
			body,td,input	{font-family:arial;font-size:12px;color:#024e6e}
			.brd		{border-style:solid;border-color:#486B83;border-width:1px}
			input	{	text-align:center;border:1px solid #024e6e}
			input[type=button],input[type=submit]{cursor:pointer;font-size:10px}
			</style>
			<form method=post>
				<table height=100% align=center>
					<td valign=middle>
						<table cellspacing=0 cellpadding=0 width=277>
							<td class=brd align=center>
								<table bgcolor="#486B83" width=100% cellspacing=0 cellpadding=0>
									<td align=center style="color:white;height:20px" valign=middle><b>You are logged out</b></td>
									<td align=right><!--<img src="<?=OOP_PATH?>img/logo.png">--></td>
								</table>
								<br /><i style=font-size:11px>Please give a valid OOPub user account</i><br />
								<table  align=center cellspacing=9>
									<tr><td align=right>User: </td><td><input id=A name=A></td></tr>
									<tr><td align=right>Pass: </td><td><input type=password name=B></td></tr>
									<tr>
										<td><input type=button value=CANCEL onclick="location.replace('?env=prd&sec=0')"></td>
										<td align=right><input type=submit value=OK></td>
									</tr>
								</table>
							</td>
						</table>
						<div style=height:133px></div>
					</td>
				</table>
			</form>
			<script>document.getElementById('A').focus()</script>
			<?
				exit();
			}else{
				header('HTTP/1.1 401 Unauthorized');
				header('WWW-Authenticate: Digest realm="'.REALM.'",qop="auth",nonce="'.uniqid().'",opaque="'.md5(REALM).'"');
				die("<script>location='?env=prd&sec=0'</script>");
			}
		}
		
		static function checkAccess(){
			global $db,$block,$lngId;
			$x=$GLOBALS["db"]->fetchRow("select blocks,icons,lngs,ips,interface from cmsUsers where user='".USR."'");
			$n=$x['interface'];if(!$n){if(strstr('enfrzh',$lngId))$n=$lngId;else $n='en';}define('USR_LNG',$n);
			$i=$x['icons'];define('USR_ICS',$i);define('SHW_USR_EDT',($i!='all'&&$i!='oe'?0:1));
			$b=$x['blocks'];define('USR_BLS',$b);define('SHW_USR_BLS',($b!='all'&&!strstr(" $b "," $block ")?0:1));
			$l=$x['lngs'];define('USR_LNS',$l);define('SHW_USR_LNS',($l!='all'&&!strstr(" $l "," $lngId ")?0:1));
			define('RMT_ADR',$_SERVER['REMOTE_ADDR']);$p=$x['ips'];define('USR_IPS',$p);
			$ok=1;if($p){$ok=0;$ar=explode(' ',$p);foreach($ar as $q)if(substr(RMT_ADR,0,strlen($q))==$q)$ok=1;}
			return (SHW_USR_BLS&&SHW_USR_LNS&&$ok);
		}
	}
?>
