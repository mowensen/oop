<?
	class cms_admin{
		static function shwAdm($html){
			global $lngId,$ctxMnu;
			$x=@preg_split("/(&env=|?env=)/",$_SERVER["QUERY_STRING"]);$hrf=$_SERVER["PHP_SELF"]."?$x[0]";
			$adm="";
			if(ENV==DEV)	$adm=	"<div style=position:absolute;top:0;width:100% onmouseover=\"this.firstChild.style.display='block'\" onmouseout=\"this.firstChild.style.display='none'\">".
							"<div id=cmsCtxMnu style=float:right;margin:5px;display:none>$ctxMnu</div>".
						"</div>";
			$adm.=	"<div id=cmsAdm style=\"position:absolute;top:0;left:0;width:23px;height:19px\" onmouseover=\"this.style.overflow='visible'\">".
					"<table cellspacing=1 cellpadding=0>".
						"<td valign=top>".
							"<div style=display:block;position:relative;margin-bottom:4px".(ENV==DEV&SHW_USR_EDT?" oncontextmenu=\"document.getElementById('cmsCtxMnu').style.display='block';return false\"":"").">".
								"<div id=progress style=position:fixed;top:1px;left:1px;background-color:red;color:white;visibility:hidden>EN COURS</div>".
								"<table cellspacing=0 cellpadding=0 onmouseover='this.parentNode.style.width=this.offsetWidth' style=background-color:white>";
					if(ENV==DEV)$adm.=		"<td>".self::imgLnk("$hrf&env=prd","eye.gif",TXTdevView)."</td>";
					if(ENV==PRD)$adm.=		"<td>".self::imgLnk("$hrf&env=dev","eyr.gif",TXTprdView)."</td>";
					if(ENV==DEV){
						if(GRP!="guests")$adm.=	"<td onmouseout=\"this.firstChild.nextSibling.style.display='none'\">".
										"<div onmouseover=\"this.nextSibling.style.display='block'\">".
											self::imgLnk("javascript:cms.ShwBlocks()","bck.png",TXTblockTree).
										"</div>";
						$adm.=				"<div style=position:absolute;display:none;background-color:white onmouseover=\"this.style.display='block'\">";
						if(GRP=="admins")$adm.=			"<div>".self::imgLnk("javascript:cms.ShwUsers()","users.png",TXTusers)."</div>".
											"<div>".self::imgLnk("javascript:cms.ShwProps()","prop.png",TXTproperties)."</div>";
						$adm.=				"</div>";
						if(GRP!="guests")$adm.=	"</td>";
						if(GRP=="admins")$adm.=	"<td onmouseout=\"this.firstChild.nextSibling.style.display='none'\">".
										"<div onmouseover=\"this.nextSibling.style.display='block'\">".
											self::imgLnk("javascript:cms.Search()","search.gif",TXTsearch).
										"</div>".
										"<div style=position:absolute;display:none;margin-left:3px;background-color:white onmouseover=\"this.style.display='block'\">".
											"<div onclick=try{alert(AJX.GetBuf(0))}catch(e){} title='".TXTajax."' style=cursor:pointer>AJX</div>".
											"<div><a href=\"".DB_ADM_WEB_PATH."\" target=SQL title='".TXTsql."' style=cursor:pointer>SQL</a></div>".
											"<div title='".'Track traces'."' style=cursor:pointer onclick=cms.ShwTraces()>TRC</a></div>".
										"</div>".
									"</td>".
									"<td &nbsp;</td>".
									"<td width=50%>&nbsp;</td>";
						if(GRP!="admins")$adm.=	"<td>".self::imgLnk("javascript:cms.ShwUsr('upd','".USR."','".GRP."')","user.png",TXTusers)."</td>";
								$adm.=	(SEC?"<td>".self::imgLnk("$hrf&logout=1","out.png",TXTlogout)."</td>":"").
									"<td width=50%>&nbsp;</td>";
						if(GRP!="guests")$adm.=	"<td align=right nowrap> ".self::imgLnk("javascript:cms.AdminInit();cms.HidElm('Tree')","left.png",TXThide,"onclick=\"cms.Elm('Adm').style.overflow='hidden'\"")."</td>";
					}
							$adm.=	"</table>".
							"</div>";
				if(ENV==DEV)$adm.=	"<div ".(GRP!='admins'?'':'class=cmsAdm')." id=cmsUsers></div>".
							"<div class=cmsAdm id=cmsTree>".
								"<table id=cmsTreeMnu cellspacing=4 cellpadding=0 align=right style=margin-top:-3px>".
									"<td>".cms_utils::lngSel(1)."</td>".
									"<td width=9>&nbsp;</td>".
									"<td>".self::imgLnk("javascript:cms.SearchBlock()","searchBloc.gif",TXTsearchBlock)."</td>".
									"<td width=9>&nbsp;</td>".
									"<td>".self::imgLnk("javascript:cms.AddBlock()","add.gif",TXTinsertBlock)."</td>".
									"<td>".self::imgLnk("javascript:cms.DelBlock()","drop.gif",TXTdeleteBlock)."</td>".
									"<td>".self::imgLnk("javascript:cms.ShwRls()","rls.gif",TXTpublish,"id=cmsRlsIco")."</td>".
								"</table><br>".
								"<div id=cmsTreeLst style=margin-top:6px></div>".
							"</div>";
					$adm.=	"</td>";
			if(ENV==DEV){
					$adm.=	"<td valign=top>";
							foreach(explode(",","users,blocks,rls,search,uploads,props,editor,easEdit,trace") as $f)include OOP_PATH."/protect/layouts/$f.htm.php";
					$adm.=	"</td>";
			}
		$adm.=			"</table>".
				"</div>";
		$adm.=		"<iframe id=cmsBuf name=cmsBuf style='display:none'></iframe>";
			if(stristr($html,"</body"))$html=str_ireplace("</body>","$adm</body>",$html);else $html.=$adm;
			return $html;
		}
		static function imgLnk($lnk,$img,$ttl,$atr="",$stl=""){
			return "<a href=\"$lnk\" title=\"$ttl\" $atr style=><img src=".CMS_IMG."$img border=0 style='margin-left:2px;margin-right:2px;$stl'></a>";
		}
	}
?>
