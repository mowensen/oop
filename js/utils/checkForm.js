/*
use:
	<script>
		object=new checkForm('frmName')
		object.addChecker('inputName','checkType','message if no good')
	</script>
*/

checkForm=function(frmName){

	this.formObj = document.forms[frmName];

   	this.old_onsubmit = this.formObj.onsubmit?this.formObj.onsubmit:false;

	validations=new Array()

	this.formObj.onsubmit = function(){
		var ok=true; var msgs=''; var objFocus=false; 
		for(var i = 0; i < validations.length; i++){
			var x=validations[i]
			if(x[4] ? eval(x[4]):true){
				if(checkForm.checkInput(x[2],x[1])){
					try{document.getElementById('lbl'+x[0]).style.color=''}catch(e){}
				}else{
					ok = false
					if(!objFocus)objFocus=x[1]
					msgs+=x[3]+"\n"
					try{document.getElementById('lbl'+x[0]).style.color='red'}catch(e){}
				}
			}
		}

		if(ok){
			if(this.old_onsubmit)eval(this.old_onsubmit)
		}else{
			objFocus.focus();
			alert(msgs);
			return false;
		}		
	}

	this.addChecker=function(itemName, descriptor, errStr){
		condition = arguments.length > 3? arguments[3]:null;
		var itemObj = this.formObj[itemName]
		if (itemObj.length && isNaN(itemObj.selectedIndex))itemObj = itemObj[0];
		validations[validations.length]=Array(itemName,itemObj,descriptor, errStr, condition);
	}
}
checkForm.checkInput=function(check, obj){
	var ret = true;
	var epos = check.search("=");
	var cmdvalue = "";
	if (epos >= 0){
		cmdvalue = check.substr(epos + 1);
		check = check.substring(0, epos);
	}
	var objValue=obj.value
	switch (check){
		case "notempty":
			ret = checkForm.required(objValue)
			break;
		case "maxlength":
			ret = checkForm.maxLen(objValue, cmdvalue)
			break;
		case "minlength":
			ret = checkForm.minLen(objValue, cmdvalue)
			break;
		case "alphabetic":
			ret = checkForm.inputType(objValue, "[^A-Za-z-']", objValue.name + ": Only alphabetic characters allowed ");
			break;
		case "alphabetic_space":
			ret = checkForm.inputType(objValue, "[^A-Za-z\\s]", objValue.name + ": Only alphabetic characters and space allowed ");
			break;
		case "alphanumeric":
			ret = checkForm.inputType(objValue, "[^A-Za-z0-9-']", objValue.name + ": Only alpha-numeric characters allowed ");
			break;
		case "alphanumeric_space":
			ret = checkForm.inputType(objValue, "[^A-Za-z0-9\\s]", objValue.name + ": Only alpha-numeric characters and space allowed ");
			break;
		case "numeric":
			if (objValue.length > 0 && !objValue.match(/^[\-\+]?[\d\,]*\.?[\d]*$/)){
				sfm_show_error_msg(strError, objValue);
				ret = false;
			}
			break;
		case "email":
			ret = checkForm.email(objValue);
			break;
		case "lessthan":
			ret = checkForm.lessThan(objValue, cmdvalue);
			break;
		case "greaterthan":
			ret = checkForm.greaterThan(objValue, cmdvalue);
			break;
		case "regexp":
			ret = checkForm.regExp(objValue, cmdvalue);
			break;
		case "dontselect":
			ret = checkForm.dontSelect(objValue, cmdvalue)
			break;
		case "dontselectchk":
			ret = checkForm.dontSelectChk(objValue, cmdvalue)
			break;
		case "shouldselchk":
			ret = checkForm.shouldSelectChk(objValue, cmdvalue)
			break;
		case "selmin":
			ret = checkForm.selMin(objValue, cmdvalue);
			break;
		case "selmax":
			ret = checkForm.selMax(objValue, cmdvalue);
			break;
		case "selone_radio":
			ret = checkForm.selectOneRadio(objValue);
			break;
		case "dontselectradio":
			ret = checkForm.selectRadio(objValue, cmdvalue, false);
			break;
		case "selectradio":
			ret = checkForm.selectRadio(objValue, cmdvalue, true);
			break;
			//Comparisons
		case "eqelmnt":
		case "ltelmnt":
		case "leelmnt":
		case "gtelmnt":
		case "geelmnt":
		case "neelmnt":
			return checkForm.comparison(obj, cmdvalue, check);
			break;
		case "file_extn":
			ret = checkForm.fileExtension(objValue, cmdvalue);
			break;
	}
	return ret;
}

