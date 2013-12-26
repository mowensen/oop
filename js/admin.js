cms.AdminInit=function(){
	cms.HidElm("Users")
	cms.HidElm("UsrDiv")
	cms.HidElm("DrgBckFrm")
	cms.HidElm("AddBckFrm")
	cms.HidElm("EdtDiv")
	cms.HidElm("FldFrm")
	cms.HidElm("RlsFrm")
	cms.HidElm("RlsPrg")
	cms.HidElm("LblDiv")
	cms.HidElm("MnuDiv")
	cms.HidElm("SchFrm")
	cms.HidElm("PrpFrm")
	cms.HidElm("RecDiv")
	cms.HidElm("RlsMulFrm")
	cms.HidElm("Trace")
}
cms.Elm=function(cmsId)			{ return document.getElementById("cms"+cmsId)	}
cms.HidElm=function(cmsId)		{ cms.Elm(cmsId).style.display="none"		}
cms.ShwElm=function(cmsId)		{ cms.Elm(cmsId).style.display="block"		}
cms.SetHtml=function(cmsId,html)	{ cms.Elm(cmsId).innerHTML=html			}
cms.ChkElm=function(cmsId)		{ cms.Elm(cmsId).checked=true			}
