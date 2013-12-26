<?
	class cms_db_backup{
		static function checkLast(){
			if($GLOBALS["CFG"]->db->autoSave)
				if(time()-cms_bldWeb::getCache("lastBckUp","cms_db_backup::last()")>3600*$GLOBALS["CFG"]->db->saveEvery)self::dumpDb();
		}
		static function last(){
			global $lastBckUp;
			$fils=self::getFiles();
			$lastBckUp=current(array_keys($fils));
		}
		static function getFiles(){
			list($dirs,$files,$sizes)=cms_utils_dirs::getDir(APP_PATH."db/");
			$fl="";for($i=0;$i<count($files);$i++)if(!strstr($files[$i],'www.'))$fl[filectime(APP_PATH."db/".$files[$i])]=array($files[$i],$sizes[$i]);krsort($fl);
			return $fl;
		}
		static function restoreDb($fil){
			global $db;
			$config = new Zend_Config_Ini(APP_PATH.'/config.ini', 'app');$cfg=$config->db->params;
			$qry="mysql --host=".$cfg->host." --user=".$cfg->username." --password=".$cfg->password." ".$cfg->dbname." < ".APP_PATH."db/$fil";
			exec($qry);
		}
		static function dumpDb(){
			global $db,$zndCache;
			$config = new Zend_Config_Ini(APP_PATH.'/config.ini', 'app');$cfg=$config->db;$dbp=$cfg->params;
			$ids=$db->fetchPairs("select idB,fields from cmsBlocks where fields!=''");
			$tbls=array("cmsBlocks","cmsLabels","cmsLabelsLang","cmsTreesLang");
			foreach($ids as $k=>$f){
				$tbls[]="block$k";
				$b=explode("\n\n",$f);if(count($f)>2)$tbls[]="block$k"."Lang";
			}
			$qry="mysqldump --single-transaction --opt --host=".$dbp->host." --user=".$dbp->username." --password=".$dbp->password." ".$dbp->dbname." "." --tables ".implode(" ",$tbls)." > ".APP_PATH."db/db";
			exec($qry);
			self::orderDumps();
			$zndCache->save(time(),"lastBckUp");
			$f=APP_PATH."db/www.tgz";if(file_exists($f))unlink($f);exec("tar czf $f * --exclude app/db --exclude oop");			
		}
		static function orderDumps(){
			$mn=strftime("%M", time());
			$H=strftime("%H", time());$f=$H;
			$an=strftime("%Y", time());
			$m=strftime("%m", time());
			if($H=="17"){
				$d=strftime("%A", time());$f=$d;
				$j=strftime("%e", time());if($j=="7" or $j=="14" or $j=="21" or $j=="28")$f="sem$j";
			}
			$dir=APP_PATH."db/";
			if(file_exists("$dir$f"))unlink("$dir$f");
			if(file_exists("$dir$f.www.tgz"))unlink("$dir$f.www.tgz");
			@rename($dir."db","$dir$f");@rename($dir."www.tgz","$dir$f.www.tgz");$msg="Le fichier mois $dir$f a été créé.";
			if(!file_exists("$dir$an$m")){@rename("$dir$f","$dir$an$m");@rename($dir."$f.www.tgz","$dir$f.www.tgz");$msg="Le fichier mois $dir$an$m a été créé.";}
		}
	}
?>
