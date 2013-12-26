<?
	class cms_admin_records{
		static function checkRecords($idB){
			global $db,$dfl;
			$BCKS=cms_admin_blocks::lstBlocks('');
			$RecLnk=$tmp="<option value=''>".TXTchoose;$isVrt=1;$fldsB=$fldsLngB=$idVB=$idHB=$form="";
			if(count($BCKS))
				foreach($BCKS as $id=>$b)
					if($b["fields"]!=""){
						if($id!=$idB){
							$RecLnk.="<option value=$id>".$b["title"];
						}else{
							list($idH,$idV,$ids,$flds,$fldsLng,$fields,$inputs,$types,$fieldsLang,$inputsLang,$typesLang)=self::detachFields($id,$b["fields"]);
							$fields=array_merge($fields,$fieldsLang);$types=array_merge($types,$typesLang);$inputs=array_merge($inputs,$inputsLang);
							$form="";
							if(count($fieldsLang))$form.="<select onchange=with(this)location.replace('?lngId='+options.selectedIndex.text)></select>";
							for($i=0;$i<count($ids);$i++)$form.="<input name=".$ids[$i]." value=<?=(\$".$ids[$i]."?1:0)\"?"."> type=hidden>\n";
							for($i=0;$i<count($fields);$i++){
								$f=$fields[$i];
								$n=$inputs[$i];
								if($n=="text")$form.="<input name=$f value=\"<?=\$$f\"?".">\n";
								if($n=="textarea")$form.="<textarea name=$f><?=\$$f\"?"."></textarea>\n";
								if($n=="checkbox")$form.="<input type=checkbox name=$f onclick=\"this.value=this.checked?1:0\" value=<?=(\$$f?1:0)\"?".">>\n";
							}
							$isVrt=$idH!=""?0:1;
							$fldsB=$flds;
							$fldsLngB=$fldsLng;
						}
					}
			if($tmp==$RecLnk)$RecLnk=$tmp="<option value=''>".TXTnoTable;
			if(isset($ids))for($i=0;$i<count($ids);$i++){
				$id=substr($ids[$i],2);
				$RecLnk=str_replace(" value=$id>"," value=$id selected>",$RecLnk);
			}

			$x=$db->fetchCol("select distinct(tree) from cmsTreesLang where tree like '%-' and lngId='$dfl'");
			$trees="<option>trees";foreach($x as $y){
				$o=substr($y,0,strlen($y)-1);
				if(!strstr($trees,$o)&&$o!="blocks")$trees.="<option>$o";
			}

			return array($RecLnk,$isVrt,$BCKS[$idB]["isForm"],$fldsB,$fldsLngB,$trees,$form);
		}
		static function detachFields($idB,$FIELDS=''){
			if(!$FIELDS)$FIELDS=$GLOBALS["db"]->fetchOne("select fields from cmsBlocks where idB=$idB");
			$x=explode("\n\n",$FIELDS);
				$ids=$flds=$fldsLng="";$ids=$x[0];if(count($x)>1)$flds=$x[1];if(count($x)>2)$fldsLng=$x[2];
				
				$ids=explode("\n",$ids);foreach($ids as $k=>$v)$ids[$k]=trim($v);$id1=$ids[0];$id2=count($ids)>1?$ids[1]:"";
				$idH=$idV="";if("id$idB"!=trim($id1)){$idH=$id1;}elseif($id2){$idV=$id2;}
				// si idH => extension d'une table existante
				// si idV => idV est une table parent

				$x=explode("\n",$flds);$fields=$inputs=$types=array();
				if(trim($flds))foreach($x as $y){
					$ary=explode(" ",$y);$fields[]=array_shift($ary);$types[]=array_pop($ary);$inputs[]=implode(' ',$ary);
				}
				
				$x=explode("\n",$fldsLng);$fieldsLang=$inputsLang=$typesLang=array();
				if(trim($fldsLng))foreach($x as $y){
					$ary=explode(" ",$y);$fieldsLang[]=array_shift($ary);$typesLang[]=array_pop($ary);$inputsLang[]=implode(' ',$ary);
				}
				
				return array($idH,$idV,$ids,$flds,$fldsLng,$fields,$inputs,$types,$fieldsLang,$inputsLang,$typesLang);
		}
		static function saveFields($id,$FIELDS,$isForm){
			global $db;
			list($idH,$idV,$ids,$flds,$fldsLng,$fields,$inputs,$types,$fieldsLang,$inputsLang,$typesLang)=self::detachFields($id,$FIELDS);
			$keys=$fIds=$tIds=$ftIds=array();foreach($ids as $i){$fIds[]=trim($i);$tIds[]="int(11) not null";$ftIds[]=trim($i)." int(11) not null";$keys[]=trim($i);}
			$tbls=implode(",",$db->fetchCol("show tables"));
			if(trim($flds))self::doFieldsJob($id,$tbls,$fields,$fIds,$flds,$inputs,$types,$tIds,$ftIds,$keys,0);
			if(trim($fldsLng))self::doFieldsJob($id,$tbls,$fieldsLang,$fIds,$fldsLng,$inputsLang,$typesLang,$tIds,$ftIds,$keys,1);
			if(!count($fields)&&!count($fieldsLang))$FIELDS="";
			$db->query("update cmsBlocks set fields='".mysql_escape_string($FIELDS)."',isForm=$isForm where idB=$id");
		}
		static function doFieldsJob($id,$tbls,$fields,$fIds,$flds,$inputs,$types,$tIds,$ftIds,$keys,$lng){
			global $db,$dfl;
			$Lang=$lng?"Lang":"";$block="block$id$Lang";$ids=implode(",",$fIds);
			if(stristr(",$tbls,",",".DB_PFX."$block,")){	// ajout modification de champs
				$fields=array_merge($fIds,$fields);$types=array_merge($tIds,$types);$inputs=array_merge($keys,$inputs);
				$x=$db->fetchAll("show fields from $block");$y=array();foreach($x as $z)$y[$z["Field"]]=$z["Type"].($z["Null"]!="NO"?"":" not null");
				foreach($y as $f=>$t)
					if(!strstr("\n$flds","\n$f ")&&!strstr(",$ids,",",$f,")&&$f!="lngId"&&$f!="xdate"&&$f!="xuser"&&$f!="xip"&&$f!="nb"){
						$x=$db->fetchOne("select $f from $block where ($f!='' and !isnull($f))"); //vérif qu'il n'y a pas de données dans la colonne $f
						if(!$x)$db->query("alter table $block drop column $f");
					}
				for($i=0;$i<count($fields);$i++){
					if(!array_key_exists($fields[$i],$y))$db->query("alter table $block add ".$fields[$i]." ".$types[$i]);
					elseif($y[$fields[$i]]!=$types[$i])$db->query("alter table $block modify ".$fields[$i]." ".$types[$i]);
					if($inputs[$i]=="tree"){
						$x=$db->fetchOne("select tree from cmsTreesLang where tree='".$fields[$i]."-'");
						if(!$x)$db->query("insert into cmsTreesLang (tree,lngId,idT,nb,title)values('".$fields[$i]."-','$dfl',0,0,'editThisOption')");
					}
				}
			}elseif($flds){				// ajout de tables
				for($i=0;$i<count($fields);$i++)$y[]=$fields[$i]." ".$types[$i];
				$lf=($lng?"lngId,":"");$lt=($lng?"lngId char(2) not null,":"");
				$trace=$lng?"":",xuser char(15),xip char(15),xdate timestamp";// on update CURRENT_TIMESTAMP";
				$nb=$lng?"":",nb int(11)";
				$f="";for($i=0;$i<count($fields);$i++)$f.=",".$fields[$i]." ".$types[$i];
				//echo "create table $block($lt".implode(",",$ftIds)."$nb$f$trace,primary key($lf".implode(",",$keys)."))ENGINE=InnoDB DEFAULT CHARSET=utf8";
				$db->query("create table $block($lt".implode(",",$ftIds)."$nb$f$trace,primary key($lf".implode(",",$keys)."))ENGINE=InnoDB DEFAULT CHARSET=utf8");
			}
		}
	}
?>
