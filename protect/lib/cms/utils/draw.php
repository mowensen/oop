<?
	class cms_utils_draw{
		function reDimImg($memoryLimit,$respect,$width,$height,$fil,$pfix,$dir,$dest,$quality=70,$ofsX=0,$ofsY=0){ // redimensions an image
			// respect=-1 => conserver les proportions et prendre le min
			// respect=1 => conserver les proportions et prendre le max
			// respect=5 => dimensionnement auto (calcul de la meilleur dimension pour redécoupe/mise à l'echelle)
			// respect=0->4 => redécoupe
				//respect=0 => centrage total
				//respect=1 => offset x + centrage hauteur
				//respect=2 => offset y+centrage largeur
				//respect=3 => offset x + offset y
			ini_set("memory_limit",$memoryLimit);
			$size=getimagesize($dir.$fil);$pfixFil=$pfix.substr($fil,0,strpos($fil,".")).".jpg";
			//1=GIF,2=JPG,3=PNG,4=SWF,5=PSD,6=BMP,7=TIFF(Intel),8=TIFF(Motorola),9=JPC,10=JP2,11=JPX,12=JB2,13=SWC,14=IFF
			$imgTypes=array("","gif","jpeg","png","","","wbmp");
			$typ=$imgTypes[$size[2]];$imageCreateFrom="imageCreateFrom".$typ;$imageSave="image".$typ;
			if($typ){
				$im0=$imageCreateFrom($dir.$fil);
				$w=$size[0];$h=$size[1];
				if($respect<4){
					$x=$y=0;$wi=$w;$hi=$h;
					if($respect==-1){
						$k=min($height/$h,$width/$w);$wf=$w*$k;$hf=$h*$k;
					}else{
						$ico=max($width,$height);
						$k=$ico/$h;if($ico!=$height)$k=$ico/$w;
						if($respect=="1"||$respect=="2"){$wf=$width;$hf=$h*$k;}
						if($respect=="1"||$respect=="3"){$hf=$height;$wf=$w*$k;}
					}
				}else{
					if($respect==4){
						$k=1;$x=$ofsX;$y=$ofsY;
					}else{
						$k=max($width/$w,$height/$h);
						$x=($w-$width/$k)/2;$x=max(0,$x);
						$y=($h-$height/$k)/2;$y=max(0,$y);
					}
					$wf=$width;$hf=$height;$wi=$wf/$k;$hi=$hf/$k;
				}
				//imageantialias($im1, true);
				//imagealphablending($im1,true);
				//imageinterlace($im1,true);
				if(!file_exists($dest.$pfixFil)){
					$im1=imagecreatetruecolor($wf,$hf);
					imageCopyResampled($im1,$im0,0,0,$x,$y,$wf,$hf,$wi,$hi);
					imageJpeg($im1,$dest.$pfixFil,$quality);
					imageDestroy($im1);
				}else{
					alert(str_replace("##","$dest$pfixFil",TXTalrtDrwFil)." !!!");
				}
				imageDestroy($im0);
			}
		}
		function camembert($camembert){//$camembert est un string contenant "%tage1-couleur1,....,%N,couleurN"
			// Création de l'image
			$h=200;$c=$h/2;
			$image = imagecreatetruecolor($h,$h);
			imagecolortransparent($image,0);

			// Allocation de quelques couleurs
			$gris= imagecolorallocate($image, 180, 195, 180);
			$grisfonce= imagecolorallocate($image, 100, 100, 100);
			$grisclair= imagecolorallocate($image, 238, 238, 238);
			$violet= imagecolorallocate($image, 255, 153, 255);
			$bleuClair= imagecolorallocate($image, 204, 255, 255);
			$white= imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
			$gray= imagecolorallocate($image, 0xC0, 0xC0, 0xC0);
			$navy=imagecolorallocate($image, 0x00, 0x00, 0x80);
			$darknavy=imagecolorallocate($image, 0x00, 0x00, 0x50);
			$red=imagecolorallocate($image, 0xFF, 0x00, 0x00);
			$darkred= imagecolorallocate($image, 0x90, 0x00, 0x00);
			$black= imagecolorallocate($image, 50, 50, 50);
			$other= imagecolorallocate($image, 187, 255, 187);
			$col=array($grisclair,$violet,$bleuClair,$other);

			$x=explode(",",$camembert);
			$t=0;$i=0;foreach($x as $y){
				$z=explode("-",$y);$j=$i;if($z[1]!="")$j=$z[1];
				if($z[0])imagefilledarc($image, $c, $c, $h, $h, 3.6*$t, round(3.6*($t+$z[0])), $col[$j], IMG_ARC_PIE);
				$t+=$z[0];$i++;
			}
			$t=0;foreach($x as $y){
				$z=explode("-",$y);
				if($GLOBALS["ray"]){
					$a=2*M_PI*$t/100;
					imageLine($image,$c,$c,(int)($c*(1+cos($a))),(int)($c*(1+sin($a))),$red);
				}
				$t+=$z[0];$i++;
			}
			$t=0;foreach($x as $y){
				$z=explode("-",$y);
				if($z[1]!="")$j=$z[1];
				$ang=2*M_PI*($t+$z[0]/2)/100;
				imagestring($image,3,round($c*(1+3*cos($ang)/4))-5,round($c*(1+3*sin($ang)/4)),"$z[0]%",$black);
				$t+=$z[0];
			}

			// Affichage de l'image
			header('Content-type: image/png');
			imagepng($image);
			imagedestroy($image);
		}
		static function plotOne($equ="x*x/2000",$deb=0,$fin=1000,$sav=0,$typ='png'){
			$crb=array();$yMin=$yMax=self::evalY($deb,$equ);
			for($i=$deb;$i<=$fin;$i++){
				$crb[$i]=self::evalY($i,$equ);
				if($yMin<$crb[$i])$yMin=$crb[$i];
				if($yMax>$crb[$i])$yMax=$crb[$i];
			}
			$h=abs($yMax-$yMin);
			list($im,$col)=self::createImg($typ,$fin-$deb,$h);
			foreach($crb as $x=>$y)imagesetpixel($im,$x,$h-$y,$col);
			self::outImg($im,$typ,$sav);
		}
		static function evalY($x,$equ){
			eval('$y='.str_replace('x',$x,$equ).';');
			return $y;
		}
		static function createImg($typ='gd2',$w=100,$h=100){
			header("Content-type: image/$typ");
			$im	= imagecreatetruecolor($w,$h);
			imagefilledrectangle($im, 0, 0, $w, $h, 0xFFFFFF);
			$col	= imagecolorallocate($im, 0, 0, 0);
			return array($im,$col);
		}
		static function outImg($im,$typ='gd2',$sav=0){
			$f="image$typ";$f($im,($sav?date("YmdHis",time()).".$typ":''));
			imagedestroy($im);
		}
	}
?>
