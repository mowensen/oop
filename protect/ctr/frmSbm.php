<?
	foreach(array("op"=>"","block"=>"","afterId"=>"") as $k=>$v)$$k=cms_bldWeb::getVar($k,$v);
	$dat=date("Y-m-d",time());
		$x=explode("-",$dat);
		$m=(int)($x[1]);$m--;if(strlen($m)<2)$m="0$m";
		$d=(int)($x[2]);$d--;if(strlen($d)<2)$d="0$d";
		$dat="$x[0]-$m-$d";
	global $db,$rtm;$rtm=array();
	$shw="";

	if(!array_key_exists("tables",$_POST)){
		$x=explode("name=tables[] value=",str_replace(array("'",'"'),"",$html));array_shift($x);$tables=array();
		if(count($x))foreach($x as $y)$tables[]=substr($y,0,strpos($y,">"));
	}else $tables=$_POST["tables"];

if(count($tables))
	foreach($tables as $idB)if("$idB"!=""&&$idB=intval($idB)){
		list($idH,$idV,$ids,$flds,$fldsLng,$fields,$inputs,$types,$fieldsLang,$inputsLang,$typesLang)=cms_admin_records::detachFields($idB);
		list($ID,$okLng)=cms_db::getIdNam($idB);
		$aftId='';$aft="$ID=";$x=explode(' and ',$afterId);foreach($x as $y){
			$z=explode('=',$y);
			if(substr($y,0,strlen($aft))==$aft)$aftId=$y;
		}
		
		//concaténation des données
		foreach($ids as $f)$rtm[$f]=array_key_exists($f,$_POST)?$_POST[$f]:"";
		if(count($fields))foreach($fields as $f){$rtm[$f]='new';$fb="$f$idB";if(isset($_POST[$fb]))if(trim($_POST[$fb])!=''||is_array($_POST[$fb]))$rtm[$f]=$_POST[$fb];}
		if(count($fieldsLang))foreach($fieldsLang as $f){$rtm[$f]='new';$fb="$f$idB";if(isset($_POST[$fb]))if(trim($_POST[$fb])!=''||is_array($_POST[$fb]))$rtm[$f]=$_POST[$fb];}
		
		$tbl="block$idB";

		if($op=="sav"){
			cms_db::genIdRecs($idB,$aftId);
			foreach($ids as $f)$rtm[$f]=$_POST[$f];print_r($ids);print_r($rtm);
			$IDS=array($ID=>$rtm["$ID"]);
			$FDS=array();
				$wh='';foreach($ids as $id)$FDS[$id]=$rtm[$id];$wh[]="$id=".$rtm[$id];
				for($i=0;$i<count($fields);$i++){
					$f=$fields[$i];$fdB=$f.$idB;
					if($inputs[$i]=='tree'){
						$rtm[$f]=serialize($rtm[$f]);
						$FDS[$f]=$rtm[$f];
					}elseif($inputs[$i]=='file'){
						if(array_key_exists($fdB,$_FILES))if(file_exists($_FILES[$fdB]["tmp_name"])){
							$dir=DEV_PATH."upl/dyn/$idB/";
							cms_utils_dirs::makeDir($dir);
							$FDS[$f]=$rtm["$ID"]."-".basename($_FILES[$fdB]["name"]);
							move_uploaded_file($_FILES[$fdB]["tmp_name"],$dir.$FDS[$f]);
						}
					}else $FDS[$f]=$rtm[$f];
				}
			$FLG=array();
				foreach($ids as $id)$FLG[$id]=$rtm[$id];
				for($i=0;$i<count($fieldsLang);$i++){
					$f=$fieldsLang[$i];$fdB=$f.$idB;
					if($inputsLang[$i]=='tree'){
						$rtm[$f]=serialize($rtm[$f]);
						$FLG[$f]=$rtm[$f];
					}elseif($inputsLang[$i]=='file'){
						if(array_key_exists($fdB,$_FILES))if(file_exists($_FILES[$fdB]["tmp_name"])){
							$dir=DEV_PATH."upl/dyn/$idB/";
							$FLG[$f]=$rtm["$ID"]."-".basename($_FILES[$fdB]["name"]);
							move_uploaded_file($_FILES[$fdB]["tmp_name"],$dir.$FDS[$f]);
						}
					}else $FLG[$f]=$rtm[$f];
				}
			if(count($fieldsLang))cms_db::setLangRecord($tbl,$IDS,$FDS,$FLG,$aftId);else cms_db::setRecord($tbl,$IDS,$FDS,$aftId);
		}
		
		if($op=="mov"){
			cms_db::movRecord($tbl,$idB);
		}
		
		if($op=="del"){
			cms_db::delRecords($idB);
		}
		
		if($op=="sav"||$op=="mov"||$op=="del"){?>
			<script>
				with(top)AJX.SetHtm('?',document.getElementById('cmsEdb<?=$block?>').getAttribute('qry')+'&frame=0&shwAdm=0&allowEdt=1&block=<?=$block?>','cmsEdb<?=$block?>')
			</script>
		<?}
		
		$F=array_merge($ids,$fields,$fieldsLang);$T=array_merge($ids,$types,$typesLang);$I=array_merge($ids,$inputs,$inputsLang);
		if(isset($html)){
			for($i=0;$i<count($F);$i++){
				$f=$F[$i];
				if($I[$i]=='tree'&&$rtm[$f]!=''){
					$x=unserialize($rtm[$f]);
					if(count($x))foreach($x as $minusIdT=>$o)
						if(count($o))foreach($o as $k=>$id)
							$html=str_replace("opt$f$idB"."[$minusIdT"."][$id"."]","selected checked",$html);
				}else $html=str_replace("#rtm$idB.$f#",$rtm[$f],$html);
			}
		}elseif($op=="sav"){// mise à jour des valeurs dans les input id
			foreach($ids as $f)if($rtm[$f]!=""){?><script>if(top.document.frm<?=$block?>)
							if(top.document.frm<?=$block.".$f"?>)
								top.document.frm<?=$block.".$f"?>.value=<?=$rtm[$f]?></script><?}
		}
	}
	if(!isset($html)&&$op=='sav'){// le message de pas de pb?>
			<script>if(top.document.getElementById('msg<?=$block?>'))top.document.getElementById('msg<?=$block?>').style.display='block'</script>
	<?}?>
