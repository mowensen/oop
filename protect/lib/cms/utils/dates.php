<?	// DATE FUNCTIONS ************************************************************************************
	class cms_utils_dates{
		//retourne la date au format jj-mm-aaaa
		static function timeDateToDate($t){
			return strstr($t," ")?substr($t,0,strpos($t," ")):$d;
		}
		static function niceDat($d,$prm='Y M d h:i:s'){ // send back a date in dd/mm/yyyy format
			/*$x=explode("-",self::timeDateToDate($d));
			if(count($x)>2)$d=$x[2]." ".self::mois($x[1])." ".$x[0].($tim?substr($d,strpos($d,' ')):'');
			return $d;*/
			$tim=strtotime($d);if($prm=='')$prm='Y M d h:i:s';
			$dat=date($prm,$tim);$months=self::months();
			if(strstr($prm,'M')){
				$lngId=$GLOBALS['lngId'];
				if($lngId!='en')for($i=0;$i<count($months['en']);$i++)$dat=str_ireplace($months['en'][$i],$months[$lngId][$i],$dat);
			}
			return $dat;
		}
		static function convertJsCalendar($prm){ // utilisé pour le script oop/js/calendar.js
			$prm=str_replace(array('L','o','Y','y'),'ye',$prm);
			$prm=str_replace(array('F','M','m','n','t'),'mo',$prm);
			$prm=str_replace(array('d','D','j','l','N','S','w','z','W'),'da',$prm);
			$prm=str_replace(array('i'),'mi',$prm);
			$prm=str_replace(array('s'),'se',$prm);
		}
		//retourne une date au format aaaa/mm/jj pour l'anglais,
		//et au format jj/mm/aaaa pour les autres langues
		static function lngDat($d){
			global $lng;
			$x=explode("-",self::timeDateToDate($d));
			if($lng=="en")
				return $x[0]."/".$x[1]."/".$x[2];
			else
				return $x[2]."/".$x[1]."/".$x[0];
		}
		//recherche d'une date en arrière de y années et m mois et d jours
		static function pvsDat($y=0,$m=0,$d=0){// date y years and m months and d days before today
			$yy=date("Y",time());$mm=(int)(date("m",time()));$dd=date("d",time());
			$yy-=$y;
			$mm-=$m;if($mm<1){$mm+=12;$yy--;}if(strlen($mm)<2)$mm="0$mm";
			$dat="$yy-$mm-$dd";
			if($d)	$dat=date("Y-m-d",mkTime(0,0,0,$mm,$dd,$yy)-$d*3600*24);
			return $dat;
		}
		static function joursOuvres($deb,$fin){//$deb, $fin timestamp periode ou format Y-m-d => array(ouvré,chomé,week-end,férie)

			global $lundiPaques,$dateAscension,$datePentecote;
			if(strstr($deb,"-")){$x=explode("-",$deb);$deb=mkTime(0,0,0,$x[1],$x[2],$x[0]);}
			if(strstr($fin,"-")){$x=explode("-",$fin);$fin=mkTime(0,0,0,$x[1],$x[2],$x[0]);}
			$sJ=3600*24;$per=($fin-$deb)/$sJ;

			$n=$m=$jf=$we=0;// recherche des jours de week-end
				for($i=$deb;$i<$fin;$i+=$sJ){ //recherche 1er lundi à partir de $deb
					$n++;
					if(date("N",$i)==6||date("N",$i)==7)$we++;
					if(date("N",$i)==1)$i=$fin+1;
				}
				for($i=$fin;$i>=$deb+$n*$sJ;$i-=$sJ){ //recherche 1er lundi avant $fin
					$m++;
					if(date("N",$i)==6||date("N",$i)==7)$we++;
					if(date("N",$i)==1)$i=$deb-1;
				}
			$nbJ=$per-$n-$m;$we+=2*(int)($nbJ/7);

			$f=self::joursFeries(date("Y",$deb),date("Y",$fin)); // recherche jours fériés
			if(count($f))foreach($f as $fr)if($fr>=$deb&&$fr<=$fin&&date("N",$fr)<6)$jf++;


			return array($per,$we,$jf);
		}
		static function joursFeries($a1,$a2=""){
			$f=array();$sJ=3600*24;if(!$a2)$a2=$a1;
			for($a=$a1;$a<=$a2;$a++){ // Les jours fériés
				$f[]=$lundiPaques=@easter_date($a)+$sJ;
				$f[]=$jeudiAscension=$lundiPaques+38*$sJ;
				$f[]=$lundiPentecote=$jeudiAscension+11*$sJ;
				$f[]=$premierJanvier=mkTime(0,0,0,1,1,$a);
				$f[]=$premierMai=mkTime(0,0,0,5,1,$a);
				$f[]=$huitMai=mkTime(0,0,0,5,8,$a);
				$f[]=$quatorzeJuillet=mkTime(0,0,0,7,14,$a);
				$f[]=$quinzeAout=mkTime(0,0,0,8,15,$a);
				$f[]=$premierNovembre=mkTime(0,0,0,11,1,$a);
				$f[]=$onzeNovembre=mkTime(0,0,0,11,11,$a);
				$f[]=$noel=mkTime(0,0,0,12,25,$a);
			}
			return $f;
		}
		static function joursMois($a,$m){
			$deb=mkTime(0,0,0,$m,1,$a);
			$fin=mkTime(0,0,0,$m,date("t",$deb),$a);
			$j=array();
				for($i=1;$i<date("N",$deb);$i++)$j[date("W",$deb)][$i]="";
				for($i=$deb;$i<=$fin;$i+=3600*24)$j[date("W",$i)][date("N",$i)]=date("d",$i);
				for($i=1+date("N",$fin);$i<8;$i++)$j[date("W",$fin)][$i]="";
			$jF="-";$f=self::joursFeries($a);if(count($f))foreach($f as $fr)if($fr>=$deb&&$fr<=$fin)$jF.=date("d",$fr)."-";
			return array($j,$jF);
		}
		static function months(){
			return array(
					"en"=>explode(",","Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec"),
					"fr"=>explode(",","Jan,Fév,Mar,Avr,Mai,Juin,Juil,Aoû,Sep,Oct,Nov,Déc"),
					"zh"=>explode(",","一月,二月,三月,四月,五月,六月,七月,八月,九月,十月,十一月,十二月")
				);
		}
		static function mois($m=''){
			global $lngId;if(!$lngId)$lngId="en";
			$months=self::months(); 
			if($m!=''){
				$m=intval($m-1);
				return $months[$lngId][$m];
			}else{
				//Listing des mois
				$htm="<table align=center>";
				foreach($months[$lngId] as $k=>$m){
					if($k/4==intval($k/4))$htm.="<tr>";
						$j=$k+1;if(strlen($j)<2)$j="0$j";
						$htm.="<td mth=$j>$m</td>";
					if(($k+1)/4==intval(($k+1)/4))$htm.="</tr>";
				}
				$htm.="</table>";
			}
			return $htm;
		}
		static function tableJoursMois($dat){
			global $lngId;if(!$lngId)$lngId="en";

			list($an,$mo,$jo,$he,$mn,$se)=array(substr($dat,0,4),substr($dat,5,2),substr($dat,8,2),substr($dat,11,2),substr($dat,14,2),substr($dat,17,2));

			$days=	array(
					"en"=>explode(",","wk,Mo,Tu,We,Th,Fr,Sa,Su"),
					"fr"=>explode(",","se,Lu,Ma,Me,Je,Ve,Sa,Di"),
					"zh"=>explode(",","星期,一,二,三,四,五,六,日")
				);			
			
			//Listing des jours
			$htm="<subDay><table align=center><tr>";
				foreach($days[$lngId] as $k=>$t)$htm.="<td class=wk>$t</td>";
			$htm.="</tr>";

			list($j,$jF)=self::joursMois($an,$mo);
			foreach($j as $s=>$a){
				$htm.="<tr><td class=wk>$s</td>";
				foreach($a as $day=>$dat){
					$cls='nul';
					if($dat)$cls='jr';
					if($day>5&&$dat)$cls='we';
					if(strstr($jF,"-$dat-"))$cls='jf';
					$htm.="<td class=$cls>$dat</td>";
				}
				$htm.="</tr>";
			}
			$htm.="</table></subDay>";
			return $htm;
		}
	}
?>
