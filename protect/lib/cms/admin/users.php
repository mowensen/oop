<?
	class cms_admin_users extends cms_login{
		static function setDigestAccount($op,$user,$group,$pass,$interface,$blocks='all',$icons='all',$lngs='all',$ips=''){
			$hash=md5("$user:".REALM.":$pass");
			$groups=self::getUserArray();
			$u=array();$msg="";
			if($op=="upd"){
				if($pass)$groups[$group][$user]=$hash;//uniquement si le passe a été changé
			}elseif($op=="del"){
				$groups[$group][$user]="";
				$blocks=$icons='none';
			}elseif($op=="add"){
				if(array_key_exists($user,$groups)){
					if($users[$group][$user])$msg="Ce compte existe déjà";
				}else $groups[$group][$user]=$hash;
			}
			$x=$y=array();
			if(count($groups)){
				foreach($groups as $g=>$ar)if(count($ar))if($g){
					$y[$g]="$g:";
					foreach($ar as $u=>$p)if($u&&$p){
						$y[$g].=" $u";
						$x[]="$u:".REALM.":$p";
					}
				}
				cms_utils_files::save(HTUF,implode("\n",$x)."\n");
				cms_utils_files::save(HTGF,implode("\n",$y)."\n");
			}else{
				$x=explode("/",$_SERVER["SCRIPT_FILENAME"]);array_pop($x);$fil=implode("/",$x);
				$t=str_replace(array("#",'"app"'), array("","$fil/".APP_PATH), fil_get_contents(OOP_PATH."skeleton/dev/.htaccess"));
				cms_utils_files::save(DEV_PATH.".htaccess",$t);
				copy(OOP_PATH."/skeleton/".APP_PATH."users.Digest",APP_PATH."users.Digest");
				copy(OOP_PATH."/skeleton/".APP_PATH."groups",APP_PATH."groups");
			}
			self::setRights($user,$group,$interface,$blocks,$icons,$lngs,$ips);
			return $msg;
		}
		static function setRights($usr,$grp,$interface,$blocks="all",$icons="all",$lngs="all",$ips=""){
			global $db;
			$wh="where user='$usr'";$tbl="cmsUsers";
			$n=$db->fetchOne("select user from $tbl $wh");
			if(!$n)$db->query("insert into $tbl (user)values('$usr')");
			$db->query("update $tbl set ".(GRP!="admins"?"":"blocks='$blocks',icons='$icons',lngs='$lngs',ips='$ips',")."interface='$interface' $wh");
			cms_db::updTrace($tbl,$wh);
		}
		static function shwUsers($usr,$grp){
			global $db;$ots='';
			$r=$db->fetchAssoc("select user,interface,blocks,icons,lngs,ips from cmsUsers");
			$groups=self::getUserArray();
			$html=	"<table style=margin-left:15px>";
			foreach($groups as $g=>$ar){
				if($grp=="")$grp=$g;
					$html.=	"<tr><td nowrap><b>$g</b></td><td colspan=3>".cms_admin::imgLnk("javascript:cms.ShwUsr('add','$g','','".USR_LNG."','all','all','all','')","add.gif","Ajouter un utilisateur")."</td></tr>";
				foreach($ar as $u=>$p)if(trim($u)){
					$o=$r[$u];
					$opts="'".$o["interface"]."','".$o["blocks"]."','".$o["icons"]."','".$o["lngs"]."','".$o["ips"]."'";
					if($u==$usr)$ots=$opts;
					if($usr==""&&$grp==$g)$usr=$u;
					$html.=	"<tr>".
							"<td></td>".
							"<td style=cursor:pointer id=cmsUsr$u onclick=\"cms.ShwUsr('upd','$g','$u',$opts)\" title=\"Editer le compte\">$u</td>".
							"<td>".cms_admin::imgLnk("javascript:cms.ShwUsr('upd','$g','$u',$opts)","edit.gif","Editer le compte")."</td>".
							"<td>".cms_admin::imgLnk("javascript:cms.DelUsr('".urlencode($u)."','".urlencode($g)."')","drop.gif","Supprimer")."</td>".
						"</tr>";
				}
			}
			$o=$r[$usr];
			$html.=	"</table><script>cms.ShwUsr('upd','$grp','$usr',$ots)</script>";
			return $html;
		}
		static function getUserArray(){
			$x=file(HTUF);
			$users=array();
			if(count($x))foreach($x as $y)if(trim($y)){
				$z=explode(":",$y);
				$users[$z[0]]=substr($z[2],0,strlen($z[2])-1);
			}
			$x=file(HTGF);
			$groups=array();foreach($x as $y){
				$y=trim($y);$p=strpos($y,":");$g=substr($y,0,$p);$z=explode(" ",trim(substr($y,$p+1)));
				foreach($z as $u)$groups[$g][$u]=array_key_exists($u,$users)?$users[$u]:"";
			}
			return $groups;
		}
		
	}
?>
