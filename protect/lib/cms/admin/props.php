<?
	class cms_admin_props{
		static function shwProps(){
			$x=$lngs=$u=array();
			$cfg = new Zend_config_Ini(APP_PATH.'/config.ini', 'dev');$devLng=$cfg->lng;
			$cfg = new Zend_config_Ini(APP_PATH.'/config.ini', 'prd');$prdLng=$cfg->lng;
			$ln=file(OOP_PATH."lng/ln.csv");
			$keys="";foreach($prdLng as $k=>$v)$keys.=",$k";
			foreach($devLng as $k=>$v)if(!strstr($keys,$k)){$x[$k]=$v;$keys.=",$k";}$devLng=$x;
			foreach($ln as $y){$z=explode(",",str_replace('"','',$y));if(!strstr($keys,$z[0]))$lngs[$z[0]]=$z[1];}

			$cmsLngs="";foreach($lngs as $k=>$v)$cmsLngs.="<option value=$k>".trim($v);
			$cmdDevLng="";foreach($devLng as $k=>$v)$cmdDevLng.="<option value=$k>".trim($v);
			$cmsPrdLng="";foreach($prdLng as $k=>$v)$cmsPrdLng.="<option value=$k>".trim($v);

			$fils=cms_db_backup::getFiles();
			$cmsDbSav="";foreach($fils as $k=>$f)if($f[0]!="my.sql")$cmsDbSav.="<option value='$f[0]'>".date("Y-m-d H:i:s",$k)." ($f[1])";

			$cfg = new Zend_config_Ini(APP_PATH.'/config.ini', 'app');$autoSav=$cfg->db->autoSave;$rwt=$cfg->mod_rewrite;$savEvry=$cfg->db->saveEvery;
			
			return "<script>cms.DevLngOpts=\"$cmdDevLng\";cms.PrdLngOpts=\"$cmsPrdLng\";cms.LngsOpts=\"$cmsLngs\";cms.DbSav=\"$cmsDbSav\";cms.AutoSav=$autoSav;cms.SavEvry=\"$savEvry\";cms.ModRwt=$rwt</script>";
		}
		static function setPropAuto($auto,$every=24){
			$msg="";$fil=APP_PATH.'/config.ini';
			$t=file_get_Contents($fil);
			$str="db.";$p=strpos($t,$str);$t0=substr($t,0,$p);$t=substr($t,$p);
			$str="\n\n";$p=strpos($t,$str);$t1=substr($t,0,$p);$t2=substr($t,$p);
			$t=$t0;
				$x=explode("\n",$t1);
				$sch1="db.autoSave";$sch2="db.saveEvery";$ok1=0;$ok2=0;
				if(count($x))
					foreach($x as $y)
						if(substr($y,0,strlen($sch1))==$sch1){
							$t.="$sch1 = $auto\n";$ok1=1;
						}elseif(substr($y,0,strlen($sch2))==$sch2){
							$t.="$sch2 = $every\n";$ok2=1;
						}else{
							$t.="$y\n";
						}
			if(!$ok1)$t.="$sch1 = $auto\n";
			if(!$ok2)$t.="$sch2 = $every\n";
			$t.=$t2;
			while(strstr($t,"\n\n\n"))$t=str_replace("\n\n\n","\n\n",$t);
			cms_utils_files::save($fil,$t);
			return $msg;
		}
		static function setPropLng($op,$dev,$prd){
			global $db,$dfl;
			$msg="";$fil=APP_PATH.'/config.ini';
			$t=file_get_Contents($fil);
			$str="[dev:app]";$p=strpos($t,$str);$t0=trim(substr($t,0,$p));$t=trim(substr($t,$p));
			$str="[prd:app]";$p=strpos($t,$str);$t1=trim(substr($t,0,$p));$t2=trim(substr($t,$p));
			$t="";
			$x=explode("\n",$t0);if(count($x))foreach($x as $y)if(substr($y,0,4)!='lng.')$t.="$y\n";$t.="$prd\n\n";
			$x=explode("\n",$t1);if(count($x))foreach($x as $y)if(substr($y,0,4)!='lng.')$t.="$y\n";$t.="$dev\n\n$t2";
			cms_utils_files::save($fil,$t);
			//check if default language has changed
			$l=trim(substr($prd,0,strpos($prd,"=")));$l=substr($l,strpos($l,".")+1);
			if($dfl!=$l&&$l){
				$x=$db->fetchAll("select label,idB,title from cmsLabelsLang where lngId='$dfl'");
				foreach($x as $y){
					$wh="where label='".$y['label']."' and idB=".$y['idB']." and lngId='$l'";
					$z=$db->fetchOne("select count(idB) from cmsLabelsLang $wh");
					if(!$z)$db->query("insert into cmsLabelsLang (lngId,idB,label,title)values('$l','".$y['idB']."','".$y['label']."','".mysql_escape_string($y['title'])."')");
				}
				$x=$db->fetchAll("select tree,idT,nb,title,isModified from cmsTreesLang where lngId='$dfl'");
				foreach($x as $y){
					$wh="where tree='".$y['tree']."' and idT=".$y['idT']." and lngId='$l'";
					$z=$db->fetchOne("select count(idT) from cmsTreesLang $wh");
					if(!$z)$db->query("insert into cmsTreesLang (lngId,tree,idT,nb,title)values('$l','".$y['tree']."','".$y['idT']."','".$y['nb']."','".mysql_escape_string($y['title'])."')");
					$db->query("update cmsTreesLang set isModified='".$y['isModified']."' $wh");
				}
			}
			return $msg;
		}
		static function checkRwt(){
			return (function_exists('apache_get_modules')?strstr(implode("",apache_get_modules()),"mod_rewrite"):1);
		}
		static function setPropRwt($ok){
			if($ok&&!self::checkRwt()){
				cms_utils::alert(TXTalrtRewrite);
			}else{
				$fil=APP_PATH.'/config.ini';
				$t=file_get_Contents($fil);
				$str="mod_rewrite";$p=strpos($t,$str);$t0=trim(substr($t,0,$p));$t=substr($t,$p);
				$str="\n";$p=strpos($t,$str);$t=substr($t,$p);
				$t=$t0."\n\nmod_rewrite = ".$ok.$t;
				cms_utils_files::save($fil,$t);
			}
		}
		static function getRwt(){
			$cfg = new Zend_config_Ini(APP_PATH.'/config.ini', 'app');
			return $cfg->mod_rewrite;
		}
		static function defConst($f){
			$t=str_replace(array("\t",";",'="',"\nconst "),array("",");",'","',"\ndefine(\""),file_get_contents($f));
			eval("?>$t");
		}
	}
?>
