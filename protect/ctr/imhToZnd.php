<?
if($src){
	include "cms/cms/utils/dirs.php";
	include "cms/cms/utils/files.php";
	$rtm=array();
	// conversion des buildPhp
		list($dirs,$files,$sizes)=cms_utils_dirs::getDir("$src/buildPhp/");
		foreach($files as $f)if($f!=".htaccess"){
			$t=file_get_contents("$src/buildPhp/$f");$t=trim($t);$id=substr($f,0,strpos($f,"."));
			if($t){
				$x=explode("\$BLD=str_replace(",$t);
				$t=$x[0];for($i=1;$i<count($x);$i++){
					$c=",\$BLD);";
					$p=strpos($x[$i],$c);
					if(!$p){$c=",\$BLD)";$p=strpos($x[$i],$c);}
					$a=substr($x[$i],0,$p);
					$y=explode(",",$a);$y[0]=str_replace("#","",$y[0]);
					eval("\$rtm[$y[0]]=$id;");
					$y[0]=str_replace($id,"",$y[0]);
					$t.="\$rtm[$y[0]]=$y[1];".substr($x[$i],$p+strlen($c));
				}
				/*cms_utils_files::save("dev/php/rtm/$f",$t);*/
			}
		}
	// conversion des build
		list($dirs,$files,$sizes)=cms_utils_dirs::getDir("$src/build",1);
		foreach($files as $f)if($f!=".htaccess"){
			$t=file_get_contents("$src/build/$f");$t=trim(utf8_encode($t));
			if($t){
				if(count($rtm))foreach($rtm as $k=>$v){
					$l=str_replace($v,"",$k);
					$t=str_replace("#$k#","<cmsRtm$v $l>",$t);
				}
				$x=explode("¤",$t);
				$t="";for($i=0;$i<count($x);$i+=2){
					$t.=$x[$i].($i<count($x)-1?"<cmsLbl".str_replace("."," ",$x[$i+1]).">":"");
				}
				if($i+1<count($x))$t.=$x[$i+1];

				$x=explode("insertBlock(",$t);
				$t=$x[0];for($i=1;$i<count($x);$i++){
					$p=strpos($x[$i],")");
					$t.="?><cmsBlock".substr($x[$i],0,$p)."><?".substr($x[$i],$p+1);
				}
				/*cms_utils_files::save("dev/php/$f",str_replace(array("<??>","<?=\$BLD?>","frameId"),array("","<?=\$this->layout()->content?>","cmsFrm"),$t));*/
			}
		}
	// conversion des upload stat
		list($dirs,$files,$sizes)=cms_utils_dirs::getDir("$src/data/pages/");
		foreach($files as $f){
			$p=strpos($f,"/");
			$d=substr($f,0,$p);$f=substr($f,$p+1);
			cms_utils_dirs::makeDir("dev/upl/sta/$d");copy("$src/data/pages/$d/$f","dev/upl/sta/$d/$f");
		}
	// conversion des upload dyn
		list($dirs,$files,$sizes)=cms_utils_dirs::getDir("$src/data/upl/");
		foreach($files as $f){
			$p=strpos($f,"-");
			$d=substr($f,0,$p);$f=substr($f,$p+1);
			cms_utils_dirs::makeDir("dev/upl/dyn/$d");copy("$src/data/upl/$d-$f","dev/upl/dyn/$d/$f");
		}

		$db->query('truncate table cmsBlocks');
		$db->query('truncate table cmsTreesLang');
		$db->query('truncate table cmsLabels');
		$db->query('truncate table cmsLabelsLang');
	// recherche des labels
		$x=$db->fetchAll("select adminTexts0.idB,adminTexts0.page,label,title,lngId from adminTexts0,adminTexts0Common where adminTexts0.page=adminTexts0Common.page and adminTexts0.idB=adminTexts0Common.idB order by nb");
		foreach($x as $y){
			$idB=$y["idB"];$page=$y["page"];$label=$y["label"];
			$lbls[$label][$page][$idB][]=array($y["lngId"],decodDat($y["title"]));
		}
		foreach($lbls as $kL=>$vP)
			if($kL!='menuTitle'){
				$nb=0;
				foreach($vP as $kP=>$vI){
					$db->query("insert into cmsLabels(idB,nb,label)values($kP,$nb,'$kL')");
					$nb++;
					foreach($vI as $kI=>$v0)
						foreach($v0 as $k=>$v){
							//echo "insert into cmsLabelsLang(idB,lngId,label,title)values($kP,$v[0],'$kL','$v[1]')<br>";
							$db->query("insert into cmsLabelsLang(idB,lngId,label,title)values($kP,'".$cvrtLngId[$v[0]]."','$kL','$v[1]')");
						}
				}
			}
	?><plaintext><?					

	// recherche des blocs
		$x=$db->fetchAll("select adminTables.idB,title,rk,labels,field,types,ass from adminTables,adminTablesCommon where lngId=1 and adminTables.idB=adminTablesCommon.idB order by nb");
		$tree[0]="blocks-";$nb=array();$rk0=0;foreach($x as $y){
			$rk=$y["rk"];$idB=$y["idB"];
			if($rk0+0<$rk+0)$tree[$rk]="blocks-$id";
			$labels=$y["labels"];
			$fields=$y["field"];
			$types=$y["types"];
			$langs=$y["ass"];
			$isMenu="";

			$trees=array();
			$trees["title"][1]=$y["title"];
			$tre=$tree[$rk];
			if(array_key_exists($tre,$nb))$nb[$tre]++;else $nb[$tre]=0;
			if(array_key_exists($idB,$lbls['menuTitle'])){
				$bcks[$idB]["isMenu"]=$isMenu=1;
				foreach($lbls['menuTitle'][$idB] as $page=>$id)
					foreach($id as $k=>$v)
						$trees["title"][$v[0]]=decodDat($v[1]);
			}
			foreach($trees["title"] as $lngId=>$v)$db->query("insert into cmsTreesLang(tree,lngId,idB,nb,title)values('$tre','".$cvrtLngId[$lngId]."',$idB,".$nb[$tre].",'$v')");
			$db->query("insert into cmsBlocks(idB,fields,types,langs,isMenu)values($idB,'$fields','$types','$langs','$isMenu')");
			$id=$idB;$rk0=$rk;
		}
		foreach($bcks as $bck)
	// recherche de groupes
		$x=$db->fetchAll("select idB,title from adminGroups0");foreach($x as $y)$grps[$y['title']]=$y['idB'];
		$x=$db->fetchAll("select title,id1,lngId from adminGroups1 order by nb");foreach($x as $y)$grps[$y['title']][]=array($y['id1'],$y['lngId'],$y['title']);
}	
	function decodDat($t){
		$txt=str_replace("\\'","'",urldecode(str_replace("+",urlencode("+"),$t)));
//		$txt=utf8_decode($txt);
		$txt=utf8_encode($txt);
		$txt=html_entity_decode($txt);
		$txt=mysql_escape_string($txt);
		return $txt;
	}
?>
