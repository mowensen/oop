<?
class cms_admin_search{
	static function shw($op,$attr,$attrVal,$rpcDirs,$rpcFiles,$static,$staticLabels,$searchIpt,$dynamic,$replaceIpt,$rec,$case){
		global $db,$lngId,$attr,$attrVal,$search,$replace,$mfIds,$while;

		$file=APP_PATH."replace.txt";
		$RPC=array("attr"=>"","attrVal"=>"","rpcDirs"=>"","rpcFiles"=>"","static"=>"","staticLabels"=>"","searchIpt"=>"","dynamic"=>"","replaceIpt"=>"","rec"=>"","case"=>"");
		if(file_exists($file))$RPC=unserialize(implode("",file($file)));
		$rlsIds=$cSta=$staticFound=$cDyn=$dynamicFound=$cTpl=$foundFiles="";
		$search=$replace=$mfIds=$while="";

		// les paramètres
		if(!$op){
			if($attr=="")$attr=$RPC["attr"];
			if($attrVal=="")$attrVal=$RPC["attrVal"]; // à normaliser: enlever " et '
			if($rpcDirs=="")$rpcDirs=$RPC["rpcDirs"];
			if($rpcFiles=="")$rpcFiles=$RPC["rpcFiles"];
			if($static=="")$static=$RPC["static"];
			if($staticLabels=="")$staticLabels=$RPC["staticLabels"];
			if($searchIpt=="")$searchIpt=$RPC["searchIpt"];
			if($dynamic=="")$dynamic=$RPC["dynamic"];
			if($replaceIpt=="")$replaceIpt=$RPC["replaceIpt"];
			if($rec=="")$rec=$RPC["rec"];
			if($case=="")$case=$RPC["case"];
		}
		$searchIpt=str_replace("\\\\","\\",str_replace('\\"','"',str_replace("\\'","'",str_replace("\\=","=",$searchIpt))));
		$replaceIpt=str_replace("\\\\","\\",str_replace('\\"','"',str_replace("\\'","'",str_replace("\\=","=",$replaceIpt))));
		cms_utils_files::save(
			$file,
			serialize(
				array("attr"=>$attr,"attrVal"=>$attrVal,"rpcDirs"=>$rpcDirs,"rpcFiles"=>$rpcFiles,"static"=>$static,"staticLabels"=>$staticLabels,"searchIpt"=>$searchIpt,"dynamic"=>$dynamic,"replaceIpt"=>$replaceIpt,"rec"=>$rec,"case"=>$case))
		);

		if($staticLabels=="")$staticLabels="*";
		if($rpcFiles=="")$rpcFiles="*";
		if(strstr($staticLabels,",")!="")$staticLabels=str_replace(",","-",$staticLabels);
		if($case!="on")$case="i";else $case="";//$eregRpc="ereg$case"."_replace";$strstr="str$case"."str";

		// ICI LE DEBUT DE TABLEAU
		eval("?>".file_get_contents(OOP_PATH."protect/layouts/searchStart.htm.php"));
	
		if($op){
			$x=$db->fetchAll("select idT,title from cmsTreesLang where tree like 'blocks-%' and lngId='$lngId'");
			$bIds=",";foreach($x as $y){$bIds.=$y["idT"].",";$B[$y["idT"]]=$y["title"];}

			$searchIpt=str_replace("**",urlencode("*"),str_replace("\\*","*",preg_quote(str_replace(",,",",",$searchIpt),"/")));
			$search=explode(",",$searchIpt);
			$replaceIpt=str_replace("**",urlencode("*"),str_replace("\\*","*",/*preg_quote(*/str_replace(",,",",",$replaceIpt)/*,"/")*/));
			$replace=explode(",",$replaceIpt);
			if($op=="replace"&&count($search)!=count($replace)){
				cms_utils::alert(TXTalrtEntries);exit();
			}
			
			for($i=0;$i<count($search);$i++){
				if($search[$i]==$replace[$i]){
					cms_utils::alert(TXTalrtRecursive);exit();
				}
				if(strstr($search[$i],"*")!=""){
					$search[$i]="(".str_replace("*",")(.*?)(",$search[$i]).")";
					$k=0;while(strstr($replace[$i],"*")!=""){$k+=2;$replace[$i]=preg_replace("/\*/","\\\${$k}",$replace[$i],1);}
				}
				$search[$i]="/".$search[$i]."/s$case";
				$search[$i]=str_replace(urlencode("*"),"*",$search[$i]);
				$replace[$i]=str_replace(urlencode("*"),"*",$replace[$i]);
			}

			$mfIds=array();

		// Les Labels
			if($static=="*"){
				$okStaAll=1;
				$static=implode(",",$db->fetchCol("select distinct(idB) from cmsLabels"));
			}
			$sta=explode(",",$static);
			if($static){
				$wh=$wh1="";
				if($staticLabels&&trim($staticLabels)!="*"){
					$z=explode("-",$staticLabels);for($i=0;$i<count($z);$i++)$wh1[]=$z[$i];
					if(count($z)){$wh="cmsLabels.label='".implode("' or cmsLabels.label='",$wh1)."'";if(count($z)>1)$wh="($wh)";$wh=" and $wh";}
				}
				
				foreach($sta as $s)if($s){
					$y=$db->fetchAll("select  title,lngId,cmsLabels.idB,cmsLabels.label from cmsLabels,cmsLabelsLang where cmsLabels.idB=$s $wh and cmsLabels.idB=cmsLabelsLang.idB and cmsLabels.label=cmsLabelsLang.label");
					for($j=0;$j<count($y);$j++){
						$t="";$t=self::replace($y[$j]["title"],$s);
						if($t){
							if($op=="replace"){
								$lng=$lngId;$lngId=$y[j]['lngId'];
								cms_admin_labels::setLabels("upd",$s,$y[$j]['label'],$t);
								$lngId=$lng;
							}
							$staticFound.="<a href='?block=$s'>$s label=".$y[$j]["label"]." lngId=".$y[$j]["lngId"]."</a>,";
						}
					}
				}
			}
			$cSta=count($mfIds);

		// Les tables dynamiques
			if($dynamic=="*"){
				$x=$db->fetchPairs("select idB,fields from cmsBlocks where fields!=''");
				if(count($x)){$dIds=",";foreach($x as $k=>$v)$dIds.="$k,";}
				$dynamic=substr($dIds,1,strlen($dIds)-2);
			}
			$dyn=explode(",",$dynamic);
			if($dyn[0]!="")foreach($dyn as $d){
				list($idH,$idV,$ids,$flds,$fldsLng,$fields,$inputs,$types,$fieldsLang,$inputsLang,$typesLang)=cms_admin_records::detachFields($d,$x[$d]);
				list($u,$typs)=cms_db::getRecords($id,'','');
				if(count($u))foreach($u as $v){
				/*	$wi=array();foreach($ids as $id)if(array_key_exists($id,$v))$wi[]="$id=".$v[$id];$ti=implode(",",$wi);
					$tf=array();foreach($fields as $f)$tf[$f]=str_replace($search,$replace,$v[$f]);
					$tl=array();foreach($fieldsLang as $f)$tl[$v["lngId"]][$f]=str_replace($search,$replace,$v[$f]);
					$t1=array();foreach($fields as $f){
						$t="";$t=self::replace($v[$f],$s);
						if($t){
							$dynamicFound.="$ti,$f<br>";
							$t1[$f]=mysql_escape_string($t)."'";
						}
					}
					$t2=array();foreach($fieldsLang as $f){
						$t="";$t=self::replace($v[$f],$s);
						if($t){
							$dynamicFound.="lngId=".$v["lngId"].",$ti,$fk<br>";
							$t2[$l][$f]=mysql_escape_string($t)."'";
						}
					}
					if($op=="replace"){
						$ti=str_replace(","," and ",$ti);
						if(count($t1))cms_db::setRecord("block$d",$wi,$t1);
						if(count($t2))foreach($t2 as $l=>$ary)cms_db::setRecord("block$d"."Lang",array_merge(array("lngId"=>$l),$wi),$ary);
					}*/
				}
			}
			$cDyn=count($mfIds)-$cSta;

		// les fichiers
			$webPath="";
			if($rpcDirs=="*"){$rpcDirs="php,php/rtm,upl/sta,upl/dyn";$webPath=ENV_PATH;}
			if($rpcFiles=="*"){$rpcFiles="*.php,*.phtml,*.html,*.htm,*.css,*.js";}
			$rpcF=explode(",",str_replace('*','',$rpcFiles));
			if($rpcFiles){$rpcFils=explode(",",$rpcFiles);for($i=0;$i<count($rpcFils);$i++)$rpcFils[$i]=$webPath.$rpcFils[$i];}
			if($rpcDirs){
				$dirs=explode(",",$rpcDirs);
				
				foreach($dirs as $d){
					$dr=$webPath.$d;list($dirs,$files,$sizes)=cms_utils_dirs::getDir($dr);
					if(count($files))foreach($files as $fil){
						$f=$dr.$fil;
						for($i=0;$i<count($rpcF);$i++)if(stristr($f,$rpcF[$i])){$rpcFils[]=$f;$i=count($rpcF);}
						//if(stristr($f,".php")!=""||stristr($f,".phtml")!=""||stristr($f,".htm")!=""||stristr($f,".doc")!=""||stristr($f,".css")!=""||stristr($f,".js")!="")$rpcFils[]=$f;
					}
				}
			}
			if($rpcFiles)foreach($rpcFils as $fil)if(file_exists($fil)){
				//déterminer id du bloc concerné
					$p="";$x=explode("/",$fil);
					if($x[0]=="php"){$y=explode(".",$x[count($x)-1]);$p=$y[0];}
					if($x[0]=="upl"){$p=$x[2];}
				$t="";$t=self::replace(file_get_Contents($fil),$p);
				if($t){
					$foundFiles.="<a href=\"javascript:cms.ShwEdt('".str_replace($webPath,"",$fil)."')\" onclick='//return false'>".str_replace($webPath,"",$fil)."</a>,";
					if($op=="replace"){
						cms_utils_files::save($fil,$t);
//						if($p==intval($p))cms_db::updTrace("cmsBlocks","idB=$p");
					}
				}else{ // affichage du fichier si le nom correspond
					if(stristr($fil,stripslashes($searchIpt)))$foundFiles.=str_replace($webPath,"",$fil).',';
				}
			}
			$cTpl=count($mfIds)-$cDyn-$cSta;

		}

		// ICI LA FIN DE TABLEAU
		eval("?>".file_get_contents(OOP_PATH."protect/layouts/searchEnd.htm.php"));

	}
	static function normalizeAttributes($attr,$attrVal,$txt){
		foreach(array("'",'"','\\"') as $f)$txt=preg_replace("/ $attr=$f$attrVal$f/i","/ $attr=$attrVal/",$txt);
		return $txt;
	}
	static function replace($txt,$p){
		global $attr,$attrVal,$search,$replace,$mfIds,$while,$rec;
		//for($i=0;$i<count($attr);$i++)$txt=self::normalizeAttributes($attr[$i],$attrVal[$i],$txt);
		$txt=self::normalizeAttributes($attr,$attrVal,$txt);
		$ok=0;$goOn=1;for($i=0;$i<count($search);$i++){
			while(preg_match($search[$i],$txt)!=""&&$goOn){
				$ok=1;$txt=preg_replace($search[$i],$replace[$i],$txt);
				$goOn=($rec!="on"?0:1);
			}
		}
		if($ok){$mfIds[]=$p;return $txt;}
	}
	function replaceBetween($b1,$b2,$r1,$r2,$txt){
		global $case;
		return preg_replace("/($b1)(.*?)($b2)/s$case","$r1$2$r2",$txt);
	}
}
?>
