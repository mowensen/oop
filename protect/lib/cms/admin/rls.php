<?
	class cms_admin_rls{
		static function rlsFiles($srcDir,$recursive=1){
			//global $unlink,$fSizUnlink,$rlsUnlink,$unLnkAr;
			$prod=PRD_PATH.$srcDir;$save=SAV_PATH.$srcDir;$dev=DEV_PATH.$srcDir;
			list($dirs,$files,$sizes)=cms_utils_dirs::getDir($dev,$recursive);				// récupére les fichier et leurs caractéristiques de la source
			for($i=0;$i<count($dirs);$i++){
				cms_utils_dirs::makeDir($prod.$dirs[$i]);cms_utils_dirs::makeDir($save.$dirs[$i]);	// crée les sous-épertoires s'ils n'existent pas
			}
			$src=",";foreach($files as $fil){								// appelle la fonction de copie
				self::cpyRlsFil($srcDir.$fil);
				$src.="$fil,";
			}
			list($dirs,$files,$sizes)=cms_utils_dirs::getDir($prod,$recursive);				// récupére les fichiers redondants dans la destination
			foreach($files as $fil)if(!strstr($src,",$fil,"))self::delFile($srcDir.$fil);
		}
		static function delDir($d){
			$dir=PRD_PATH.$d;
			list($dirs,$files,$sizes)=cms_utils_dirs::getDir($dir);
			if(count($files))foreach($files as $f)self::delFile($dir.$f);
		}
		static function delFile($f){
			global $unlink,$fSizUnlink,$rlsUnlink,$unLnkAr;
			$prod=PRD_PATH.$f;$save=SAV_PATH.$f;
			if(file_exists($prod)){
				$rlsUnlink++; $unLnkAr[]=$prod; $unlink[]=$prod; $fSizUnlink+=filesize($prod);
				cms_utils_files::moveFile($prod,$save);
			}
		}
		static function cpyRlsFil($f){
			global $quick,$rlsFilCop,$rlsFilNot,$fSizCop,$fSizNot,$fichiers,$fichiersSiz,$fichNotAr,$fichCopAr;
			$fil=DEV_PATH.$f;

			if(file_exists($fil)){
				$prod=PRD_PATH.$f;
				$x=explode("/",$prod);array_pop($x);$d=@implode("/",$x);cms_utils_dirs::makeDir($d);
				$save=SAV_PATH.$f;
				$x=explode("/",$save);array_pop($x);$d=@implode("/",$x);cms_utils_dirs::makeDir($d);
				$fSiz=filesize($fil);
				$ok=($quick!="1"||!file_exists($prod)?1:($quick=="1"&&filemtime($fil)!=filemtime($prod)));
				if($ok){
					$fichiers[]=$f;$fichiersSiz[]=$fSiz;
					$fichCopAr[]=$f;
					$rlsFilCop++;$fSizCop+=$fSiz;
				}else{
					$fichNotAr[]=$f;
					$rlsFilNot++;$fSizNot+=$fSiz;
				}
			}
		}
		static function rlsCache(){
			$prod=PRD_PATH."tmp/";$save=PRD_PATH."tmp/";$i=0;
			list($dirs,$files,$sizes)=cms_utils_dirs::getDir($prod);
			?><script>cms.UpdRls("td20","innerHTML='0'");cms.UpdRls("td21","innerHTML='<?=" / ".count($files)?>'")</script><?
			$i=0;foreach($files as $fil){
				cms_utils_files::moveFile($prod.$fil,$save.$fil);$i++;
				?><script>cms.UpdRls("td20","innerHTML=<?=$i?>")</script><?
			}
			?><script>cms.UpdRls("td20","style.color='green'");cms.UpdRls("td21","style.color='green'")</script><?
		}

// LES FONCTIONS BASE DE DONNEE
		static function copyRlsTable($table){
			global $db;
			$x=$db->fetchAll("show columns from $table");
			foreach($x as $k=>$v)if($x[$k]["Field"]=="isModified")$db->query("update $table set isModified=0");
			$x=$db->fetchAll("show index from $table");
			$db->query("drop table if exists rls_".ucFirst($table).";create table rls_".ucFirst($table)." select * from $table");
			$key=array();for($i=0;$i<count($x);$i++)if($x[$i]["Key_name"]=="PRIMARY")$key[]=$x[$i]["Column_name"];
			$k=implode(",",$key);if($k)$db->query("alter table rls_".ucFirst($table)." add primary key($k)");
		}
		static function copyRlsBlock($id,$fields){
			$y=explode("\n\n",$fields);
			$block="block$id";
			self::copyRlsTable($block);
			if(count($y)>2)if(trim($y[2]))self::copyRlsTable($block."Lang");	// si table Lang
		}
		static function copyRlsField($table,$field,$val,$and=''){
			global $db;
			$x=$db->fetchAll("show columns from $table");
			if(array_key_exists("isModified",$x))$db->query("update $table set isModified=0 where $field='$val'");
			//cms_utils::alert("insert into rls_".ucFirst($table)." select * from $table where $field=$val");
			$db->query("delete from rls_".ucFirst($table)." where $field='$val' $and;insert into rls_".ucFirst($table)." select * from $table where $field=$val $and");
		}

// AFFICHAGE DE LA PUBLICATION		
		static function popTxt($step){
			global $time,$tim,$rlsFilCop,$rlsFilNot,$quick,$fSizCop,$fSizNot,$fSizUnlink,$unlink,$table,$Txt,$RFN,$FSN,$RFC,$FSC,$RFU,$FSU,$ND,$fichiers,$fichiersSiz,$rlsUnlink,$unLnkAr,$fichNotAr,$fichCopAr;
			if($step>6&&$step<11){
				$files=$fichiers;
				$total=$notCopied=$FSC;$copied=0;for($i=0;$i<count($files);$i++){
					$f=$files[$i];$fSiz=$fichiersSiz[$i];
					$notCopied-=$fSiz;$copied+=$fSiz;
					cms_utils_files::copyFile(PRD_PATH.$f,SAV_PATH.$f);cms_utils_files::copyFile(DEV_PATH.$f,PRD_PATH.$f);
					// le script ci-dessous est une barre de progression qui doit etre adaptée à ajx
					/*?><script>
						cms.UpdRls("rlsInfo","innerHTML='<?="$f (".cms_utils_files::prettyBytes($fSiz).")"?>'");
						cms.UpdRls("toDo","style.width='<?=@intval($copied*333/$total)?>'");
						cms.UpdRls("done","style.width='<?=@intval($notCopied*333/$total)?>'");
					</script><?*/
				}
			}
			if($step<5){
				?><script>cms.UpdRls("dat<?=$step?>","innerHTML='<?=$ND?>'")<?=($step<2?";cms.RlsTime=".time():"")?></script><?="\n"?><?
			}elseif($step>5&&$step<11){
				$val[0]=($rlsFilNot?
					"<a class=hov href=\\\"javascript:alert('".implode("\\\\n",$fichNotAr)."')\\\" style='color:green'>".
						"$rlsFilNot (".cms_utils_files::prettyBytes($fSizNot).")</a>"
					:"&nbsp;");
				$val[1]=($rlsFilCop?
					"<a class=hov href=\\\"javascript:alert('".implode("\\\\n",$fichCopAr)."')\\\" style='color:green'>".
					"$rlsFilCop (".cms_utils_files::prettyBytes($fSizCop).")</a>"
					:"&nbsp;");
				$val[2]=($rlsUnlink?
					"<a class=hov href=\\\"javascript:alert('".implode("\\\\n",$unLnkAr)."')\\\" style='color:green'>".
						"$rlsUnlink (".cms_utils_files::prettyBytes($fSizUnlink).")</a>"
					:"&nbsp;");
				for($i=0;$i<3;$i++){?><script>v="<?=$val[$i]?>";cms.UpdRls("td<?=(5+($step-6)*3+$i)?>","innerHTML=v")</script><?}
				$RFN+=$rlsFilNot;$FSN+=$fSizNot;$RFC+=$rlsFilCop;$FSC+=$fSizCop;$RFU+=$rlsUnlink;$FSU+=$fSizUnlink;
			}
			if($step>3){
				?><script>cms.UpdRls("b<?=($step-3)?>","style.color='navy'")</script><?
			}
			if($step==11){
				?><script>cms.UpdRls("progress","style.display='none'")</script><?
				$txt=str_replace("#n1",($RFN?"$RFN (".cms_utils_files::prettyBytes($FSN).")":$Txt[13]),
					str_replace("#n2",($RFC?"$RFC (".cms_utils_files::prettyBytes($FSC).")":$Txt[13]),
						str_replace("#n3",($RFU?"$RFU (".cms_utils_files::prettyBytes($FSU).")":$Txt[13]),
							str_replace("#t",(time()-$time),$Txt[12]))));
				?><script>cms.UpdRls("td22","innerHTML='<?=$txt?>'")</script><?="\n"?><?
				self::rlsCache();
			}
			if($step==4||$step==5||$step==11){
				?><script>cms.UpdRls("tim<?=($step-3)?>","innerHTML='<?=(time()-$time)?>'")</script><?
				if($step==4){?><script>cms.RlsTime=<?=time()?></script><?}
			}
			$rlsFilCop=$rlsFilNot=$fSizCop=$fSizNot=$fSizUnlink=$ND=$rlsUnlink=0;$tim=time();$unLnkAr=$fichNotAr=$fichCopAr=array();
		}
		static function htEscFile($f){$e="\\";return str_replace(array($e," ","+"),array($e.$e,"$e ","$e+"),$f);}
	}
?>
