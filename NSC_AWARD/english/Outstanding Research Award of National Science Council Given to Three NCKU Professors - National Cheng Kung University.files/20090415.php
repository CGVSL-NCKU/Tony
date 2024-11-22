
var Drag = {
obj : null,
init : function(o, oRoot, minX, maxX, minY, maxY, bSwapHorzRef, bSwapVertRef, fXMapper, fYMapper)
{
o.onmousedown	= Drag.start;
o.hmode			= bSwapHorzRef ? false : true ;
o.vmode			= bSwapVertRef ? false : true ;
o.root = oRoot && oRoot != null ? oRoot : o ;
if (o.hmode  && isNaN(parseInt(o.root.style.left  ))) o.root.style.left   = "0px";
if (o.vmode  && isNaN(parseInt(o.root.style.top   ))) o.root.style.top    = "0px";
if (!o.hmode && isNaN(parseInt(o.root.style.right ))) o.root.style.right  = "0px";
if (!o.vmode && isNaN(parseInt(o.root.style.bottom))) o.root.style.bottom = "0px";
o.minX	= typeof minX != 'undefined' ? minX : null;
o.minY	= typeof minY != 'undefined' ? minY : null;
o.maxX	= typeof maxX != 'undefined' ? maxX : null;
o.maxY	= typeof maxY != 'undefined' ? maxY : null;
o.xMapper = fXMapper ? fXMapper : null;
o.yMapper = fYMapper ? fYMapper : null;
o.root.onDragStart	= new Function();
o.root.onDragEnd	= new Function();
o.root.onDrag		= new Function();
},
start : function(e)
{
var o = Drag.obj = this;
e = Drag.fixE(e);
var y = parseInt(o.vmode ? o.root.style.top  : o.root.style.bottom);
var x = parseInt(o.hmode ? o.root.style.left : o.root.style.right );
o.root.onDragStart(x, y);
o.lastMouseX	= e.clientX;
o.lastMouseY	= e.clientY;
if (o.hmode) {
if (o.minX != null)	o.minMouseX	= e.clientX - x + o.minX;
if (o.maxX != null)	o.maxMouseX	= o.minMouseX + o.maxX - o.minX;
} else {
if (o.minX != null) o.maxMouseX = -o.minX + e.clientX + x;
if (o.maxX != null) o.minMouseX = -o.maxX + e.clientX + x;
}
if (o.vmode) {
if (o.minY != null)	o.minMouseY	= e.clientY - y + o.minY;
if (o.maxY != null)	o.maxMouseY	= o.minMouseY + o.maxY - o.minY;
} else {
if (o.minY != null) o.maxMouseY = -o.minY + e.clientY + y;
if (o.maxY != null) o.minMouseY = -o.maxY + e.clientY + y;
}
document.onmousemove	= Drag.drag;
document.onmouseup		= Drag.end;
return false;
},
drag : function(e)
{
e = Drag.fixE(e);
var o = Drag.obj;
var ey	= e.clientY;
var ex	= e.clientX;
var y = parseInt(o.vmode ? o.root.style.top  : o.root.style.bottom);
var x = parseInt(o.hmode ? o.root.style.left : o.root.style.right );
var nx, ny;
if (o.minX != null) ex = o.hmode ? Math.max(ex, o.minMouseX) : Math.min(ex, o.maxMouseX);
if (o.maxX != null) ex = o.hmode ? Math.min(ex, o.maxMouseX) : Math.max(ex, o.minMouseX);
if (o.minY != null) ey = o.vmode ? Math.max(ey, o.minMouseY) : Math.min(ey, o.maxMouseY);
if (o.maxY != null) ey = o.vmode ? Math.min(ey, o.maxMouseY) : Math.max(ey, o.minMouseY);
nx = x + ((ex - o.lastMouseX) * (o.hmode ? 1 : -1));
ny = y + ((ey - o.lastMouseY) * (o.vmode ? 1 : -1));
if (o.xMapper)		nx = o.xMapper(y)
else if (o.yMapper)	ny = o.yMapper(x)
Drag.obj.root.style[o.hmode ? "left" : "right"] = nx + "px";
Drag.obj.root.style[o.vmode ? "top" : "bottom"] = ny + "px";
Drag.obj.lastMouseX	= ex;
Drag.obj.lastMouseY	= ey;
Drag.obj.root.onDrag(nx, ny);
return false;
},
end : function()
{
document.onmousemove = null;
document.onmouseup   = null;
Drag.obj.root.onDragEnd(	parseInt(Drag.obj.root.style[Drag.obj.hmode ? "left" : "right"]),
parseInt(Drag.obj.root.style[Drag.obj.vmode ? "top" : "bottom"]));
Drag.obj = null;
},
fixE : function(e)
{
if (typeof e == 'undefined') e = window.event;
if (typeof e.layerX == 'undefined') e.layerX = e.offsetX;
if (typeof e.layerY == 'undefined') e.layerY = e.offsetY;
return e;
}
};
function divOsClass(name) {
this.name = name;
this.Cookie = new Cookie();
this.sajaxIO = new sajaxIO();
this.setDebug(0);
this.tagTitleVisibleStyle = "active";
this.tagTitleInVisibleStyle = "inactive";
this.tagContentVisibleStyle = "showThis";
this.tagContentInVisibleStyle = "hideThis";
this.waitWord = "Please waiting...";
this.contentDivPrefix = "C";
this.TagDivPrefix = "T";
this.ResizeFix = "0";
this.tagSeq = 10;
this.popSeq = 10;
this.act="";
this.imagedir = "images";
this.styledir = "style";
this.hashTable = new hashUtil();
this.tagUrl = new hashUtil();
this.hashTable.put("home",0);
this.TagName = "C0";
this.popHandle = new hashUtil();
this.closeTagHtml = "<div onclick='"+this.name+".selectTag(===TAG===)'><div><a href=\"javascript:void(0)\" class=\"close\" title=\"\" onclick='"+this.name+".delTag(===TAG===);event.cancelBubble=true'><img src=\""+this.imagedir+"/clear.gif\" alt=\"close\" /></a><a href=\"javascript:void(0)\"><img src=\"===ICON===\" align=\"absmiddle\" alt=\"\" />&nbsp;===TITLE===</a></div></div>	";
this.Info = new Array();
this.getBrowser();
this.position="";
return this;
}
var tempHideDiv="";
divOsClass.prototype.AlertSuccess= 100;
divOsClass.prototype.AlertFailed = 101;
divOsClass.prototype.AlertWait= 102;
divOsClass.prototype.AlertNote= 103;
divOsClass.prototype.setInfo = function(p_word,p_info) {
eval("this."+p_word+"='"+p_info+"'");
}
divOsClass.prototype.refreshCloseTagHtml = function() {
this.closeTagHtml = "<div onclick='"+this.name+".selectTag(===TAG===)'><div><a href=\"javascript:void(0)\" class=\"close\" title=\"\" onclick='"+this.name+".delTag(===TAG===);event.cancelBubble=true'><img src=\""+this.imagedir+"/clear.gif\" alt=\"close\" /></a><a href=\"javascript:void(0)\"><img src=\"===ICON===\" align=\"absmiddle\" alt=\"\" />&nbsp;===TITLE===</a></div></div>	";
}
divOsClass.prototype.getBrowser = function() {
var navi = window.navigator.userAgent.toUpperCase();
if (navi.indexOf("MSIE")>=1) this.browser = "IE";
else if (navi.indexOf("FIREFOX")>=1) this.browser = "FF";
return this.browser;
}
divOsClass.prototype.setTagDiv = function(tagname) {
this.tagDivName = tagname;
this.tagDiv = document.getElementById(tagname);
}
divOsClass.prototype.setContentDiv = function(main) {
this.contentDivName = main;
this.contentDiv = document.getElementById(main);
}
divOsClass.prototype.setDebug = function(p_debug) {
if(p_debug)
this._Debug = true;
else
this._Debug = false;
}
divOsClass.prototype.setTagCloseHtml = function(p_str) {
this.closeTagHtml = p_str;
}
divOsClass.prototype.virtualOpen = function(p_url,p_name,p_target,p_icon,p_setcookie) {
var tagSeq;
if(p_name=='undefined' || p_name=='') return;
tagSeq = this.addTag(p_name,p_icon);
this.hashTable.put(p_target,tagSeq) ;
this.tagUrl.put(tagSeq,p_url) ;
if(typeof(p_setcookie)!="undefined" && p_setcookie) {
var u=p_url.split("?");
this.Cookie.setCookie("_epage_tags",this.Cookie.getCookie("_epage_tags")+";;"+p_target+"=="+p_name+"=="+u[1]+"=="+p_icon);
}
}
divOsClass.prototype.open = function(p_url,p_name,p_target,p_icon) {
var tagSeq;
if(p_target=='_blank' )  {
tagSeq = this.addTag(p_name,p_icon);
}
else {
tagSeq = this.hashTable.get(p_target);
if(!this.hasTag(tagSeq) || tagSeq==null) {
tagSeq = this.addTag(p_name,p_icon);
var u=p_url.split("?");
this.Cookie.setCookie("_epage_tags",this.Cookie.getCookie("_epage_tags")+";;"+p_target+"=="+p_name+"=="+u[1]+"=="+p_icon);
}
else {
this.tagUrl.remove(tagSeq);
this.selectTag(tagSeq);
}
}
this.hashTable.put(p_target,tagSeq) ;
var contDiv = this.contentDivPrefix+tagSeq;
this.openUrl(contDiv,p_url);
}
divOsClass.prototype.openUrl = function(p_div,p_url,p_silent) {
if(p_url.indexOf("?")<0) p_url+="?";
else p_url+="&";
p_url+="TagName="+p_div;
this.genDivContentCallBackFunction(p_div);
this.sajaxIO.sajaxSubmit('','',this.name+".setdivContent"+p_div,'_displayProgram',p_url);
if (!p_silent) divOs.openWaitingWindow(divOs.waitWord,"sending");
}
divOsClass.prototype.openSajaxUrl = function(p_div,p_url) {
if(p_url.indexOf("?")<0) p_url+="?";
else p_url+="&";
p_url+="TagName="+p_div;
p_url+="&DivId="+p_div;
this.sajaxIO.sajaxSubmit('','',divOsClass.prototype.DivSajaxCallBack,'sajaxSubmit',p_url);
}
divOsClass.prototype.openSubmitForm = function(p_title,p_url,p_form,p_param,p_style,p_event) {
divid="tmp"+this.popSeq;
this.openPopWindow(p_title,p_style,'',divid,p_event) ;
this.submitForm('ppcont'+divid,p_url,p_form,p_param,'sajaxSubmit');
}
divOsClass.prototype.submitForm = function() {
a=divOsClass.prototype.submitForm.arguments;
argDiv = a[0];
argUrl = a[1];
if(a[2]) argForm = a[2];
else argForm = "";
if(a[3]) argParam = a[3];
else argParam = "";
if(a[4]) argFun = a[4];
else argFun = "sajaxSubmit";
if(argUrl.indexOf("?")<0) argUrl+="?";
else argUrl+="&";
argUrl+="TagName="+argDiv;
this.genDivSubmitCallBackFunction(argDiv);
this.sajaxIO.sajaxSubmit(argParam,argForm,this.name+".setDivSubmitContent"+argDiv,argFun,argUrl);
this.Cookie.setCookie(argDiv,argUrl);
}
divOsClass.prototype.DivSajaxCallBack= function(z) {
var divOs_obj = eval("divOs");
if(divOs_obj._Debug)  alert(z);
Res= sajaxIO.prototype.getMsg(z);
if(Res.DivId) $("#"+Res.DivId).html(Res.Content);
if(Res.JsFunction) eval(Res.JsFunction);
}
divOsClass.prototype.getDivUrl = function(p_div) {
var p =  this.Cookie.getCookie(p_div);
return p;
}
divOsClass.prototype.genDivContentCallBackFunction = function(p_div) {
var str = "divOsClass.prototype.setdivContent"+p_div+" = function(z) {\n";
if(this._Debug) str+=" alert(z);\n";
str+=	"var obj = document.getElementById('"+p_div+"');\n";
str+=	"divOsClass.prototype.setInnerHTML(obj, z);\n"+
"divOs.closeWaitingWindow('sending');}";
if(this._Debug) alert(str);
eval(str);
}
divOsClass.prototype.genDivSubmitCallBackFunction = function(p_div) {
var str = "divOsClass.prototype.setDivSubmitContent"+p_div+" = function(z) {\n";
if(this._Debug) str+=" alert(z);\n";
str +=	 "var ret = sajaxIO.prototype.getMsg(z);\n"+
"divOsClass.prototype.setInnerHTML(document.getElementById('"+p_div+"'),ret.Content);\n"+
"if(ret.JsFunction!='') eval(ret.JsFunction);\n"+
"return ret.Content;\n"+
"}\n";
if(this._Debug) alert(str);
eval(str);
}
divOsClass.prototype.setInnerHTML = function (el, htmlCode) {
if(el==null) return false;
$(el).html(htmlCode);
return;
}
divOsClass.prototype.createDiv = function(container,id,className) {
if(typeof(container)=="string") containerObj = document.getElementById(container);
else containerObj = container;
var newDiv=document.createElement("DIV");
containerObj.appendChild(newDiv);
newDiv.id=id;
newDiv.className=className;
}
divOsClass.prototype.delDiv = function(container,id) {
if(typeof(container)=="string") containerObj = document.getElementById(container);
else containerObj = container;
var child = document.getElementById(id);
if(child==null) return false;
containerObj.removeChild(child);
}
divOsClass.prototype.handleIEhasLayout=function(){
document.body.style.zoom = '1.1';
document.body.style.zoom = '';
}
divOsClass.prototype.checkTagDivReady = function() {
if(typeof(this.tagDiv)!='undefined')  return true;
return false;
}
divOsClass.prototype.addTag = function(p_name,p_icon) {
var tagDivName = this.TagDivPrefix+this.tagSeq;
var newLi=document.createElement("li");
newLi.id= tagDivName;
newLi.className = this.tagTitleVisibleStyle ;
var res = this.closeTagHtml.replace(/===TITLE===/,p_name);
var icon = this.styledir+"/backstyle/back_skin_grey/icon/6.png";
if (typeof p_icon != 'undefined' && trim(p_icon)!='' && p_icon!='undefined') {
icon = p_icon;
}
res = res.replace(/===ICON===/,icon);
var str = " ";
while(res!=str) {
str = res;
res = str.replace(/===TAG===/,this.tagSeq);
}
str = res;
newLi.innerHTML = str;
try{
this.tagDiv.appendChild(newLi);
var contDivName = this.contentDivPrefix+this.tagSeq;
this.createDiv(this.contentDiv,contDivName,this.tagContentVisibleStyle);
this.selectTag(this.tagSeq);
}catch(e) {
if(this._Debug) alert(" addTag Exception \n");
this.tagDiv = document.getElementById(this.tagDivName);
}
return this.tagSeq++;
}
divOsClass.prototype.isEmptyDiv = function(p_div) {
var tdiv = document.getElementById(p_div);
if(tdiv==null) return false;
if(tdiv.innerHTML.trim()) return false;
return true;
}
divOsClass.prototype.hasTag = function(p_seq) {
var tdiv = document.getElementById(this.TagDivPrefix+p_seq);
if(tdiv==null) return false;
return true;
}
divOsClass.prototype.delTag = function(p_seq) {
this.delDiv(this.tagDiv,this.TagDivPrefix+p_seq);
this.delDiv(this.contentDiv,this.contentDivPrefix+p_seq);
var key = this.hashTable.getKey(p_seq);
var newcookie = "";
var tags = this.Cookie.getCookie("_epage_tags").split(";;");
var tag;
for(var i=0;i<tags.length;i++) {
if(tags[i]=='undefine') continue;
if(tags[i]=='') continue;
tag = tags[i].split("==");
if(tag[0]==key) continue;
newcookie+=tag[0]+"=="+tag[1]+"=="+tag[2]+"=="+tag[3]+";;";
}
this.Cookie.setCookie("_epage_tags",newcookie);
this.hashTable.remove(key);
seq = this.hashTable.getFirst();
this.selectTag(seq);
try{
resize(this.contentDivPrefix+seq);
}catch(e){}
}
divOsClass.prototype.selectTag = function(p_seq) {
var cDiv;
for(var i=0;i<this.tagSeq;i++) {
cDiv = document.getElementById(this.contentDivPrefix+i);
tDiv = document.getElementById(this.TagDivPrefix+i);
if(cDiv==null) continue;
if(i==p_seq) {
cDiv.className=this.tagContentVisibleStyle;
tDiv.className=this.tagTitleVisibleStyle;
}
else {
cDiv.className=this.tagContentInVisibleStyle;
tDiv.className=this.tagTitleInVisibleStyle;
}
}
this.TagName="C"+p_seq;
resize(this.TagName);
var key = this.hashTable.getKey(p_seq);
var url = this.tagUrl.get(p_seq);
if(key!=null)
this.hashTable.mvToFirst(key);
if(url) {
this.openUrl(this.contentDivPrefix+p_seq,url);
this.tagUrl.remove(p_seq);
}
this.handleIEhasLayout();
}
divOsClass.prototype.getScrollTop = function() {
if (typeof document.compatMode != 'undefined' && document.compatMode != 'BackCompat') {
scrollPos = document.documentElement.scrollTop;
}
else if (typeof document.body != 'undefined') {
scrollPos = document.body.scrollTop;
}
return scrollPos;
}
divOsClass.prototype.clearQuote = function(text){
if (typeof( text ) == "undefined") return text;
if (typeof( text ) != "string") text = text.toString() ;
text = text.replace(/"/g, "&quot;") ;
text = text.replace(/'/g, "&#39;") ;
return text ;
}
divOsClass.prototype.openPopUrl = function(p_title,p_style,p_url,p_div,p_event) {
var divid = "urlPop"+this.popSeq;
this.openPopWindow(p_title,p_style,"<div id=\""+divid+"\">loading...</div>",p_div);
this.openUrl(divid,p_url);
return false;
}
divOsClass.prototype.openPopSajaxUrl = function(p_title,p_style,p_url,p_div,p_event) {
var divid = "urlPop"+this.popSeq;
this.openPopWindow(p_title,p_style,"<div id=\""+divid+"\">loading...</div>",p_div,p_event);
this.openSajaxUrl(divid,p_url);
return false;
}
divOsClass.prototype.openAlertWindow = function(p_type,p_msg,p_style,p_div,p_button,p_title) {
var divClass, icon,cont2="";
icon ="wait.gif";
if(!p_style) p_style="width:350;height:110;Button:ok;top:180";
if(p_type==divOsClass.prototype.AlertSuccess) icon ="success.png";
if(p_type==divOsClass.prototype.AlertWait) icon = "wait.gif";
if(p_type==divOsClass.prototype.AlertFailed) icon ="failure.png";
var cont;// = document.getElementById("ppcont"+seq);
cont =	 "<div><table width=\"100%\" cellpadding=\"5\"><tr><td width=\"1%\" nowrap=\"nowrap\"><img src=\""+this.imagedir+"/"+icon+"\" width=\"34\" height=\"35\" align=\"absmiddle\" /></td><td align=\"left\" valign=\"top\">"+p_msg+"</td></tr></table></div>\n";
cont += "<div style=\"text-align:center\">\n";
if (p_button && p_button!="NoButton"){
while (p_button.getFirstKey()){
var BtnKey = p_button.getFirstKey();
if(BtnKey=='_Ok')
cont+="<input type='button' name='_Ok' value='  OK  ' onclick=\"javascript:"+this.clearQuote(p_button.get(BtnKey))+";"+this.name+".closePopWindow("+this.popSeq+");\" />\n";
if(BtnKey=='_Cancel')
cont+="<input type='button' name='_Cancel' value='Cancel' onclick=\"javascript:"+this.clearQuote(p_button.get(BtnKey))+";"+this.name+".closePopWindow("+this.popSeq+");\" />\n";
if(BtnKey=='_Js')
cont2="<script language='javascript' defer>"+(p_button.get(BtnKey))+";</script>\n";
if (BtnKey!="_Ok" && BtnKey!="_Cancel" && BtnKey!="_Js"){
var BtnVal = p_button.get(BtnKey).split("=o=");
cont+=" <input type='button' name='"+BtnKey+"' value='"+BtnVal[0]+"' onclick=\"javascript:"+this.clearQuote(BtnVal[1])+";"+this.name+".closePopWindow("+this.popSeq+");\" />\n";
}
p_button.remove(BtnKey);
}
}
else if(p_button!="NoButton") cont+="<input type='button' name='_Close' value=' Close ' onclick=\"javascript:"+this.name+".closePopWindow("+this.popSeq+");\" />\n";
cont += "</div>";
cont += cont2;
if(!p_title) p_title = window.location.host;
var seq = this.openPopWindow(p_title,p_style,cont,p_div);
return seq;
}
divOsClass.prototype.closeWaitingWindow = function(p_id) {
if (!p_id) p_id="waitingId";
try {
var obj = document.getElementById(p_id);
obj.parentNode.removeChild(obj);
}catch(e){}
}
divOsClass.prototype.openWaitingWindow = function(p_cont,p_id) {
if (!p_id) p_id="waitingId";
var newDiv=document.createElement("DIV");
newDiv.id = p_id;
document.body.appendChild(newDiv);
str="<style>body,td,th,img,a,* {cursor:normal;}</style><div class=\"alertmsg\"><div class=\"alertmsg-inner2\"><div class=\"alertmsg-inner\"><a href=\"javascript:void(0)\" onclick=\"divOs.closeWaitingWindow('"+p_id+"');\" class=\"close\">Close</a><p><img class=\"loadingimg\" src=\""+this.imagedir+"/loading.gif\" align=\"absmiddle\" width=\"16\" height=\"16\" border=\"0\" />"+p_cont+"</p></div></div></div>";
this.setInnerHTML(newDiv,str);
}
divOsClass.prototype.changePopTitle = function(p_divid,p_title) {
try{
var headDiv = document.getElementById(p_divid+"__overPopTitle");
divOsClass.prototype.setInnerHTML(headDiv,"<h4>"+strUtil.prototype.htmlspecialchars(decodeURIComponent(p_title))+"</h4>");
}catch(e){}
}
divOsClass.prototype.openPopWindow = function(p_title,p_style,p_cont,p_div,event) {
if(p_div!="") {
var checkObj=document.getElementById(p_div);
if(checkObj) return false;
}
var divWidth="100%",divHeight=300,divTop=70,divLeft=0,divStatic=1,divSpeed=0;
var Maximize = false;
var Close = true;
var ret ;
var divNavigate=false;
var divFoot=false;
var Nav = "";
if(typeof p_style!="undefined") {
ret = p_style.split(";");
for(var i=0;i<ret.length;i++) {
var tmp;
if(ret[i].indexOf('=')!=-1) tmp = ret[i].split("=");
if(ret[i].indexOf(':')!=-1) tmp = ret[i].split(":");
if(tmp[0]=="width") divWidth = tmp[1];
else if(tmp[0]=="height") divHeight = tmp[1];
else if(tmp[0]=="top") divTop = tmp[1];
else if(tmp[0]=="left") divLeft = tmp[1];
else if(tmp[0]=="Maximize") {
if(tmp[1]==1) Maximize=true;
else Maximize=false;
}
else if(tmp[0]=="Close") {
if(tmp[1]==1) Close=true;
else Close=false;
}
else if(tmp[0]=="Static") {
if(tmp[1]==1) divStatic=true;
else divStatic=false;
}
else if(tmp[0]=="Nav") {
Nav = tmp[1];
Nav.substr(0,1)
if(Nav.substr(0,1)=="1") divNavigate=1;
else if(Nav.substr(0,1)=="2") divNavigate=2;
else divNavigate=0;
if(Nav.substr(1,1)=="1") divFoot=1;
else divFoot=0;
}
}
}
divTop=0;
AbsDivTop=parseInt(divTop);
divTop=parseInt(parseInt(divTop)+parseInt(divOsClass.prototype.getScrollTop()));//add the mouse scroll distance
var ppcontId = "";
var newDiv=document.createElement("DIV");
document.body.appendChild(newDiv);
if(typeof p_div=="undefined" ||  p_div=="") {
newDiv.id="popDiv"+this.popSeq;
ppcontId = "ppcont"+this.popSeq;
p_div=newDiv.id;
}
else{
ppcontId ="ppcont"+p_div;
newDiv.id=p_div;
}
newDiv.className="pop-float";
if(event){
newDiv.style.width="100px";
}
var str="<script language=\"javascript\">\n"+
"var popMaxWidth =document.body.offsetWidth-80;\n"+
"var popMaxHeight =document.body.offsetHeight-100;\n";
if(event){
str +="var h=document.getElementById(\""+newDiv.id+"\");\n"+
"var o=document.getElementById(\""+newDiv.id+"__overPopTitle\");\n"+
"Drag.init(o,h,0,popMaxWidth,0,popMaxHeight);\n";
}else{
str +="var h=document.getElementById(\""+newDiv.id+"__movePopOut\");\n"+
"var o=document.getElementById(\""+newDiv.id+"__overPopTitle\");\n"+
"Drag.init(o,h,0,popMaxWidth,0,popMaxHeight);\n";
}
if(!event)
str +="__InitPopDiv(o,h);\n";
str +="h.onDragEnd = function (x, y) {RecP(x,y,h);}\n"+
"function RecP(x,y,h){\n"+
"if(divOs.position==\"back\"){\n"+
"if(x){Cookie.prototype.setCookie(\"__movePopOut_back_x\",x);}\n"+
"if(y){Cookie.prototype.setCookie(\"__movePopOut_back_y\",y);}\n"+
"}else{\n"+
"if(x){Cookie.prototype.setCookie(\"__movePopOut_front_x\",x);}\n"+
"if(y){Cookie.prototype.setCookie(\"__movePopOut_front_y\",y);}\n"+
"}\n"+
"}\n"+
"function __InitPopDiv(o,h)\n"+
"{h.style.position=\"absolute\";\n"+
"o.style.cursor=\"move\";\n"+
"if(divOs.position==\"back\"){\n"+
"var a=Cookie.prototype.getCookie(\"__movePopOut_back_x\");\n"+
"var b=Cookie.prototype.getCookie(\"__movePopOut_back_y\");\n"+
"}else{\n"+
"var a=Cookie.prototype.getCookie(\"__movePopOut_front_x\");\n"+
"var b=80;\n"+
"}\n"+
"if(a==null || a==\"\"){\n"+
"var tmpWidth=parseInt(\""+divWidth+"\");\n"+
"if(tmpWidth==\""+divWidth+"\"){\n"+
"a=(popMaxWidth/2)-(tmpWidth/2);\n"+
"}else a=popMaxWidth*(100-tmpWidth)/200;\n"+
"b=30;\n"+
"}\n"+
"if (a!=null&&a!=\"\"){ a=parseInt(a)+\"px\";\n h.style.left=a; }\n"+
"if (b!=null&&b!=\"\"){ b=parseInt(b)+\"px\";\n h.style.top=b; }\n"+
"}\n"+
"</script>\n";
str += "<div id=\""+newDiv.id+"__movePopOut\" class=\"pop-outer\">";
if(event){
str += "<div  class=\"pop-container pop-shadow\">\n"+
"<div id=\"fullDiv"+this.popSeq+"\" class=\"pop-module pop-overlay\">\n"+
"<div class=\"pop-head\">\n";
}
else{
str += "<div  class=\"pop-container pop-shadow\">\n"+
"<div id=\"fullDiv"+this.popSeq+"\" class=\"pop-module pop-overlay\">\n"+
"<div class=\"pop-head\">\n";
}
str+="<div id=\""+newDiv.id+"__overPopTitle\" class=\"pop-head-inner\"><h4>"+strUtil.prototype.htmlspecialchars(decodeURIComponent(p_title))+"</h4></div>\n";
if(Close) {
str+= "<div class=\"pop-close\" onclick=\"javascript:"+this.name+".closePopWindow("+this.popSeq+");\"></div>\n";
}
str+="</div>\n";
var navigateHeight=0;
if(divNavigate==1) {
str+="<div class=\"pop-navigate\" style=\"height:61px\"><div class=\"pop-navigate-inner\"></div></div>\n";
navigateHeight=61;
}
else if(divNavigate==2) {
str+="<div class=\"pop-navigate\" style=\"height:36px\"><div class=\"pop-navigate-inner\"></div></div>\n";
navigateHeight=36;
}
if(divFoot) navigateHeight+=43;
if(typeof(p_cont)=='object') {
str+=	"<div id=\""+ppcontId+"\" class=\"pop-body\"><div class=\"pop-body-inner\">"+p_cont.innerHTML+"\n";
}
else
str+=	"<div id=\""+ppcontId+"\" class=\"pop-body\"><div class=\"pop-body-inner\">"+p_cont+"\n";
str+= "</div></div>\n";
str+= "</div>\n";
str+= "<div id=\"underLay"+this.popSeq+"\" class=\"underlay\"></div>\n";
str+= "</div></div>\n";
this.setInnerHTML(newDiv,str);
var fullDiv = document.getElementById("fullDiv"+this.popSeq);
var ppcontent = document.getElementById(ppcontId);
var underLay = document.getElementById("underLay"+this.popSeq);
if(divTop) {
newDiv.style.top = divTop+"px";
}
if(divLeft) {
newDiv.style.left = divLeft+"px";
}
if(event){
var chkTopSide=false;
var chkLeftSide=false;
var bodywidth = document.body.offsetWidth;
var bodyheight ;
if(document.documentElement)
bodyheight = document.documentElement.clientHeight;
else
bodyheight = document.body.offsetHeight;
var chktop =parseInt(event.clientY) + parseInt(divHeight);
var chkleft=parseInt(event.clientX) + parseInt(divWidth);
if(chktop > bodyheight){
chkTopSide=true;
newtop=parseInt(event.clientY) - parseInt(divHeight);
if(newtop<0) newtop=10;
}
else newtop=event.clientY;
newDiv.style.top = newtop+"px";
if(chkleft > bodywidth){
chkLeftSide=true;
newleft=parseInt(event.clientX)-parseInt(divWidth)-215;
if(newleft<0) newleft=10;
}else newleft=event.clientX-215;
newDiv.style.left = newleft+"px";
divWidthSm =20;
divHeightSm =20;
divSpeed =10;
}
if(divWidth) {
if(divWidth.indexOf("%")!=-1) {
fullDiv.parentNode.parentNode.style.width = divWidth;
if (this.browser=="FF") ppcontent.style.width="100%";
}
else {
fullDiv.parentNode.parentNode.style.width=divWidth+"px";
if (this.browser=="FF") ppcontent.style.width="100%";//divWidth+"px";
}
}
if(divHeight) {
if(divHeight.indexOf("%")!=-1) {
var myOffset = 0;
if (this.browser=="FF") myOffset = 0;
newDivHeight=(document.documentElement.clientHeight-myOffset)*divHeight.slice(0,-1)/100;
if(newDivHeight>(divTop+myOffset)) newDivHeight = newDivHeight-AbsDivTop-myOffset;
if(newDivHeight<40) newDivHeight=40;
fullDiv.style.height=newDivHeight+"px";
h = newDivHeight+3;
$(newDiv).children("DIV").css("height",h);
ppcontent.style.height=(newDivHeight-27-navigateHeight)+"px";
underLay.style.height=(newDivHeight+2)+"px";
}
else{
divHeight = parseInt(divHeight);
if(divHeight<40) divHeight=40;
fullDiv.style.height=divHeight+"px";
h = divHeight+3;
$(newDiv).children("DIV").css("height",h);
ppcontent.style.height=(parseInt(divHeight)-27-navigateHeight)+"px";
underLay.style.height =(parseInt(divHeight)+2)+"px";
}
}
if(divWidth && divHeight && divSpeed){
$(newDiv).children("DIV").css("width",divWidthSm+"px");
$(newDiv).children("DIV").css("height",divHeightSm+"px");
newDiv.style.left = event.clientX+"px";
newDiv.style.top = (event.clientY+parseInt(divOsClass.prototype.getScrollTop()))+"px";
widthSpeed = (divWidth-divWidthSm)/10;
heightSpeed = (divHeight-divHeightSm)/10;
if(widthSpeed<10) widthSpeedperH=10;
if(heightSpeed<10) heightSpeed=10;
divOsClass.prototype.ActiveDiv(p_div,chkTopSide,chkLeftSide,divWidth,divHeight,widthSpeed,heightSpeed,divSpeed);
$(p_div).children("DIV").css("overflow","hidden");;
}
if(true) {
var floatDiv=document.createElement("DIV");
document.body.appendChild(floatDiv);
floatDiv.id="floatDiv"+this.popSeq;
floatDiv.className="floatDiv_1";
floatDiv.style.height=document.documentElement.scrollHeight;
var clientHeight = document.documentElement.clientHeight;
floatDiv.innerHTML="<iframe class=\"superDiv\" height=\""+clientHeight+"\" width=\"100%\" border=\"0\" frameborder=\"0\" src=\"about:blank\"></iframe>";
}
this.popHandle.put(this.popSeq,newDiv.id) ;
return this.popSeq++;
}
divOsClass.prototype.freePopdivHeight =function(p_div) {
var obj = document.getElementById(p_div);
if(obj.style.height) {
obj.style.height='auto';
obj.style.position='static';
obj.style.padding='0 0 0 0';
obj.style.overflowY='hidden';
obj.parentNode.parentNode.parentNode.style.height="auto";
obj.parentNode.parentNode.parentNode.style.overflow="";
obj.parentNode.parentNode.parentNode.style.overflow="";
obj.parentNode.style.height="auto";
divOs.nextSibling(obj.parentNode).style.height="auto";
}
}
divOsClass.prototype.ActiveDiv =function(p_div,chkTop,chkLeft,Rwidth,Rheight,width,height,speed){
obj = document.getElementById(p_div);
var fixHeight=false;
if($(obj).children("DIV").css("height")=='auto') fixHeight = true;
if(!fixHeight) {
var height =  $(obj).children("DIV").css("height").split('px')[0];
var perH = (Rheight-height)/7;
}
var width =  $(obj).children("DIV").css("width").split('px')[0];
var top =  obj.style.top.split('px')[0];
var left =  obj.style.left.split('px')[0];
if((Rwidth-width)>140)
var perW = (Rwidth-width)/7;
else perW=20;
if (width < Rwidth){
if(!fixHeight && height<Rheight)
height = parseInt(height)+perH;
width = parseInt(width)+perW;
if(chkTop && chkLeft){
top = parseInt(top)-perH;
left = parseInt(left)-perW;
}
else if(chkTop){
top = parseInt(top)-perH;
}
else if(chkLeft){
left = parseInt(left)-perW;
}
if(!fixHeight) {
$(obj).children("DIV").css("height",height+"px");
}
$(obj).children("DIV").css("width",width+"px");
if(width>Rwidth) {
Rheight+=10;
$(obj).children("DIV").css("height",Rheight+"px");
}
if(top<0) top=0;
if(left<0) left=0;
obj.style.top = top+"px";
obj.style.left = left+"px";
clearTimeout(this.act);
this.act = setTimeout('divOsClass.prototype.ActiveDiv(\"'+p_div+'\",'+chkTop+','+chkLeft+','+Rwidth+','+Rheight+','+width+','+height+','+speed+')', speed);
}
return false;
}
divOsClass.prototype.popWindow = divOsClass.prototype.openPopWindow ;
divOsClass.prototype.closePopWindow = function(p_div) {
if(strUtil.prototype.isInteger(p_div)) p_seq = p_div;
else p_seq = this.popHandle.getKey(p_div);
try{
var floatDiv = document.getElementById("floatDiv"+p_seq);
var obj = document.getElementById(this.popHandle.get(p_seq));
obj.parentNode.removeChild(obj);
floatDiv.parentNode.removeChild(floatDiv);
}catch(e){}
}
divOsClass.prototype.closeAllPopWindow = function(p_from) {
if(!p_from) p_from=this.popSeq;
var p_to=p_from-10;
if(p_to<0) p_to=0;
for (var i=p_from;i>p_to;i--){
try{
divOs.closePopWindow(i);
}catch(e){}
}
}
divOsClass.prototype.getPopHandle = function(p_seq)  {
return this.popHandle.get(p_seq);
}
divOsClass.prototype.getParentNodeById = function(p_obj,p_id,p_islike){
var str = new String();
if(p_id == "") return p_obj.parentNode;
if(p_obj==null) return null;
if(typeof(p_obj)=="undefined" || p_obj.tagName.toLowerCase()=="body") return null;
p_obj = p_obj.parentNode;
if(p_obj==null) return null;
if(p_obj.id=="") return divOsClass.prototype.getParentNodeById(p_obj,p_id,p_islike);
if(p_islike){
str = p_obj.id;
if(str.indexOf(p_id)>-1){
return p_obj;
}
else
return divOsClass.prototype.getParentNodeById(p_obj,p_id,p_islike);
}
else{
if(p_obj.id==p_id)
return p_obj;
else
return divOsClass.prototype.getParentNodeById(p_obj,p_id,p_islike);
}
}
divOsClass.prototype.getParentForm = function(p_obj){
while(p_obj.tagName.toLowerCase()!="body" && p_obj.tagName.toLowerCase()!="form") {
p_obj = p_obj.parentNode;
}
if(p_obj.tagName.toLowerCase()=="form") return p_obj;
else return null;
}
divOsClass.prototype.previousSibling = function(p_obj){
if(typeof(p_obj.previousSibling.tagName)=='undefined')
return divOsClass.prototype.previousSibling(p_obj.previousSibling);
else return p_obj.previousSibling;
}
divOsClass.prototype.childNode = function(p_obj,p_seq){
var counter = 0;
try{
for(var i=0;i<p_obj.childNodes.length;i++) {
if(typeof(p_obj.childNodes[i].tagName)=='undefined') continue;
else {
if(counter==p_seq) return p_obj.childNodes[i];
counter++;
}
}
}catch(e) {
if(this._Debug) alert(" childNode Exception \n");
return null;
}
}
divOsClass.prototype.nextSibling = function(p_obj){
if(typeof(p_obj.nextSibling.tagName)=='undefined')
return divOsClass.prototype.nextSibling(p_obj.nextSibling);
else return p_obj.nextSibling;
}
divOsClass.prototype.addClickEvent = function(p_clickobj,p_hidediv,p_method){
tempHideDiv = p_hidediv;
if (typeof event=='undefined')
p_clickobj.parentNode.addEventListener("click",divOsClass.prototype.tempStop,false);
else
event.cancelBubble = true;
if (p_method)
Func = eval(p_method);
else Func = divOsClass.prototype.tempHide;
if (window.addEventpstener)
window.addEventpstener("click", Func, false)
else if (document.attachEvent)
document.attachEvent("onclick", Func)
else if (document.getElementById)
window.onclick=Func
}
divOsClass.prototype.tempStop = function (func){
func.cancelBubble = true;
}
divOsClass.prototype.tempHide = function (){
try	{
if (tempHideDiv!=null)
document.getElementById(tempHideDiv).style.display="none";
}catch (e){}
}
divOsClass.prototype.showDetailMsg = function(p_type,p_msg) {
try{
var objDetail = document.getElementById(p_type+"_DetailMsg");
objDetail.style.display="";
objDetail.className = "msg msg_success";
objDetail.innerHTML = p_msg;
setTimeout("divOsClass.prototype.hideDetailMsg('"+p_type+"')",2000);
}
catch(e) {
if(this._Debug) alert(" showDetailMsg Exception \n");
}
}
divOsClass.prototype.hideDetailMsg = function(p_type,p_msg) {
var objDetail = document.getElementById(p_type+"_DetailMsg");
if(objDetail) objDetail.className = "msg msg_success2";
}
divOsClass.prototype.swapImgRestore = function() {
var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
divOsClass.prototype.preloadImages = function() {
var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
var i,j=d.MM_p.length,a=divOsClass.prototype.preloadImages.arguments; for(i=0; i<a.length; i++)
if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
divOsClass.prototype.findObj = function(n, d) {
if(typeof(n)=="object") return n;
var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=divOsClass.prototype.findObj(n,d.layers[i].document);
if(!x && d.getElementById) x=d.getElementById(n); return x;
}
divOsClass.prototype.swapImage = function() {
var i,j=0,x,a=divOsClass.prototype.swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
if ((x=divOsClass.prototype.findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
divOsClass.prototype.getCurrentStyle = function(ele) {
if (ele.currentStyle)
return ele.currentStyle;
else
return document.defaultView.getComputedStyle(ele, null);
}
function strUtil(p_str) {
if(typeof(p_str)=='undefined') p_str = "";
this.String = p_str;
return this;
}
strUtil.prototype.length = function() {
a=strUtil.prototype.length.arguments;
if(typeof(a[0])!='undefined') p_in = a[0];
else p_in = this.String;
return p_in.length;
}
strUtil.prototype.isInteger = function() {
a=strUtil.prototype.isInteger.arguments;
if(typeof(a[0])!='undefined') p_in = a[0];
else p_in = this.String;
var p_val = p_in.toString();
for(var i=0;i<p_val.length; i++)  {
var oneChar = p_val.charAt(i);
if(i==0 && oneChar =="-")       continue;
if(oneChar<"0" || oneChar>"9")  return false;
}
return true;
}
strUtil.prototype.isPosInteger = function() {
a=strUtil.prototype.isPosInteger.arguments;
if(typeof(a[0])!='undefined') p_in = a[0];
else p_in = this.String;
var p_val = p_in.toString();
for(var i=0;i<p_val.length; i++)  {
var oneChar = p_val.charAt(i);
if(oneChar<"0" || oneChar>"9")  return false;
}
return true;
}
strUtil.prototype.isEmpty = function () {
a=strUtil.prototype.isEmpty.arguments;
if(typeof(a[0])!='undefined') p_in = a[0];
else p_in = this.String;
p_val = this.trim(p_in);
if(p_val == null || p_val=="")  return true;
if(p_val.length==0) return true;
return false;
}
strUtil.prototype.isEmail = function(){
a=strUtil.prototype.isEmail.arguments;
if(typeof(a[0])!='undefined') p_in = a[0];
else p_in = this.String;
var reg = new RegExp("^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z-]+\.)+[a-zA-Z]{2,3}$");
if(p_in.search(reg)!=-1)
return true;
else
return false;
}
strUtil.prototype.isCode = function()  {
a=strUtil.prototype.isCode.arguments;
if(typeof(a[0])!='undefined') p_in = a[0];
else p_in = this.String;
var  reg =new RegExp(/^[a-z\d\_]+$/i);
if(p_in.search(reg)!=-1)
return true;
else
return false;
}
strUtil.prototype.ltrim = function(p_str)  {
a=strUtil.prototype.ltrim.arguments;
if(typeof(a[0])!='undefined') p_str = a[0];
else p_str = this.String;
return p_str.replace(/(^\s*)/g, "");
}
strUtil.prototype.trim = function(p_str)
{
a=strUtil.prototype.trim.arguments;
if(typeof(a[0])!='undefined') p_str = a[0];
else p_str = this.String;
return p_str.replace(/(^\s*)|(\s*$)/g, "");
}
String.prototype.trim = function()
{
return this.replace(/(^\s*)|(\s*$)/g, "");
}
strUtil.prototype.rtrim = function(p_str)  {
a=strUtil.prototype.rtrim.arguments;
if(typeof(a[0])!='undefined') p_str = a[0];
else p_str = this.String;
return p_str.replace(/(\s*$)/g, "");
}
strUtil.prototype.wipeTag = function(p_str,p_tag)  {
var reg = new RegExp("<"+p_tag+"[^>]*>([\\s|\\S]*?)<\\/"+p_tag+"\\s*>","i");
p_str = p_str.replace(reg,"");
return p_str;
}
strUtil.prototype.wipeScript = function(p_str)  {
var reg1 = /<script[^>]*>([\s|\S]*?)<\/script\s*>/i;
var reg2 = /<iframe[^>]*>([\s|\S]*?)<\/iframe\s*>/i;
var reg3 = /<frameset[^>]*>([\s|\S]*?)<\/frameset\s*>/i;
var reg4 = /<a\s*href=[\s\S]*javascript[^>]*>([\s|\S]*?)<\/a\s*>/i;
p_str = p_str.replace(reg1,"");
p_str = p_str.replace(reg2,"");
p_str = p_str.replace(reg3,"");
p_str = p_str.replace(reg4,"");
return p_str;
}
strUtil.prototype.striptags = function(p_str)  {
var reg1 = /<[^>]*>/i;
var reg2 = /<\/[^>]*>/i;
var reg3 = /&nbsp;/i;
p_str = p_str.replace(reg1,"");
p_str = p_str.replace(reg2,"");
p_str = p_str.replace(reg3,"");
return p_str;
}
strUtil.prototype.wipeForm = function(p_str)  {
var reg1 = /<form[^>]*>/i;
var reg2 = /<\/form[^>]*>/i;
p_str = p_str.replace(reg1,"");
p_str = p_str.replace(reg2,"");
return p_str;
}
strUtil.prototype.addslashes = function (str) {
str=str.replace(/\'/g,'\\\'');
str=str.replace(/\"/g,'\\"');
str=str.replace(/\\/g,'\\\\');
str=str.replace(/\0/g,'\\0');
return str;
}
strUtil.prototype.stripslashes = function (str) {
str=str.replace(/\\'/g,'\'');
str=str.replace(/\\"/g,'"');
str=str.replace(/\\\\/g,'\\');
str=str.replace(/\\0/g,'\0');
return str;
}
strUtil.prototype.nl2br = function (str) {
return str.replace(/(\r\n)|(\n\r)|\r|\n/g,"<BR>");
}
strUtil.prototype.directShowInput = function (str) {
return strUtil.prototype.nl2br(strUtil.prototype.htmlspecialchars(strUtil.prototype.stripslashes(str)));
}
strUtil.prototype.htmlspecialchars = function(str)  {
str = str.replace(/\</g,"&lt;");
str = str.replace(/\>/g,"&gt;");
str = str.replace(/\"/g,"&quot;");
return str;
}
strUtil.prototype.reversespecialchars = function(str)  {
str = str.replace(/&lt;/g,"<");
str = str.replace(/&gt;/g,">");
str = str.replace(/&quot;/g,"\"");
str = str.replace(/&amp;/g,"&");
return str;
}
strUtil.prototype.markIp = function () {
a=strUtil.prototype.markIp.arguments;
ip = a[0];
if(typeof(a[1])!='undefined') part = a[1];
else part = 3;
if(part>4) return ip;
ret = ip.split(".");
str="";
for(i=0;i<ret.length;i++) {
if(i+1<part) str+=ret[i]+".";
else str+="X.";
}
return str.substring(0,str.length-1);
}
function Cookie(path,domain,duration,secure) {
this.defPath = path;
this.defDomain = domain;
this.defDuration = duration;
this.defSecure = secure;
return this;
}
Cookie.prototype.setCookie = function (name, value, duration, path, domain, secure) {
if(!duration && this.defDuration) duration = this.defDuration;
if(!path && this.defPath) path = this.defDuration;
if(!domain && this.defDomain) domain = this.defDomain;
if(!secure && this.defSecure) secure = this.defSecure;
if(duration){
var expires = new Date();
var curTime = new Date().getTime();
expires.setTime(curTime + (1000 * 60 * duration));
}
this.setCookieDT(name, value, expires, path, domain, secure);
}
Cookie.prototype.setCookieDT = function (name, value, expires, path, domain, secure) {
document.cookie =
name+"="+encodeURIComponent(value)+
(expires ? "; expires="+expires.toGMTString() : "")+
(path    ? "; path="   +path   : "")+
(domain  ? "; domain=" +domain : "")+
(secure  ? "; secure" : "");
}
Cookie.prototype.getCookie = function (name) {
cookie = " "+document.cookie;
offset = cookie.indexOf(" "+name+"=");
if (offset == -1) return undefined;
offset += name.length+2;
end     = cookie.indexOf(";", offset)
if (end == -1) end = cookie.length;
return decodeURIComponent(cookie.substring(offset, end));
}
Cookie.prototype.existCookie = function (name) {
var cookieVal = new strUtil(Cookie.prototype.getCookie(name));
cookieVal.trim(' ');
if(cookieVal.length()==0) return false;
return true;
}
Cookie.prototype.delCookie = function (name,path,domain) {
if (this.getCookie(name)) {
var date = new Date("January 01, 2000 00:00:01");
this.setCookieDT(name, "", date, path, domain);
}
}
function dateUtil() {
return this;
}
dateUtil.prototype.monthdays = function (p_year,p_month) {
if(p_month==1 || p_month==3 || p_month==5 || p_month==7 || p_month==8 ||p_month==10 || p_month==12 )
return 31;
if(p_month==4 || p_month==6 || p_month==9 || p_month==11 )
return 30;
if(p_month==2 && ((p_year%4==0 && p_year%100!=0) || p_year%400==0 ))
return 29;
return 28;
}
dateUtil.prototype.nextmonth = function(p_year,p_month) {
p_month=parseInt(p_month);
if(p_month<12)  {p_month +=1; }
else  {
p_year +=1;
p_month =1;
}
return(p_year+"-"+p_month);
}
dateUtil.prototype.nextdate = function(p_year,p_month,p_day)  {
if(p_day<this.monthdays(p_year,p_month)) {p_day +=1; }
else{
if(p_month<12) {
p_month +=1;
p_day = 1;
}
else {
p_year +=1;
p_month =1;
p_day   =1;
}
}
return(p_year+"-"+p_month+"-"+p_day);
}
dateUtil.prototype.validdate = function (p_year,p_month,p_day){
if(!(!isNaN(p_year) && p_year>1000 && p_year<=9999))
return false;
if(!(!isNaN(p_month) && p_month>=1 && p_month<=12))
return false;
if(!(!isNaN(p_day) && p_day>=1 && p_day<=this.monthdays(p_year,p_month)))
return false;
return true;
}
dateUtil.prototype.isDates = function(p_date) {
deli ='';
for(i=0;i<=p_date.length;i++){
c=p_date.substring(i,i+1);
switch (c) {
case '-' : deli = '-'
break;
case '/' : deli = '/'
break;
case ':' : deli = ':'
break;
case '.' : deli = '.'
break;
case ',' : deli = ','
break;
case "'" : deli = "'"
break;
}
if (deli!="") break;
}
if(deli=="") return false;
l_date=p_date.split(deli);
if(l_date.length>3) return false;
try {
if(!this.validdate(l_date[0],l_date[1],l_date[2]))
return false;
}catch(e){return false;}
return true;
}
function dataCheck() {
this.ErrMsg = new Array();
this.ErrMsg[2] = "Please Input Something!";
this.ErrMsg[1] = "Error1!";
return this;
}
dataCheck.prototype.setField = function(p_field) {
this.screenField = p_field;
}
dataCheck.prototype.setName = function(p_name) {
this.screenName = p_name;
}
dataCheck.prototype.setNull = function(p_null) {
this.screenNull = p_null;
}
dataCheck.prototype.setType = function(p_type) {
this.screenType = p_type;
}
dataCheck.prototype.getObj = function(p_form,p_name,p_prefix) {
var obj ;
if(typeof(p_form)=='object') {
obj = eval("p_form."+p_prefix+p_name);
}
else {
obj = eval("document."+p_form+"."+p_prefix+p_name);
}
if(!obj) return false;
return obj;
}
dataCheck.prototype.setMsg = function(p_msg) {
this.ErrMsg = p_msg;
}
dataCheck.prototype.datavalid = function(p_form,p_prefix)  {
var msg = "";
var focusobj ;
var flag = 1;
var f_length = this.screenField.length;
var strUtl = new strUtil('');
for(var i=0;i<f_length;i++)  {
if(typeof(this.screenName[i])=='undefined') continue;
if(this.screenType[i]=="l") {
if(this.screenNull[i]=='1')  continue;
obj = this.getObj(p_form,this.screenField[i],p_prefix);
if(!obj) continue;
if(obj.checked) continue;
var l_len = obj.length;
var chk = false;
for(j=0;j<l_len;j++) {
obj = this.getObj(p_form,this.screenField[i]+"["+j+"]",p_prefix);
if(obj.checked) { chk = true;  break; }
}
if(chk) continue;
msg = msg + this.screenName[i]+" : "+this.ErrMsg[1]+"\n";
if(flag) {
focusobj = obj;
flag = 0;
}
continue;
}
obj = this.getObj(p_form,this.screenField[i],p_prefix);
if(!obj) continue;
if(!this.screenNull[i] && strUtl.trim(obj.value) == "") {
msg =msg +  this.screenName[i]+ " : "+this.ErrMsg[1]+"\n";
if(flag) { focusobj = obj;  flag = 0; }
}
switch(this.screenType[i]){
case "d":
if(obj.value != "" && !dateUtil.prototype.isDates(obj.value)) {
msg +=  this.screenName[i]+ " : " + this.ErrMsg[3] +" \n";
if(flag) { focusobj = obj;  flag = 0; }
}
break;
case "i":
if(obj.value != "" && !strUtl.isInteger(obj.value)) {
msg +=  this.screenName[i]+ " : "+ this.ErrMsg[2]+" \n";
if(flag) { focusobj = obj;  flag = 0; }
}
break;
case "n":
if(obj.value != "" && isNaN(obj.value)) {
msg += this.screenName[i]+ " : "+ this.ErrMsg[4]+" \n";
if(flag) { focusobj = obj;  flag = 0; }
}
break;
case "f":
if(obj.value != "" && isNaN(obj.value)) {
msg += this.screenName[i]+ " : " + this.ErrMsg[4] +" \n";
if(flag) { focusobj = obj;  flag = 0; }
}
break;
case "l":
if(obj.value != "" && !strUtl.isInteger(obj.value))  {
msg += this.screenName[i]+ " : " + this.ErrMsg[2]+" \n";
if(flag) { focusobj = obj;  flag = 0; }
}
break;
case "e":
if(obj.value != "" && !strUtl.isEmail(obj.value))  {
msg += this.screenName[i]+ " : " + this.ErrMsg[5]+" \n";
if(flag) { focusobj = obj;  flag = 0; }
}
break;
}
}
if(!strUtl.isEmpty(msg))  {
msg = this.ErrMsg[0] + " :\n" + msg ;
alert(msg);
try {
focusobj.focus();
}catch(e){}
return false;
}
return true;
}
function hashUtil() {
this.Storage = new Object();
this.RevStorage = new Object();
this.queueSeq = "";
this.Sep = ";;";
this.Pointer = 0;
this.Length=0;
return this;
}
hashUtil.prototype.put = function(key,value) {
if(this.hasKey(key)) this.remove(key);
this.queueSeq = key+this.Sep+this.queueSeq;
this.Storage[key] = value;
this.RevStorage[value] = key;
this.Length++;
}
hashUtil.prototype.push = function(key,value) {
this.put(key,value);
}
hashUtil.prototype.isEmpty = function() {
if(this.Length>0) return false;
return true;
}
hashUtil.prototype.pop = function() {
var l_key = this.getFirstKey();
var l_val = this.getFirst();
this.remove(l_key);
return l_val;
}
hashUtil.prototype.read = function() {
var ret = this.queueSeq.split(this.Sep);
if(typeof(ret[this.Pointer])=="undefined") return "";
var l_key = ret[this.Pointer++];
var l_val = this.get(l_key);
return l_val;
}
hashUtil.prototype.isEOF = function() {
if(this.Pointer>=this.Length) return true;
return false;
}
hashUtil.prototype.get = function(key) {
if(typeof this.Storage[key] == "undefined") return null;
else return this.Storage[key];
}
hashUtil.prototype.getKey = function(val) {
if(typeof this.RevStorage[val] == "undefined") return null;
return this.RevStorage[val];
}
hashUtil.prototype.mvToFirst = function(key) {
this.queueSeq = this.queueSeq.replace(key+this.Sep,"");
this.queueSeq = key+this.Sep+this.queueSeq;
}
hashUtil.prototype.getFirst = function() {
while(true) {
var key = this.queueSeq.substring(0,this.queueSeq.indexOf(this.Sep));
if(typeof this.Storage[key] == "undefined") {
this.queueSeq = this.queueSeq.substring(this.queueSeq.indexOf(this.Sep));
}
return this.Storage[key];
}
}
hashUtil.prototype.getFirstKey = function() {
while(true) {
var key = this.queueSeq.substring(0,this.queueSeq.indexOf(this.Sep));
if(typeof this.Storage[key] == "undefined") break;
return key;
}
}
hashUtil.prototype.hasKey = function(key) {
if(typeof this.Storage[key] == "undefined") return false;
return true;
}
hashUtil.prototype.hasValue = function(val) {
if(typeof this.RevStorage[val] == "undefined") return false;
return true;
}
hashUtil.prototype.remove = function(key) {
this.queueSeq = this.queueSeq.replace(key+this.Sep,"");
var val = this.Storage[key] ;
delete this.Storage[key];
delete this.RevStorage[val];
this.Length--;
}
hashUtil.prototype.reset = function() {
this.Storage = new Object();
this.RevStorage = new Object();
this.queueSeq = "";
this.Length=0;
this.Pointer = 0;
return this;
}
hashUtil.prototype.resetPointer = function() {
this.Pointer=0;
}
function Scroll(p_width,p_height,p_speed,p_direct){
this.ns = (document.layers)? true:false
this.ie = (document.all)? true:false
this.preTop = 0;
this.preLeft = 0;
this.moveLimit = 0;
this.currentLeft = 0;
this.currentTop = 0;
this.marquee_name = "";
this.template_name = "";
this.marquee_hidden = "";
this.marquee_width = p_width;
this.marquee_height = p_height;
this.marquee_speed = p_speed;
this.marquee_direct = p_direct;
this.setMarObject = setMarObject;
this.setTempObject = setTempObject;
this.setHiddenObject = setHiddenObject;
this.beginScroll = beginScroll;
this.scrollInit = scrollInit;
this.scrollUp = scrollUp;
this.scrollDown = scrollDown;
this.scrollRight = scrollRight;
this.scrollLeft = scrollLeft;
this.getObject = getObject;
return this;
}
function setTempObject(p_obj){
this.template_name = p_obj;
}
function setMarObject(p_obj){
this.marquee_name = p_obj;
}
function setHiddenObject(p_obj){
this.marquee_hidden = p_obj;
}
function getObject(p_obj){
if(typeof(p_obj)=="string") {
if(this.ie) return  eval("document.all."+p_obj);
else return document.getElementById(p_obj);//Joyce firefox method
} else {
if(this.ie) return eval("p_obj");
else return p_obj;
}
}
function beginScroll(){
var marq = this.getObject(this.marquee_name);
var temp = this.getObject(this.template_name);
with(marq){
style.height = this.marquee_height+'px';//Joyce add "px"
if(this.marquee_direct == 'up' || this.marquee_direct == 'down'){
style.overflowX="visible";
style.overflowY="hidden";
}
else{
style.overflowX="hidden";
style.overflowY="visible";
}
var myStopValue = this.marquee_hidden+ "= 1";
var myStartValue = this.marquee_hidden + "=0";
marq.onmouseover = new Function(myStopValue);
marq.onmouseout = new Function(myStartValue);
}
}
function scrollInit(){
var marq = this.getObject(this.marquee_name);
var temp = this.getObject(this.template_name);
var tmpstr;
var Nheight=this.marquee_height/4;
var Nwidth=this.marquee_width/4;
if(this.marquee_direct == "up" || this.marquee_direct == "down")
marq.innerHTML += "<span style='height:"+Nheight+"px;'></span>";
else
marq.innerHTML += "<span style='width:"+Nwidth+"px;'></span>";
tmpstr =  marq.innerHTML;
if(this.marquee_direct == "up" || this.marquee_direct == "down"){
marq.noWrap = false;
temp.noWrap = false;
var marq_height = temp.offsetHeight;
var counter = 0;
while(!marq_height) {
if(counter++>1000) { marq_height=100; break; }
marq_height = temp.offsetHeight;
}
while(marq_height < this.marquee_height){
marq_height += marq_height;
tmpstr+=marq.innerHTML;
}
}else{
marq.noWrap = true;
temp.noWrap = true;
var marq_width = temp.offsetWidth;
var counter = 0;
while(!marq_width) {
if(counter++>1000) { marq_width=100; break; }
marq_width = temp.offsetWidth;
}
while(marq_width < this.marquee_width){
marq_width += marq_width;
tmpstr+=marq.innerHTML;
}
}
if(this.marquee_direct == "up" || this.marquee_direct == "down"){
if(temp.offsetHeight<2*marq.offsetHeight)
marq.innerHTML = tmpstr+tmpstr;
else
marq.innerHTML = tmpstr;
}
else {
if(temp.offsetWidth<2*marq.offsetWidth)
marq.innerHTML = tmpstr+tmpstr;
else
marq.innerHTML = tmpstr;
}
temp.innerHTML = "";
var param_up = "this.scrollUp('"+this.marquee_name+"','"+this.template_name+"',"+this.marquee_hidden+","+this.marquee_height+")";
var param_down = "this.scrollDown('"+this.marquee_name+"','"+this.template_name+"','"+this.marquee_hidden+"',"+this.marquee_height+")";
var param_left = "this.scrollLeft('"+this.marquee_name+"','"+this.template_name+"',"+this.marquee_hidden+","+this.marquee_width+")";
var param_right = "this.scrollRight('"+this.marquee_name+"','"+this.template_name+"','"+this.marquee_hidden+"',"+this.marquee_width+")";
if(this.marquee_direct == "up")
setInterval(param_up,this.marquee_speed);
if(this.marquee_direct == "left")
setInterval(param_left,this.marquee_speed);
if(this.marquee_direct == "right")
setInterval(param_right,this.marquee_speed);
if(this.marquee_direct == "down")
setInterval(param_down,this.marquee_speed);
}
function scrollUp(p_marquee,p_template,p_stop,p_height){
var stop_value = eval(p_stop);
if(stop_value == 1) return;
var marq = this.getObject(p_marquee);
var temp = this.getObject(p_template);
this.preTop = marq.scrollTop;
marq.scrollTop += 1;
if(this.preTop == marq.scrollTop){
marq.scrollTop = temp.offsetHeight- p_height + 1;
marq.scrollTop += 1;
}
}
function scrollRight(p_marquee,p_template,p_stop,p_width){
var stop_value = eval(p_stop);
if(stop_value == 1) return;
var marq = this.getObject(p_marquee);
var temp = this.getObject(p_template);
this.preLeft = marq.scrollLeft;
marq.scrollLeft -= 1;
if(this.preLeft == marq.scrollLeft){
if(!this.moveLimit){
marq.scrollLeft = temp.offsetWidth*2;
this.moveLimit = marq.scrollLeft;
}
marq.scrollLeft = this.moveLimit - temp.offsetWidth + p_width;
marq.scrollLeft -= 1;
}
}
function scrollDown(p_marquee,p_template,p_stop,p_height){
var stop_value = eval(p_stop);
if(stop_value == 1) return;
var marq = this.getObject(p_marquee);
var temp = this.getObject(p_template);
this.preTop = marq.scrollTop;
marq.scrollTop -= 1;
if(this.preTop == marq.scrollTop){
if(!this.moveLimit){
marq.scrollTop = temp.offsetHeight*2;
this.moveLimit = marq.scrollTop;
}
marq.scrollTop = this.moveLimit - temp.offsetHeight + p_height;
marq.scrollTop -= 1;
}
}
function scrollLeft(p_marquee,p_template,p_stop,p_width){
var stop_value = eval(p_stop);
if(stop_value == 1) return;
var marq = this.getObject(p_marquee);
var temp = this.getObject(p_template);
marq.noWrap = true;
this.preLeft = marq.scrollLeft;
marq.scrollLeft = marq.scrollLeft + 1;
if(this.preLeft == marq.scrollLeft){
marq.scrollLeft = temp.offsetWidth - p_width + 1;
marq.scrollLeft += 1;
}
}
function begin_frame(p_id,p_pct, p_pix,p_height,p_speed) {
marq_frame = new Scroll(marqueWidth,p_height,p_speed,'up');
if(p_pct>0)
var marqueWidth = screen.width * p_pct * 0.82 / 100 ;
if(p_pix>0)
var marqueWidth = p_pix;
marq_frame.setMarObject(p_id);
marq_frame.setTempObject(p_id+"_temp");
marq_frame.setHiddenObject(p_id+"_stop");
marq_frame.beginScroll();
marq_frame.scrollInit();
}
function startScroll(marquee_id,p_height,p_speed) {
eval(marquee_id+"_stop =0;");
eval(marquee_id+"_Width =0;");
eval("marq_"+marquee_id+" = new Scroll("+marquee_id+"_Width,p_height,p_speed,'up');");
eval("marq_"+marquee_id+".setMarObject('"+marquee_id+"');");
eval("marq_"+marquee_id+".setTempObject('"+marquee_id+"_temp');");
eval("marq_"+marquee_id+".setHiddenObject('"+marquee_id+"_stop');");
eval("marq_"+marquee_id+".beginScroll();");
eval("marq_"+marquee_id+".scrollInit();");
}
function resize(p_TagName,p_fix,p_home) {
if (!p_TagName) p_TagName="C0";
if (!p_fix) p_fix=0;
var WinHeight = document.documentElement.clientHeight - (p_fix);
try{
var mc1 = document.getElementById("main");
var mc2 = document.getElementById("sidebar");
var mc3 = eval("document.getElementById('"+p_TagName+"_maincontent')");
var mc4 = document.getElementById("listTB");
var statusbar = document.getElementById(p_TagName+"_statusbar");
var statHeight = 0;
if(typeof(statusbar)=="object") {
try{
statHeight = statusbar.clientHeight;
}catch(e) {}
}
mc1.style.height = (WinHeight - 60)+"px";
mc2.style.height = (WinHeight - 62)+"px";
mc3.style.height = (WinHeight - 125-statHeight)+"px";
if(eval("document.getElementById('"+p_TagName+"_mainDiv')")){
var mc3 = eval("document.getElementById('"+p_TagName+"_mainDiv')");
var mc4 = eval("document.getElementById('"+p_TagName+"_showDiv')");
mc3.style.height = (WinHeight - 125 - statHeight)+"px";
mc4.style.height = (WinHeight - 125)+"px";
}
if(eval("document.getElementById('"+p_TagName+"_mainPgDiv')")){
var mc3 = eval("document.getElementById('"+p_TagName+"_mainPgDiv')");
var mc4 = eval("document.getElementById('"+p_TagName+"_showDiv')");
mc3.style.height = (WinHeight - 155 - statHeight)+"px";
mc4.style.height = (WinHeight - 125)+"px";
}
}catch(e){}//alert(e.message)}
checktab(30,3);
}
function ShowHide() {
for (i=0;i<ShowHide.arguments.length;i++) {
var _item = document.getElementById(ShowHide.arguments[i]);
if (_item.className == (ShowHide.arguments[i]+"_1")) {
_item.className = ShowHide.arguments[i]+"_2";
} else {
_item.className = ShowHide.arguments[i]+"_1";
}
}
}
function chgBG(_item,_bgcolor) {
_item.style.backgroundColor = _bgcolor;
}
function swapStyle(_item,_bdcolor,_bgcolor) {
var oricolor = _item.style.borderColor;
var oribgcolor = _item.style.backgroundColor;
_item.style.borderColor = _bdcolor;
_item.style.backgroundColor = _bgcolor;
_item.onmouseout = function() {
this.style.borderColor = oricolor;
this.style.backgroundColor = oribgcolor;
}
}
function swapImage(_image,newsrc) {
var _img = document.getElementById(_image);
var oriimg = _img.src;
_img.src = newsrc;
_image.onmouseout = function() {
_img.src = oriimg;
}
}
function chgClass(_obj) {
_obj.className = "info_actived";
_obj.onblur = function() {
this.className = "info_normal";
}
}
function chgStyle(_obj,_bdcolor,_bgcolor) {
if (!_bdcolor) _bdcolor = "#008080";
if (!_bgcolor) _bgcolor = "#FFFFF0";
var oribdcolor = _obj.style.borderColor;
var oribgcolor = _obj.style.backgroundColor;
_obj.style.borderColor = _bdcolor;
_obj.style.backgroundColor = _bgcolor;
_obj.onblur = function() {
this.style.borderColor = oribdcolor;
this.style.backgroundColor = oribgcolor;
}
}
function chgImage(_image,newsrc) {
var _img = document.getElementById(_image);
_img.src = newsrc;
}
function swapClassName(_Item,_class) {
var oriclassName = _Item.className;
_Item.className = _class;
_Item.onmouseout = function() {
_Item.className = oriclassName;
}
}
function setClassName(pid,val) {
document.getElementById(pid).className = val;
}
function selectOnce(p_type,p_obj,p_id,p_cnt){
for(var i=1;i<=p_cnt;i++){
var obj = p_obj+i;
var obj_id = p_id+i;
if(p_type==i){
document.getElementById(obj).className = "o_block";
document.getElementById(obj_id).className = "now";
}else{
document.getElementById(obj).className = "o_none";
document.getElementById(obj_id).className = "no";
}
}
}
function findElementId(pid, cid) {
var a = document.getElementById(pid).getElementsByTagName(cid);
var pData = Array(a.length);
for (i=0;i<a.length;i++){
pData[i] = Array(a[i].id);
}
return pData;
}
function findElementText(pid, cid) {
var navi = window.navigator.userAgent.toUpperCase();
var a = document.getElementById(pid).getElementsByTagName(cid);
var pData = Array(a.length);
for (i=0;i<a.length;i++){
if (navi.indexOf("FIREFOX")>=1)
pData[i] = Array(a[i].textContent);
else
pData[i] = Array(a[i].innerText);
}
return pData;
}
var allItem = new Array();
function checktab(_ext,space) {
var _allItem = new Array();
var showItem = new Array();
var hideItem = new Array();
var hideItemName = new Array();
var pData = findElementId("maintbul","li");
var pName =	findElementText("maintbul","li");
var a = pData.length;
var b = document.getElementById("main").clientWidth;
var allwidth = j = k = 0;
for (i=0;i<a;i++ ) {
allwidth = allwidth + space + document.getElementById(pData[i]).clientWidth;
if ((allwidth+_ext) < b) {
document.getElementById(pData[i]).style.display = "block";
showItem[j] = pData[i];
j++;
} else {
document.getElementById(pData[i]).style.display = "none";
hideItem[k] = pData[i];
hideItemName[k] = pName[i];
k++;
}
}
if (hideItem.length>0) {
var _hideItem = "";
for (m=0;m<hideItem.length;m++ ) {
var tagId = hideItem[m].toString();
var tagName = hideItemName[m].toString();
var tagSeq = tagId.slice(1);
_hideItem = _hideItem + "'<span class=\"extTab\"><a href=\"javascript:void(null)\" onclick=\"divOs.selectTag("+tagSeq+");hideExtTab();\">"+tagName+"</a><span>&nbsp;&nbsp;<span style=\"text-align:right;\"><a href=\"javascript:void(null)\" onclick=\"divOs.delTag("+tagSeq+");checktab(30,3);\">X</span>',";
document.getElementById("exttab").style.display = "none";
}
_hideItem=_hideItem.slice(0,-1);
setTimeout("showExt("+_hideItem+")",200);
} else {
hideExt();
}
_allItem[0]=showItem;
_allItem[1]=hideItem;
allItem = _allItem;
}
function showExt() {
var _Ext = document.getElementById("exttab");
var _ExtArrow = document.getElementById("exttabarrow");
var _ExtVal = "";
_ExtArrow.style.display = "block";
_Ext.innerHTML = "";
for (i=0; i<showExt.arguments.length; i++) {
var obj = document.createElement("div");
obj.innerHTML = showExt.arguments[i];
_Ext.appendChild(obj);
}
}
function hideExt() {
document.getElementById("exttab").style.display = "none";
document.getElementById("exttabarrow").style.display = "none";
}
function hideExtTab() {
try{
document.getElementById("exttab").style.display = "none";
}catch(e){}
}
function showTab(p_tabDiv){
if (!p_tabDiv) p_tabDiv = "exttab";
var tabDiv = document.getElementById(p_tabDiv);
if (tabDiv.style.display=='none'){
tabDiv.style.display='block';
}
else{
tabDiv.style.display='none';
}
}
if(typeof(sajax_request_type)=='undefined') sajax_request_type = 'POST';
if(typeof(sajax_debug_mode)=='undefined') sajax_debug_mode = false;
function sajax_debug(text) {
if (sajax_debug_mode)
alert("RSD: " + text)
}
function sajax_init_object() {
sajax_debug("sajax_init_object() called..")
try{
netscape.security.PrivilegeManager.enablePrivilege("UniversalBrowserRead");
}catch(e){}
var A;
try {
A=new ActiveXObject("Msxml2.XMLHTTP");
} catch (e) {
try {
A=new ActiveXObject("Microsoft.XMLHTTP");
} catch (oc) {
A=null;
}
}
if(!A && typeof XMLHttpRequest != "undefined")
A = new XMLHttpRequest();
if (!A)
sajax_debug("Could not create connection object.");
return A;
}
function sajax_do_call(func_name, args) {
var i, x, n;
var uri;
var post_data;
uri = uri_in_sajax;
if (sajax_request_type == "GET") {
if (uri.indexOf("?") == -1)
uri = uri + "?rs=" + escape(func_name);
else
uri = uri + "&rs=" + escape(func_name);
for (i = 0; i < args.length-1; i++)
uri = uri + "&rsargs[]=" + escape(args[i]);
uri = uri + "&rsrnd=" + new Date().getTime();
post_data = null;
} else {
post_data = "rs=" + escape(func_name);
for (i = 0; i < args.length-1; i++)
post_data = post_data + "&rsargs[]=" + escape(args[i]);
}
x = sajax_init_object();
x.open(sajax_request_type, uri, true);
if (sajax_request_type == "POST") {
x.setRequestHeader("Method", "POST " + uri + " HTTP/1.1");
x.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
}
x.onreadystatechange = function() {
if (x.readyState != 4)
return;
sajax_debug("received " + x.responseText);
var status;
var data;
var mark;
status = x.responseText.charAt(0);
data = x.responseText.substring(2);
if (status == "-")
alert("Error: " + data);
else if(status== "+"){
args[args.length-1](data);
}
else if(status=="="){
args[args.length-1](data);
}
else {
if(x.responseText.replace(/(^[\s]*)|([\s]*$)/g,"")=='') return;
args[args.length-1](x.responseText);
}
}
x.send(post_data);
sajax_debug(func_name + " uri = " + uri + "/post = " + post_data);
sajax_debug(func_name + " waiting..");
delete x;
}
var whitespace = "\n\r\t ";
XMLP = function(strXML) {
strXML = SAXStrings.replace(strXML, null, null, "\r\n", "\n");
strXML = SAXStrings.replace(strXML, null, null, "\r", "\n");
this.m_xml = strXML;
this.m_iP = 0;
this.m_iState = XMLP._STATE_PROLOG;
this.m_stack = new Stack();
this._clearAttributes();
}
XMLP._NONE= 0;
XMLP._ELM_B= 1;
XMLP._ELM_E= 2;
XMLP._ELM_EMP = 3;
XMLP._ATT = 4;
XMLP._TEXT= 5;
XMLP._ENTITY= 6;
XMLP._PI= 7;
XMLP._CDATA= 8;
XMLP._COMMENT = 9;
XMLP._DTD = 10;
XMLP._ERROR= 11;
XMLP._CONT_XML = 0;
XMLP._CONT_ALT = 1;
XMLP._ATT_NAME = 0;
XMLP._ATT_VAL= 1;
XMLP._STATE_PROLOG = 1;
XMLP._STATE_DOCUMENT = 2;
XMLP._STATE_MISC = 3;
XMLP._errs = new Array();
XMLP._errs[XMLP.ERR_CLOSE_PI= 0 ] = "PI: missing closing sequence";
XMLP._errs[XMLP.ERR_CLOSE_DTD= 1 ] = "DTD: missing closing sequence";
XMLP._errs[XMLP.ERR_CLOSE_COMMENT= 2 ] = "Comment: missing closing sequence";
XMLP._errs[XMLP.ERR_CLOSE_CDATA= 3 ] = "CDATA: missing closing sequence";
XMLP._errs[XMLP.ERR_CLOSE_ELM= 4 ] = "Element: missing closing sequence";
XMLP._errs[XMLP.ERR_CLOSE_ENTITY= 5 ] = "Entity: missing closing sequence";
XMLP._errs[XMLP.ERR_PI_TARGET= 6 ] = "PI: target is required";
XMLP._errs[XMLP.ERR_ELM_EMPTY= 7 ] = "Element: cannot be both empty and closing";
XMLP._errs[XMLP.ERR_ELM_NAME= 8 ] = "Element: name must immediatly follow \"<\"";
XMLP._errs[XMLP.ERR_ELM_LT_NAME= 9 ] = "Element: \"<\" not allowed in element names";
XMLP._errs[XMLP.ERR_ATT_VALUES = 10] = "Attribute: values are required and must be in quotes";
XMLP._errs[XMLP.ERR_ATT_LT_NAME= 11] = "Element: \"<\" not allowed in attribute names";
XMLP._errs[XMLP.ERR_ATT_LT_VALUE= 12] = "Attribute: \"<\" not allowed in attribute values";
XMLP._errs[XMLP.ERR_ATT_DUP= 13] = "Attribute: duplicate attributes not allowed";
XMLP._errs[XMLP.ERR_ENTITY_UNKNOWN = 14] = "Entity: unknown entity";
XMLP._errs[XMLP.ERR_INFINITELOOP= 15] = "Infininte loop";
XMLP._errs[XMLP.ERR_DOC_STRUCTURE= 16] = "Document: only comments, processing instructions, or whitespace allowed outside of document element";
XMLP._errs[XMLP.ERR_ELM_NESTING= 17] = "Element: must be nested correctly";
XMLP.prototype._addAttribute = function(name, value) {
this.m_atts[this.m_atts.length] = new Array(name, value);
}
XMLP.prototype._checkStructure = function(iEvent) {
if(XMLP._STATE_PROLOG == this.m_iState) {
if((XMLP._TEXT == iEvent) || (XMLP._ENTITY == iEvent)) {
if(SAXStrings.indexOfNonWhitespace(this.getContent(), this.getContentBegin(), this.getContentEnd()) != -1) {
return this._setErr(XMLP.ERR_DOC_STRUCTURE);
}
}
if((XMLP._ELM_B == iEvent) || (XMLP._ELM_EMP == iEvent)) {
this.m_iState = XMLP._STATE_DOCUMENT;
}
}
if(XMLP._STATE_DOCUMENT == this.m_iState) {
if((XMLP._ELM_B == iEvent) || (XMLP._ELM_EMP == iEvent)) {
this.m_stack.push(this.getName());
}
if((XMLP._ELM_E == iEvent) || (XMLP._ELM_EMP == iEvent)) {
var strTop = this.m_stack.pop();
if((strTop == null) || (strTop != this.getName())) {
return this._setErr(XMLP.ERR_ELM_NESTING);
}
}
if(this.m_stack.count() == 0) {
this.m_iState = XMLP._STATE_MISC;
return iEvent;
}
}
if(XMLP._STATE_MISC == this.m_iState) {
if((XMLP._ELM_B == iEvent) || (XMLP._ELM_E == iEvent) || (XMLP._ELM_EMP == iEvent) || (XMLP.EVT_DTD == iEvent)) {
return this._setErr(XMLP.ERR_DOC_STRUCTURE);
}
if((XMLP._TEXT == iEvent) || (XMLP._ENTITY == iEvent)) {
if(SAXStrings.indexOfNonWhitespace(this.getContent(), this.getContentBegin(), this.getContentEnd()) != -1) {
return this._setErr(XMLP.ERR_DOC_STRUCTURE);
}
}
}
return iEvent;
}
XMLP.prototype._clearAttributes = function() {
this.m_atts = new Array();
}
XMLP.prototype._findAttributeIndex = function(name) {
for(var i = 0; i < this.m_atts.length; i++) {
if(this.m_atts[i][XMLP._ATT_NAME] == name) {
return i;
}
}
return -1;
}
XMLP.prototype.getAttributeCount = function() {
return this.m_atts ? this.m_atts.length : 0;
}
XMLP.prototype.getAttributeName = function(index) {
return ((index < 0) || (index >= this.m_atts.length)) ? null : this.m_atts[index][XMLP._ATT_NAME];
}
XMLP.prototype.getAttributeValue = function(index) {
return ((index < 0) || (index >= this.m_atts.length)) ? null : __unescapeString(this.m_atts[index][XMLP._ATT_VAL]);
}
XMLP.prototype.getAttributeValueByName = function(name) {
return this.getAttributeValue(this._findAttributeIndex(name));
}
XMLP.prototype.getColumnNumber = function() {
return SAXStrings.getColumnNumber(this.m_xml, this.m_iP);
}
XMLP.prototype.getContent = function() {
return (this.m_cSrc == XMLP._CONT_XML) ? this.m_xml : this.m_cAlt;
}
XMLP.prototype.getContentBegin = function() {
return this.m_cB;
}
XMLP.prototype.getContentEnd = function() {
return this.m_cE;
}
XMLP.prototype.getLineNumber = function() {
return SAXStrings.getLineNumber(this.m_xml, this.m_iP);
}
XMLP.prototype.getName = function() {
return this.m_name;
}
XMLP.prototype.next = function() {
return this._checkStructure(this._parse());
}
XMLP.prototype._parse = function() {
if(this.m_iP == this.m_xml.length) {
return XMLP._NONE;
}
if(this.m_iP == this.m_xml.indexOf("<?", this.m_iP)) {
return this._parsePI (this.m_iP + 2);
}
else if(this.m_iP == this.m_xml.indexOf("<!DOCTYPE", this.m_iP)) {
return this._parseDTD (this.m_iP + 9);
}
else if(this.m_iP == this.m_xml.indexOf("<!--", this.m_iP)) {
return this._parseComment(this.m_iP + 4);
}
else if(this.m_iP == this.m_xml.indexOf("<![CDATA[", this.m_iP)) {
return this._parseCDATA (this.m_iP + 9);
}
else if(this.m_iP == this.m_xml.indexOf("<", this.m_iP)) {
return this._parseElement(this.m_iP + 1);
}
else if(this.m_iP == this.m_xml.indexOf("&", this.m_iP)) {
return this._parseEntity (this.m_iP + 1);
}
else{
return this._parseText (this.m_iP);
}
}
XMLP.prototype._parseAttribute = function(iB, iE) {
var iNB, iNE, iEq, iVB, iVE;
var cQuote, strN, strV;
this.m_cAlt = "";
iNB = SAXStrings.indexOfNonWhitespace(this.m_xml, iB, iE);
if((iNB == -1) ||(iNB >= iE)) {
return iNB;
}
iEq = this.m_xml.indexOf("=", iNB);
if((iEq == -1) || (iEq > iE)) {
return this._setErr(XMLP.ERR_ATT_VALUES);
}
iNE = SAXStrings.lastIndexOfNonWhitespace(this.m_xml, iNB, iEq);
iVB = SAXStrings.indexOfNonWhitespace(this.m_xml, iEq + 1, iE);
if((iVB == -1) ||(iVB > iE)) {
return this._setErr(XMLP.ERR_ATT_VALUES);
}
cQuote = this.m_xml.charAt(iVB);
if(SAXStrings.QUOTES.indexOf(cQuote) == -1) {
return this._setErr(XMLP.ERR_ATT_VALUES);
}
iVE = this.m_xml.indexOf(cQuote, iVB + 1);
if((iVE == -1) ||(iVE > iE)) {
return this._setErr(XMLP.ERR_ATT_VALUES);
}
strN = this.m_xml.substring(iNB, iNE + 1);
strV = this.m_xml.substring(iVB + 1, iVE);
if(strN.indexOf("<") != -1) {
return this._setErr(XMLP.ERR_ATT_LT_NAME);
}
if(strV.indexOf("<") != -1) {
return this._setErr(XMLP.ERR_ATT_LT_VALUE);
}
strV = SAXStrings.replace(strV, null, null, "\n", " ");
strV = SAXStrings.replace(strV, null, null, "\t", " ");
iRet = this._replaceEntities(strV);
if(iRet == XMLP._ERROR) {
return iRet;
}
strV = this.m_cAlt;
if(this._findAttributeIndex(strN) == -1) {
this._addAttribute(strN, strV);
}
else {
return this._setErr(XMLP.ERR_ATT_DUP);
}
this.m_iP = iVE + 2;
return XMLP._ATT;
}
XMLP.prototype._parseCDATA = function(iB) {
var iE = this.m_xml.indexOf("]]>", iB);
if (iE == -1) {
return this._setErr(XMLP.ERR_CLOSE_CDATA);
}
this._setContent(XMLP._CONT_XML, iB, iE);
this.m_iP = iE + 3;
return XMLP._CDATA;
}
XMLP.prototype._parseComment = function(iB) {
var iE = this.m_xml.indexOf("-" + "->", iB);
if (iE == -1) {
return this._setErr(XMLP.ERR_CLOSE_COMMENT);
}
this._setContent(XMLP._CONT_XML, iB, iE);
this.m_iP = iE + 3;
return XMLP._COMMENT;
}
XMLP.prototype._parseDTD = function(iB) {
var iE, strClose, iInt, iLast;
iE = this.m_xml.indexOf(">", iB);
if(iE == -1) {
return this._setErr(XMLP.ERR_CLOSE_DTD);
}
iInt = this.m_xml.indexOf("[", iB);
strClose = ((iInt != -1) && (iInt < iE)) ? "]>" : ">";
while(true) {
if(iE == iLast) {
return this._setErr(XMLP.ERR_INFINITELOOP);
}
iLast = iE;
iE = this.m_xml.indexOf(strClose, iB);
if(iE == -1) {
return this._setErr(XMLP.ERR_CLOSE_DTD);
}
if (this.m_xml.substring(iE - 1, iE + 2) != "]]>") {
break;
}
}
this.m_iP = iE + strClose.length;
return XMLP._DTD;
}
XMLP.prototype._parseElement = function(iB) {
var iE, iDE, iNE, iRet;
var iType, strN, iLast;
iDE = iE = this.m_xml.indexOf(">", iB);
if(iE == -1) {
return this._setErr(XMLP.ERR_CLOSE_ELM);
}
if(this.m_xml.charAt(iB) == "/") {
iType = XMLP._ELM_E;
iB++;
} else {
iType = XMLP._ELM_B;
}
if(this.m_xml.charAt(iE - 1) == "/") {
if(iType == XMLP._ELM_E) {
return this._setErr(XMLP.ERR_ELM_EMPTY);
}
iType = XMLP._ELM_EMP;
iDE--;
}
iDE = SAXStrings.lastIndexOfNonWhitespace(this.m_xml, iB, iDE);
if (iE - iB != 1 ) {
if(SAXStrings.indexOfNonWhitespace(this.m_xml, iB, iDE) != iB) {
return this._setErr(XMLP.ERR_ELM_NAME);
}
}
this._clearAttributes();
iNE = SAXStrings.indexOfWhitespace(this.m_xml, iB, iDE);
if(iNE == -1) {
iNE = iDE + 1;
}
else {
this.m_iP = iNE;
while(this.m_iP < iDE) {
if(this.m_iP == iLast) return this._setErr(XMLP.ERR_INFINITELOOP);
iLast = this.m_iP;
iRet = this._parseAttribute(this.m_iP, iDE);
if(iRet == XMLP._ERROR) return iRet;
}
}
strN = this.m_xml.substring(iB, iNE);
if(strN.indexOf("<") != -1) {
return this._setErr(XMLP.ERR_ELM_LT_NAME);
}
this.m_name = strN;
this.m_iP = iE + 1;
return iType;
}
XMLP.prototype._parseEntity = function(iB) {
var iE = this.m_xml.indexOf(";", iB);
if(iE == -1) {
return this._setErr(XMLP.ERR_CLOSE_ENTITY);
}
this.m_iP = iE + 1;
return this._replaceEntity(this.m_xml, iB, iE);
}
XMLP.prototype._parsePI = function(iB) {
var iE, iTB, iTE, iCB, iCE;
iE = this.m_xml.indexOf("?>", iB);
if(iE== -1) {
return this._setErr(XMLP.ERR_CLOSE_PI);
}
iTB = SAXStrings.indexOfNonWhitespace(this.m_xml, iB, iE);
if(iTB == -1) {
return this._setErr(XMLP.ERR_PI_TARGET);
}
iTE = SAXStrings.indexOfWhitespace(this.m_xml, iTB, iE);
if(iTE== -1) {
iTE = iE;
}
iCB = SAXStrings.indexOfNonWhitespace(this.m_xml, iTE, iE);
if(iCB == -1) {
iCB = iE;
}
iCE = SAXStrings.lastIndexOfNonWhitespace(this.m_xml, iCB, iE);
if(iCE== -1) {
iCE = iE - 1;
}
this.m_name = this.m_xml.substring(iTB, iTE);
this._setContent(XMLP._CONT_XML, iCB, iCE + 1);
this.m_iP = iE + 2;
return XMLP._PI;
}
XMLP.prototype._parseText = function(iB) {
var iE, iEE;
iE = this.m_xml.indexOf("<", iB);
if(iE == -1) {
iE = this.m_xml.length;
}
iEE = this.m_xml.indexOf("&", iB);
if((iEE != -1) && (iEE <= iE)) {
iE = iEE;
}
this._setContent(XMLP._CONT_XML, iB, iE);
this.m_iP = iE;
return XMLP._TEXT;
}
XMLP.prototype._replaceEntities = function(strD, iB, iE) {
if(SAXStrings.isEmpty(strD)) return "";
iB = iB || 0;
iE = iE || strD.length;
var iEB, iEE, strRet = "";
iEB = strD.indexOf("&", iB);
iEE = iB;
while((iEB > 0) && (iEB < iE)) {
strRet += strD.substring(iEE, iEB);
iEE = strD.indexOf(";", iEB) + 1;
if((iEE == 0) || (iEE > iE)) {
return this._setErr(XMLP.ERR_CLOSE_ENTITY);
}
iRet = this._replaceEntity(strD, iEB + 1, iEE - 1);
if(iRet == XMLP._ERROR) {
return iRet;
}
strRet += this.m_cAlt;
iEB = strD.indexOf("&", iEE);
}
if(iEE != iE) {
strRet += strD.substring(iEE, iE);
}
this._setContent(XMLP._CONT_ALT, strRet);
return XMLP._ENTITY;
}
XMLP.prototype._replaceEntity = function(strD, iB, iE) {
if(SAXStrings.isEmpty(strD)) return -1;
iB = iB || 0;
iE = iE || strD.length;
switch(strD.substring(iB, iE)) {
case "amp": strEnt = "&"; break;
case "lt": strEnt = "<"; break;
case "gt": strEnt = ">"; break;
case "apos": strEnt = "'"; break;
case "quot": strEnt = "\""; break;
default:
if(strD.charAt(iB) == "#") {
strEnt = String.fromCharCode(parseInt(strD.substring(iB + 1, iE)));
} else {
return this._setErr(XMLP.ERR_ENTITY_UNKNOWN);
}
break;
}
this._setContent(XMLP._CONT_ALT, strEnt);
return XMLP._ENTITY;
}
XMLP.prototype._setContent = function(iSrc) {
var args = arguments;
if(XMLP._CONT_XML == iSrc) {
this.m_cAlt = null;
this.m_cB = args[1];
this.m_cE = args[2];
} else {
this.m_cAlt = args[1];
this.m_cB = 0;
this.m_cE = args[1].length;
}
this.m_cSrc = iSrc;
}
XMLP.prototype._setErr = function(iErr) {
var strErr = XMLP._errs[iErr];
this.m_cAlt = strErr;
this.m_cB = 0;
this.m_cE = strErr.length;
this.m_cSrc = XMLP._CONT_ALT;
return XMLP._ERROR;
}
SAXDriver = function() {
this.m_hndDoc = null;
this.m_hndErr = null;
this.m_hndLex = null;
}
SAXDriver.DOC_B = 1;
SAXDriver.DOC_E = 2;
SAXDriver.ELM_B = 3;
SAXDriver.ELM_E = 4;
SAXDriver.CHARS = 5;
SAXDriver.PI= 6;
SAXDriver.CD_B= 7;
SAXDriver.CD_E= 8;
SAXDriver.CMNT= 9;
SAXDriver.DTD_B = 10;
SAXDriver.DTD_E = 11;
SAXDriver.prototype.parse = function(strD) {
var parser = new XMLP(strD);
if(this.m_hndDoc && this.m_hndDoc.setDocumentLocator) {
this.m_hndDoc.setDocumentLocator(this);
}
this.m_parser = parser;
this.m_bErr = false;
if(!this.m_bErr) {
this._fireEvent(SAXDriver.DOC_B);
}
this._parseLoop();
if(!this.m_bErr) {
this._fireEvent(SAXDriver.DOC_E);
}
this.m_xml = null;
this.m_iP = 0;
}
SAXDriver.prototype.setDocumentHandler = function(hnd) {
this.m_hndDoc = hnd;
}
SAXDriver.prototype.setErrorHandler = function(hnd) {
this.m_hndErr = hnd;
}
SAXDriver.prototype.setLexicalHandler = function(hnd) {
this.m_hndLex = hnd;
}
SAXDriver.prototype.getColumnNumber = function() {
return this.m_parser.getColumnNumber();
}
SAXDriver.prototype.getLineNumber = function() {
return this.m_parser.getLineNumber();
}
SAXDriver.prototype.getMessage = function() {
return this.m_strErrMsg;
}
SAXDriver.prototype.getPublicId = function() {
return null;
}
SAXDriver.prototype.getSystemId = function() {
return null;
}
SAXDriver.prototype.getLength = function() {
return this.m_parser.getAttributeCount();
}
SAXDriver.prototype.getName = function(index) {
return this.m_parser.getAttributeName(index);
}
SAXDriver.prototype.getValue = function(index) {
return this.m_parser.getAttributeValue(index);
}
SAXDriver.prototype.getValueByName = function(name) {
return this.m_parser.getAttributeValueByName(name);
}
SAXDriver.prototype._fireError = function(strMsg) {
this.m_strErrMsg = strMsg;
this.m_bErr = true;
if(this.m_hndErr && this.m_hndErr.fatalError) {
this.m_hndErr.fatalError(this);
}
}
SAXDriver.prototype._fireEvent = function(iEvt) {
var hnd, func, args = arguments, iLen = args.length - 1;
if(this.m_bErr) return;
if(SAXDriver.DOC_B == iEvt) {
func = "startDocument"; hnd = this.m_hndDoc;
}
else if (SAXDriver.DOC_E == iEvt) {
func = "endDocument"; hnd = this.m_hndDoc;
}
else if (SAXDriver.ELM_B == iEvt) {
func = "startElement"; hnd = this.m_hndDoc;
}
else if (SAXDriver.ELM_E == iEvt) {
func = "endElement"; hnd = this.m_hndDoc;
}
else if (SAXDriver.CHARS == iEvt) {
func = "characters"; hnd = this.m_hndDoc;
}
else if (SAXDriver.PI== iEvt) {
func = "processingInstruction"; hnd = this.m_hndDoc;
}
else if (SAXDriver.CD_B== iEvt) {
func = "startCDATA"; hnd = this.m_hndLex;
}
else if (SAXDriver.CD_E== iEvt) {
func = "endCDATA"; hnd = this.m_hndLex;
}
else if (SAXDriver.CMNT== iEvt) {
func = "comment"; hnd = this.m_hndLex;
}
if(hnd && hnd[func]) {
if(0 == iLen) {
hnd[func]();
}
else if (1 == iLen) {
hnd[func](args[1]);
}
else if (2 == iLen) {
hnd[func](args[1], args[2]);
}
else if (3 == iLen) {
hnd[func](args[1], args[2], args[3]);
}
}
}
SAXDriver.prototype._parseLoop = function(parser) {
var iEvent, parser;
parser = this.m_parser;
while(!this.m_bErr) {
iEvent = parser.next();
if(iEvent == XMLP._ELM_B) {
this._fireEvent(SAXDriver.ELM_B, parser.getName(), this);
}
else if(iEvent == XMLP._ELM_E) {
this._fireEvent(SAXDriver.ELM_E, parser.getName());
}
else if(iEvent == XMLP._ELM_EMP) {
this._fireEvent(SAXDriver.ELM_B, parser.getName(), this);
this._fireEvent(SAXDriver.ELM_E, parser.getName());
}
else if(iEvent == XMLP._TEXT) {
this._fireEvent(SAXDriver.CHARS, parser.getContent(), parser.getContentBegin(), parser.getContentEnd() - parser.getContentBegin());
}
else if(iEvent == XMLP._ENTITY) {
this._fireEvent(SAXDriver.CHARS, parser.getContent(), parser.getContentBegin(), parser.getContentEnd() - parser.getContentBegin());
}
else if(iEvent == XMLP._PI) {
this._fireEvent(SAXDriver.PI, parser.getName(), parser.getContent().substring(parser.getContentBegin(), parser.getContentEnd()));
}
else if(iEvent == XMLP._CDATA) {
this._fireEvent(SAXDriver.CD_B);
this._fireEvent(SAXDriver.CHARS, parser.getContent(), parser.getContentBegin(), parser.getContentEnd() - parser.getContentBegin());
this._fireEvent(SAXDriver.CD_E);
}
else if(iEvent == XMLP._COMMENT) {
this._fireEvent(SAXDriver.CMNT, parser.getContent(), parser.getContentBegin(), parser.getContentEnd() - parser.getContentBegin());
}
else if(iEvent == XMLP._DTD) {
}
else if(iEvent == XMLP._ERROR) {
this._fireError(parser.getContent());
}
else if(iEvent == XMLP._NONE) {
return;
}
}
}
SAXStrings = function() {
}
SAXStrings.WHITESPACE = " \t\n\r";
SAXStrings.QUOTES = "\"'";
SAXStrings.getColumnNumber = function(strD, iP) {
if(SAXStrings.isEmpty(strD)) {
return -1;
}
iP = iP || strD.length;
var arrD = strD.substring(0, iP).split("\n");
var strLine = arrD[arrD.length - 1];
arrD.length--;
var iLinePos = arrD.join("\n").length;
return iP - iLinePos;
}
SAXStrings.getLineNumber = function(strD, iP) {
if(SAXStrings.isEmpty(strD)) {
return -1;
}
iP = iP || strD.length;
return strD.substring(0, iP).split("\n").length
}
SAXStrings.indexOfNonWhitespace = function(strD, iB, iE) {
if(SAXStrings.isEmpty(strD)) {
return -1;
}
iB = iB || 0;
iE = iE || strD.length;
for(var i = iB; i < iE; i++){
if(SAXStrings.WHITESPACE.indexOf(strD.charAt(i)) == -1) {
return i;
}
}
return -1;
}
SAXStrings.indexOfWhitespace = function(strD, iB, iE) {
if(SAXStrings.isEmpty(strD)) {
return -1;
}
iB = iB || 0;
iE = iE || strD.length;
for(var i = iB; i < iE; i++) {
if(SAXStrings.WHITESPACE.indexOf(strD.charAt(i)) != -1) {
return i;
}
}
return -1;
}
SAXStrings.isEmpty = function(strD) {
return (strD == null) || (strD.length == 0);
}
SAXStrings.lastIndexOfNonWhitespace = function(strD, iB, iE) {
if(SAXStrings.isEmpty(strD)) {
return -1;
}
iB = iB || 0;
iE = iE || strD.length;
for(var i = iE - 1; i >= iB; i--){
if(SAXStrings.WHITESPACE.indexOf(strD.charAt(i)) == -1){
return i;
}
}
return -1;
}
SAXStrings.replace = function(strD, iB, iE, strF, strR) {
if(SAXStrings.isEmpty(strD)) {
return "";
}
iB = iB || 0;
iE = iE || strD.length;
return strD.substring(iB, iE).split(strF).join(strR);
}
Stack = function() {
this.m_arr = new Array();
}
Stack.prototype.clear = function() {
this.m_arr = new Array();
}
Stack.prototype.count = function() {
return this.m_arr.length;
}
Stack.prototype.destroy = function() {
this.m_arr = null;
}
Stack.prototype.peek = function() {
if(this.m_arr.length == 0) {
return null;
}
return this.m_arr[this.m_arr.length - 1];
}
Stack.prototype.pop = function() {
if(this.m_arr.length == 0) {
return null;
}
var o = this.m_arr[this.m_arr.length - 1];
this.m_arr.length--;
return o;
}
Stack.prototype.push = function(o) {
this.m_arr[this.m_arr.length] = o;
}
function isEmpty(str) {
return (str==null) || (str.length==0);
}
function trim(trimString, leftTrim, rightTrim) {
if (isEmpty(trimString)) {
return "";
}
if (leftTrim == null) {
leftTrim = true;
}
if (rightTrim == null) {
rightTrim = true;
}
var left=0;
var right=0;
var i=0;
var k=0;
if (leftTrim == true) {
while ((i<trimString.length) && (whitespace.indexOf(trimString.charAt(i++))!=-1)) {
left++;
}
}
if (rightTrim == true) {
k=trimString.length-1;
while((k>=left) && (whitespace.indexOf(trimString.charAt(k--))!=-1)) {
right++;
}
}
return trimString.substring(left, trimString.length - right);
}
function __escapeString(str) {
var escAmpRegEx = /&/g;
var escLtRegEx = /</g;
var escGtRegEx = />/g;
var quotRegEx = /"/g;
var aposRegEx = /'/g;
str = str.replace(escAmpRegEx, "&amp;");
str = str.replace(escLtRegEx, "&lt;");
str = str.replace(escGtRegEx, "&gt;");
str = str.replace(quotRegEx, "&quot;");
str = str.replace(aposRegEx, "&apos;");
return str;
}
function __unescapeString(str) {
var escAmpRegEx = /&amp;/g;
var escLtRegEx = /&lt;/g;
var escGtRegEx = /&gt;/g;
var quotRegEx = /&quot;/g;
var aposRegEx = /&apos;/g;
str = str.replace(escAmpRegEx, "&");
str = str.replace(escLtRegEx, "<");
str = str.replace(escGtRegEx, ">");
str = str.replace(quotRegEx, "\"");
str = str.replace(aposRegEx, "'");
return str;
}
function addClass(classCollectionStr, newClass) {
if (classCollectionStr) {
if (classCollectionStr.indexOf("|"+ newClass +"|") < 0) {
classCollectionStr += newClass + "|";
}
}
else {
classCollectionStr = "|"+ newClass + "|";
}
return classCollectionStr;
}
DOMException = function(code) {
this._class = addClass(this._class, "DOMException");
this.code = code;
};
DOMException.INDEX_SIZE_ERR= 1;
DOMException.DOMSTRING_SIZE_ERR= 2;
DOMException.HIERARCHY_REQUEST_ERR= 3;
DOMException.WRONG_DOCUMENT_ERR= 4;
DOMException.INVALID_CHARACTER_ERR= 5;
DOMException.NO_DATA_ALLOWED_ERR= 6;
DOMException.NO_MODIFICATION_ALLOWED_ERR= 7;
DOMException.NOT_FOUND_ERR = 8;
DOMException.NOT_SUPPORTED_ERR= 9;
DOMException.INUSE_ATTRIBUTE_ERR= 10;
DOMException.INVALID_STATE_ERR= 11;
DOMException.SYNTAX_ERR = 12;
DOMException.INVALID_MODIFICATION_ERR= 13;
DOMException.NAMESPACE_ERR = 14;
DOMException.INVALID_ACCESS_ERR= 15;
DOMImplementation = function() {
this._class = addClass(this._class, "DOMImplementation");
this._p = null;
this.preserveWhiteSpace = false;
this.namespaceAware = true;
this.errorChecking= true;
};
DOMImplementation.prototype.escapeString = function DOMNode__escapeString(str) {
return __escapeString(str);
};
DOMImplementation.prototype.unescapeString = function DOMNode__unescapeString(str) {
return __unescapeString(str);
};
DOMImplementation.prototype.hasFeature = function DOMImplementation_hasFeature(feature, version) {
var ret = false;
if (feature.toLowerCase() == "xml") {
ret = (!version || (version == "1.0") || (version == "2.0"));
}
else if (feature.toLowerCase() == "core") {
ret = (!version || (version == "2.0"));
}
return ret;
};
DOMImplementation.prototype.loadXML = function DOMImplementation_loadXML(xmlStr) {
var parser;
try {
parser = new XMLP(xmlStr);
}
catch (e) {
alert("Error Creating the SAX Parser. Did you include xmlsax.js or tinyxmlsax.js in your web page?\nThe SAX parser is needed to populate XML for <SCRIPT>'s W3C DOM Parser with data.");
}
var doc = new DOMDocument(this);
this._parseLoop(doc, parser);
doc._parseComplete = true;
return doc;
};
DOMImplementation.prototype.translateErrCode = function DOMImplementation_translateErrCode(code) {
var msg = "";
switch (code) {
case DOMException.INDEX_SIZE_ERR :
msg = "INDEX_SIZE_ERR: Index out of bounds";
break;
case DOMException.DOMSTRING_SIZE_ERR :
msg = "DOMSTRING_SIZE_ERR: The resulting string is too long to fit in a DOMString";
break;
case DOMException.HIERARCHY_REQUEST_ERR :
msg = "HIERARCHY_REQUEST_ERR: The Node can not be inserted at this location";
break;
case DOMException.WRONG_DOCUMENT_ERR :
msg = "WRONG_DOCUMENT_ERR: The source and the destination Documents are not the same";
break;
case DOMException.INVALID_CHARACTER_ERR :
msg = "INVALID_CHARACTER_ERR: The string contains an invalid character";
break;
case DOMException.NO_DATA_ALLOWED_ERR :
msg = "NO_DATA_ALLOWED_ERR: This Node / NodeList does not support data";
break;
case DOMException.NO_MODIFICATION_ALLOWED_ERR :
msg = "NO_MODIFICATION_ALLOWED_ERR: This object cannot be modified";
break;
case DOMException.NOT_FOUND_ERR :
msg = "NOT_FOUND_ERR: The item cannot be found";
break;
case DOMException.NOT_SUPPORTED_ERR :
msg = "NOT_SUPPORTED_ERR: This implementation does not support function";
break;
case DOMException.INUSE_ATTRIBUTE_ERR :
msg = "INUSE_ATTRIBUTE_ERR: The Attribute has already been assigned to another Element";
break;
case DOMException.INVALID_STATE_ERR :
msg = "INVALID_STATE_ERR: The object is no longer usable";
break;
case DOMException.SYNTAX_ERR :
msg = "SYNTAX_ERR: Syntax error";
break;
case DOMException.INVALID_MODIFICATION_ERR :
msg = "INVALID_MODIFICATION_ERR: Cannot change the type of the object";
break;
case DOMException.NAMESPACE_ERR :
msg = "NAMESPACE_ERR: The namespace declaration is incorrect";
break;
case DOMException.INVALID_ACCESS_ERR :
msg = "INVALID_ACCESS_ERR: The object does not support this function";
break;
default :
msg = "UNKNOWN: Unknown Exception Code ("+ code +")";
}
return msg;
}
DOMImplementation.prototype._parseLoop = function DOMImplementation__parseLoop(doc, p) {
var iEvt, iNode, iAttr, strName;
iNodeParent = doc;
var el_close_count = 0;
var entitiesList = new Array();
var textNodesList = new Array();
if (this.namespaceAware) {
var iNS = doc.createNamespace("");
iNS.setValue("http://www.w3.org/2000/xmlns/");
doc._namespaces.setNamedItem(iNS);
}
while(true) {
iEvt = p.next();
if (iEvt == XMLP._ELM_B) {
var pName = p.getName();
pName = trim(pName, true, true);
if (!this.namespaceAware) {
iNode = doc.createElement(p.getName());
for(var i = 0; i < p.getAttributeCount(); i++) {
strName = p.getAttributeName(i);
iAttr = iNode.getAttributeNode(strName);
if(!iAttr) {
iAttr = doc.createAttribute(strName);
}
iAttr.setValue(p.getAttributeValue(i));
iNode.setAttributeNode(iAttr);
}
}
else {
iNode = doc.createElementNS("", p.getName());
iNode._namespaces = iNodeParent._namespaces._cloneNodes(iNode);
for(var i = 0; i < p.getAttributeCount(); i++) {
strName = p.getAttributeName(i);
if (this._isNamespaceDeclaration(strName)) {
var namespaceDec = this._parseNSName(strName);
if (strName != "xmlns") {
iNS = doc.createNamespace(strName);
}
else {
iNS = doc.createNamespace("");
}
iNS.setValue(p.getAttributeValue(i));
iNode._namespaces.setNamedItem(iNS);
}
else {
iAttr = iNode.getAttributeNode(strName);
if(!iAttr) {
iAttr = doc.createAttributeNS("", strName);
}
iAttr.setValue(p.getAttributeValue(i));
iNode.setAttributeNodeNS(iAttr);
if (this._isIdDeclaration(strName)) {
iNode.id = p.getAttributeValue(i);
}
}
}
if (iNode._namespaces.getNamedItem(iNode.prefix)) {
iNode.namespaceURI = iNode._namespaces.getNamedItem(iNode.prefix).value;
}
for (var i = 0; i < iNode.attributes.length; i++) {
if (iNode.attributes.item(i).prefix != "") {
if (iNode._namespaces.getNamedItem(iNode.attributes.item(i).prefix)) {
iNode.attributes.item(i).namespaceURI = iNode._namespaces.getNamedItem(iNode.attributes.item(i).prefix).value;
}
}
}
}
if (iNodeParent.nodeType == DOMNode.DOCUMENT_NODE) {
iNodeParent.documentElement = iNode;
}
iNodeParent.appendChild(iNode);
iNodeParent = iNode;
}
else if(iEvt == XMLP._ELM_E) {
iNodeParent = iNodeParent.parentNode;
}
else if(iEvt == XMLP._ELM_EMP) {
pName = p.getName();
pName = trim(pName, true, true);
if (!this.namespaceAware) {
iNode = doc.createElement(pName);
for(var i = 0; i < p.getAttributeCount(); i++) {
strName = p.getAttributeName(i);
iAttr = iNode.getAttributeNode(strName);
if(!iAttr) {
iAttr = doc.createAttribute(strName);
}
iAttr.setValue(p.getAttributeValue(i));
iNode.setAttributeNode(iAttr);
}
}
else {
iNode = doc.createElementNS("", p.getName());
iNode._namespaces = iNodeParent._namespaces._cloneNodes(iNode);
for(var i = 0; i < p.getAttributeCount(); i++) {
strName = p.getAttributeName(i);
if (this._isNamespaceDeclaration(strName)) {
var namespaceDec = this._parseNSName(strName);
if (strName != "xmlns") {
iNS = doc.createNamespace(strName);
}
else {
iNS = doc.createNamespace("");
}
iNS.setValue(p.getAttributeValue(i));
iNode._namespaces.setNamedItem(iNS);
}
else {
iAttr = iNode.getAttributeNode(strName);
if(!iAttr) {
iAttr = doc.createAttributeNS("", strName);
}
iAttr.setValue(p.getAttributeValue(i));
iNode.setAttributeNodeNS(iAttr);
if (this._isIdDeclaration(strName)) {
iNode.id = p.getAttributeValue(i);
}
}
}
if (iNode._namespaces.getNamedItem(iNode.prefix)) {
iNode.namespaceURI = iNode._namespaces.getNamedItem(iNode.prefix).value;
}
for (var i = 0; i < iNode.attributes.length; i++) {
if (iNode.attributes.item(i).prefix != "") {
if (iNode._namespaces.getNamedItem(iNode.attributes.item(i).prefix)) {
iNode.attributes.item(i).namespaceURI = iNode._namespaces.getNamedItem(iNode.attributes.item(i).prefix).value;
}
}
}
}
if (iNodeParent.nodeType == DOMNode.DOCUMENT_NODE) {
iNodeParent.documentElement = iNode;
}
iNodeParent.appendChild(iNode);
}
else if(iEvt == XMLP._TEXT || iEvt == XMLP._ENTITY) {
var pContent = p.getContent().substring(p.getContentBegin(), p.getContentEnd());
if (!this.preserveWhiteSpace ) {
if (trim(pContent, true, true) == "") {
pContent = "";
}
}
if (pContent.length > 0) {
var textNode = doc.createTextNode(pContent);
iNodeParent.appendChild(textNode);
if (iEvt == XMLP._ENTITY) {
entitiesList[entitiesList.length] = textNode;
}
else {
textNodesList[textNodesList.length] = textNode;
}
}
}
else if(iEvt == XMLP._PI) {
iNodeParent.appendChild(doc.createProcessingInstruction(p.getName(), p.getContent().substring(p.getContentBegin(), p.getContentEnd())));
}
else if(iEvt == XMLP._CDATA) {
pContent = p.getContent().substring(p.getContentBegin(), p.getContentEnd());
if (!this.preserveWhiteSpace) {
pContent = trim(pContent, true, true);
pContent.replace(/ +/g, ' ');
}
if (pContent.length > 0) {
iNodeParent.appendChild(doc.createCDATASection(pContent));
}
}
else if(iEvt == XMLP._COMMENT) {
var pContent = p.getContent().substring(p.getContentBegin(), p.getContentEnd());
if (!this.preserveWhiteSpace) {
pContent = trim(pContent, true, true);
pContent.replace(/ +/g, ' ');
}
if (pContent.length > 0) {
iNodeParent.appendChild(doc.createComment(pContent));
}
}
else if(iEvt == XMLP._DTD) {
}
else if(iEvt == XMLP._ERROR) {
throw(new DOMException(DOMException.SYNTAX_ERR));
}
else if(iEvt == XMLP._NONE) {
if (iNodeParent == doc) {
break;
}
else {
throw(new DOMException(DOMException.SYNTAX_ERR));
}
}
}
var intCount = entitiesList.length;
for (intLoop = 0; intLoop < intCount; intLoop++) {
var entity = entitiesList[intLoop];
var parentNode = entity.getParentNode();
if (parentNode) {
parentNode.normalize();
if(!this.preserveWhiteSpace) {
var children = parentNode.getChildNodes();
var intCount2 = children.getLength();
for ( intLoop2 = 0; intLoop2 < intCount2; intLoop2++) {
var child = children.item(intLoop2);
if (child.getNodeType() == DOMNode.TEXT_NODE) {
var childData = child.getData();
childData = trim(childData, true, true);
childData.replace(/ +/g, ' ');
child.setData(childData);
}
}
}
}
}
if (!this.preserveWhiteSpace) {
var intCount = textNodesList.length;
for (intLoop = 0; intLoop < intCount; intLoop++) {
var node = textNodesList[intLoop];
if (node.getParentNode() != null) {
var nodeData = node.getData();
nodeData = trim(nodeData, true, true);
nodeData.replace(/ +/g, ' ');
node.setData(nodeData);
}
}
}
};
DOMImplementation.prototype._isNamespaceDeclaration = function DOMImplementation__isNamespaceDeclaration(attributeName) {
return (attributeName.indexOf('xmlns') > -1);
}
DOMImplementation.prototype._isIdDeclaration = function DOMImplementation__isIdDeclaration(attributeName) {
return (attributeName.toLowerCase() == 'id');
}
DOMImplementation.prototype._isValidName = function DOMImplementation__isValidName(name) {
return name.match(re_validName);
}
re_validName = /^[a-zA-Z_:][a-zA-Z0-9\.\-_:]*$/;
DOMImplementation.prototype._isValidString = function DOMImplementation__isValidString(name) {
return (name.search(re_invalidStringChars) < 0);
}
re_invalidStringChars = /\x01|\x02|\x03|\x04|\x05|\x06|\x07|\x08|\x0B|\x0C|\x0E|\x0F|\x10|\x11|\x12|\x13|\x14|\x15|\x16|\x17|\x18|\x19|\x1A|\x1B|\x1C|\x1D|\x1E|\x1F|\x7F/
DOMImplementation.prototype._parseNSName = function DOMImplementation__parseNSName(qualifiedName) {
var resultNSName = new Object();
resultNSName.prefix= qualifiedName;
resultNSName.namespaceName= "";
delimPos = qualifiedName.indexOf(':');
if (delimPos > -1) {
resultNSName.prefix = qualifiedName.substring(0, delimPos);
resultNSName.namespaceName = qualifiedName.substring(delimPos +1, qualifiedName.length);
}
return resultNSName;
}
DOMImplementation.prototype._parseQName = function DOMImplementation__parseQName(qualifiedName) {
var resultQName = new Object();
resultQName.localName = qualifiedName;
resultQName.prefix= "";
delimPos = qualifiedName.indexOf(':');
if (delimPos > -1) {
resultQName.prefix= qualifiedName.substring(0, delimPos);
resultQName.localName = qualifiedName.substring(delimPos +1, qualifiedName.length);
}
return resultQName;
}
DOMNodeList = function(ownerDocument, parentNode) {
this._class = addClass(this._class, "DOMNodeList");
this._nodes = new Array();
this.length = 0;
this.parentNode = parentNode;
this.ownerDocument = ownerDocument;
this._readonly = false;
};
DOMNodeList.prototype.getLength = function DOMNodeList_getLength() {
return this.length;
};
DOMNodeList.prototype.item = function DOMNodeList_item(index) {
var ret = null;
if ((index >= 0) && (index < this._nodes.length)) {
ret = this._nodes[index];
}
return ret;
};
DOMNodeList.prototype._findItemIndex = function DOMNodeList__findItemIndex(id) {
var ret = -1;
if (id > -1) {
for (var i=0; i<this._nodes.length; i++) {
if (this._nodes[i]._id == id) {
ret = i;
break;
}
}
}
return ret;
};
DOMNodeList.prototype._insertBefore = function DOMNodeList__insertBefore(newChild, refChildIndex) {
if ((refChildIndex >= 0) && (refChildIndex < this._nodes.length)) {
var tmpArr = new Array();
tmpArr = this._nodes.slice(0, refChildIndex);
if (newChild.nodeType == DOMNode.DOCUMENT_FRAGMENT_NODE) {
tmpArr = tmpArr.concat(newChild.childNodes._nodes);
}
else {
tmpArr[tmpArr.length] = newChild;
}
this._nodes = tmpArr.concat(this._nodes.slice(refChildIndex));
this.length = this._nodes.length;
}
};
DOMNodeList.prototype._replaceChild = function DOMNodeList__replaceChild(newChild, refChildIndex) {
var ret = null;
if ((refChildIndex >= 0) && (refChildIndex < this._nodes.length)) {
ret = this._nodes[refChildIndex];
if (newChild.nodeType == DOMNode.DOCUMENT_FRAGMENT_NODE) {
var tmpArr = new Array();
tmpArr = this._nodes.slice(0, refChildIndex);
tmpArr = tmpArr.concat(newChild.childNodes._nodes);
this._nodes = tmpArr.concat(this._nodes.slice(refChildIndex + 1));
}
else {
this._nodes[refChildIndex] = newChild;
}
}
return ret;
};
DOMNodeList.prototype._removeChild = function DOMNodeList__removeChild(refChildIndex) {
var ret = null;
if (refChildIndex > -1) {
ret = this._nodes[refChildIndex];
var tmpArr = new Array();
tmpArr = this._nodes.slice(0, refChildIndex);
this._nodes = tmpArr.concat(this._nodes.slice(refChildIndex +1));
this.length = this._nodes.length;
}
return ret;
};
DOMNodeList.prototype._appendChild = function DOMNodeList__appendChild(newChild) {
if (newChild.nodeType == DOMNode.DOCUMENT_FRAGMENT_NODE) {
this._nodes = this._nodes.concat(newChild.childNodes._nodes);
}
else {
this._nodes[this._nodes.length] = newChild;
}
this.length = this._nodes.length;
};
DOMNodeList.prototype._cloneNodes = function DOMNodeList__cloneNodes(deep, parentNode) {
var cloneNodeList = new DOMNodeList(this.ownerDocument, parentNode);
for (var i=0; i < this._nodes.length; i++) {
cloneNodeList._appendChild(this._nodes[i].cloneNode(deep));
}
return cloneNodeList;
};
DOMNodeList.prototype.toString = function DOMNodeList_toString() {
var ret = "";
for (var i=0; i < this.length; i++) {
ret += this._nodes[i].toString();
}
return ret;
};
DOMNamedNodeMap = function(ownerDocument, parentNode) {
this._class = addClass(this._class, "DOMNamedNodeMap");
this.DOMNodeList = DOMNodeList;
this.DOMNodeList(ownerDocument, parentNode);
};
DOMNamedNodeMap.prototype = new DOMNodeList;
DOMNamedNodeMap.prototype.getNamedItem = function DOMNamedNodeMap_getNamedItem(name) {
var ret = null;
var itemIndex = this._findNamedItemIndex(name);
if (itemIndex > -1) {
ret = this._nodes[itemIndex];
}
return ret;
};
DOMNamedNodeMap.prototype.setNamedItem = function DOMNamedNodeMap_setNamedItem(arg) {
if (this.ownerDocument.implementation.errorChecking) {
if (this.ownerDocument != arg.ownerDocument) {
throw(new DOMException(DOMException.WRONG_DOCUMENT_ERR));
}
if (this._readonly || (this.parentNode && this.parentNode._readonly)) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if (arg.ownerElement && (arg.ownerElement != this.parentNode)) {
throw(new DOMException(DOMException.INUSE_ATTRIBUTE_ERR));
}
}
var itemIndex = this._findNamedItemIndex(arg.name);
var ret = null;
if (itemIndex > -1) {
ret = this._nodes[itemIndex];
if (this.ownerDocument.implementation.errorChecking && ret._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
else {
this._nodes[itemIndex] = arg;
}
}
else {
this._nodes[this.length] = arg;
}
this.length = this._nodes.length;
arg.ownerElement = this.parentNode;
return ret;
};
DOMNamedNodeMap.prototype.removeNamedItem = function DOMNamedNodeMap_removeNamedItem(name) {
var ret = null;
if (this.ownerDocument.implementation.errorChecking && (this._readonly || (this.parentNode && this.parentNode._readonly))) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
var itemIndex = this._findNamedItemIndex(name);
if (this.ownerDocument.implementation.errorChecking && (itemIndex < 0)) {
throw(new DOMException(DOMException.NOT_FOUND_ERR));
}
var oldNode = this._nodes[itemIndex];
if (this.ownerDocument.implementation.errorChecking && oldNode._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
return this._removeChild(itemIndex);
};
DOMNamedNodeMap.prototype.getNamedItemNS = function DOMNamedNodeMap_getNamedItemNS(namespaceURI, localName) {
var ret = null;
var itemIndex = this._findNamedItemNSIndex(namespaceURI, localName);
if (itemIndex > -1) {
ret = this._nodes[itemIndex];
}
return ret;
};
DOMNamedNodeMap.prototype.setNamedItemNS = function DOMNamedNodeMap_setNamedItemNS(arg) {
if (this.ownerDocument.implementation.errorChecking) {
if (this._readonly || (this.parentNode && this.parentNode._readonly)) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if (this.ownerDocument != arg.ownerDocument) {
throw(new DOMException(DOMException.WRONG_DOCUMENT_ERR));
}
if (arg.ownerElement && (arg.ownerElement != this.parentNode)) {
throw(new DOMException(DOMException.INUSE_ATTRIBUTE_ERR));
}
}
var itemIndex = this._findNamedItemNSIndex(arg.namespaceURI, arg.localName);
var ret = null;
if (itemIndex > -1) {
ret = this._nodes[itemIndex];
if (this.ownerDocument.implementation.errorChecking && ret._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
else {
this._nodes[itemIndex] = arg;
}
}
else {
this._nodes[this.length] = arg;
}
this.length = this._nodes.length;
arg.ownerElement = this.parentNode;
return ret;
};
DOMNamedNodeMap.prototype.removeNamedItemNS = function DOMNamedNodeMap_removeNamedItemNS(namespaceURI, localName) {
var ret = null;
if (this.ownerDocument.implementation.errorChecking && (this._readonly || (this.parentNode && this.parentNode._readonly))) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
var itemIndex = this._findNamedItemNSIndex(namespaceURI, localName);
if (this.ownerDocument.implementation.errorChecking && (itemIndex < 0)) {
throw(new DOMException(DOMException.NOT_FOUND_ERR));
}
var oldNode = this._nodes[itemIndex];
if (this.ownerDocument.implementation.errorChecking && oldNode._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
return this._removeChild(itemIndex);
};
DOMNamedNodeMap.prototype._findNamedItemIndex = function DOMNamedNodeMap__findNamedItemIndex(name) {
var ret = -1;
for (var i=0; i<this._nodes.length; i++) {
if (this._nodes[i].name == name) {
ret = i;
break;
}
}
return ret;
};
DOMNamedNodeMap.prototype._findNamedItemNSIndex = function DOMNamedNodeMap__findNamedItemNSIndex(namespaceURI, localName) {
var ret = -1;
if (localName) {
for (var i=0; i<this._nodes.length; i++) {
if ((this._nodes[i].namespaceURI == namespaceURI) && (this._nodes[i].localName == localName)) {
ret = i;
break;
}
}
}
return ret;
};
DOMNamedNodeMap.prototype._hasAttribute = function DOMNamedNodeMap__hasAttribute(name) {
var ret = false;
var itemIndex = this._findNamedItemIndex(name);
if (itemIndex > -1) {
ret = true;
}
return ret;
}
DOMNamedNodeMap.prototype._hasAttributeNS = function DOMNamedNodeMap__hasAttributeNS(namespaceURI, localName) {
var ret = false;
var itemIndex = this._findNamedItemNSIndex(namespaceURI, localName);
if (itemIndex > -1) {
ret = true;
}
return ret;
}
DOMNamedNodeMap.prototype._cloneNodes = function DOMNamedNodeMap__cloneNodes(parentNode) {
var cloneNamedNodeMap = new DOMNamedNodeMap(this.ownerDocument, parentNode);
for (var i=0; i < this._nodes.length; i++) {
cloneNamedNodeMap._appendChild(this._nodes[i].cloneNode(false));
}
return cloneNamedNodeMap;
};
DOMNamedNodeMap.prototype.toString = function DOMNamedNodeMap_toString() {
var ret = "";
for (var i=0; i < this.length -1; i++) {
ret += this._nodes[i].toString() +" ";
}
if (this.length > 0) {
ret += this._nodes[this.length -1].toString();
}
return ret;
};
DOMNamespaceNodeMap = function(ownerDocument, parentNode) {
this._class = addClass(this._class, "DOMNamespaceNodeMap");
this.DOMNamedNodeMap = DOMNamedNodeMap;
this.DOMNamedNodeMap(ownerDocument, parentNode);
};
DOMNamespaceNodeMap.prototype = new DOMNamedNodeMap;
DOMNamespaceNodeMap.prototype._findNamedItemIndex = function DOMNamespaceNodeMap__findNamedItemIndex(localName) {
var ret = -1;
for (var i=0; i<this._nodes.length; i++) {
if (this._nodes[i].localName == localName) {
ret = i;
break;
}
}
return ret;
};
DOMNamespaceNodeMap.prototype._cloneNodes = function DOMNamespaceNodeMap__cloneNodes(parentNode) {
var cloneNamespaceNodeMap = new DOMNamespaceNodeMap(this.ownerDocument, parentNode);
for (var i=0; i < this._nodes.length; i++) {
cloneNamespaceNodeMap._appendChild(this._nodes[i].cloneNode(false));
}
return cloneNamespaceNodeMap;
};
DOMNamespaceNodeMap.prototype.toString = function DOMNamespaceNodeMap_toString() {
var ret = "";
for (var ind = 0; ind < this._nodes.length; ind++) {
var ns = null;
try {
var ns = this.parentNode.parentNode._namespaces.getNamedItem(this._nodes[ind].localName);
}
catch (e) {
break;
}
if (!(ns && (""+ ns.nodeValue == ""+ this._nodes[ind].nodeValue))) {
ret += this._nodes[ind].toString() +" ";
}
}
return ret;
};
DOMNode = function(ownerDocument) {
this._class = addClass(this._class, "DOMNode");
if (ownerDocument) {
this._id = ownerDocument._genId();
}
this.namespaceURI = "";
this.prefix= "";
this.localName= "";
this.nodeName = "";
this.nodeValue = "";
this.nodeType = 0;
this.parentNode= null;
this.childNodes= new DOMNodeList(ownerDocument, this);
this.firstChild= null;
this.lastChild= null;
this.previousSibling = null;
this.nextSibling = null;
this.attributes = new DOMNamedNodeMap(ownerDocument, this);
this.ownerDocument= ownerDocument;
this._namespaces = new DOMNamespaceNodeMap(ownerDocument, this);
this._readonly = false;
};
DOMNode.ELEMENT_NODE= 1;
DOMNode.ATTRIBUTE_NODE= 2;
DOMNode.TEXT_NODE= 3;
DOMNode.CDATA_SECTION_NODE= 4;
DOMNode.ENTITY_REFERENCE_NODE= 5;
DOMNode.ENTITY_NODE= 6;
DOMNode.PROCESSING_INSTRUCTION_NODE = 7;
DOMNode.COMMENT_NODE= 8;
DOMNode.DOCUMENT_NODE = 9;
DOMNode.DOCUMENT_TYPE_NODE= 10;
DOMNode.DOCUMENT_FRAGMENT_NODE= 11;
DOMNode.NOTATION_NODE = 12;
DOMNode.NAMESPACE_NODE= 13;
DOMNode.prototype.hasAttributes = function DOMNode_hasAttributes() {
if (this.attributes.length == 0) {
return false;
}
else {
return true;
}
};
DOMNode.prototype.getNodeName = function DOMNode_getNodeName() {
return this.nodeName;
};
DOMNode.prototype.getNodeValue = function DOMNode_getNodeValue() {
return this.nodeValue;
};
DOMNode.prototype.setNodeValue = function DOMNode_setNodeValue(nodeValue) {
if (this.ownerDocument.implementation.errorChecking && this._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
this.nodeValue = nodeValue;
};
DOMNode.prototype.getNodeType = function DOMNode_getNodeType() {
return this.nodeType;
};
DOMNode.prototype.getParentNode = function DOMNode_getParentNode() {
return this.parentNode;
};
DOMNode.prototype.getChildNodes = function DOMNode_getChildNodes() {
return this.childNodes;
};
DOMNode.prototype.getFirstChild = function DOMNode_getFirstChild() {
return this.firstChild;
};
DOMNode.prototype.getLastChild = function DOMNode_getLastChild() {
return this.lastChild;
};
DOMNode.prototype.getPreviousSibling = function DOMNode_getPreviousSibling() {
return this.previousSibling;
};
DOMNode.prototype.getNextSibling = function DOMNode_getNextSibling() {
return this.nextSibling;
};
DOMNode.prototype.getAttributes = function DOMNode_getAttributes() {
return this.attributes;
};
DOMNode.prototype.getOwnerDocument = function DOMNode_getOwnerDocument() {
return this.ownerDocument;
};
DOMNode.prototype.getNamespaceURI = function DOMNode_getNamespaceURI() {
return this.namespaceURI;
};
DOMNode.prototype.getPrefix = function DOMNode_getPrefix() {
return this.prefix;
};
DOMNode.prototype.setPrefix = function DOMNode_setPrefix(prefix) {
if (this.ownerDocument.implementation.errorChecking) {
if (this._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if (!this.ownerDocument.implementation._isValidName(prefix)) {
throw(new DOMException(DOMException.INVALID_CHARACTER_ERR));
}
if (!this.ownerDocument._isValidNamespace(this.namespaceURI, prefix +":"+ this.localName)) {
throw(new DOMException(DOMException.NAMESPACE_ERR));
}
if ((prefix == "xmlns") && (this.namespaceURI != "http://www.w3.org/2000/xmlns/")) {
throw(new DOMException(DOMException.NAMESPACE_ERR));
}
if ((prefix == "") && (this.localName == "xmlns")) {
throw(new DOMException(DOMException.NAMESPACE_ERR));
}
}
this.prefix = prefix;
if (this.prefix != "") {
this.nodeName = this.prefix +":"+ this.localName;
}
else {
this.nodeName = this.localName;
}
};
DOMNode.prototype.getLocalName = function DOMNode_getLocalName() {
return this.localName;
};
DOMNode.prototype.insertBefore = function DOMNode_insertBefore(newChild, refChild) {
var prevNode;
if (this.ownerDocument.implementation.errorChecking) {
if (this._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if (this.ownerDocument != newChild.ownerDocument) {
throw(new DOMException(DOMException.WRONG_DOCUMENT_ERR));
}
if (this._isAncestor(newChild)) {
throw(new DOMException(DOMException.HIERARCHY_REQUEST_ERR));
}
}
if (refChild) {
var itemIndex = this.childNodes._findItemIndex(refChild._id);
if (this.ownerDocument.implementation.errorChecking && (itemIndex < 0)) {
throw(new DOMException(DOMException.NOT_FOUND_ERR));
}
var newChildParent = newChild.parentNode;
if (newChildParent) {
newChildParent.removeChild(newChild);
}
this.childNodes._insertBefore(newChild, this.childNodes._findItemIndex(refChild._id));
prevNode = refChild.previousSibling;
if (newChild.nodeType == DOMNode.DOCUMENT_FRAGMENT_NODE) {
if (newChild.childNodes._nodes.length > 0) {
for (var ind = 0; ind < newChild.childNodes._nodes.length; ind++) {
newChild.childNodes._nodes[ind].parentNode = this;
}
refChild.previousSibling = newChild.childNodes._nodes[newChild.childNodes._nodes.length-1];
}
}
else {
newChild.parentNode = this;
refChild.previousSibling = newChild;
}
}
else {
prevNode = this.lastChild;
this.appendChild(newChild);
}
if (newChild.nodeType == DOMNode.DOCUMENT_FRAGMENT_NODE) {
if (newChild.childNodes._nodes.length > 0) {
if (prevNode) {
prevNode.nextSibling = newChild.childNodes._nodes[0];
}
else {
this.firstChild = newChild.childNodes._nodes[0];
}
newChild.childNodes._nodes[0].previousSibling = prevNode;
newChild.childNodes._nodes[newChild.childNodes._nodes.length-1].nextSibling = refChild;
}
}
else {
if (prevNode) {
prevNode.nextSibling = newChild;
}
else {
this.firstChild = newChild;
}
newChild.previousSibling = prevNode;
newChild.nextSibling = refChild;
}
return newChild;
};
DOMNode.prototype.replaceChild = function DOMNode_replaceChild(newChild, oldChild) {
var ret = null;
if (this.ownerDocument.implementation.errorChecking) {
if (this._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if (this.ownerDocument != newChild.ownerDocument) {
throw(new DOMException(DOMException.WRONG_DOCUMENT_ERR));
}
if (this._isAncestor(newChild)) {
throw(new DOMException(DOMException.HIERARCHY_REQUEST_ERR));
}
}
var index = this.childNodes._findItemIndex(oldChild._id);
if (this.ownerDocument.implementation.errorChecking && (index < 0)) {
throw(new DOMException(DOMException.NOT_FOUND_ERR));
}
var newChildParent = newChild.parentNode;
if (newChildParent) {
newChildParent.removeChild(newChild);
}
ret = this.childNodes._replaceChild(newChild, index);
if (newChild.nodeType == DOMNode.DOCUMENT_FRAGMENT_NODE) {
if (newChild.childNodes._nodes.length > 0) {
for (var ind = 0; ind < newChild.childNodes._nodes.length; ind++) {
newChild.childNodes._nodes[ind].parentNode = this;
}
if (oldChild.previousSibling) {
oldChild.previousSibling.nextSibling = newChild.childNodes._nodes[0];
}
else {
this.firstChild = newChild.childNodes._nodes[0];
}
if (oldChild.nextSibling) {
oldChild.nextSibling.previousSibling = newChild;
}
else {
this.lastChild = newChild.childNodes._nodes[newChild.childNodes._nodes.length-1];
}
newChild.childNodes._nodes[0].previousSibling = oldChild.previousSibling;
newChild.childNodes._nodes[newChild.childNodes._nodes.length-1].nextSibling = oldChild.nextSibling;
}
}
else {
newChild.parentNode = this;
if (oldChild.previousSibling) {
oldChild.previousSibling.nextSibling = newChild;
}
else {
this.firstChild = newChild;
}
if (oldChild.nextSibling) {
oldChild.nextSibling.previousSibling = newChild;
}
else {
this.lastChild = newChild;
}
newChild.previousSibling = oldChild.previousSibling;
newChild.nextSibling = oldChild.nextSibling;
}
return ret;
};
DOMNode.prototype.removeChild = function DOMNode_removeChild(oldChild) {
if (this.ownerDocument.implementation.errorChecking && (this._readonly || oldChild._readonly)) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
var itemIndex = this.childNodes._findItemIndex(oldChild._id);
if (this.ownerDocument.implementation.errorChecking && (itemIndex < 0)) {
throw(new DOMException(DOMException.NOT_FOUND_ERR));
}
this.childNodes._removeChild(itemIndex);
oldChild.parentNode = null;
if (oldChild.previousSibling) {
oldChild.previousSibling.nextSibling = oldChild.nextSibling;
}
else {
this.firstChild = oldChild.nextSibling;
}
if (oldChild.nextSibling) {
oldChild.nextSibling.previousSibling = oldChild.previousSibling;
}
else {
this.lastChild = oldChild.previousSibling;
}
oldChild.previousSibling = null;
oldChild.nextSibling = null;
return oldChild;
};
DOMNode.prototype.appendChild = function DOMNode_appendChild(newChild) {
if (this.ownerDocument.implementation.errorChecking) {
if (this._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if (this.ownerDocument != newChild.ownerDocument) {
throw(new DOMException(DOMException.WRONG_DOCUMENT_ERR));
}
if (this._isAncestor(newChild)) {
throw(new DOMException(DOMException.HIERARCHY_REQUEST_ERR));
}
}
var newChildParent = newChild.parentNode;
if (newChildParent) {
newChildParent.removeChild(newChild);
}
this.childNodes._appendChild(newChild);
if (newChild.nodeType == DOMNode.DOCUMENT_FRAGMENT_NODE) {
if (newChild.childNodes._nodes.length > 0) {
for (var ind = 0; ind < newChild.childNodes._nodes.length; ind++) {
newChild.childNodes._nodes[ind].parentNode = this;
}
if (this.lastChild) {
this.lastChild.nextSibling = newChild.childNodes._nodes[0];
newChild.childNodes._nodes[0].previousSibling = this.lastChild;
this.lastChild = newChild.childNodes._nodes[newChild.childNodes._nodes.length-1];
}
else {
this.lastChild = newChild.childNodes._nodes[newChild.childNodes._nodes.length-1];
this.firstChild = newChild.childNodes._nodes[0];
}
}
}
else {
newChild.parentNode = this;
if (this.lastChild) {
this.lastChild.nextSibling = newChild;
newChild.previousSibling = this.lastChild;
this.lastChild = newChild;
}
else {
this.lastChild = newChild;
this.firstChild = newChild;
}
}
return newChild;
};
DOMNode.prototype.hasChildNodes = function DOMNode_hasChildNodes() {
return (this.childNodes.length > 0);
};
DOMNode.prototype.cloneNode = function DOMNode_cloneNode(deep) {
try {
return this.ownerDocument.importNode(this, deep);
}
catch (e) {
return null;
}
};
DOMNode.prototype.normalize = function DOMNode_normalize() {
var inode;
var nodesToRemove = new DOMNodeList();
if (this.nodeType == DOMNode.ELEMENT_NODE || this.nodeType == DOMNode.DOCUMENT_NODE) {
var adjacentTextNode = null;
for(var i = 0; i < this.childNodes.length; i++) {
inode = this.childNodes.item(i);
if (inode.nodeType == DOMNode.TEXT_NODE) {
if (inode.length < 1) {
nodesToRemove._appendChild(inode);
}
else {
if (adjacentTextNode) {
adjacentTextNode.appendData(inode.data);
nodesToRemove._appendChild(inode);
}
else {
adjacentTextNode = inode;
}
}
}
else {
adjacentTextNode = null;
inode.normalize();
}
}
for(var i = 0; i < nodesToRemove.length; i++) {
inode = nodesToRemove.item(i);
inode.parentNode.removeChild(inode);
}
}
};
DOMNode.prototype.isSupported = function DOMNode_isSupported(feature, version) {
return this.ownerDocument.implementation.hasFeature(feature, version);
}
DOMNode.prototype.getElementsByTagName = function DOMNode_getElementsByTagName(tagname) {
return this._getElementsByTagNameRecursive(tagname, new DOMNodeList(this.ownerDocument));
};
DOMNode.prototype._getElementsByTagNameRecursive = function DOMNode__getElementsByTagNameRecursive(tagname, nodeList) {
if (this.nodeType == DOMNode.ELEMENT_NODE || this.nodeType == DOMNode.DOCUMENT_NODE) {
if((this.nodeName == tagname) || (tagname == "*")) {
nodeList._appendChild(this);
}
for(var i = 0; i < this.childNodes.length; i++) {
nodeList = this.childNodes.item(i)._getElementsByTagNameRecursive(tagname, nodeList);
}
}
return nodeList;
};
DOMNode.prototype.getXML = function DOMNode_getXML() {
return this.toString();
}
DOMNode.prototype.getElementsByTagNameNS = function DOMNode_getElementsByTagNameNS(namespaceURI, localName) {
return this._getElementsByTagNameNSRecursive(namespaceURI, localName, new DOMNodeList(this.ownerDocument));
};
DOMNode.prototype._getElementsByTagNameNSRecursive = function DOMNode__getElementsByTagNameNSRecursive(namespaceURI, localName, nodeList) {
if (this.nodeType == DOMNode.ELEMENT_NODE || this.nodeType == DOMNode.DOCUMENT_NODE) {
if (((this.namespaceURI == namespaceURI) || (namespaceURI == "*")) && ((this.localName == localName) || (localName == "*"))) {
nodeList._appendChild(this);
}
for(var i = 0; i < this.childNodes.length; i++) {
nodeList = this.childNodes.item(i)._getElementsByTagNameNSRecursive(namespaceURI, localName, nodeList);
}
}
return nodeList;
};
DOMNode.prototype._isAncestor = function DOMNode__isAncestor(node) {
return ((this == node) || ((this.parentNode) && (this.parentNode._isAncestor(node))));
}
DOMNode.prototype.importNode = function DOMNode_importNode(importedNode, deep) {
var importNode;
this.getOwnerDocument()._performingImportNodeOperation = true;
try {
if (importedNode.nodeType == DOMNode.ELEMENT_NODE) {
if (!this.ownerDocument.implementation.namespaceAware) {
importNode = this.ownerDocument.createElement(importedNode.tagName);
for(var i = 0; i < importedNode.attributes.length; i++) {
importNode.setAttribute(importedNode.attributes.item(i).name, importedNode.attributes.item(i).value);
}
}
else {
importNode = this.ownerDocument.createElementNS(importedNode.namespaceURI, importedNode.nodeName);
for(var i = 0; i < importedNode.attributes.length; i++) {
importNode.setAttributeNS(importedNode.attributes.item(i).namespaceURI, importedNode.attributes.item(i).name, importedNode.attributes.item(i).value);
}
for(var i = 0; i < importedNode._namespaces.length; i++) {
importNode._namespaces._nodes[i] = this.ownerDocument.createNamespace(importedNode._namespaces.item(i).localName);
importNode._namespaces._nodes[i].setValue(importedNode._namespaces.item(i).value);
}
}
}
else if (importedNode.nodeType == DOMNode.ATTRIBUTE_NODE) {
if (!this.ownerDocument.implementation.namespaceAware) {
importNode = this.ownerDocument.createAttribute(importedNode.name);
}
else {
importNode = this.ownerDocument.createAttributeNS(importedNode.namespaceURI, importedNode.nodeName);
for(var i = 0; i < importedNode._namespaces.length; i++) {
importNode._namespaces._nodes[i] = this.ownerDocument.createNamespace(importedNode._namespaces.item(i).localName);
importNode._namespaces._nodes[i].setValue(importedNode._namespaces.item(i).value);
}
}
importNode.setValue(importedNode.value);
}
else if (importedNode.nodeType == DOMNode.DOCUMENT_FRAGMENT) {
importNode = this.ownerDocument.createDocumentFragment();
}
else if (importedNode.nodeType == DOMNode.NAMESPACE_NODE) {
importNode = this.ownerDocument.createNamespace(importedNode.nodeName);
importNode.setValue(importedNode.value);
}
else if (importedNode.nodeType == DOMNode.TEXT_NODE) {
importNode = this.ownerDocument.createTextNode(importedNode.data);
}
else if (importedNode.nodeType == DOMNode.CDATA_SECTION_NODE) {
importNode = this.ownerDocument.createCDATASection(importedNode.data);
}
else if (importedNode.nodeType == DOMNode.PROCESSING_INSTRUCTION_NODE) {
importNode = this.ownerDocument.createProcessingInstruction(importedNode.target, importedNode.data);
}
else if (importedNode.nodeType == DOMNode.COMMENT_NODE) {
importNode = this.ownerDocument.createComment(importedNode.data);
}
else {
throw(new DOMException(DOMException.NOT_SUPPORTED_ERR));
}
if (deep) {
for(var i = 0; i < importedNode.childNodes.length; i++) {
importNode.appendChild(this.ownerDocument.importNode(importedNode.childNodes.item(i), true));
}
}
this.getOwnerDocument()._performingImportNodeOperation = false;
return importNode;
}
catch (eAny) {
this.getOwnerDocument()._performingImportNodeOperation = false;
throw eAny;
}
};
DOMNode.prototype.__escapeString = function DOMNode__escapeString(str) {
return __escapeString(str);
};
DOMNode.prototype.__unescapeString = function DOMNode__unescapeString(str) {
return __unescapeString(str);
};
DOMDocument = function(implementation) {
this._class = addClass(this._class, "DOMDocument");
this.DOMNode = DOMNode;
this.DOMNode(this);
this.doctype = null;
this.implementation = implementation;
this.documentElement = null;
this.all= new Array();
this.nodeName= "#document";
this.nodeType = DOMNode.DOCUMENT_NODE;
this._id = 0;
this._lastId = 0;
this._parseComplete = false;
this.ownerDocument = this;
this._performingImportNodeOperation = false;
};
DOMDocument.prototype = new DOMNode;
DOMDocument.prototype.getDoctype = function DOMDocument_getDoctype() {
return this.doctype;
};
DOMDocument.prototype.getImplementation = function DOMDocument_implementation() {
return this.implementation;
};
DOMDocument.prototype.getDocumentElement = function DOMDocument_getDocumentElement() {
return this.documentElement;
};
DOMDocument.prototype.createElement = function DOMDocument_createElement(tagName) {
if (this.ownerDocument.implementation.errorChecking && (!this.ownerDocument.implementation._isValidName(tagName))) {
throw(new DOMException(DOMException.INVALID_CHARACTER_ERR));
}
var node = new DOMElement(this);
node.tagName= tagName;
node.nodeName = tagName;
this.all[this.all.length] = node;
return node;
};
DOMDocument.prototype.createDocumentFragment = function DOMDocument_createDocumentFragment() {
var node = new DOMDocumentFragment(this);
return node;
};
DOMDocument.prototype.createTextNode = function DOMDocument_createTextNode(data) {
var node = new DOMText(this);
node.data= data;
node.nodeValue = data;
node.length= data.length;
return node;
};
DOMDocument.prototype.createComment = function DOMDocument_createComment(data) {
var node = new DOMComment(this);
node.data= data;
node.nodeValue = data;
node.length= data.length;
return node;
};
DOMDocument.prototype.createCDATASection = function DOMDocument_createCDATASection(data) {
var node = new DOMCDATASection(this);
node.data= data;
node.nodeValue = data;
node.length= data.length;
return node;
};
DOMDocument.prototype.createProcessingInstruction = function DOMDocument_createProcessingInstruction(target, data) {
if (this.ownerDocument.implementation.errorChecking && (!this.implementation._isValidName(target))) {
throw(new DOMException(DOMException.INVALID_CHARACTER_ERR));
}
var node = new DOMProcessingInstruction(this);
node.target= target;
node.nodeName= target;
node.data= data;
node.nodeValue = data;
node.length= data.length;
return node;
};
DOMDocument.prototype.createAttribute = function DOMDocument_createAttribute(name) {
if (this.ownerDocument.implementation.errorChecking && (!this.ownerDocument.implementation._isValidName(name))) {
throw(new DOMException(DOMException.INVALID_CHARACTER_ERR));
}
var node = new DOMAttr(this);
node.name = name;
node.nodeName = name;
return node;
};
DOMDocument.prototype.createElementNS = function DOMDocument_createElementNS(namespaceURI, qualifiedName) {
if (this.ownerDocument.implementation.errorChecking) {
if (!this.ownerDocument._isValidNamespace(namespaceURI, qualifiedName)) {
throw(new DOMException(DOMException.NAMESPACE_ERR));
}
if (!this.ownerDocument.implementation._isValidName(qualifiedName)) {
throw(new DOMException(DOMException.INVALID_CHARACTER_ERR));
}
}
var node= new DOMElement(this);
var qname = this.implementation._parseQName(qualifiedName);
node.nodeName = qualifiedName;
node.namespaceURI = namespaceURI;
node.prefix= qname.prefix;
node.localName= qname.localName;
node.tagName= qualifiedName;
this.all[this.all.length] = node;
return node;
};
DOMDocument.prototype.createAttributeNS = function DOMDocument_createAttributeNS(namespaceURI, qualifiedName) {
if (this.ownerDocument.implementation.errorChecking) {
if (!this.ownerDocument._isValidNamespace(namespaceURI, qualifiedName, true)) {
throw(new DOMException(DOMException.NAMESPACE_ERR));
}
if (!this.ownerDocument.implementation._isValidName(qualifiedName)) {
throw(new DOMException(DOMException.INVALID_CHARACTER_ERR));
}
}
var node= new DOMAttr(this);
var qname = this.implementation._parseQName(qualifiedName);
node.nodeName = qualifiedName
node.namespaceURI = namespaceURI
node.prefix= qname.prefix;
node.localName= qname.localName;
node.name= qualifiedName
node.nodeValue= "";
return node;
};
DOMDocument.prototype.createNamespace = function DOMDocument_createNamespace(qualifiedName) {
var node= new DOMNamespace(this);
var qname = this.implementation._parseQName(qualifiedName);
node.nodeName = qualifiedName
node.prefix= qname.prefix;
node.localName= qname.localName;
node.name= qualifiedName
node.nodeValue= "";
return node;
};
DOMDocument.prototype.getElementById = function DOMDocument_getElementById(elementId) {
retNode = null;
for (var i=0; i < this.all.length; i++) {
var node = this.all[i];
if ((node.id == elementId) && (node._isAncestor(node.ownerDocument.documentElement))) {
retNode = node;
break;
}
}
return retNode;
};
DOMDocument.prototype._genId = function DOMDocument__genId() {
this._lastId += 1;
return this._lastId;
};
DOMDocument.prototype._isValidNamespace = function DOMDocument__isValidNamespace(namespaceURI, qualifiedName, isAttribute) {
if (this._performingImportNodeOperation == true) {
return true;
}
var valid = true;
var qName = this.implementation._parseQName(qualifiedName);
if (this._parseComplete == true) {
if (qName.localName.indexOf(":") > -1 ){
valid = false;
}
if ((valid) && (!isAttribute)) {
if (!namespaceURI) {
valid = false;
}
}
if ((valid) && (qName.prefix == "")) {
valid = false;
}
}
if ((valid) && (qName.prefix == "xml") && (namespaceURI != "http://www.w3.org/XML/1998/namespace")) {
valid = false;
}
return valid;
}
DOMDocument.prototype.toString = function DOMDocument_toString() {
return "" + this.childNodes;
}
DOMElement = function(ownerDocument) {
this._class = addClass(this._class, "DOMElement");
this.DOMNode= DOMNode;
this.DOMNode(ownerDocument);
this.tagName = "";
this.id = "";
this.nodeType = DOMNode.ELEMENT_NODE;
};
DOMElement.prototype = new DOMNode;
DOMElement.prototype.getTagName = function DOMElement_getTagName() {
return this.tagName;
};
DOMElement.prototype.getAttribute = function DOMElement_getAttribute(name) {
var ret = "";
var attr = this.attributes.getNamedItem(name);
if (attr) {
ret = attr.value;
}
return ret;
};
DOMElement.prototype.setAttribute = function DOMElement_setAttribute(name, value) {
var attr = this.attributes.getNamedItem(name);
if (!attr) {
attr = this.ownerDocument.createAttribute(name);
}
var value = new String(value);
if (this.ownerDocument.implementation.errorChecking) {
if (attr._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if (!this.ownerDocument.implementation._isValidString(value)) {
throw(new DOMException(DOMException.INVALID_CHARACTER_ERR));
}
}
if (this.ownerDocument.implementation._isIdDeclaration(name)) {
this.id = value;
}
attr.value = value;
attr.nodeValue = value;
if (value.length > 0) {
attr.specified = true;
}
else {
attr.specified = false;
}
this.attributes.setNamedItem(attr);
};
DOMElement.prototype.removeAttribute = function DOMElement_removeAttribute(name) {
return this.attributes.removeNamedItem(name);
};
DOMElement.prototype.getAttributeNode = function DOMElement_getAttributeNode(name) {
return this.attributes.getNamedItem(name);
};
DOMElement.prototype.setAttributeNode = function DOMElement_setAttributeNode(newAttr) {
if (this.ownerDocument.implementation._isIdDeclaration(newAttr.name)) {
this.id = newAttr.value;
}
return this.attributes.setNamedItem(newAttr);
};
DOMElement.prototype.removeAttributeNode = function DOMElement_removeAttributeNode(oldAttr) {
if (this.ownerDocument.implementation.errorChecking && oldAttr._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
var itemIndex = this.attributes._findItemIndex(oldAttr._id);
if (this.ownerDocument.implementation.errorChecking && (itemIndex < 0)) {
throw(new DOMException(DOMException.NOT_FOUND_ERR));
}
return this.attributes._removeChild(itemIndex);
};
DOMElement.prototype.getAttributeNS = function DOMElement_getAttributeNS(namespaceURI, localName) {
var ret = "";
var attr = this.attributes.getNamedItemNS(namespaceURI, localName);
if (attr) {
ret = attr.value;
}
return ret;
};
DOMElement.prototype.setAttributeNS = function DOMElement_setAttributeNS(namespaceURI, qualifiedName, value) {
var attr = this.attributes.getNamedItem(namespaceURI, qualifiedName);
if (!attr) {
attr = this.ownerDocument.createAttributeNS(namespaceURI, qualifiedName);
}
var value = new String(value);
if (this.ownerDocument.implementation.errorChecking) {
if (attr._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if (!this.ownerDocument._isValidNamespace(namespaceURI, qualifiedName)) {
throw(new DOMException(DOMException.NAMESPACE_ERR));
}
if (!this.ownerDocument.implementation._isValidString(value)) {
throw(new DOMException(DOMException.INVALID_CHARACTER_ERR));
}
}
if (this.ownerDocument.implementation._isIdDeclaration(name)) {
this.id = value;
}
attr.value = value;
attr.nodeValue = value;
if (value.length > 0) {
attr.specified = true;
}
else {
attr.specified = false;
}
this.attributes.setNamedItemNS(attr);
};
DOMElement.prototype.removeAttributeNS = function DOMElement_removeAttributeNS(namespaceURI, localName) {
return this.attributes.removeNamedItemNS(namespaceURI, localName);
};
DOMElement.prototype.getAttributeNodeNS = function DOMElement_getAttributeNodeNS(namespaceURI, localName) {
return this.attributes.getNamedItemNS(namespaceURI, localName);
};
DOMElement.prototype.setAttributeNodeNS = function DOMElement_setAttributeNodeNS(newAttr) {
if ((newAttr.prefix == "") && this.ownerDocument.implementation._isIdDeclaration(newAttr.name)) {
this.id = newAttr.value;
}
return this.attributes.setNamedItemNS(newAttr);
};
DOMElement.prototype.hasAttribute = function DOMElement_hasAttribute(name) {
return this.attributes._hasAttribute(name);
}
DOMElement.prototype.hasAttributeNS = function DOMElement_hasAttributeNS(namespaceURI, localName) {
return this.attributes._hasAttributeNS(namespaceURI, localName);
}
DOMElement.prototype.toString = function DOMElement_toString() {
var ret = "";
var ns = this._namespaces.toString();
if (ns.length > 0) ns = " "+ ns;
var attrs = this.attributes.toString();
if (attrs.length > 0) attrs = " "+ attrs;
ret += "<" + this.nodeName + ns + attrs +">";
ret += this.childNodes.toString();;
ret += "</" + this.nodeName+">";
return ret;
}
DOMAttr = function(ownerDocument) {
this._class = addClass(this._class, "DOMAttr");
this.DOMNode = DOMNode;
this.DOMNode(ownerDocument);
this.name= "";
this.specified = false;
this.value = "";
this.nodeType= DOMNode.ATTRIBUTE_NODE;
this.ownerElement = null;
this.childNodes = null;
this.attributes = null;
};
DOMAttr.prototype = new DOMNode;
DOMAttr.prototype.getName = function DOMAttr_getName() {
return this.nodeName;
};
DOMAttr.prototype.getSpecified = function DOMAttr_getSpecified() {
return this.specified;
};
DOMAttr.prototype.getValue = function DOMAttr_getValue() {
return this.nodeValue;
};
DOMAttr.prototype.setValue = function DOMAttr_setValue(value) {
if (this.ownerDocument.implementation.errorChecking && this._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
this.setNodeValue(value);
};
DOMAttr.prototype.setNodeValue = function DOMAttr_setNodeValue(value) {
this.nodeValue = new String(value);
this.value = this.nodeValue;
this.specified = (this.value.length > 0);
};
DOMAttr.prototype.toString = function DOMAttr_toString() {
var ret = "";
ret += this.nodeName +"=\""+ this.__escapeString(this.nodeValue) +"\"";
return ret;
}
DOMAttr.prototype.getOwnerElement = function() {
return this.ownerElement;
}
DOMNamespace = function(ownerDocument) {
this._class = addClass(this._class, "DOMNamespace");
this.DOMNode = DOMNode;
this.DOMNode(ownerDocument);
this.name= "";
this.specified = false;
this.value = "";
this.nodeType= DOMNode.NAMESPACE_NODE;
};
DOMNamespace.prototype = new DOMNode;
DOMNamespace.prototype.getValue = function DOMNamespace_getValue() {
return this.nodeValue;
};
DOMNamespace.prototype.setValue = function DOMNamespace_setValue(value) {
this.nodeValue = new String(value);
this.value = this.nodeValue;
};
DOMNamespace.prototype.toString = function DOMNamespace_toString() {
var ret = "";
if (this.nodeName != "") {
ret += this.nodeName +"=\""+ this.__escapeString(this.nodeValue) +"\"";
}
else {
ret += "xmlns=\""+ this.__escapeString(this.nodeValue) +"\"";
}
return ret;
}
DOMCharacterData = function(ownerDocument) {
this._class = addClass(this._class, "DOMCharacterData");
this.DOMNode= DOMNode;
this.DOMNode(ownerDocument);
this.data= "";
this.length = 0;
};
DOMCharacterData.prototype = new DOMNode;
DOMCharacterData.prototype.getData = function DOMCharacterData_getData() {
return this.nodeValue;
};
DOMCharacterData.prototype.setData = function DOMCharacterData_setData(data) {
this.setNodeValue(data);
};
DOMCharacterData.prototype.setNodeValue = function DOMCharacterData_setNodeValue(data) {
if (this.ownerDocument.implementation.errorChecking && this._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
this.nodeValue = new String(data);
this.data= this.nodeValue;
this.length = this.nodeValue.length;
};
DOMCharacterData.prototype.getLength = function DOMCharacterData_getLength() {
return this.nodeValue.length;
};
DOMCharacterData.prototype.substringData = function DOMCharacterData_substringData(offset, count) {
var ret = null;
if (this.data) {
if (this.ownerDocument.implementation.errorChecking && ((offset < 0) || (offset > this.data.length) || (count < 0))) {
throw(new DOMException(DOMException.INDEX_SIZE_ERR));
}
if (!count) {
ret = this.data.substring(offset);
}
else {
ret = this.data.substring(offset, offset + count);
}
}
return ret;
};
DOMCharacterData.prototype.appendData= function DOMCharacterData_appendData(arg) {
if (this.ownerDocument.implementation.errorChecking && this._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
this.setData(""+ this.data + arg);
};
DOMCharacterData.prototype.insertData= function DOMCharacterData_insertData(offset, arg) {
if (this.ownerDocument.implementation.errorChecking && this._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if (this.data) {
if (this.ownerDocument.implementation.errorChecking && ((offset < 0) || (offset > this.data.length))) {
throw(new DOMException(DOMException.INDEX_SIZE_ERR));
}
this.setData(this.data.substring(0, offset).concat(arg, this.data.substring(offset)));
}
else {
if (this.ownerDocument.implementation.errorChecking && (offset != 0)) {
throw(new DOMException(DOMException.INDEX_SIZE_ERR));
}
this.setData(arg);
}
};
DOMCharacterData.prototype.deleteData= function DOMCharacterData_deleteData(offset, count) {
if (this.ownerDocument.implementation.errorChecking && this._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if (this.data) {
if (this.ownerDocument.implementation.errorChecking && ((offset < 0) || (offset > this.data.length) || (count < 0))) {
throw(new DOMException(DOMException.INDEX_SIZE_ERR));
}
if(!count || (offset + count) > this.data.length) {
this.setData(this.data.substring(0, offset));
}
else {
this.setData(this.data.substring(0, offset).concat(this.data.substring(offset + count)));
}
}
};
DOMCharacterData.prototype.replaceData= function DOMCharacterData_replaceData(offset, count, arg) {
if (this.ownerDocument.implementation.errorChecking && this._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if (this.data) {
if (this.ownerDocument.implementation.errorChecking && ((offset < 0) || (offset > this.data.length) || (count < 0))) {
throw(new DOMException(DOMException.INDEX_SIZE_ERR));
}
this.setData(this.data.substring(0, offset).concat(arg, this.data.substring(offset + count)));
}
else {
this.setData(arg);
}
};
DOMText = function(ownerDocument) {
this._class = addClass(this._class, "DOMText");
this.DOMCharacterData= DOMCharacterData;
this.DOMCharacterData(ownerDocument);
this.nodeName= "#text";
this.nodeType= DOMNode.TEXT_NODE;
};
DOMText.prototype = new DOMCharacterData;
DOMText.prototype.splitText = function DOMText_splitText(offset) {
var data, inode;
if (this.ownerDocument.implementation.errorChecking) {
if (this._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if ((offset < 0) || (offset > this.data.length)) {
throw(new DOMException(DOMException.INDEX_SIZE_ERR));
}
}
if (this.parentNode) {
data= this.substringData(offset);
inode = this.ownerDocument.createTextNode(data);
if (this.nextSibling) {
this.parentNode.insertBefore(inode, this.nextSibling);
}
else {
this.parentNode.appendChild(inode);
}
this.deleteData(offset);
}
return inode;
};
DOMText.prototype.toString = function DOMText_toString() {
return this.__escapeString(""+ this.nodeValue);
}
DOMCDATASection = function(ownerDocument) {
this._class = addClass(this._class, "DOMCDATASection");
this.DOMCharacterData= DOMCharacterData;
this.DOMCharacterData(ownerDocument);
this.nodeName= "#cdata-section";
this.nodeType= DOMNode.CDATA_SECTION_NODE;
};
DOMCDATASection.prototype = new DOMCharacterData;
DOMCDATASection.prototype.splitText = function DOMCDATASection_splitText(offset) {
var data, inode;
if (this.ownerDocument.implementation.errorChecking) {
if (this._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
if ((offset < 0) || (offset > this.data.length)) {
throw(new DOMException(DOMException.INDEX_SIZE_ERR));
}
}
if(this.parentNode) {
data= this.substringData(offset);
inode = this.ownerDocument.createCDATASection(data);
if (this.nextSibling) {
this.parentNode.insertBefore(inode, this.nextSibling);
}
else {
this.parentNode.appendChild(inode);
}
this.deleteData(offset);
}
return inode;
};
DOMCDATASection.prototype.toString = function DOMCDATASection_toString() {
var ret = "";
ret += "<![CDATA[" + this.nodeValue + "\]\]\>";
return ret;
}
DOMComment = function(ownerDocument) {
this._class = addClass(this._class, "DOMComment");
this.DOMCharacterData= DOMCharacterData;
this.DOMCharacterData(ownerDocument);
this.nodeName= "#comment";
this.nodeType= DOMNode.COMMENT_NODE;
};
DOMComment.prototype = new DOMCharacterData;
DOMComment.prototype.toString = function DOMComment_toString() {
var ret = "";
ret += "<!--" + this.nodeValue + "-->";
return ret;
}
DOMProcessingInstruction = function(ownerDocument) {
this._class = addClass(this._class, "DOMProcessingInstruction");
this.DOMNode= DOMNode;
this.DOMNode(ownerDocument);
this.target = "";
this.data= "";
this.nodeType= DOMNode.PROCESSING_INSTRUCTION_NODE;
};
DOMProcessingInstruction.prototype = new DOMNode;
DOMProcessingInstruction.prototype.getTarget = function DOMProcessingInstruction_getTarget() {
return this.nodeName;
};
DOMProcessingInstruction.prototype.getData = function DOMProcessingInstruction_getData() {
return this.nodeValue;
};
DOMProcessingInstruction.prototype.setData = function DOMProcessingInstruction_setData(data) {
this.setNodeValue(data);
};
DOMProcessingInstruction.prototype.setNodeValue = function DOMProcessingInstruction_setNodeValue(data) {
if (this.ownerDocument.implementation.errorChecking && this._readonly) {
throw(new DOMException(DOMException.NO_MODIFICATION_ALLOWED_ERR));
}
this.nodeValue = new String(data);
this.data = this.nodeValue;
};
DOMProcessingInstruction.prototype.toString = function DOMProcessingInstruction_toString() {
var ret = "";
ret += "<?" + this.nodeName +" "+ this.nodeValue + " ?>";
return ret;
}
DOMDocumentFragment = function(ownerDocument) {
this._class = addClass(this._class, "DOMDocumentFragment");
this.DOMNode = DOMNode;
this.DOMNode(ownerDocument);
this.nodeName= "#document-fragment";
this.nodeType = DOMNode.DOCUMENT_FRAGMENT_NODE;
};
DOMDocumentFragment.prototype = new DOMNode;
DOMDocumentFragment.prototype.toString = function DOMDocumentFragment_toString() {
var xml = "";
var intCount = this.getChildNodes().getLength();
for (intLoop = 0; intLoop < intCount; intLoop++) {
xml += this.getChildNodes().item(intLoop).toString();
}
return xml;
}
DOMDocumentType= function() { alert("DOMDocumentType.constructor(): Not Implemented" ); };
DOMEntity= function() { alert("DOMEntity.constructor(): Not Implemented" ); };
DOMEntityReference = function() { alert("DOMEntityReference.constructor(): Not Implemented"); };
DOMNotation = function() { alert("DOMNotation.constructor(): Not Implemented" ); };
Strings = new Object()
Strings.WHITESPACE = " \t\n\r";
Strings.QUOTES = "\"'";
Strings.isEmpty = function Strings_isEmpty(strD) {
return (strD == null) || (strD.length == 0);
};
Strings.indexOfNonWhitespace = function Strings_indexOfNonWhitespace(strD, iB, iE) {
if(Strings.isEmpty(strD)) return -1;
iB = iB || 0;
iE = iE || strD.length;
for(var i = iB; i < iE; i++)
if(Strings.WHITESPACE.indexOf(strD.charAt(i)) == -1) {
return i;
}
return -1;
};
Strings.lastIndexOfNonWhitespace = function Strings_lastIndexOfNonWhitespace(strD, iB, iE) {
if(Strings.isEmpty(strD)) return -1;
iB = iB || 0;
iE = iE || strD.length;
for(var i = iE - 1; i >= iB; i--)
if(Strings.WHITESPACE.indexOf(strD.charAt(i)) == -1)
return i;
return -1;
};
Strings.indexOfWhitespace = function Strings_indexOfWhitespace(strD, iB, iE) {
if(Strings.isEmpty(strD)) return -1;
iB = iB || 0;
iE = iE || strD.length;
for(var i = iB; i < iE; i++)
if(Strings.WHITESPACE.indexOf(strD.charAt(i)) != -1)
return i;
return -1;
};
Strings.replace = function Strings_replace(strD, iB, iE, strF, strR) {
if(Strings.isEmpty(strD)) return "";
iB = iB || 0;
iE = iE || strD.length;
return strD.substring(iB, iE).split(strF).join(strR);
};
Strings.getLineNumber = function Strings_getLineNumber(strD, iP) {
if(Strings.isEmpty(strD)) return -1;
iP = iP || strD.length;
return strD.substring(0, iP).split("\n").length
};
Strings.getColumnNumber = function Strings_getColumnNumber(strD, iP) {
if(Strings.isEmpty(strD)) return -1;
iP = iP || strD.length;
var arrD = strD.substring(0, iP).split("\n");
var strLine = arrD[arrD.length - 1];
arrD.length--;
var iLinePos = arrD.join("\n").length;
return iP - iLinePos;
};
StringBuffer = function() {this._a=new Array();};
StringBuffer.prototype.append = function StringBuffer_append(d){this._a[this._a.length]=d;};
StringBuffer.prototype.toString = function StringBuffer_toString(){return this._a.join("");};
function sajaxSubmit_cb(z){
var sio = new sajaxIO();
Res= sio.getMsg(z);
sio.executeJsFunction(Res.JsFunction) ;
}
function x__displayProgram() {
sajax_do_call("_displayProgram",x__displayProgram.arguments);
}
function x_sajaxSubmit() {
sajax_do_call("sajaxSubmit",x_sajaxSubmit.arguments);
}
function Response(RetCode,jsFunction,Content,other,tagid,divid) {
this.RetCode = RetCode;
this.JsFunction = trim(jsFunction);
this.Content = Content;
this.Other = other;
this.TagId = tagid;
this.DivId = divid;
return this;
}
function sajaxIO() {
this.break_up_char = "+++L+++";
this._Debug=false;
return this;
}
sajaxIO.prototype.getMsg = function (z){
if(typeof (this.break_up_char)=="undefined") break_up_char = "+++L+++";
else break_up_char = this.break_up_char;
var zz = z.split(break_up_char);
if(this._Debug)
alert(z);
var parser = new DOMImplementation();
var domDoc = parser.loadXML(zz[1]);
var docRoot = domDoc.getDocumentElement();
var tag1 = docRoot.getElementsByTagName("JsFunction").item(0);
var tag2 = docRoot.getElementsByTagName("Content").item(0);
var tag3 = docRoot.getElementsByTagName("RetCode").item(0);
var tag4 = docRoot.getElementsByTagName("Other").item(0);
var obj5 = docRoot.getElementsByTagName("DivId");
var obj6 = docRoot.getElementsByTagName("TagId");
var tag5;
if(obj5!=null)
tag5 = obj5.item(0);
if(obj6!=null)
tag6 = obj6.item(0);
var jsfun = "";
var content = "";
var retcode = "";
var other = "";
var tagid = "";
var divid = "";
if(tag1.getFirstChild()) jsfun = tag1.getFirstChild().getNodeValue();
if(tag2.getFirstChild()) content = tag2.getFirstChild().getNodeValue();
if(tag3.getFirstChild()) retcode = tag3.getFirstChild().getNodeValue();
if(tag4.getFirstChild()) other = tag4.getFirstChild().getNodeValue();
if(obj5!=null)
if(tag5.getFirstChild()) divid = tag5.getFirstChild().getNodeValue();
if(obj6!=null)
if(tag6.getFirstChild()) tagid = tag6.getFirstChild().getNodeValue();
res = new Response(retcode,jsfun,content,other,tagid,divid);
return res;
}
sajaxIO.prototype.getXMLValue = 	function(k,v){
var len = k.length;
if(len != v.length) return "error";
var xmlVal = "<Input>";
for(var i=0;i<len;i++){
xmlVal += "<F><K>"+k[i]+"</K><V>"+v[i]+"</V></F>";
}
xmlVal += "</Input>";
return xmlVal;
}
sajaxIO.prototype.insertXMLValue = function(k,v,p_xml){
var len = k.length;
if(len != v.length) return "error";
var val = p_xml.split("</Input>");
var xmlVal = val[0];
for(var i=0;i<len;i++){
xmlVal += "<F><K>"+k[i]+"</K><V>"+encodeURIComponent(v[i])+"</V></F>";
}
xmlVal += "</Input>";
return xmlVal;
}
sajaxIO.prototype.validValue = function(p_value){
if(typeof(p_value)=='undefined') return "";
var val = p_value.replace(/&/g,"&amp;");
val = val.replace(/>/g,"&gt;");
val = val.replace(/</g,"&lt;");
val = val.replace(/"/g,"&quot;");
val = val.replace(/'/g,"&apos;");
return val;
}
sajaxIO.prototype.keyValue = function (p_form){
var keys = new Array();
var values = new Array();
var frm;
if(typeof(p_form)=='object') frm = p_form;
else frm = eval("document."+p_form);
var elements = frm.elements;
var len = elements.length;
for(var i=0;i<len;i++){
if(elements[i].type){
switch(elements[i].type){
case "checkbox":
if(elements[i].checked){
keys[i]=elements[i].name;
values[i]=encodeURIComponent(elements[i].value);
}
break;
case "radio":
if(elements[i].checked){
keys[i]=elements[i].name;
values[i]=encodeURIComponent(this.validValue(elements[i].value));
};
break;
default:
keys[i]=elements[i].name;
values[i]=encodeURIComponent(this.validValue(elements[i].value));
break;
}
}
else{
keys[i] = elements[i].name;
values[i] = encodeURIComponent(this.validValue(elements[i].value));
}
}
return this.getXMLValue(keys,values);
}
sajaxIO.prototype.executeJsFunction = function (JsFunction) {
if(JsFunction != ""){
eval(JsFunction+";");
}
}
sajaxIO.prototype.setDebug = function(){
this._Debug=true;
}
sajaxIO.prototype.setUri = function(str){
uri_in_sajax=str;
}
sajaxIO.prototype.sajaxSubmit = function(){
var a=sajaxIO.prototype.sajaxSubmit.arguments;
var p_param = a[0];
var p_form = '';
if(typeof(a[1])!='undefined') p_form = a[1];
callback = sajaxSubmit_cb;
if(typeof(a[2])!='undefined') callback = eval(a[2]);
callsajax = x_sajaxSubmit;
if(typeof(a[3])!='undefined') callsajax = eval("x_"+a[3]);
if(typeof(a[4])!='undefined') uri_in_sajax=a[4];//LUOYING
if(p_form!='')
var vars = this.keyValue(p_form);
else
var vars = "<Input></Input>";
var k = new Array();
var v = new Array();
ret = p_param.split("&");
var len = ret.length;
for(var i=0;i<len;i++) {
param = ret[i].split("=");
k[i] = param[0];
v[i] = param[1];
}
if(this._Debug)
alert(this.insertXMLValue(k,v,vars));
callsajax(this.insertXMLValue(k,v,vars),callback);
}
function Column(p_field,p_name,p_type) {
this.Field = p_field;
this.Name = p_name;
this.Type = p_type;
return this;
}
function AttachUploadField(p_field,p_name,p_type,p_kind){
this.Field = p_field;
this.Name = p_name;
this.Type = p_type;
this.Kind = p_kind;
return this;
}
function AttachFile(p_name,p_tmpname,p_field){
this.RealName = p_name;
this.TmpName = p_tmpname;
this.FieldName = p_field;
return this;
}
function AjaxEdit(p_name) {
this.name = p_name;
this.TagName = p_name;
this.sajaxIO = new sajaxIO();
this.Column = new Array();
this.AttachUploadField = new Array();
this.addAttach = new Array();
this.AttachLength = 0;
this.AttachTmpLength = 0;
this.ColumnLength = 0;
this.DispCheckBox = false;
this.DispUpdate = false;
this.ListForm = this.TagName+'_ListForm';
this.ListDiv = this.TagName+'_ListDiv';
this.AddForm = this.TagName+'_AddForm';
this.RefreshStat=0;
this.RowValue=new Array(15);
this.RowNewValue=new Array(15);
this.RowText=new Array(15);
this.PicUrl = 'pictures';
this.msgDiv = this.TagName+'_msg';
this.silent = 0;
this.QuickEditSave = "Save";
this.QuickEditCancel = "Cancel";
this.QuickEditButton = "Quick Edit";
this.FullEditButton = "Full Edit";
this.noteMsgClassName="note";
this.successMsgClassName="msg success";
this.failedMsgClassName="msg failure";
this.waitWord="Please wait...";
this.NoChk="Select items Please!";
this.ConfirmDelete="Are you sure to delete!";
this.Browser = divOsClass.prototype.getBrowser();
this.CheckData = true;
}
AjaxEdit.prototype._DEBUG_Display_z = 1
AjaxEdit.prototype._DEBUG_Display_content = 10;
AjaxEdit.prototype._DEBUG_Display_Retcode = 100;
AjaxEdit.prototype._DEBUG_Display_xxx = 1000;
AjaxEdit.prototype.dispField = new Array();
AjaxEdit.prototype.setCheckData = function(flag){
this.CheckData = flag;
}
AjaxEdit.prototype.setUri = function(p_uri){
this.sajaxUri = p_uri;
}
AjaxEdit.prototype.setTmpUri = function(p_uri){
this.backupUri = this.sajaxUri ;
this.sajaxUri = p_uri;
}
AjaxEdit.prototype.restoreUri = function(){
if(this.backupUri)
this.sajaxUri = this.backupUri;
}
AjaxEdit.prototype.setListForm = function(p_form) {
this.ListForm = p_form;
}
AjaxEdit.prototype.setListDiv = function(p_div) {
this.ListDiv = p_div;
}
AjaxEdit.prototype.setAddForm = function(p_form) {
this.AddForm = p_form;
}
AjaxEdit.prototype.setPicUrl = function(p_url) {
this.PicUrl = p_url;
}
AjaxEdit.prototype.setVar = function(p_word,p_info) {
eval("this."+p_word+"='"+p_info+"'");
}
AjaxEdit.prototype.setMsgDiv = function(p_div,p_noteClass,p_succClass,p_failedClass) {
this.msgDiv = p_div;
if(typeof p_noteClass!="undefined")
this.noteMsgClassName=p_noteClass;
if(typeof p_succClass!="undefined")
this.successMsgClassName=p_succClass;
if(typeof p_succClass!="undefined")
this.failedMsgClassName=p_failedClass;
}
AjaxEdit.prototype.setDebug = function(p_debug) {
if (!p_debug) {
AjaxEdit.prototype.DebugNo = 11;
this.sajaxIO.setDebug();
return;
}
if (p_debug=='send') {
this.sajaxIO.setDebug();
return;
}
if (p_debug=='close') {
AjaxEdit.prototype.DebugNo = 0;
return;
}
AjaxEdit.prototype.DebugNo = p_debug;
}
AjaxEdit.prototype.addListColumn = function(p_field,p_name,p_type) {
this.Column[this.ColumnLength++] = new Column(p_field,p_name,p_type);
}
AjaxEdit.prototype.setCheckBoxStat = function(p_field) {
this.DispCheckBox = true;
this.PrimaryKey = p_field;
}
AjaxEdit.prototype.setUpdateField = function(p_opjs) {
this.DispUpdate = true;
if(p_opjs)
this.UpdateOpJs = p_opjs;
else this.UpdateOpJs = '';
}
AjaxEdit.prototype.getValue = function(p_form,p_othervalue) {
var form = p_form;
this.form=p_form;
var value = new Array();
for(i=0;i<this.ColumnLength;i++){
if(this.Column[i].Type !="b"){
}
}
other_arr=p_othervalue.split("&");
for(j=0;j<other_arr.length;j++){
other_string=other_arr[j].split("=");
value[other_string[0]] = other_string[1];
}
return value;
}
AjaxEdit.prototype.addLine = function(p_value,p_line) {
var i;
var primary = p_value[this.PrimaryKey];
var form = this.form;
if(p_line=='1')
var newobj = document.getElementById('Add_line').insertRow(p_line);
else var newobj = document.getElementById('Add_line').insertRow();
newobj.className="addline";
newobj.id = this.name + "-+-" + primary+"_tr";
if(this.DispUpdate) {
this.dispField["UpdateColumn"](newobj,p_value,this.UpdateOpJs);
}
for(i=0;i<this.ColumnLength;i++) {
var field = this.Column[i].Field;
var val = p_value[this.Column[i].Field];
if(AjaxEdit.prototype.dispField[field]) {
AjaxEdit.prototype.dispField[field](p_value);
continue;
}
if(val=="") {
str1="var cell"+i+" =newobj.insertCell(null);\n";
str2="cell"+i+".innerHTML='&nbsp;'\n";
str3="";
}
else if(this.Column[i].Type=='b'){
str1="var cell"+i+" =newobj.insertCell(null);\n";
str2="cell"+i+".innerHTML='&nbsp;'\n";
str3="";
}
else if(this.Column[i].Type=='v'){
str1="var cell"+i+" =newobj.insertCell(null)\n";
str2="cell"+i+".innerHTML=\""+val+"\"\n";
str3="cell"+i+".id='"+primary+"-+-"+field+"-+-td'";
}
else if(this.Column[i].Type=='e'){
str1="var cell"+i+" =newobj.insertCell(null)\n";
str2="cell"+i+".innerHTML='<a href=mailto:"+val+">"+val+"</a>'\n";
str3="cell"+i+".id='"+primary+"-+-"+field+"-+-td'";
}
eval(str1);
eval(str2);
eval(str3);
}
if(this.DispCheckBox) {
this.dispField["CheckBox"](newobj,p_value);
}
newobj.style.display="none";
}
AjaxEdit.prototype.dispField["UpdateColumn"] = function(p_obj,p_value,p_js) {
var nbr = p_value[this.PrimaryKey];
var cell0=p_obj.insertCell(null);
Js=p_js.replace("===key===",nbr);
cell0.innerHTML="<a href=\"javascript:void(0)\" onclick=\"javascript:void("+Js+")\"><img src='"+this.PicUrl+"/edit.gif' border=0></a>";
cell0.align="middle";
}
AjaxEdit.prototype.dispField["CheckBox"] = function(p_obj,p_value) {
var primary = p_value[this.PrimaryKey];
var cell99=p_obj.insertCell(null);
cell99.innerHTML="<input type='checkbox' name='Chk[]' id='"+primary+"_input' value='"+primary+"' onclick='changeStyle(this)'>";
}
AjaxEdit.prototype.showMsg = function(content,p_type) {
if(typeof p_type == "undefined") p_type="note";
var obj = document.getElementById(this.msgDiv);
if(obj==null) return ;
obj.style.display = "block";
obj.innerHTML = content;
if(p_type=='Success')
obj.className=this.successMsgClassName;
else if(p_type=='Failure')
obj.className=this.failedMsgClassName;
else
obj.className=this.noteMsgClassName;
}
AjaxEdit.prototype.hideMsg = function() {
try{
var obj = document.getElementById(this.msgDiv);
if (typeof obj !="undefined" && obj) obj.style.display = "none";
}catch(e){}
}
AjaxEdit.prototype.removeTag = function(p_tag) {
if(typeof p_tag=="object")	obj.parentNode.removeChild(p_tag);
else {
if (document.getElementById(p_tag))  document.getElementById(p_tag).parentNode.removeChild(document.getElementById(p_tag));
}
}
AjaxEdit.prototype.showDiv = function (p_div) {
if(typeof p_div=="object")	p_div.style.display='block';
else {
if(document.getElementById(p_div)) document.getElementById(p_div).style.display='block';
}
}
AjaxEdit.prototype.hideDiv = function (p_div) {
if(typeof p_div=="object")	p_div.style.display='none';
else {
if(document.getElementById(p_div)) document.getElementById(p_div).style.display='none';
}
}
AjaxEdit.prototype.showDiv = function (p_div) {
if(typeof p_div=="object")	p_div.style.display='inline';
else {
if(document.getElementById(p_div)) document.getElementById(p_div).style.display='inline';
}
}
AjaxEdit.prototype.delLine = function(obj){
var arr=obj.split(",");
for(i=0;i<arr.length-1;i++){
if(arr[i])
document.getElementById(arr[i]+'_tr').parentElement.deleteRow(document.getElementById(arr[i]+'_tr').rowIndex-1);
}
if(arr.length==1)
document.getElementById(arr[0]+'_tr').parentElement.deleteRow(document.getElementById(arr[0]+'_tr').rowIndex-1);
}
AjaxEdit.prototype.rightClick = function(p_nbr,a,p_obj){
var menu=document.getElementById("menu");
a = window.event || a;
if(!a.pageX)a.pageX=a.clientX;
if(!a.pageY)a.pageY=a.clientY;
menu.style.left = a.pageX + "px";
menu.style.top  = a.pageY + "px";
menu.style.display = "block";
return false;
return false;
}
AjaxEdit.prototype.selectCheckbox = function(p_nbr){
var chkBox=p_nbr+"_input";
document.getElementById(chkBox).checked="true";
}
AjaxEdit.prototype.rightClickChild = function(str,a,obj){
var menuChild=document.getElementById("menuChild");
menuChild.style.display = "block";
return false;
}
AjaxEdit.prototype.hideMenuChild = function(){
document.getElementById('menuChild').style.display='none';
}
AjaxEdit.prototype.getScrollTop = function() {
if (typeof document.compatMode != 'undefined' && document.compatMode != 'BackCompat') {
scrollPos = document.documentElement.scrollTop;
}
else if (typeof document.body != 'undefined') {
scrollPos = document.body.scrollTop;
}
return scrollPos;
}
AjaxEdit.prototype.preAddLine = function(p_form,p_posi) {
if (this.isNull(p_form)) p_form=this.AddForm;
if (this.isNull(p_posi)) p_posi="";
this.addLine(this.getValue(p_form,p_posi));
}
AjaxEdit.prototype.callBack = function(z) {
var scrollHeight=AjaxEdit.prototype.getScrollTop() + 150;
var scrollWidth=document.body.clientWidth/2-150;
if (AjaxEdit.prototype.DebugNo==11) divOs.popWindow("===DEBUG===","Close=1;width:90%;height:95%;top:30",AjaxEdit.prototype.clearHtml(z));
if (AjaxEdit.prototype.DebugNo==12) divOs.popWindow("===DEBUG===","Close=1;width:90%;height:95%;top:30",z);
Res= sajaxIO.prototype.getMsg(z);
var TagId = Res.TagId;
var editor = eval(TagId+"_editor");
arr=Res.Content;
if (AjaxEdit.prototype.DebugNo==21) divOs.popWindow("===DEBUG===","Close=1;width:90%;height:95%;top:30",AjaxEdit.prototype.clearHtml(arr));
if (AjaxEdit.prototype.DebugNo==22) divOs.popWindow("===DEBUG===","Close=1;width:90%;height:95%;top:30",arr);
if (AjaxEdit.prototype.DebugNo==31) divOs.popWindow("===DEBUG===","Close=1",Res.RetCode);
if (editor.silent == 0) divOs.closeWaitingWindow('sending');
if(Res.DivId=="_List"){
divOs.setInnerHTML(document.getElementById(editor.ListDiv),Res.Content);
}
else if(Res.DivId=="_PopUp"){
if (!Res.RetCode) Res.RetCode="Pop Up Title";
var infoArr=Res.Other.split('=o=');
var p_style=infoArr[0];
var p_div=infoArr[1];
if (!p_style) p_style="middle";
if (p_style=="large") p_style="width:95%;height:90%;top:20;Close:1";
if (p_style=="middle") p_style="width:420;height:300;top:100;Close:1";
if (p_style=="small") p_style="width:280;height:150;top:150;Close:1";
divOs.popWindow(Res.RetCode,p_style,Res.Content,p_div);
}
else if(Res.DivId=="_Msg"){
editor.showMsg(Res.Content,Res.RetCode) ;
}
else if(Res.DivId=="_QuickEdit"){
editor.refreshEditRow(editor.OperateObj,1);
}
else if(Res.DivId.indexOf("(")>-1){
var fun = Res.DivId.replace("()","(Res)");
eval(fun);
}
else {
if(Res.DivId && document.getElementById(Res.DivId))
document.getElementById(Res.DivId).innerHTML = Res.Content;
}
editor.hideMsg();
if(Res.JsFunction) eval(Res.JsFunction);
}
AjaxEdit.prototype.attachUpload = function(p_successJs,p_faildJs,p_form) {
var FieldInfo='';
for(i=0;i<this.AttachLength;i++){
FieldInfo +=this.AttachUploadField[i]["Name"]+"@@"+this.AttachUploadField[i]["Type"]+"@@"+this.AttachUploadField[i]["Kind"]+"&&";
}
this.successJs = p_successJs;
this.faildJs = p_faildJs;
this.UploadForm = p_form;
var obj = eval("document."+this.UploadForm+".AttachUploadFildHidden");
obj.value=FieldInfo;
}
AjaxEdit.prototype.attachUploadFild = function(p_field,p_name,p_type,p_kind){
this.AttachUploadField[this.AttachLength++] = new AttachUploadField(p_field,p_name,p_type,p_kind);
}
AjaxEdit.prototype.UploadSuccess = function(){
var xmlVal=AjaxEdit.prototype.attachXml();
var obj = eval("document."+this.UploadForm+".__AttachList");
obj.value=xmlVal;
if(this.successJs) eval(this.successJs);
else {
eval("document."+this.UploadForm+".submit()");
}
}
AjaxEdit.prototype.UploadFaild = function(p_type,p_field){
if(p_type==1) {
for(i=0;i<this.AttachLength;i++){
if(this.AttachUploadField[i]["Name"]==p_field){
this.FaildField=this.AttachUploadField[i]["Field"];
break;
}
}
document.getElementById('msgbox2').style.display='block';
document.getElementById('msgbox2').innerHTML=msghead +this.FaildField+":"+this.faildJs+ msgtail;
}
if(p_type==2) {
}
}
AjaxEdit.prototype.addAttachFile = function(p_name,p_tmpname,p_field){
this.addAttach[this.AttachTmpLength++] = new AttachFile(p_name,p_tmpname,p_field);
}
AjaxEdit.prototype.attachXml = function(){
var xmlValue = "<?xml version='1.0'?><Input>";
for(var i=0;i<this.AttachTmpLength;i++){
xmlValue += "<F><Name>"+this.addAttach[i]["RealName"]+"</Name><Tmp>"+this.addAttach[i]["TmpName"]+"</Tmp><Field>"+this.addAttach[i]["FieldName"]+"</Field></F>";
}
xmlValue += "</Input>";
return xmlValue;
}
AjaxEdit.prototype.clearForm = function (p_form){
var formObj=eval("document."+p_form);
var cols = formObj.getElementsByTagName("INPUT");
var length = cols.length;
for (j=0;j<length;j++){
if (cols[j].getAttribute("type")=="text") cols[j].value="";
}
}
AjaxEdit.prototype.focusForm = function (p_form,p_focus){
if (!p_focus) p_focus = "firstText";
if (!p_form) return false;
var formObj=eval("document."+p_form);
if (typeof formObj =='undefined') return false;
var cols = formObj.getElementsByTagName("INPUT");
var length = cols.length;
for (j=0;j<length;j++){
if (cols[j].getAttribute("type")=="text") {
if (p_focus=="firstText") {cols[j].focus();return;}
}
}
}
AjaxEdit.prototype.clearHtml = function(text){
if (typeof( text ) != "string") text = text.toString() ;
text = text.replace(/&/g, "&amp;") ;
text = text.replace(/"/g, "&quot;") ;
text = text.replace(/</g, "&lt;") ;
text = text.replace(/>/g, "&gt;") ;
text = text.replace(/'/g, "&#39;") ;
text = text.replace(/\n/g, "<br />") ;
return text ;
}
AjaxEdit.prototype.clearQuote = function(text){
if (typeof( text ) != "string") text = text.toString() ;
text = text.replace(/"/g, "&quot;") ;
text = text.replace(/'/g, "&apos;") ;
return text ;
}
AjaxEdit.prototype.unClearQuote = function(text){
if (typeof( text ) != "string") text = text.toString() ;
text = text.replace(/&quot;/g, "\"") ;
text = text.replace(/&apos;/g, "'") ;
return text ;
}
AjaxEdit.prototype.chgSort = function(p_form,p_sort,p_callBack){
if (AjaxEdit.prototype.isNull(p_form)) p_form=this.ListForm;
if (AjaxEdit.prototype.isNull(p_callBack)) p_callBack=this.name+"_editor.callBack";
var frm = eval("document."+p_form);
if(this.trim(p_sort) == this.trim(frm.Sort.value)) frm.Direct.value = (-1)*frm.Direct.value;
else frm.Sort.value = p_sort;
frm.Gopage.value = 1;
frm.Begin.value = 0;
var refreshOpStat = this.RefreshStat;
if(refreshOpStat) var refreshOp="Op=search";
else var refreshOp="";
this.sajaxSubmit(refreshOp,p_form,p_callBack);
}
AjaxEdit.prototype.sajaxSubmit = function(p_op,p_form,p_callback,p_silent){
if (this.CheckData && p_form) {
if (!this.checkData(p_form)) return false;
}
if(p_op.indexOf("=")>=0) p_op+="&TagName="+this.TagName;
else p_op = "TagName="+this.TagName;
this.sajaxIO.sajaxSubmit(p_op,p_form,p_callback,'sajaxSubmit',this.sajaxUri);
var scrollHeight= 180;
var scrollWidth=document.body.clientWidth/2-100;
this.silent = 0;
if (!p_silent) divOs.openWaitingWindow(divOs.waitWord,"sending");
else this.silent=1;
this.editing = false;
}
AjaxEdit.prototype.isNull = function(p_val){
var isnull = false;
if (p_val==null || p_val=="null" || p_val=="")  isnull = true;
return isnull;
}
AjaxEdit.prototype.editRow = function(obj,p_form,p_callBack){
if (this.editing) return;
if (this.isNull(p_form)) p_form=this.ListForm;
if (this.isNull(p_callBack)) p_callBack=this.name+"_editor.callBack";
var par=obj.parentNode.parentNode;
var cols = par.getElementsByTagName("TD");
var length = cols.length;
for (j=0;j<length-1;j++){
if (this.isNull(cols[j].getAttribute("id"))) continue;
var myid=cols[j].getAttribute("id");
var idarr=myid.split("-+-");
var sendid="TB_"+idarr[1];
if(cols[j].innerHTML=="&nbsp;"){
this.RowValue[j]="&nbsp;";
this.RowText[j]="&nbsp;";
}
else {
this.RowValue[j]=escape(this.clearQuote(this.trim(cols[j].innerHTML)));
if (this.Browser=="IE") {
if (idarr[3] == 'select') this.RowText[j]=escape(this.clearQuote(this.trim(cols[j].childNodes[0].innerText)));
else this.RowText[j]=escape(this.clearQuote(this.trim(cols[j].innerText)));
}
else {
if (idarr[3] == 'select') this.RowText[j]=escape(this.clearQuote(this.trim(cols[j].childNodes[0].textContent)));
else this.RowText[j]=escape(this.clearQuote(this.trim(cols[j].textContent)));
}
}
if (idarr[3] == 'select'){
document.getElementById(this.name+'_selectField_'+idarr[0]).style.display="block";
document.getElementById(this.name+'_originValue_'+idarr[0]).style.display="none";
document.getElementById(this.name+'_select_'+idarr[0]).name=sendid;
}
else {
if (idarr[2] == 'boolean') {
var chked="";
if (unescape(this.trim(this.RowText[j]))==1) chked = " checked=\"checked\" ";
cols[j].innerHTML="<input type=\"checkbox\" class=\"checkbox\" id=\""+idarr[1]+"\" "+chked+" name=\""+sendid+"\"/ value=\"1\">";
}else {
var showCal ="";
var inputWidth ="90%";
if (idarr[2] == 'date') {
showCal = "<input class=\"checkbox\" onclick=\"showCalendar(document."+this.ListForm+"."+sendid+",this);return false\" type=\"image\" alt=\"Calendar\" src=\""+this.PicUrl+"/cal.gif\" align=\"absMiddle\" \/>";
inputWidth = "80%";
}
cols[j].innerHTML="<input type=\"text\" class=\"inputtext\" id=\""+idarr[1]+"\" name=\""+sendid+"\" style=\"width:"+inputWidth+";\" value=\""+unescape(this.trim(this.RowText[j]))+"\">"+showCal;
}
}
}
this.OperationInnerHtml = escape(obj.parentNode.innerHTML);
this.focusForm(this.name+"_ListForm");
if (idarr) obj.parentNode.innerHTML="<a href='javascript:void(null)' onclick=\"if("+this.name+"_editor.checkData('"+p_form+"')){"+this.name+"_editor.saveNewValue(this);"+this.name+"_editor.sajaxSubmit('Op=edit&keyId="+idarr[0]+"','"+p_form+"','"+p_callBack+"');"+this.name+"_editor.editing=false;}\"><img border='0' src='"+this.PicUrl+"/save.gif' alt='"+this.QuickEditSave+"' /></a><a href='javascript:void(null)' onclick=\""+this.name+"_editor.refreshEditRow(this)\"><img border='0' src='"+this.PicUrl+"/close.gif' alt=\""+this.QuickEditCancel+"\" /></a>";
this.editing = true;
}
AjaxEdit.prototype.saveNewValue = function(obj){
var par=obj.parentNode.parentNode;
this.OperateObj = obj;
var cols = par.getElementsByTagName("TD");
var length = cols.length;
for (j=0;j<length-1;j++){
if (this.isNull(cols[j].getAttribute("id"))) continue;
var myid=cols[j].getAttribute("id");
var idarr=myid.split("-+-");
if (idarr[3] == 'select'){
var SelObj = cols[j].getElementsByTagName("SELECT");
var SelText = SelObj[0].options[SelObj[0].selectedIndex].text;
this.RowNewValue[j] = escape(this.clearQuote(SelText));
}
else this.RowNewValue[j] = escape(this.clearQuote(cols[j].childNodes[0].value));
}
}
AjaxEdit.prototype.refreshList = function(str){
var formName = this.name+"_searchTmp";
var obj = eval("document."+formName);
p_form = this.name+"_searchForm";
if(obj && obj.parentNode.style.display !="none") p_form =this.name+"_searchTmp";
var refreshOpStat = this.RefreshStat;
if(refreshOpStat && str) var refreshOp="Op=search&refStr="+str;
else if(refreshOpStat && !str) var refreshOp="Op=search";
else var refreshOp="";
this.sajaxSubmit(refreshOp,p_form,this.callBack);
}
AjaxEdit.prototype.checkData = function(p_form,p_prefix){
if (!p_prefix) p_prefix = "TB_";
var FormData ;
try {
FormData = eval(this.name+"_FormData");
}catch(e){return true;}
var checked = FormData.datavalid(p_form,p_prefix);
if (checked == false) return false;
return true;
}
AjaxEdit.prototype.refreshEditRow = function(obj,p_newValue){
this.editing = false;
var par=obj.parentNode.parentNode;
if (p_newValue) par.className="row_operate";
var cols = par.getElementsByTagName("TD");
var length = cols.length;
for (j=0;j<length-1;j++){
if (this.isNull(cols[j].getAttribute("id"))) continue;
var myid=cols[j].getAttribute("id");
var idarr=myid.split("-+-");
if (p_newValue) {
if (idarr[3]=="select") {
cols[j].innerHTML=this.unClearQuote(unescape(this.RowValue[j].replace(this.RowText[j]+"%3C\/span%3E",this.RowNewValue[j]+"%3C\/span%3E")));
}
else cols[j].innerHTML=this.unClearQuote(unescape(this.RowValue[j].replace(eval("/"+this.RowText[j]+"/g"),this.RowNewValue[j])));
}
else cols[j].innerHTML=this.unClearQuote(unescape(this.RowValue[j]));
}
obj.parentNode.innerHTML=unescape(this.OperationInnerHtml);
}
AjaxEdit.prototype.view = function(p_page){
eval("document."+this.ListForm+".Gopage.value="+p_page);
this.sajaxSubmit('',this.ListForm,this.callBack);
}
AjaxEdit.prototype.search = function(p_form){
if (!p_form) p_form = this.name+"_searchForm";
this.RefreshStat =1;
this.sajaxSubmit('Op=search',p_form,this.callBack);
}
AjaxEdit.prototype.showDetail = function(p_seq,p_noPop,p_param){
if (!p_seq) return false;
if (!p_param) p_param="";
if (p_noPop)
this.sajaxSubmit('Op=detail&NoPop=1'+p_param+'&seq='+p_seq,'',this.callBack);
else
this.sajaxSubmit('Op=detail'+p_param+'&seq='+p_seq,'',this.callBack);
}
AjaxEdit.prototype._update = function(p_form){
if (!p_form) p_form = this.ListForm;
if (this.checkData(p_form)){
this.sajaxSubmit('Op=update',p_form,this.callBack);
}
}
AjaxEdit.prototype.moveTo = function(p_key){
if (!p_key) return false;
if (!chkSel(this.ListForm,'Chk[]')) {
alert(this.NoChk);
return false;
}
this.sajaxSubmit('Op=moveTo&moveKey='+p_key,this.ListForm,this.callBack);
}
AjaxEdit.prototype._delete = function(p_id) {
if (p_id) {
if (confirm(this.ConfirmDelete)) this.sajaxSubmit('Op=delete&Chk='+p_id,'',this.callBack);
return;
}
if (!chkSel(this.ListForm,'Chk[]')) {
alert(this.NoChk);
return false;
}
if (confirm(this.ConfirmDelete)) this.sajaxSubmit('Op=delete',this.ListForm,this.callBack);
}
AjaxEdit.prototype.deleteOne = function(p_id,p_extparam) {
if (p_id) {
if(p_extparam) p_extparam="&"+p_extparam;
else p_extparam="";
if (confirm(this.ConfirmDelete)) this.sajaxSubmit('Op=deleteOne&Chk='+p_id+p_extparam,'',this.callBack);
}
}
AjaxEdit.prototype.showAdd = function(p_title,p_style,p_div) {
if (!p_title) p_title="Quick Add";
if (!p_style) p_style="middle";
if (!p_div) p_div=this.name+"_popDiv";
if (p_style=="large") p_style="width:95%;height:90%;top:20;Close:1";
if (p_style=="middle") p_style="width:420;height:300;top:100;Close:1";
if (p_style=="small") p_style="width:280;height:150;top:150;Close:1";
try {
var Obj_addDiv = eval(this.name+"_addDiv");
}
catch(e){
alert(e.message);
return false;
}
divOs.popWindow(p_title,p_style,Obj_addDiv,p_div);
this.focusForm(this.name+"_AddForm","firstText");
}
AjaxEdit.prototype.saveAdd = function(p_noclose,p_prefix) {
if (!p_prefix) p_prefix = "TB_";
var FormData = eval(this.name+"_FormData");
var checked = FormData.datavalid(this.name+"_AddForm",p_prefix);
if (checked == false) return false;
this.sajaxSubmit("Op=add",this.name+"_AddForm",this.callBack);
}
AjaxEdit.prototype.saveFull = function(p_id,p_form,p_noclose,p_prefix) {
if (!p_prefix) p_prefix = "TB_";
var FormData = eval(this.name+"_FormData");
if (!p_form) p_form = this.name+"_FullForm";
var checked = FormData.datavalid(p_form,p_prefix);
if (checked == false) return false;
this.sajaxSubmit("FullEdit=1&Op=edit&keyId="+p_id,p_form,this.callBack);
}
AjaxEdit.prototype.createDivNode = AjaxEdit.prototype.popWindow;
AjaxEdit.prototype.trim = function(p_str) {
p_str= p_str.replace(/(^\s*)|(\s*$)/g, "");
p_str= p_str.replace(/(^&nbsp;*)|(&nbsp;*$)/g, "");
return p_str;
}
AjaxEdit.prototype.chkSel = function(p_chk){
if (!chkSel(this.ListForm,p_chk)) {
alert(this.NoChk);
return false;
}
return true;
}
var currentForm ;
function frm_chkForm(p_form,p_prefix) {
var dtCheck = new dataCheck();
dtCheck.setField(eval("new Array("+p_form.FieldList.value+")"));
dtCheck.setName(eval("new Array("+p_form.FieldName.value+")"));
dtCheck.setNull(eval("new Array("+p_form.FieldNull.value+")"));
dtCheck.setType(eval("new Array("+p_form.FieldType.value+")"));
dtCheck.setMsg(eval("new Array("+p_form.FieldMsg.value+")"));
return dtCheck.datavalid(p_form,p_prefix);
}
function frm_formPreview(p_form,p_prefix) {
var par = p_form;
var dispDiv ;
var reviewTable ,reviewDiv;
for(i=0;i<par.childNodes.length;i++) {
if(par.childNodes[i].id=='formdisplay') {
dispDiv = par.childNodes[i];
}
if(par.childNodes[i].id=='formpreview') {
reviewDiv = par.childNodes[i];
}
}
for(i=0;i<reviewDiv.childNodes.length;i++) {
if(reviewDiv.childNodes[i].tagName=='TABLE') {
for(j=0;j<reviewDiv.childNodes[i].childNodes.length;j++) {
if(reviewDiv.childNodes[i].childNodes[j].tagName=='TBODY') {
reviewTable = reviewDiv.childNodes[i].childNodes[j]; break;
}
}
break;
}
}
frmElements = new Object();
var elm ;
var name;
var val = "";
var sepField = new Array();
var sepField_max = new Array();
var sepCounter=0;
for(i=0;i<p_form.elements.length;i++){
elm = p_form.elements[i];
name = elm.name;
if(name.indexOf("\[\]")>0) name = name.substr(0,name.indexOf("\[\]"));
if(elm.type=='checkbox' || elm.type=='radio') {
if(elm.checked) frmElements[name] = ((typeof(frmElements[name])=='undefined')?'':frmElements[name]+"<br>")+elm.value;
}
else if(elm.type=='select-one') {
if(elm.options[elm.selectedIndex].text)
frmElements[name] = elm.options[elm.selectedIndex].text;
else
frmElements[name] = elm.value;
}
else {
frmElements[name] = elm.value;
}
if(name.indexOf("SepMax")>0) {
sepField[sepCounter] = name.substr(10);
sepField_max[sepCounter++] = elm.value;
}
}
for(i=0;i<sepField.length;i++) {
seq = sepField[i];
max = sepField_max[i];
var Symbol = eval('p_form.FrmSepSymbol_'+seq+'.value');
for(j=1;j<max;j++) {
val+=Symbol+eval('p_form.FrmSep_'+seq+'_'+j+'.value');
}
name = p_prefix+seq;
frmElements[name] = ((typeof(frmElements[name])=='undefined')?'':frmElements[name])+val;
}
for(i=0;i<reviewTable.childNodes.length;i++) {
var trNode = reviewTable.childNodes[i];
if(trNode.tagName!='TR') continue;
for(j=0;j<trNode.cells.length;j++) {
var tdNode = trNode.cells[j];
if(tdNode.id) {
name = p_prefix+tdNode.id;
if(typeof(frmElements[name])=="undefined") frmElements[name] = "";
tdNode.innerHTML = "<strong>"+strUtil.prototype.htmlspecialchars(frmElements[name])+"&nbsp;</strong>";
}
}
}
dispDiv.style.display="none";
reviewDiv.style.display="block";
}
function frm_uploaded(type,res) {
var obj = currentForm.parentNode;
if(type==1){
currentForm.FileUploadName.value=res;
random_num = (Math.round((Math.random()*1000)+1))
obj.id="formdiv_"+random_num;
divOs.submitForm(obj.id,currentForm.oldAction,currentForm);
return false;
}
}
function frm_submitForm(p_form,p_url) {
upField = p_form.UploadField.value.split(",");
for(i=0;i<upField.length-1;i++) {
upobj = eval('p_form.Frm_'+upField[i]);
if(upobj.value) {
currentForm = p_form;
p_form.oldAction = p_url;
p_form.submit();
return;
}
}
eval(p_form.name+"_editor").setUri(p_url);
eval(p_form.name+"_editor").sajaxSubmit('FormName='+p_form.name,p_form.name,'frm_callBack')
}
function frm_callBack(z) {
var dispTable ;
var reviewTable ;
var formmsg ;
var formbackbtn ;
Res= sajaxIO.prototype.getMsg(z);
var frm = eval("document."+Res.Other);
for(i=0;i<frm.childNodes.length;i++) {
if(frm.childNodes[i].id=='formdisplay') {
dispTable = frm.childNodes[i];
}
else if(frm.childNodes[i].id=='formpreview') {
reviewTable = frm.childNodes[i];
}
else if(frm.childNodes[i].id=='formmsg') {
formmsg = frm.childNodes[i];
}
else if(frm.childNodes[i].id=='formbackbtn') {
formbackbtn = frm.childNodes[i];
}
}
if(Res.RetCode==1){
formmsg.style.display="block";
divOs.setInnerHTML(formmsg,Res.Content);
dispTable.style.display="none";
reviewTable.style.display="none";
formbackbtn.style.display="block";
}
else{
divOs.openAlertWindow(divOs.AlertFailed,Res.Content);
}
if(eval(frm.name+"_editor").silent == 0) divOs.closeWaitingWindow('sending');
}
function frm_backForm(p_form) {
var par = p_form;
var dispTable ;
var reviewTable ;
var formmsg ;
var formbackbtn ;
for(i=0;i<par.childNodes.length;i++) {
if(par.childNodes[i].id=='formdisplay') {
dispTable = par.childNodes[i];
}
else if(par.childNodes[i].id=='formpreview') {
reviewTable = par.childNodes[i];
}
else if(par.childNodes[i].id=='formmsg') {
formmsg = par.childNodes[i];
}
else if(par.childNodes[i].id=='formbackbtn') {
formbackbtn = par.childNodes[i];
}
}
dispTable.style.display="block";
reviewTable.style.display="none";
formmsg.style.display="none";
formbackbtn.style.display="none";
}
function frm_cancelSubmit(p_form) {
var par = p_form;
var dispTable ;
var reviewTable ;
for(i=0;i<par.childNodes.length;i++) {
if(par.childNodes[i].id=='formdisplay') {
dispTable = par.childNodes[i];
}
if(par.childNodes[i].id=='formpreview') {
reviewTable = par.childNodes[i];
}
}
dispTable.style.display="block";
reviewTable.style.display="none";
}
function dealHln(p_ishome,p_op) {
var itemId,item;
if(p_ishome) {
setHlnStat(_HomeHln,"block");
setHlnStat(_InternalHln,"none");
}
if(!p_ishome) {
setHlnStat(_HomeHln,"none");
setHlnStat(_InternalHln,"block");
}
if(p_op=="logout") {
setHlnStat(_LoginHln,"none");
setHlnStat(_LogoutHln,"block");
}
if(p_op=="login") {
setHlnStat(_LoginHln,"block");
setHlnStat(_LogoutHln,"none");
}
}
function setHlnStat(p_hash,p_style){
var itemId,item;
while(!p_hash.isEOF()){
itemId = p_hash.read()
item = document.getElementById(itemId);
if(item!=null && typeof(item)!='undefined')
item.style.display = p_style;
}
p_hash.resetPointer();
}
function setRecentCookie(name,val,separator,path,duration,cnt){
var str = divOs.Cookie.getCookie(name);
if(str!=undefined){
var val_arr = new Array();
var val_tmp = new Array();
val_arr = str.split(separator);
val_tmp[0] = val;
for(var i=0,j=1;i<val_arr.length;i++){
if(cnt && j==cnt) continue;
if(val_arr[i]==val) continue;
val_tmp[j] = val_arr[i];
j++;
}
str = val_tmp.join(separator);
divOs.Cookie.setCookie(name,str,duration,path);
}
else{
divOs.Cookie.setCookie(name,val,duration,path);
}
}
function dealRefresh(imgId,codeId,img,code) {
document.getElementById(imgId).src=img;
document.getElementById(codeId).value=code;
}
function refreshAuthCode(p_editor,p_program,p_imgsrc,p_code) {
var oldUri = p_editor.sajaxUri
p_editor.setUri(p_program);
p_editor.sajaxSubmit('Op=refresh&TagId='+p_editor.TagName+'&imgid='+p_imgsrc+'&codeid='+p_code,'','AjaxEdit.prototype.callBack ')
p_editor.setUri(oldUri);
}
function showCmField(p_field,p_default) {
var val = new strUtil(divOs.Cookie.getCookie(p_field));
if(trim(val.String)!="") return val.String;
else return p_default;
}
function checkLogin(p_cookiename) {
if(divOs.Cookie.existCookie(p_cookiename)) return true;
return false;
}
function loginFirst(p_event,p_cookiename,p_front,p_title,p_js) {
var navi = window.navigator.userAgent.toUpperCase();
if (navi.indexOf("MSIE 6.0")>=1) {
if(!p_event) return true;
_chklogin_editor = new AjaxEdit('_chklogin');
_chklogin_editor.setUri(p_front+"/checklogin.php");
_chklogin_editor.setVar('Front',p_front);
_chklogin_editor.setVar('Title',p_title);
_chklogin_editor.setVar('Js',escape(p_js));
_chklogin_editor.sajaxSubmit('','',loginFirst_cb);
return false;
}
if(!divOs.Cookie.existCookie(p_cookiename)) {
var url = p_front+'/showmodule.php?Mo=34&Type=poplogin&Nbr=0&Js='+escape(p_js);
divOs.openPopSajaxUrl(p_title,"Close=1;Static=0;width=330;height=250;top:160;",url,'PopLogin',p_event);
return false;
}
else return true;
}
function loginFirst_cb(z) {
Res= sajaxIO.prototype.getMsg(z);
if(Res.RetCode=='Login') {
eval(unescape(_chklogin_editor.Js)+";");
}else if(Res.RetCode=='Logout') {
var url=_chklogin_editor.Front+'/showmodule.php?Mo=34&Type=poplogin&Nbr=0&Js='+_chklogin_editor.Js;
divOs.openPopSajaxUrl(_chklogin_editor.Title,"Close=1;Static=0;width=330;height=250;top:160;",url,'PopLogin');
}
}
function logoutSuccess() {
dealHln('','logout') ;
onActionTrigger(0);
}
function loginSuccess() {
dealHln('','login') ;
onActionTrigger(1);
}
function reload(p_div,p_moname,p_moid,p_type,p_nbr,p_front,p_loading,p_blankimg) {
var el = document.getElementById(p_div);
var dynamicLoad =  "<div class=\"module-loading\"><div class=\"md_top\"><div class=\"mt_03\"><div class=\"mt_02\"><div class=\"mt_01\"><h3>"+p_moname+"</h3></div></div></div></div><div class=\"md_middle\"><div class=\"mm_03\"><div class=\"mm_02\"><div class=\"mm_01\">"+p_loading+"</div></div></div></div><div class=\"md_bottom\"><div class=\"mb_03\"><div class=\"mb_02\"><div class=\"mb_01\"></div></div></div></div></div></div>\n";
dynamicLoad+=  "<img src=\""+p_blankimg+"\" border=\"0\" onload=\"divOs.openSajaxUrl('"+p_div+"','"+p_front+"/showmodule.php?Mo="+p_moid+"&Type="+p_type+"&OO=1&Nbr="+p_nbr+"');\"/>\n";
divOs.setInnerHTML(el,dynamicLoad);
}
function fixMenuPosition(par,id,cnt) {
var p=par;
var scrollTop ;
if(document.documentElement)
scrollTop = document.documentElement.scrollTop;
else
scrollTop = document.body.scrollTop;
var clientHeight = document.documentElement.clientHeight;
var obj = document.getElementById(id);
if(obj.style.display=="none") obj.style.display="block";
offsetTop = 0;
while(true) {
if(par.parentNode.tagName=='UL' && par.parentNode.id=='MenuTop') {
offsetTop+= par.offsetTop;
break;
}
if(par.parentNode.tagName=='UL' ) {
offsetTop+=cnt*25;
}
par = par.parentNode;
}
var diff = ( offsetTop+obj.clientHeight ) +68 - (scrollTop+clientHeight);
if(diff>0) {
obj.style.top= "-"+diff+"px";
}
else  {
obj.style.top= "-1px";
}
}
function chkVote(p_form,p_cnt) {
if(typeof p_cnt=='undefined' ) p_cnt=0;
var elements = p_form.elements;
var len = elements.length;
var elements = p_form.elements;
var voteName= "";
var votedName= "";
var preName = "";
var preVote ="";
var vName ="";
var votedCnt = 0;
for(var i=0;i<len;i++){
vName = elements[i].name;
if(elements[i].type){
switch(elements[i].type){
case "checkbox":
if(vName!=preName) { preName = vName;  voteName +=","+vName; }
if(elements[i].checked){
if(preVote!=vName) votedName +=","+vName;
preVote = vName;
votedCnt++;
}
break;
case "radio":
if(vName!=preName) { preName = vName;  voteName +=","+vName; }
if(elements[i].checked){
votedName +=","+vName;
preVote = vName;
};
break;
}
}
}
if(voteName!=votedName) return -1;
else if(p_cnt>0 && votedCnt>p_cnt) return -2;
else return 1;
}
function addBookmark(url,title) {
if (window.sidebar) {
window.sidebar.addPanel(title, url,"");
} else if( document.all ) {
window.external.AddFavorite( url, title);
} else if( window.opera && window.print ) {
return true;
}
}
function resizeTrigger() {
var func = window.onresize;
if(typeof(func)!="function") return;
func();
}
function onActionTrigger(p_type) {
for(var i=0;i<triggerAction[p_type].length;i++) {
try{
eval(triggerAction[p_type][i]);
}catch(e){}
}
}
triggerAction = new Array();
triggerAction[0] = new Array();
triggerAction[1] = new Array();