checkForm.required=function(objValue){
    var ret = true;
    if (checkForm.isEmpty(objValue)){
        ret = false;
    }else if (objValue.getcal && !objValue.getcal())ret = false;
    return ret;
}
checkForm.maxLen=function(objValue, strMaxLen){
    var ret = true;
    if (eval(objValue.length) > eval(strMaxLen))ret = false;
    return ret;
}
checkForm.minLen=function(objValue, strMinLen){
    var ret = true;
    if (eval(objValue.length) < eval(strMinLen))ret = false;
    return ret;
}
checkForm.inputType=function(objValue, strRegExp, strDefaultError){
    var ret = true;

    var charpos = objValue.search(strRegExp);
    if (objValue.length > 0 && charpos >= 0)ret = false;
    return ret;
}
checkForm.email=function(email){
    var splitted = email.match("^(.+)@(.+)$");
    if (splitted == null) return false;
    if (splitted[1] != null){
        var regexp_user = /^\"?[\w-_\.]*\"?$/;
        if (splitted[1].match(regexp_user) == null) return false;
    }
    if (splitted[2] != null){
        var regexp_domain = /^[\w-\.]*\.[A-Za-z]{2,4}$/;
        if (splitted[2].match(regexp_domain) == null){
            var regexp_ip = /^\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]$/;
            if (splitted[2].match(regexp_ip) == null) return false;
        }
        return true;
    }
    return false;
}
checkForm.lessThan=function(objValue, strLessThan){
    var ret = true;
    var obj_value = objValue.replace(/\,/g, "");
    strLessThan = strLessThan.replace(/\,/g, "");

    if (isNaN(obj_value)){
        ret = false;
    }else if (eval(obj_value) >= eval(strLessThan))ret = false;
    return ret;
}

checkForm.greaterThan=function(objValue, strGreaterThan){
    var ret = true;
    var obj_value = objValue.replace(/\,/g, "");
    strGreaterThan = strGreaterThan.replace(/\,/g, "");

    if (isNaN(obj_value)){
        ret = false;
    }else if (eval(obj_value) <= eval(strGreaterThan))ret = false;
    return ret;
}

checkForm.regExp=function(objValue, strRegExp){
    var ret = true;
    if (objValue.length > 0 && !objValue.match(strRegExp))ret = false;
    return ret;
}

checkForm.dontSelect=function(objValue, dont_sel_value){
    var ret = true;
    if (objValue == null){
         ret = false;
    }else if (objValue == dont_sel_value)ret = false;
    return ret;
}
checkForm.dontSelectChk=function(objValue, chkValue){
    var pass = true;
    pass = IsCheckSelected(objValue, chkValue) ? false : true;
    return pass;
}

checkForm.shouldSelectChk=function(objValue, chkValue){
    var pass = true;

    pass = IsCheckSelected(objValue, chkValue) ? true : false;
    return pass;
}
checkForm.selMin=function(objValue, strMinSel){
    var bret = true;
    var objcheck = objValue.form.elements[objValue.name];
    var chkcount = 0;
    if (objcheck.length){
        for (var c = 0; c < objcheck.length; c++)
            if (objcheck[c].checked == "1")chkcount++;
    }else{
        chkcount = (objcheck.checked == "1") ? 1 : 0;
    }
    var minsel = eval(strMinSel);
    if (chkcount < minsel)bret = false;
    return bret;
}

