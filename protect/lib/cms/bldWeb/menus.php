<?
	class cms_bldWeb_menus{
		static function getMnu($idTree,$rls=0){
			global $MNU,$BCKS,$block;$H=file_get_contents(".htaccess");
			if($idTree==""){
				$MNU=array("pth"=>array(),"rwt"=>array(),"len"=>array(),"title"=>array());
				$MNU["pth"][$idTree]="";$MNU["ids"][$idTree]=" ";$MNU["hrf"][$idTree]="";
			}
			$MNU["len"][$idTree]=0;$okRw=cms_admin_props::checkRwt()&&cms_admin_props::getRwt();
			$lst=cms_db_trees::getTreeArray("blocks",$idTree,($rls?"rls_C":"c")."msTreesLang");
			foreach($lst as $k=>$b){
				$idB=$b["idT"];$a=$BCKS[$idB];
				if($a["isMenu"]){
					$MNU["len"][$idTree]++;
					$MNU["title"][$idB]=$b["title"];
					$rwT=($MNU["hrf"][$idTree]!=""?$MNU["hrf"][$idTree]." - ":"").cms_utils_files::http($b["title"]);
					$MNU["hrf"][$idB]=($okRw&&($rls||strstr($H,"^".cms_admin_rls::htEscFile($rwT)."\$ "))?$rwT:"?block=$idB");
					$MNU["ids"][$idB]=$MNU["ids"][$idTree].$idB." ";
					// concaténation du fil d'ariane
					$x=explode(" ",trim($MNU["ids"][$idB]));
					$MNU["pth"][$idB]='';for($i=0;$i<count($x)-1;$i++)$MNU["pth"][$idB].="<a href=\"".$MNU["hrf"][$x[$i]]."\">".$MNU["title"][$x[$i]]."</a> / ";$MNU["pth"][$idB].=$MNU["title"][$idB];
					// itération sur le groupe de menus suivants
					if($b["next"])self::getMnu($idB,$rls);
				}
			}
		}
		static function bldMnuLvl($html,$idTree,$lvl){ // itérations sur le tag de menu
			global $MNU,$BCKS,$block;
			while(stristr($html,"<cmsMnu")){
				list($i,$qs,$bef,$cnt,$aft)=cms_bldWeb_macros::sepStartEndTags($html,"cmsMnu");
				$html=$bef;
				$lst=cms_db_trees::getTreeArray("blocks",$idTree);
				foreach($lst as $k=>$b){
					$idB=$b["idT"];$a=$BCKS[$idB];$c=$cnt;
					$selected=0;if(array_key_exists($block,$MNU["ids"]))if(strstr($MNU["ids"][$block]," $idB "))$selected=1;
					if($a["isMenu"]||$qs=='all'){
						$ary=array("nb"=>$b["nb"],"idB"=>$idB,"title"=>$b["title"],"selected"=>$selected,"lvl"=>$lvl);
						if($qs!='all')$ary=array_merge($ary,array("pth"=>$MNU["pth"][$idB],"hrf"=>$MNU["hrf"][$idB],"len"=>$MNU["len"][$idTree]));
						foreach($ary as $k=>$v){
							$c=str_ireplace("¤cmsMnu $k"."¤",$v,$c);
							$c=str_ireplace("<cmsMnu $k".">",
								"<div id=cmsEdtMnu_$idB"."_$idTree style=display:inline;cursor:pointer contenteditable=true onclick=cms.clkMnu(this) onblur=cms.blrMnu(this)>$v</div>",
								$c);
						}
						$next="";if($b["next"]&&strstr($c,"<cmsMnuNext>"))$next=self::bldMnuLvl("<cmsMnu".($qs?" $qs":"").">$cnt</cmsMnu>",$idB,$lvl+1);
						$html.=str_replace("<cmsMnuNext>",$next,$c);
					}
				}
				$html.=$aft;
			}
			if(array_key_exists($block,$MNU["ids"]))$html=str_replace("¤cmsMnu selected-ids¤",$MNU["ids"][$block],$html);
			//if(stristr($html,"<cmsMnu>"))$html=self::bldMnuLvl($html,"","",0);
			if($idTree=="")$html=self::bldMnuGbl($html);
			return $html;
		}
		static function bldMnuGbl($html){
			global $MNU,$BCKS,$block;
			if(stristr($html,"¤cmsMnu"))
				foreach($MNU as $k=>$ids)
					foreach($MNU[$k] as $id=>$v)if("$id"!=""){
						if(stristr($html,"¤cmsMnu$id $k"."¤"))$html=str_replace("¤cmsMnu$id $k"."¤",$v,$html);
						if($id==$block)if(stristr($html,"¤cmsMnu $k"."¤"))$html=str_replace("¤cmsMnu $k"."¤",$v,$html);
					}
			return $html;
		}
	}
?>
