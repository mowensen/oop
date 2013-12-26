<?
	class cms_db{
		private $DB;
		function __construct() {
			global $CFG;
			//$err="";//try{
				$this->DB=Zend_Db::factory($CFG->db->adapter, $CFG->db->params);
				$this->DB->getConnection();
				define ('DB_PFX',$CFG->db->params->prefix);
			/*}catch(Zend_Db_Adapter_Exception $e){
				$err=$e->getMessage();
			}*/
		}
		public function __call($name, $arguments) { // zend_db hack to check prefix to add on table name
			$qry=count($arguments)?self::checkTableName($arguments[0]):'';
			if($GLOBALS['dbg'])echo "$name: $qry<br>\n";
			return $this->DB->$name($qry);
		}
		static function checkTableName($qry){ // checks prefix to add on table name
			if(DB_PFX){
				$x=preg_split("/[ .,()=;]/",$qry);$old=$qry;$qry='';
				for($i=0;$i<count($x);$i++){
					$ok=0;$z=substr($x[$i],0,5);
					if($z=="block"){
						$w=str_replace("Lang","",str_replace($z,"",$x[$i]));
						$ok=strlen($w)>0&&$w==strval(intval($w));
					}
					if($ok||$x[$i]=="cmsTreesLang"||$x[$i]=="cmsBlocks"||$x[$i]=="cmsLabels"||$x[$i]=="cmsLabelsLang"||$x[$i]=="cmsUsers"){
						$t=explode($x[$i],$old);
						$qry.=$t[0].DB_PFX.$x[$i];
						array_shift($t);$old=implode($x[$i],$t);
					}
				}
				$qry.=$old;
			}
			return $qry;
		}
		public static $sqlFunctions=array(
			// les clés sont les fonctions mySql
			"mysql"=> array("ifnull"=>"ifnull","substring"=>"substring","ltrim"=>"ltrim","position"=>"position"),
			"mysqli"=> array("ifnull"=>"ifnull","substring"=>"substring","ltrim"=>"ltrim","position"=>"position"),
			"oracle"=> array("ifnull"=>"nvl","substring"=>"substr","ltrim"=>"ltrim","position"=>"instr"),
			"oci"=> array("ifnull"=>"nvl","substring"=>"substr","ltrim"=>"ltrim","position"=>"instr"),
			"mssql"=> array("ifnull"=>"isnull","substring"=>"substring","ltrim"=>"ltrim","position"=>"charindex"),
			"pgsql"=> array("ifnull"=>"coalesce","substring"=>"substr","ltrim"=>"trim","position"=>"position")
		);
		static function getFunction($function){
			$dbType=str_replace("pdo_","",strtolower($GLOBALS["CFG"]->db->adapter));
			return self::$sqlFunctions[$dbType][$function];
		}
		static function getRecords($block,$wh,$limit){
			global $db,$lngId;
			$fields=$db->fetchOne("select fields from cmsBlocks where idB=$block");
			list($idH,$idV,$ids,$flds,$fldsLng,$fields,$inputs,$types,$fieldsLang,$inputsLang,$typesLang)=cms_admin_records::detachFields($block,$fields);
			$sel=$idTyps="";$okLng=count($fieldsLang);$tbl="block$block";
			for($i=0;$i<count($ids);$i++){
				$idTyps[]="id";
				$sel.=($sel?",":"")."$tbl.".$ids[$i];
				//if($okLng)$wh[]="$tbl.".$ids[$i]."=$tbl"."Lang.".$ids[$i];
				if(array_key_exists($ids[$i],$GLOBALS))if($GLOBALS[$ids[$i]]!="")$wh[]="$tbl.".$ids[$i]."=".$GLOBALS[$ids[$i]];
			}
			for($i=0;$i<count($fields);$i++)$sel.=",$tbl.".$fields[$i];
			$where="";if(count($wh)&&$wh!="")$where=" where ".implode(" and ",$wh);
			$ary=$db->fetchAll(trim("select $sel from $tbl $where ".(strstr($limit,'order by')?'':'order by nb ').$limit));
			if($okLng&&count($ary)){
				$sel="lngId";for($i=0;$i<count($fieldsLang);$i++)$sel.=",".$fieldsLang[$i];
				array_unshift($inputsLang,"id");array_unshift($fieldsLang,"lngId");
				foreach($ary as $k=>$a){
					$b=$db->fetchAll("select $sel from $tbl"."Lang where id$block=".$a["id$block"]);
					if(count($b)){
						// récupération de la langue par défaut
						$c=$b[0];for($i=0;$i<count($b);$i++)if($b[$i]["lngId"]==$lngId)$c=$b[$i];
						$ary[$k]=array_merge($ary[$k],$c);
					}
				}
			}
			return array(
					$ary,
					array_merge($idTyps,$inputs,$inputsLang),
					array_merge($ids,$fields,$fieldsLang)
				);
		}
		static function addRecord($idB){
			global $db;
			$fields=$db->fetchOne("select fields from cmsBlocks where idB=$idB");
			list($idH,$idV,$ids,$flds,$fldsLng,$fields,$inputs,$types,$fieldsLang,$inputsLang,$typesLang)=cms_admin_records::detachFields($idB,$fields);
			self::genIdRecs($idB);$IDS=array();foreach($ids as $id)$IDS[$id]=$_POST[$id];
			$i=0;$datLang=array();foreach($fields as $f){
				$dat[$f]="new data";
				if($inputs[$i]=="time")$dat[$f]=date("Y-m-d H:i:s",time());
				if($inputs[$i]=="tree")$dat[$f]='';
				$i++;
			}
			if(count($fieldsLang)){
				$i=0;$datLang=array();foreach($fieldsLang as $f){
					$datLang[$f]="new data";
					if($types[$i]=="time")$datLang[$f]=date("Y-m-d H:i:s",time());
					if($types[$i]=="tree")$datLang[$f]='';
					$i++;
				}
				self::setLangRecord("block$idB",$IDS,$dat,$datLang);
			}else self::setRecord("block$idB",$IDS,$dat);
		}
		static function setLangRecord($tbl,$ids,$dat,$datLang,$afterId=""){ // update table et tableLang
			global $db;
			$ids=self::setRecord( $tbl, $ids, $dat, $afterId);
			$tbl.="Lang";$ids=array_merge(array("lngId"=>$GLOBALS["lngId"]),$ids);
			$wh=self::where($ids,array());$ok=$db->fetchOne("select count(lngId) from $tbl $wh");
			if(!$ok)self::insert($tbl,$ids);//cms_utils::alert($ok."select count(lngId) from $tbl $wh");
			self::setRecord(	$tbl,
						$ids,
						$datLang);
			return $ids;
		}
		static function setRecord($tbl,$ids,$dat,$afterId=""){ // set = si enregistrement n'existe pas => insert avant le update
			global $db;
			$ok=0;foreach($ids as $k=>$v)if(trim($v)==''){
					$ok=1;
					$ids[$k]=$dat[$k]=$_POST[$k]=self::addId($tbl,array());
			}
			if($ok)self::insert($tbl,array_merge($ids,$dat),$afterId);
			if(count($dat)){
				$wh=self::where($ids,array());
				$db->query("update $tbl set " . implode(",",self::fieldValues($dat)) . $wh);
				if(!strstr($tbl,"Lang"))self::updTrace($tbl,$wh);
			}
			return $ids;
		}
		static function tblHasNb($tbl){
			global $db;
			$x=$db->fetchall("show columns from $tbl");$ok=0;for($i=0;$i<count($x);$i++)if($x[$i]["Field"]=="nb")$ok=1;
			return $ok;
		}
		static function delRecords($idB){
			global $db;
			$ary=$db->fetchAll("select idB from cmsBlocks where fields like '%id$idB"." \n%' or fields like '%id$idB"."\n%'");
			foreach($ary as $ar)self::delRecord("block".$ar['idB'],$ar['idB']);
		}
		static function delRecord($tbl,$idB){
			global $db;
				list($w,$okLng)=self::getWhere($idB);$wh=self::where($w);
				if(self::tblHasNb($tbl)){
					$nb=$db->fetchOne("select nb from $tbl $wh");
					$db->query("update $tbl set nb=nb-1 where nb>$nb");
				}
				$db->query("delete from $tbl $wh");
				if($okLng)$db->query("delete from $tbl"."Lang $wh");
		}
		static function genIdRecs($idB,$aftId=""){ // cherche récursivement les id des tables
			global $db;
			list($idH,$idV,$ids,$flds,$fldsLng,$fields,$inputs,$types,$fieldsLang,$inputsLang,$typesLang)=cms_admin_records::detachFields($idB);
			if($idH!="")self::genIdRecs(substr($idH,2));else self::genIdRec($idB,$ids,$fieldsLang,$aftId);
			if($idV!="")self::genIdRecs(substr($idV,2));
		}
		static function genIdRec($id,$ids,$fieldsLang,$aftId){ //générer les identité si elles n'existent pas -> fieldsLang sert à vérifier si la table dépendante de la langue existe également
			global $db;
			$tbl="block$id";$ID="id$id";
			$IDS=array();$wh=array();foreach($ids as $i){
				$IDS[$i]=(!array_key_exists($i,$_POST)?'':(strstr($_POST[$i],"#")?'':$_POST[$i]));
				if(trim($IDS[$i])!='')$wh[]=$i."='".$IDS[$i]."'";
			}
			$whr=(count($wh)?"where ".implode(' and ',$wh):"");
			$ok=$db->fetchOne("select count($ID) from $tbl $whr");
			if(!$ok){
				if(count($fieldsLang))$ids=self::setLangRecord($tbl,$IDS,array(),array(),$aftId);
				else $ids=self::setRecord($tbl,$IDS,array(),$aftId);
			}
			foreach($ids as $k=>$v)$_POST[$k]=$v;
		}
		static function movRecord($tbl,$idB,$after=0){// before=0 => after=-1 - after= => after=0
			// RQ: Seule une table avec un id$idB peut déplacer ses enregistrements par contre les autres id sont nécessaires pour changer les nb
			global $db;
			list($w,$okLng)=self::getWhere($idB);$wh=self::where($w);array_shift($w);$wh1=self::where($w);
			$nb=$db->fetchOne("select nb from $tbl $wh");
			$db->query("update $tbl set nb=nb-1 where nb>$nb $wh1");
			$toNb=$db->fetchOne("select nb from $tbl ".self::where(self::extractIds($idB,$_POST['toId'])));
			$toNb+=$after;
			$db->query("update $tbl set nb=nb+1 where nb>=$toNb $wh1");
			$db->query("update $tbl set nb=$toNb $wh");
		}
		static function extractIds($idB,$strIds){
			list($idH,$idV,$ids,$flds,$fldsLng,$fields,$inputs,$types,$fieldsLang,$inputsLang,$typesLang)=cms_admin_records::detachFields($idB);
			$IDS=explode('_',$strIds);
			$wh=array();for($i=0;$i<count($IDS);$i++)$wh[$ids[$i]]=$IDS[$i];
			return $wh;
		}
		static function where($fields,$oth=array()){
			$fv=array_merge(self::fieldValues($fields),$oth);
			return count($fv)?" where ".implode(" and ",$fv):"";
		}
		static function fieldValues($fields){
			$fv=array();if(count($fields))foreach($fields as $k=>$v)$fv[]="$k='".mysql_escape_string($v)."'";return $fv;
		}
		static function getIdNam($idB){
			list($idH,$idV,$ids,$flds,$fldsLng,$fields,$inputs,$types,$fieldsLang,$inputsLang,$typesLang)=cms_admin_records::detachFields($idB);
			return array(($idH!=""?$idH:"id$idB"),count($fieldsLang));
		}
		static function getWhere($idB){
			list($idH,$idV,$ids,$flds,$fldsLng,$fields,$inputs,$types,$fieldsLang,$inputsLang,$typesLang)=cms_admin_records::detachFields($idB);
			$w=array();foreach($ids as $id)if(isset($_POST[$id]))if($_POST[$id]!='')$w[$id]=$_POST[$id];
			return array($w,count($fieldsLang));
		}
		static function insert($tbl,$fields,$afterId=""){
			global $db;$nams=$vals=array();
			// gestion du rang
			if(self::tblHasNb($tbl)){
				$nams[]="nb";
				if($afterId){
					$nb=$db->fetchOne("select nb+1 from $tbl where $afterId");
					$vals[]=$nb;
					$db->query("update $tbl set nb=nb+1 where nb>=$nb");
				}else $vals[]=$db->fetchOne("select ".cms_db::getFunction("ifnull")."(max(nb)+1,0) from $tbl");
			}
			//ajout des champs
			foreach($fields as $k=>$v){$nams[]=$k;$vals[]=$v;}
			$db->query("insert into $tbl (".implode(",",$nams).")values('".implode("','",$vals)."')");
		}
		static function addId($tbl,$whr){
			$id=str_replace("block","id",str_replace("Lang","",$tbl));
			return $GLOBALS["db"]->fetchOne("select ".cms_db::getFunction("ifnull")."(max($id)+1,0) from $tbl ".self::where($whr));
		}
		static function getTrace(){
			return array("xuser"=>USR,"xdate"=>date("Y-m-d H:i:s",time()),"xip"=>$_SERVER["REMOTE_ADDR"]);
		}
		static function updTrace($tbl,$wh){
			global $db;
			$trace=implode(",",self::fieldValues(self::getTrace()));
			$isM=$tbl!="cmsBlocks"?"":"isModified=1,";
			if(substr(trim($wh),0,6)!="where ")$wh=" where $wh";
			$db->query("update $tbl set $isM$trace $wh");
			if(substr($tbl,0,5)=="block")$db->query("update cmsBlocks set isModified='1',$trace where idB=".substr($tbl,5));
		}
		static function getTraces($tbls='',$usr='',$limit=''){
			global $db;$wh=$ary=$usrs=array();
			if(!$tbls)$tbls=explode(' ','Blocks Labels TreesLang Users');else $tbls=array($tbls);
			if(!$limit)$limit='limit 0,9';
			if($usr)$wh[]="xuser='$usr'";
			$w=implode(' and ',$wh);if($w)$w="where $w";
			foreach($tbls as $tb){
				$a=$db->fetchAll("select * from cms$tb $w group by xuser,xip order by xdate desc,xuser,xip $limit");
				if(count($a))$ary[$tb]=array();foreach($a as $b){
					$usrs[$b['xuser']]=$b['xuser'];
					$c=array();foreach($b as $k=>$v)$c[]="$k=>".substr($v,0,33).(strlen($v)>33?'...':'');
					$ary[$tb][]=implode(' ',$c);
				}
			}
			return array($tbls,$usrs,$ary);
		}

	}
?>
