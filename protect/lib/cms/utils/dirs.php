<?
	// DIRECTORY FUNCTIONS ************************************************************************************
	class cms_utils_dirs{
		static function getDir($directory,$recursive=1){	// get directories of array $dir	returns  array($dirs,$files,$sizes);
			$s=substr($directory,strlen($directory)-1)=="/"?1:0;
			$dir=array($directory);
			$dirs=array();$files=$sizes=array();
			while(count($dir)>0){
				$d=array_pop($dir);
				try{
					if(is_dir($d)){
						$handle=opendir($d);					 // ouverture du dossier
						if($handle){
							$file=readdir($handle);
							while(is_string($file)){	//is_string car si le fichier s'appelle 0, la condition $file=readdir marche pas
								$x=$d."/".$file;$y=substr($x,strlen($directory)+$s);
								if($file!="."&&$file!=".."){
									if(is_dir($x)){
										$dirs[]=$y;if($recursive>0)$dir[]=$x;
									}else{
										$files[]=$y;$sizes[]=cms_utils_files::filSiz("$d/$file");
									}
								}
								$file=readdir($handle);
							}
							closedir($handle);				// fermeture du dossier
						}
					}
				}catch(Exception $e){
					if(ENV=="dev")echo "exception utils/dirs:",$e->getMessage();
				}
			}
			array_multisort($files, $sizes);
			return array($dirs,$files,$sizes);
		}
		static function makeDir($dir){ // create a dir with adequat permissions
			$x=explode("/",$dir);$d="";for($i=0;$i<count($x);$i++){
				$d.=$x[$i]."/";
				if($x[$i]!=".."&&$x[$i]!=".")if(!is_dir($d))mkdir($d,0755);else @chmod($d,0755);
			}
		}
		static function delDirFiles($dir,$removeDir=1){
			global $dirs,$files;
			list($dirs,$files,$sizes)=self::getDir($dir);
			if(is_string($dir))$dir=array($dir);
			for($i=0;$i<count($files);$i++)unlink($dir[0].$files[$i]);
			for($i=count($dirs)-1;$i>-1;$i--)rmDir($dir[0].$dirs[$i]);if($removeDir&&file_exists($dir[0]))rmdir($dir[0]);
		}
		static function delDir($dir,$removeDir=1){ // delete a directory
			//if(is_string($dir))$dir=array($dir);
			self::delDirFiles($dir,$removeDir);//rmdir($dir[0]);
		}
		static function copyDir($dir,$dest,$squash=1,$unlink=0){ // copy a directory and keep timestamps
			//global $dirs,$files;
			list($dirs,$files,$sizes)=self::getDir($dir);
			self::makeDir($dest);for($i=0;$i<count($dirs);$i++){self::makeDir($dest.$dirs[$i]);}
			for($i=0;$i<count($files);$i++){
				$fil=$dest.$files[$i];
				if(!(file_exists($fil))||$squash)cms_utils_files::copyFile($dir.$files[$i],$fil);
				if($unlink)unlink($dir[0].$files[$i]);
			}
		}
		static function moveDir($dir,$dest,$exist=1){ // move a directory and keep timestamps
			global $dirs,$files;
			self::copyDir($dir,$dest,$exist);
			self::delDir($dir);
		}
	}
?>
