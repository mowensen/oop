<?
	class cms_bldWeb_records{
		static function bldRecords($html,$wh=array(),$limit=""){ // les différentes formes du tag temps réel
			$OKEDT=(!ALLOWEDT&&SHW_USR_EDT);
			while(stristr($html,"</cmsRec")){
				list($idB,$qs,$html,$CNT,$aft)=cms_bldWeb_macros::sepStartEndTags($html,'cmsRec');
				
				// vérif que les id dans wh appartiennent tous à la table $idB
				list($idH,$idV,$ids,$flds,$fldsLng,$fields,$inputs,$types,$fieldsLang,$inputsLang,$typesLang)=cms_admin_records::detachFields($idB);
				$strIds=','.implode(',',$ids).',';$ary=array();
				foreach($wh as $w){
					$id=substr($w,0,strpos($w,'='));
					if(strstr($strIds,",$id,"))$ary[]=$w;
				}
				$wh=$ary;
				
				// analyse des attributs du tag cmsRec
				$limit='';$editableInTag=1;
				if($qs){
					$q=cms_utils_format::parseTagAttributes($qs);
					foreach($q as $k=>$v){
						$k=strtolower($k);
						if($k=="lngid")$wh[]="$k=$v";
						if(substr($k,0,2)=="id")$wh[]="$k=$v";
						if($k=="limit")$limit=$v;
						if($k=="noteditable")$editableInTag=$OKEDT=0;
					}
				}
				// ajout d'un enregistrement s'il n'existe pas
				list($ary,$typs,$flds)=cms_db::getRecords($idB,$wh,$limit);
				if($OKEDT){
					if(!count($ary)){
						foreach($wh as $w){$x=explode('=',$w);$_POST[$x[0]]=$x[1];}
						cms_db::genIdRecs($idB);
						list($w,$okLng)=cms_db::getWhere($idB);
						$w1=array();foreach($w as $k=>$v)$w1[]="$k=$v";
						list($ary,$typs,$flds)=cms_db::getRecords($idB,$w1,$limit);
					}
				}
				foreach($ary as $k=>$a){
					$j=0;
					foreach($flds as $f){
						if($typs[$j]!='id')if(trim($a[$f])=='')$ary[$k][$f]='null';
						$j++;
					}
				}
				
				$html.=($OKEDT?"<span>":"");
				if(count($ary)){
					$cnt=str_ireplace("¤cmsRec$idB len¤",count($ary),$CNT);
					$i=0;$uidP="";foreach($ary as $a){
						$c=str_ireplace("¤cmsRec$idB nb¤",$i,$cnt);$i++;
						list($c,$uidP)=self::bldOne($idB,$uidP,$a,$typs,$c,$wh,$editableInTag);
						$html.=$c;
					}
				}
				$html.=($OKEDT?"</span>":"").$aft;
			}
			return $html;
		}
		static function bldOne($idB,$uidP,$a,$typs,$c,$wh,$editableInTag){
			global $db;
			$idR=$a["id$idB"];$OKEDT=(SHW_USR_EDT);
			$uid=$idB;parse_str(implode('&',$wh));
			$j=0;foreach($a as $k=>$v){
				$typ=$typs[$j];$j++;$p=strpos($typ,':');$prm='';if($p){$prm=substr($typ,$p+1);$typ=substr($typ,0,$p);}
				$c=str_ireplace("¤cmsRec$idB $k"."¤",$v,$c);
					if($typ=="id"&&$k!='lngId'&&trim($v)!=''){
						$uid.="_$v";
						$wh[]="$k=$v";
					}
					if($typ=="tree"&&$x=@unserialize($v)){
						$u=$o=array();
						if(is_array($x))foreach($x as $kt=>$y){
							$w=array();
							foreach($y as $idT){$o[]=$idT;$w[]=cms_db_trees::getOption($k.$kt,$idT);}
							$u[]=implode($w," + ");//chaque niveau de select multiple
						}
						$v=implode(" - ",$u);
					}
					if($typ=='time')$v=cms_utils_dates::niceDat($v,$prm);
					if($typ=='pass')$v='';
					if($typ=='alien'){
						list($null,$field,$ary)=self::getAlien($prm);
						$v=$ary[0][$field];
					}
					if($OKEDT&&substr($typ,0,4)=="text")$v="<span id=cmsRec$k"."_$uid>$v</span><input type=hidden name=$k>";
					if($OKEDT)$c=str_ireplace("<cmsRec$idB $k".">","<cmsIpt><cmsRec$idB $k"."></cmsIpt>",$c);
				$c=str_ireplace("<cmsRec$idB $k".">",$v,$c);
			}
			if($OKEDT)$c=	"<cmsfrm id=cmsxfrm$uid style=position:absolute;background-color:yellow></cmsfrm>".
					str_replace("<cmsIpt>","<cmsIpt onclick=\"cms.RecordShw('$uid','$uidP')\">",$c);
			if(strstr($c,"</cmsRec"))$c=self::bldRecords($c,$wh);
			return array($c,$uid);
		}
		static function getAlien($prm){
			$ary=explode(':',$prm);$id=array_shift($ary);$field=array_shift($ary);$wh=array_shift($ary);$lm=array_shift($ary);
			$wh=$wh?explode('|',$wh):array();
			list($ary,$ary1,$ary2)=cms_db::getRecords($id,$wh,'');
			return array($id,$field,$ary);
		}
		static function bldForm($idB,$uid,$uidP,$wh){
			$rk=$uidP!='';
			list($ary,$typs,$flds)=cms_db::getRecords($idB,$wh," limit 0,1");
			$ar=$ary[0];
				$frm=	"<iframe name=ifm$uid style=display:none></iframe>".
					"<div><form name=frm$uid method=post enctype=mutipart/form-data target=ifm$uid>".
						"<input type=hidden name=op value=sav>".
						"<input type=hidden name=do value=frmSbm>";
						"<input type=hidden name=mailTo[] value=''>";
					$fds=	"<input type=hidden name=tables[] value=$idB>".
						"<input type=hidden name=afterId value='".implode(' and ',$wh)."'>".
						"<input type=hidden name=toId value=''>";
				$j=0;foreach($ar as $f=>$v){
					$t=$typs[$j];$j++;$p=strpos($t,':');$prm='';if($p){$prm=substr($t,$p+1);$t=substr($t,0,$p);}
					if($t=='id')$fds.="<input type=hidden name=$f value=$v title=$f>";
					if($t=="text")$fds.="<br>\n\t<input type=text name=$f$idB value=\"$v\" title=$f>";
					if($t=="textarea")$fds.="<br>\n\t<textarea name=$f$idB title=$f>$v</textarea>";
					if($t=="file")$fds.="<br>\n\t$v <input type=file name=$f$idB title=$f>";
					if($t=="checkbox")$fds.="<br>\n\t<input type=checkbox name=$f$idB".($v?' checked':'')." onclick=with(this)value=checked?1:0 title=$f>";
					if($t=="pass")$fds.="<br>\n\t<input type=password id=a$f$idB title=$f>\n\t<input type=password id=b$f$idB title=$f>";
					if($t=="time")$fds.="<br>\n\t<table style=display:inline;top:9px;position:relative border=0 cellspacing=0 cellpadding=0>".
									"<td><input id=$f$uid name=$f$idB value=\"$v\" title=$f></td>".
								"</table>".
								"<script>calendar.show(null,'$f$uid','".cms_utils_dates::convertJsCalendar($prm)."')</script>";
					if($t=="tree"){
						$o=array();if($y=@unserialize($v))foreach($y as $kt=>$z)foreach($z as $idT)$o[]=$idT;
						$fds.="<br>\n\t".str_replace("\n","",cms_bldWeb_trees::makeTree($idB,$f,'','select',' '.implode(" ",$o).' ',1));
					}
					if($t=='alien'){
						list($id,$field,$ary)=self::getAlien($prm);
						$fds.="<br>\n\t<select name=$f$idB title=$f>";
							foreach($ary as $a)$fds.="<option value=".$a["id$id"].">".$a[$field];
						$fds.='</select>';
					}
				}
				$frm.=	$fds."<br><div style=float:right>".
					cms_admin::imgLnk("javascript:cms.RecordDo('$uid','$uidP','add')","add.gif",TXTinsRec,'','margin:4px').
					cms_admin::imgLnk("javascript:cms.RecordDo('$uid','$uidP','del')","drop.gif",TXTdelRec,'','margin:4px').
					($rk?cms_admin::imgLnk("javascript:cms.RecordDo('$uid','$uidP','mov')","up.gif",TXTmovRec,'','margin:4px'):"").
					cms_admin::imgLnk("javascript:cms.RecordDo('$uid','$uidP','sav')","save.gif",TXTsav,'','margin:4px').
					cms_admin::imgLnk("#","left.png",TXThide," onmouseover=\"document.getElementById('cmsxfrm$uid').style.display='none';return false\"",'margin:6px').
					"</div></form></div>";
				return $frm;
		}
	}
?>