checkForm.selMax=function(objValue, strMaxSel){
    var bret = true;
    var objcheck = objValue.form.elements[objValue.name];
    var chkcount = 0;
    if (objcheck.length){
        for (var c = 0; c < objcheck.length; c++)
        	if (objcheck[c].checked == "1")chkcount++;
    }else{
        chkcount = (objcheck.checked == "1") ? 1 : 0;
    }
    var maxsel = eval(strMaxSel);
    if (chkcount > maxsel)bret = false;
    return bret;
}
checkForm.selectOneRadio=function(objValue){
    var objradio = objValue.form.elements[objValue.name];
    var one_selected = false;
    for (var r = 0; r < objradio.length; r++){
        if (objradio[r].checked == "1"){
            one_selected = true;
            break;
        }
    }
    return one_selected;
}

checkForm.selectRadio=function(objValue, cmdvalue, testselect){
    var objradio = objValue.form.elements[objValue.name];
    var selected = false;

    for (var r = 0; r < objradio.length; r++){
        if (objradio[r].value == cmdvalue && objradio[r].checked == "1"){
            selected = true;
            break;
        }
    }
    if (testselect == true && false == selected || testselect == false && true == selected)return false;
    return true;
}
checkForm.comparison=function(obj, strCompareElement, strvalidator){
	objValue=obj.value
    var bRet = true;
    var objCompare = null;
    objCompare = obj.form.elements[strCompareElement];
    if (!objCompare)return false;

    var objval_value = objValue.replace(/( |-|:)/g,"");
    var objcomp_value = objCompare.value.replace(/( |-|:)/g,"");

    if (strvalidator != "eqelmnt" && strvalidator != "neelmnt"){
        objval_value = objval_value.replace(/\,/g, "");
        objcomp_value = objcomp_value.replace(/\,/g, "");

        if (isNaN(objval_value))return false;
        if (isNaN(objcomp_value))return false;
    }
    var cmpstr = "";
    switch (strvalidator){
	    case "eqelmnt":
		    if (objval_value != objcomp_value)bRet = false;
		    break;
	    case "ltelmnt":
		    if (eval(objval_value) >= eval(objcomp_value))bRet = false;
		    break;
	    case "leelmnt":
		    if (eval(objval_value) > eval(objcomp_value))bRet = false;
		    break;
	    case "gtelmnt":
		    if (eval(objval_value) <= eval(objcomp_value))bRet = false;
		    break;
	    case "geelmnt":
		    if (eval(objval_value) < eval(objcomp_value))bRet = false;
		    break;
	    case "neelmnt":
		    if (objval_value.length > 0 && objcomp_value.length > 0 && objval_value == objcomp_value)bRet = false;
		    break;
    }
    return bRet;
}
checkForm.fileExtension=function(objValue, cmdvalue){
    var ret = false;
    var found = false;

    if (objValue.length <= 0)return true;

    var extns = cmdvalue.split(";");
    for (var i = 0; i < extns.length; i++){
        ext = objValue.substr(objValue.length - extns[i].length, extns[i].length);
        ext = ext.toLowerCase();
        if (ext == extns[i]){
            found = true;
            break;
        }
    }
    return found;
}


//sous fonctions
checkForm.trim=function(strIn){return strIn.replace(/^\s\s*/, '').replace(/\s\s*$/, '');}
checkForm.isEmpty=function(value){return checkForm.trim(value).length == 0}
checkForm.isCheckSelected=function(objValue, chkValue){
    var selected = false;
    var objcheck = objValue.form.elements[objValue.name];
    if (objcheck.length){
        var idxchk = -1;
        for (var c = 0; c < objcheck.length; c++){
            if (objcheck[c].value == chkValue){
                idxchk = c;
                break;
            }
        }
        if (idxchk >= 0)
            if (objcheck[idxchk].checked == "1")selected = true;
    }else{
        if (objValue.checked == "1")selected = true;
    }
    return selected;
}

