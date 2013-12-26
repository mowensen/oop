/*
use: with an object that show the calendar when clicked

	<div>
		<input type="hidden" id="inputId" name="whatever" value="yyyy-mm-dd hh:ii:ss">
		<img src="pathToImg" onclick="calendar.show(this,'inputId','parameters')" style="cursor:pointer">
	</div>
		
use: to show directly the calendar on page load

	<div>
		<input type="hidden" id="inputId" name="whatever" value="yyyy-mm-dd hh:ii:ss">
	</div>
	<script>
		calendar.show(null,'inputId','parmeters')
	</script>
	
explanations:
	if value=="yyyy-mm-dd hh:ii:ss" today's date shows up
	parameters two first letters of date part (year month day hour minute second) you want to see
		parameters='yemo' means year+ month
		parameters='homi' means hour + minute
		parameters='' means all
	inputId is the id of the input 
*/

calInit=function(calObjNam){// global calendar constants
	document.write(
		"<style>"+
			".calendar {position:relative;border:0px solid gray;margin:4px;background-color:white}"+
			".calendar subDiv {position:absolute;width:175px;background-color:white;border:1px solid black}"+
			".calendar .topDate {font-size:12px;margin:5px;font-style:italic;white-space:nowrap}"+
			".calendar td,.calendar div {font-size:11px;font-family:arial;color:navy;color:black}"+
			".calendar .wk {font-style:italic;color:black;font-weight:bold;background-color:#EBC2C0}"+
			".calendar .we {background-color:#ccc}"+
			".calendar .jf {background-color:pink}"+
			".calendar year,.calendar month1,.calendar day,.calendar hour,.calendar minute,.calendar second{cursor:pointer}"+
			".calendar table {border-collapse:collapse;width:100%}"+
			".calendar table td {border:1px solid #EEEEEE}"+
			".calendar .nul {border:1px solid #EEEEEE;background-color:#f2e0df}"+
			".calendar .sd td:hover,.calendar .jr:hover,.calendar .we:hover,.calendar .jf:hover {cursor:pointer;background-color: #EBE4C0}"+
			".calendar month,.calendar subDay {display:none}"+
		"</style>")
	calObj=calObjNam
	AJX.Req("index.php","do=utils&inc=dates&echo=cms_utils_dates::mois('')",calInitNext)
}
calInitNext=function(){
	calInitEnd(calObj)
}
calInitEnd=function(calObjNam){
	function createTable(l,c){
		var h="<table align=center>";for(var i=0;i<c;i++){
			h+="<tr>";
			for(var j=0;j<l;j++){
				k=i*l+j;
				if(!i)k="0"+k;
				h+="<td onclick=\""+calObjNam+".set('xx',this.innerHTML)\">"+k+"</td>";
			}
			h+="</tr>"
		}
		h+="</table>"
		return h
	}
	this.sub60=createTable(10,6)
	this.sub24=createTable(8,3)
	this.months=AJX.GetBuf(0).replace(/<td/g,"<td onclick=\""+calObjNam+".set('month',this.getAttribute('mth'))\"")
	var mth=this.months.replace(/<.*?>/g," ");while(mth.indexOf("  ")>-1)mth=mth.replace(/  /g," ")
	mth=mth.split(/ /g)
	this.mth=mth
	this.head=	"<div class=topDate>"+
					"<day onclick=\""+calObjNam+".showSub('day',this.style.color)\"></day> "+
					"<month></month><month1 onclick=\""+calObjNam+".showSub('month',this.style.color)\"></month1> "+
					"<year onclick=\""+calObjNam+".showSub('year',this.style.color)\"></year> "+
					"<hour onclick=\""+calObjNam+".showSub('hour',this.style.color)\"></hour><sepHr>:</sepHr>"+
					"<minute onclick=\""+calObjNam+".showSub('minute',this.style.color)\"></minute><sepMn>:</sepMn>"+
					"<second onclick=\""+calObjNam+".showSub('second',this.style.color)\"></second>"+
			"</div>"
	this.tags=['year','month','day','minute','hour','minute','second']
	this.show=function(ob,ipt,prm){// display or hide the calendar otherwise instantiate a new calendar
		calId="cal"+ipt
		/*try{
			with(eval(calId).top.getElementsByTagName('subDiv')[0].style){display=display!='none'?'none':'block';width='100%'}
		}catch(e){ // calendar does not exist*/
			eval(calId+"=new "+calObjNam+".add(ob,ipt,prm)")
		//}
	}
	this.showSub=function(wh,c){ // show a pickup choice for year, month, day, hour, minute or seconds
		if(c=='red'){
			with(self.top.getElementsByTagName('subDiv')[0].style)display=display!='none'?'none':'block'
		}else{
			with(self.top.getElementsByTagName('subDiv')[0].style){display='block'}
			var col='red'
			if(wh=='day'&&self.yearMonthChanged)eval(calObjNam).getDays(self.input.value)
			else with(self.top){
				var va=getElementsByTagName(wh)[0].innerHTML;var w=""
				if(wh=="minute"||wh=="second")w=eval(calObjNam).sub60.replace(/xx/g,wh).replace(">"+va+"<"," style=color:"+col+">"+va+"<")
				if(wh=="hour")w=eval(calObjNam).sub24.replace(/xx/g,wh).replace(">"+va+"<"," style=color:"+col+">"+va+"<")
				if(wh=="month")w=eval(calObjNam).months.replace(">"+eval(calObjNam).mth[parseInt(va)]+"<"," style=color:"+col+">"+eval(calObjNam).mth[parseInt(va)]+"<")
				if(wh=="day")w=getElementsByTagName("sub"+wh[0].toUpperCase()+wh.substr(1))[0].innerHTML.replace(">"+va+"<"," style=color:"+col+">"+va+"<")
				if(wh=="year")w=eval(calObjNam).years(parseInt(va)).replace(">"+va+"<"," style=color:"+col+">"+va+"<")
				getElementsByTagName("subDiv")[0].innerHTML=w;getElementsByTagName("subDiv")[0].className=wh!='day'?'sd':''
				for(var i=0;i<eval(calObjNam).tags.length;i++){
					var nm=eval(calObjNam).tags[i];var tg=nm;if(nm=='month')tg+=1;getElementsByTagName(tg)[0].style.color=nm!=wh?'':col
				}
			}
		}
	}
	this.build=function(){ // we have th days so we can build the calendar
		var prm=self.prm;self.yearMonthChanged=0
		with(self.top){
			innerHTML=eval(calObjNam).head+AJX.GetBuf(0);
			appendChild(document.createElement("subDiv"))
			var els=getElementsByTagName('td');
				for(var i=0;i<els.length;i++)
					if(els[i].className!=''&&els[i].className!='nul')
						els[i].setAttribute('onclick',calObjNam+".set('day',this.innerHTML)")
			first='';
			for(var i=0;i<eval(calObjNam).tags.length;i++){
				var nm=eval(calObjNam).tags[i];var ob=getElementsByTagName(nm)[0];
				var va=eval('self.'+nm);if(va.toString().length<2)va='0'+va
				if(prm.indexOf(nm.substr(0,2))>-1){
					eval(calObjNam).set(nm,va)
					if(prm.substr(0,2)==nm.substr(0,2))first=nm
				}else{
					ob.style.display='none'
					if(nm=='hour'||nm=='minute')getElementsByTagName('sepHr')[0].style.display='none'
					if(nm=='second')getElementsByTagName('sepMn')[0].style.display='none'
				}
			}
			//eval(calObjNam).showSub(first,'')
		}
	}
	this.getDays=function(d){// get the calendar days for a specified date
		AJX.Req("?","do=utils&inc=dates&echo=cms_utils_dates::tableJoursMois($dat)&dat="+d,eval(calObjNam).build)
	}
	this.set=function(wh,va){ // change a value in the date
		eval('self.'+wh+'='+va);
		var v=self.input.value;if(v=="")v="0000-00-00 00:00:00";
		var d=v.substr(8,2);var m=v.substr(5,2);var a=v.substr(0,4)
		if((wh=='year'&&a!=va&&a!='0000')||(wh=='month'&&m!=va&&m!='00'))self.yearMonthChanged=1
		if(wh=='year')v=va+v.substr(4)
		if(wh=='month')v=v.substr(0,5)+va+v.substr(7)
		if(wh=='day')v=v.substr(0,8)+va+v.substr(10)
		if(wh=='hour')v=v.substr(0,11)+va+v.substr(13)
		if(wh=='minute')v=v.substr(0,14)+va+v.substr(16)
		if(wh=='second')v=v.substr(0,17)+va
		self.top.getElementsByTagName(wh)[0].innerHTML=va;if(wh=='month')self.top.getElementsByTagName(wh+1)[0].innerHTML=eval(calObjNam).mth[parseInt(va)]
		self.input.value=v
		self.top.getElementsByTagName('subDiv')[0].style.display='none'
	}
	this.years=function(y){ // create years table
		var h="<table align=center>"
		for(i=-3;i<3;i++){
			h+="<tr>"
			for(var j=0;j<5;j++)h+="<td onclick=\""+calObjNam+".set('year',this.innerHTML)\">"+(y+j+5*i)+"</td>"
			h+="</tr>"
		}
		h+="</table>"
		return h
	}
	this.add=function(ob,ipt,prm){ // calendar object
		self=this
		self.input=document.getElementById(ipt);self.prm=prm;if(prm=='')self.prm='dayemodahomise';
		self.top=document.createElement("div");self.top.className='calendar'
		self.top.setAttribute('onmouseover',"self=cal"+ipt)
		if(ob==null)ob=self.input
		ob.parentNode.appendChild(self.top)
		ob.style.display='none'
		with(new Date()){
			self.year=getFullYear();self.month=getMonth()+1;self.day=getDate();
			self.hour=getHours();self.minute=getMinutes();self.second=getSeconds()
		}
		if(self.input.value!=''){
			var x=self.input.value.split(/(-| |:)/g);
			if(x.length<11)alert(self.input.value+' should be xxxx-xx-xx xx:xx:xx in value attribute of input '+ipt)
			if(x.length>0)self.year=x[0];if(x.length>2)self.month=x[2];if(x.length>4)self.day=x[4];
			if(x.length>6)self.hour=x[6];if(x.length>8)self.minute=x[8];if(x.length>10)self.second=x[10]
			if(prm.indexOf('ye')>-1&&self.year.length!=4)alert('year value '+self.year+' not correct in '+self.input.value+' of '+ipt)
			if(prm.indexOf('mo')>-1&&self.month.length!=2)alert('month value '+self.month+' not correct in '+self.input.value+' of '+ipt)
			if(prm.indexOf('da')>-1&&self.day.length!=2)alert('day value '+self.day+' not correct in '+self.input.value+' of '+ipt)
			if(prm.indexOf('ho')>-1&&self.hour.length!=2)alert('hour value '+self.hour+' not correct in '+self.input.value+' of '+ipt)
			if(prm.indexOf('mi')>-1&&self.minute.length!=2)alert('minute value '+self.minute+' not correct in '+self.input.value+' of '+ipt)
			if(prm.indexOf('se')>-1&&self.second.length!=2)alert('second value '+self.second+' not correct in '+self.input.value+' of '+ipt)
		}
		eval(calObjNam).getDays(self.input.value)
	}
	eval(calObjNam+'=this')
}
new calInit('calendar')
