<?
	foreach(array("ids"=>"","op"=>"","quick"=>1,"step"=>"","time"=>"","toDel"=>"") as $k=>$v)$$k=cms_bldWeb::getVar($k,$v);

	if($ids=="all"){
		// Mise à jour tables rls - la fonction copyRlsTable est dans inc/sql/mySql/query.php
		$ND="all";
			if($step==1){cms_admin_rls::copyRlsTable("cmsBlocks");}
			if($step==2){cms_admin_rls::copyRlsTable("cmsTreesLang");}
			if($step==3){cms_admin_rls::copyRlsTable("cmsLabels");cms_admin_rls::copyRlsTable("cmsLabelsLang");}
			if($step==4){
				$x=$db->fetchAll("select idB,fields from cmsBlocks where fields!='' and isForm='0'");	//recherche tables dynamiques
				for($i=0;$i<count($x);$i++)cms_admin_rls::copyRlsBlock($x[$i]['idB'],$x[$i]['fields']);
			}

		// Mise à jour des fichiers du site
			if($step==7)cms_admin_rls::rlsFiles("php/",0);
			if($step==8)cms_admin_rls::rlsFiles("php/rtm/");
			if($step==8)cms_admin_rls::rlsFiles("php/mdl/");
			if($step==9)cms_admin_rls::rlsFiles("upl/sta/");
			if($step==10)cms_admin_rls::rlsFiles("upl/dyn/");
	}elseif($ids!=""||$toDel!=""){
		$Ids=explode(",",$ids);$ND=0;
		if($ids!="")foreach($Ids as $id){
			// Table cmsBlocks
			if($step==1){
				cms_admin_rls::copyRlsField("cmsBlocks","idB",$id);$ND++;
				cms_admin_rls::copyRlsField("cmsTreesLang","idT",$id," and tree='blocks-'");
			}
			// Publication des trees
			if($step==2){
				$flds=$db->fetchOne("select fields from cmsBlocks where idB=$id");
				if(strstr($flds," tree ")){
					$t=explode(" tree ","\n$flds");		// rechercher des Groups : tree, select
					for($i=0;$i<count($flds)-1;$i++){ 	// pour chaque niveau dynamique
						$u=explode("\n",$flds[$i]);$tree=$u[count($u)-1];
						$ND++;
						$y=$db->fetchAll("select idT from cmsTreesLang where title like '$tree-%'");
						for($j=0;$j<count($y);$j++)cms_admin_rls::copyRlsField("cmsTreesLang","idT",$y[$j]["idT"]);
					}
				}
			}
			//maj des textes statiques
			if($step==3){
				cms_admin_rls::copyRlsField("cmsLabels","idB",$id);cms_admin_rls::copyRlsField("cmsLabelsLang","idB",$id);
				$ND+=$db->fetchOne("select count(idB) from cmsLabels where idB=$id");
			}
			//maj des tables dynamiques
			if($step==4){
				$x=$db->fetchRow("select fields from cmsBlocks where idB=$id and fields!=''");
				if($x['fields']){cms_admin_rls::copyRlsBlock($id,$x['fields']);$ND++;}
			}
			// Mise à jour des fichiers du site
				if($step==7)cms_admin_rls::cpyRlsFil("php/$id.php");
				if($step==8)cms_admin_rls::cpyRlsFil("php/rtm/$id.php");
				if($step==8)cms_admin_rls::cpyRlsFil("php/mdl/$id.php");
				if($step==9)if(is_dir(DEV_PATH."upl/sta/$id/"))cms_admin_rls::rlsFiles("upl/sta/$id/");
				if($step==10)if(is_dir(DEV_PATH."upl/dyn/$id/"))cms_admin_rls::rlsFiles("upl/dyn/$id/");
			// isModified
				if($step==11)$db->query("update cmsBlocks set isModified=0 where idB=$id");
		}
		$ToDel=explode(",",$toDel);
		if($toDel!="")foreach($ToDel as $id){
			if($step=="1"){
				$db->query("delete from rls_CmsBlocks where idB=$id");
				$db->query("delete from rls_CmsTreesLang where tree like 'blocks-%' and idT=$id");
			}
			if($step=="2"){
				// on n'efface pas les arboresence (cmsTreesLang) car peuvent encore être utilisées
			}
			if($step=="3"){
				$db->query("delete from rls_AdminLabels where idB=$id");
				$db->query("delete from rls_AdminLabelsLang where idB=$id");
			}
			if($step==4){
				$db->query("drop table if exists block$id");
				$db->query("drop table if exists block$id"."Lang");
			}
			if($step==7)cms_admin_rls::delFile("php/$id.php");
			if($step==8)cms_admin_rls::delFile("php/rtm/$id.php");
			if($step==9)cms_admin_rls::delDir("upl/sta/$id/");
			if($step==10)cms_admin_rls::delDir("upl/sta/$id/");
		}
		// Vérif arborescence
		if($step==1){
			$x=$db->fetchAll("select idT,nb,title from cmsTreesLang where lngId=1 and title like 'blocks-%' order by nb");
			for($i=0;$i<count($x);$i++)$db->query("update cmsBlocks set nb=".$x['nb']." where idB=".$x['idT']." and title='".$x['title']."'");
		}
	}
	
	// Apache RW rule
	if($step==11){
		$t=file_get_contents(".htaccess");
		$x=explode("<IfModule mod_rewrite.c>\n",$t);
		if(count($x)>1){$y=explode("</IfModule>\n",$x[1]);$x[1]=$y[1];}
		// language and page iteration
		$y=explode("/",$_SERVER["REQUEST_URI"]);$y[count($x)-1]="";$base=implode("/",$y);
		$t=$tx="";
		$cfg = new Zend_Config_Ini(APP_PATH.'/config.ini', 'prd');$lngs=$cfg->lng;
		$lng=$lngId;
			foreach($lngs as $lngId=>$v){
				$BCKS=cms_admin_blocks::lstBlocks('');
				cms_bldWeb_menus::getMnu("",1);
				foreach($MNU["hrf"] as $id=>$rw)if($rw){
					$nm=cms_admin_rls::htEscFile($rw);
					$ru="RewriteRule ^$nm\$ ?block=$id [L,QSA]\n";
					if(!strstr($t,"RewriteRule ^$nm\$ ?block=")){
						$t.=$ru;
						$tx.=	"\t<url>\n".
								"\t\t<loc>http://".$_SERVER['HTTP_HOST']."/".str_replace("\\","",$nm)."</loc>\n".
								"\t\t<lastmod>".date("Y-m-d",time())."</lastmod>\n".
							"\t</url>\n";
					}
				}
			}
		$lngId=$lng;
		$t=cms_admin_props::getRwt()?"<IfModule mod_rewrite.c>\nRewriteEngine On\nRewriteBase $base\n$t</IfModule>":"";
		while(strstr($t,"\n\n"))$t=str_replace("\n\n","\n",$t);
		cms_utils_files::save(".htaccess",$x[0].$t.(count($x)>1?"\n$x[1]":""));
		cms_utils_files::save("sitemap.xml","<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n$tx</urlset>");
		cms_utils_files::save("robots.txt","User-agent: *\nDisallow: /sav\nSitemap: http://".$_SERVER['HTTP_HOST']."/sitemap.xml");
	}

	cms_admin_rls::popTxt($step);
	echo $step;
?>

