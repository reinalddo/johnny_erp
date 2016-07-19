/*

CASCADING POPUP MENUS v5.2beta (c) 2001-2003 Angus Turnbull, http://www.twinhelix.com
Altering this notice or redistributing this file is prohibited.
* @rev    Ene-10-08     Agregar opciones de Estadisticas de Compras, por año
*/

var isDOM=document.getElementById?1:0,isIE=document.all?1:0,isNS4=navigator.appName=='Netscape'&&!isDOM?1:0,isIE4=isIE&&!isDOM?1:0,isOp=self.opera?1:0,isDyn=isDOM||isIE||isNS4;function getRef(i,p){p=!p?document:p.navigator?p.document:p;return isIE?p.all[i]:isDOM?(p.getElementById?p:p.ownerDocument).getElementById(i):isNS4?p.layers[i]:null;};function getSty(i,p){var r=getRef(i,p);return r?isNS4?r:r.style:null;};if(!self.LayerObj)var LayerObj=new Function('i','p','this.ref=getRef(i,p);this.sty=getSty(i,p);return this');function getLyr(i,p){return new LayerObj(i,p)};function LyrFn(n,f){LayerObj.prototype[n]=new Function('var a=arguments,p=a[0],px=isNS4||isOp?0:"px";'+'with(this){'+f+'}');};LyrFn('x','if(!isNaN(p))sty.left=p+px;else return parseInt(sty.left)');LyrFn('y','if(!isNaN(p))sty.top=p+px;else return parseInt(sty.top)');LyrFn('vis','sty.visibility=p');LyrFn('bgColor','if(isNS4)sty.bgColor=p?p:null;'+'else sty.background=p?p:"transparent"');LyrFn('bgImage','if(isNS4)sty.background.src=p?p:null;'+'else sty.background=p?"url("+p+")":"transparent"');LyrFn('clip','if(isNS4)with(sty.clip){left=a[0];top=a[1];right=a[2];bottom=a[3]}'+'else sty.clip="rect("+a[1]+"px "+a[2]+"px "+a[3]+"px "+a[0]+"px)" ');LyrFn('write','if(isNS4)with(ref.document){write(p);close()}else ref.innerHTML=p');LyrFn('alpha','var f=ref.filters,d=(p==null);if(f){'+'if(!d&&sty.filter.indexOf("alpha")==-1)sty.filter+=" alpha(opacity="+p+")";'+'else if(f.length&&f.alpha)with(f.alpha){if(d)enabled=false;else{opacity=p;enabled=true}}}'+'else if(isDOM)sty.MozOpacity=d?"":p/100');function setLyr(v,dw,p){if(!setLyr.seq)setLyr.seq=0;if(!dw)dw=0;var o=!p?isNS4?self:document.body:!isNS4&&p.navigator?p.document.body:p,IA='insertAdjacentHTML',AC='appendChild',id='_sl_'+setLyr.seq++;if(o[IA])o[IA]('beforeEnd','<div id="'+id+'" style="position:absolute"></div>');else if(o[AC]){var n=document.createElement('div');o[AC](n);n.id=id;n.style.position='absolute';}else if(isNS4){var n=new Layer(dw,o);id=n.id;}var l=getLyr(id,p);with(l)if(ref){vis(v);x(0);y(0);sty.width=dw+(isNS4?0:'px')}return l;};if(!self.page)var page={win:self,minW:0,minH:0,MS:isIE&&!isOp};page.db=function(p){with(this.win.document)return(isDOM?documentElement[p]:0)||body[p]||0};page.winW=function(){with(this)return Math.max(minW,MS?db('clientWidth'):win.innerWidth)};page.winH=function(){with(this)return Math.max(minH,MS?db('clientHeight'):win.innerHeight)};page.scrollX=function(){with(this)return MS?db('scrollLeft'):win.pageXOffset};page.scrollY=function(){with(this)return MS?db('scrollTop'):win.pageYOffset};function addProps(obj,data,names,addNull){for(var i=0;i<names.length;i++)if(i<data.length||addNull)obj[names[i]]=data[i];};function PopupMenu(myName){this.myName=myName;this.showTimer=this.hideTimer=this.showDelay=0;this.hideDelay=500;this.menu=[];this.litNow=[];this.litOld=[];this.overM='';this.overI=0;this.hideDocClick=0;this.actMenu=null;PopupMenu.list[myName]=this;};PopupMenu.list=[];var PmPt=PopupMenu.prototype;PmPt.callEvt=function(mN,iN,evt){var i=this.menu[mN][iN],r=this[evt]?(this[evt](mN,iN)==false):0;if(i[evt]){if(i[evt].substr)i[evt]=new Function('mN','iN',i[evt]);r|=(i[evt](mN,iN)==false);}return r;};PmPt.over=function(mN,iN){with(this){clearTimeout(hideTimer);overM=mN;overI=iN;var cancel=iN?callEvt(mN,iN,'onmouseover'):0;litOld=litNow;litNow=[];var litM=mN,litI=iN;if(mN)do{litNow[litM]=litI;litI=menu[litM][0].parentItem;litM=menu[litM][0].parentMenu;}while(litM);var same=1;for(var z in menu)same&=(litNow[z]==litOld[z]);if(same)return 1;clearTimeout(showTimer);for(var thisM in menu)with(menu[thisM][0]){if(!lyr)continue;lI=litNow[thisM];oI=litOld[thisM];if(lI!=oI){if(lI)changeCol(thisM,lI);if(oI)changeCol(thisM,oI);}if(!lI)clickDone=0;if(isRoot)continue;if(lI&&!visNow)doVis(thisM,1);if(!lI&&visNow)doVis(thisM,0);}nextMenu='';if(!cancel&&menu[mN]&&menu[mN][iN].type=='sm:'){var m=menu[mN],targ=m[iN].href;if(!menu[targ])return 0;if(m[0].clickSubs&&!m[0].clickDone)return 0;nextMenu=targ;if(showDelay)showTimer=setTimeout(myName+'.doVis("'+targ+'",1)',showDelay);else doVis(targ,1);}return 1;}};PmPt.out=function(mN,iN){with(this){if(mN!=overM||iN!=overI)return;var thisI=menu[mN][iN],cancel=callEvt(mN,iN,'onmouseout');if(thisI.href!=nextMenu){clearTimeout(showTimer);nextMenu='';}if(hideDelay&&!cancel){var delay=(menu[mN][0].isRoot&&(thisI.type!='sm:'))?50:hideDelay;hideTimer=setTimeout(myName+'.over("",0)',delay);}overM='';overI=0;}};PmPt.click=function(mN,iN){with(this){var m=menu[mN];if(callEvt(mN,iN,'onclick'))return 0;with(m[iN])S:switch(type){case 'sm:':{if(m[0].clickSubs){m[0].clickDone=1;doVis(href,1);return 1;}break S;}case 'js:':{eval(href);break S}case '':type='window';default:if(href)eval(type+'.location.href="'+href+'"');}return over('',0);}};PmPt.changeCol=function(mN,iN,fc){with(this.menu[mN][iN]){if(!lyr||!lyr.ref)return;var bgFn=outCol!=overCol?(outCol.indexOf('.')==-1?'bgColor':'bgImage'):0;var ovr=(this.litNow[mN]==iN)?1:0,doFX=(!fc&&this.litNow[mN]!=this.litOld[mN]);var col=ovr?overCol:outCol;if(fade[0]){clearTimeout(timer);col='#';count=Math.max(0,Math.min(count+(2*ovr-1)*parseInt(fade[ovr][0]),100));var oc,nc,hexD='0123456789ABCDEF';for(var i=1;i<4;i++){oc=parseInt('0x'+fade[0][i]);nc=parseInt(oc+(parseInt('0x'+fade[1][i])-oc)*(count/100));col+=hexD.charAt(Math.floor(nc/16)).toString()+hexD.charAt(nc%16);}if(count%100>0)timer=setTimeout(this.myName+'.changeCol("'+mN+'",'+iN+',1)',50);}if(bgFn&&isNS4)lyr[bgFn](col);var reCSS=(overClass!=outClass||outBorder!=overBorder);if(doFX)with(lyr){if(!this.noRW&&(overText||overInd||isNS4&&reCSS))write(this.getHTML(mN,iN,ovr));if(!isNS4&&reCSS){ref.className=(ovr?overBorder:outBorder);var chl=(isDOM?ref.childNodes:ref.children);if(chl&&!overText)for(var i=0;i<chl.length;i++)chl[i].className=ovr?overClass:outClass;}}if(bgFn&&!isNS4)lyr[bgFn](col);if(doFX&&outAlpha!=overAlpha)lyr.alpha(ovr?overAlpha:outAlpha);}};PmPt.position=function(posMN){with(this){for(mN in menu)if(!posMN||posMN==mN)with(menu[mN][0]){if(!lyr||!lyr.ref||!visNow)continue;var pM,pI,newX=eval(offX),newY=eval(offY);if(!isRoot){pM=menu[parentMenu];pI=pM[parentItem].lyr;if(!pI)continue;}var eP=eval(par),pW=(eP&&eP.navigator?eP:window);with(pW.page)var sX=scrollX(),wX=sX+winW()||9999,sY=scrollY(),wY=winH()+sY||9999;var sb=page.MS?5:20;if(pM&&typeof(offX)=='number')newX=Math.max(sX,Math.min(newX+pM[0].lyr.x()+pI.x(),wX-menuW-sb));if(pM&&typeof(offY)=='number')newY=Math.max(sY,Math.min(newY+pM[0].lyr.y()+pI.y(),wY-menuH-sb));lyr.x(newX);lyr.y(newY);}}};PmPt.doVis=function(mN,show){with(this){var m=menu[mN],mA=(show?'show':'hide')+'Menu';if(!m||!m[0].lyr||!m[0].lyr.ref)return;m[0].visNow=show;if(show)position(mN);var p=m[0].parentMenu;if(p)m[0].lyr.sty.zIndex=m[0].zIndex=menu[p][0].zIndex+2;if(this[mA])this[mA](mN);else m[0].lyr.vis(show?'visible':'hidden');}};function ItemStyle(){var names=['len','spacing','popInd','popPos','pad','outCol','overCol','outClass','overClass','outBorder','overBorder','outAlpha','overAlpha','normCursor','nullCursor'];addProps(this,arguments,names,1);};PmPt.startMenu=function(mName){with(this){if(!menu[mName]){menu[mName]=new Array();menu[mName][0]=new Object();}actMenu=menu[mName];aM=actMenu[0];actMenu.length=1;var names=['name','isVert','offX','offY','width','itemSty','par','clickSubs','clickDone','visNow','parentMenu','parentItem','oncreate','isRoot'];addProps(aM,arguments,names,1);aM.extraHTML='';aM.menuW=aM.menuH=0;aM.zIndex=1000;if(!aM.lyr)aM.lyr=null;if(mName.substring(0,4)=='root'){aM.isRoot=1;aM.oncreate=new Function('this.visNow=1;'+myName+'.position("'+mName+'");this.lyr.vis("visible")');}return aM;}};PmPt.addItem=function(){with(this)with(actMenu[0]){var aI=actMenu[actMenu.length]=new Object();var names=['text','href','type','itemSty','len','spacing','popInd','popPos','pad','outCol','overCol','outClass','overClass','outBorder','overBorder','outAlpha','overAlpha','normCursor','nullCursor','iX','iY','iW','iH','fW','fH','overText','overInd','lyr','onclick','onmouseover','onmouseout'];addProps(aI,arguments,names,1);var iSty=(arguments[3]?arguments[3]:actMenu[0].itemSty);for(prop in iSty)if(aI[prop]+''=='undefined')aI[prop]=iSty[prop];var r=RegExp,re=/^SWAP:(.*)\^(.*)$/;if(aI.text.match(re)){aI.text=r.$1;aI.overText=r.$2}if(aI.popInd.match(re)){aI.popInd=r.$1;aI.overInd=r.$2}aI.timer=aI.count=0;aI.fade=[];for(var i=0;i<2;i++){var oC=i?'overCol':'outCol';if(aI[oC].match(/^(\d+)\#(..)(..)(..)$/)){aI[oC]='#'+r.$2+r.$3+r.$4;aI.fade[i]=[r.$1,r.$2,r.$3,r.$4];}}if(aI.outBorder&&isNS4)aI.pad++;aI.iW=(isVert?width:aI.len);aI.iH=(isVert?aI.len:width);var lastGap=(actMenu.length>2)?actMenu[actMenu.length-2].spacing:0;var spc=((actMenu.length>2)&&aI.outBorder?1:0);if(isVert){menuH+=lastGap-spc;aI.iX=0;aI.iY=menuH;menuW=width;menuH+=aI.iH;}else{menuW+=lastGap-spc;aI.iX=menuW;aI.iY=0;menuW+=aI.iW;menuH=width;}return aI;}};PmPt.getHTML=function(mN,iN,isOver){with(this){var itemStr='';with(menu[mN][iN]){var textClass=(isOver?overClass:outClass),txt=(isOver&&overText?overText:text),popI=(isOver&&overInd?overInd:popInd);if((type=='sm:')&&popI){if(isNS4)itemStr+='<layer class="'+textClass+'" left="'+((popPos+fW)%fW)+'" top="'+pad+'" height="'+(fH-2*pad)+'">'+popI+'</layer>';else itemStr+='<div class="'+textClass+'" style="position:absolute;left:'+((popPos+fW)%fW)+'px;top:'+pad+'px;height:'+(fH-2*pad)+'px">'+popI+'</div>';}if(isNS4)itemStr+=(outBorder?'<span class="'+(isOver?overBorder:outBorder)+'"><spacer type="block" width="'+(fW-8)+'" height="'+(fH-8)+'"></span>':'')+'<layer left="'+pad+'" top="'+pad+'" width="'+(fW-2*pad)+'" height="'+(fH-2*pad)+'"><a class="'+textClass+'" href="#" '+'onClick="return false" onMouseOver="status=\'\';'+myName+'.over(\''+mN+'\','+iN+');return true">'+txt+'</a></layer>';else itemStr+='<div class="'+textClass+'" style="position:absolute;left:'+pad+'px;top:'+pad+'px;width:'+(fW-2*pad)+'px;height:'+(fH-2*pad)+'px">'+txt+'</div>';}return itemStr;}};PmPt.update=function(docWrite,upMN){with(this){if(!isDyn)return;for(mN in menu)with(menu[mN][0]){if(upMN&&(upMN!=mN))continue;var str='',eP=eval(par);with(eP&&eP.navigator?eP:self)var dC=document.compatMode,dT=document.doctype;dFix=(dC&&dC.indexOf('CSS')>-1||isOp&&!dC||dT&&dT.name.indexOf('.dtd')>-1||isDOM&&!isIE)?2:0;for(var iN=1;iN<menu[mN].length;iN++)with(menu[mN][iN]){var itemID=myName+'-'+mN+'-'+iN;var targM=menu[href];if(targM&&(type=='sm:')){targM[0].parentMenu=mN;targM[0].parentItem=iN;}if(outBorder){fW=iW-dFix;fH=iH-dFix}else{fW=iW;fH=iH}var isImg=(outCol.indexOf('.')!=-1);if(!isIE){if(normCursor=='hand')normCursor='pointer';if(nullCursor=='hand')nullCursor='pointer';}if(isDOM||isIE4){str+='<div id="'+itemID+'" '+(outBorder?'class="'+outBorder+'" ':'')+'style="position:absolute;left:'+iX+'px;top:'+iY+'px;width:'+fW+'px;height:'+fH+'px;z-index:'+zIndex+';'+(outCol?'background:'+(isImg?'url('+outCol+')':outCol):'')+((typeof(outAlpha)=='number')?';filter:alpha(opacity='+outAlpha+');-moz-opacity:'+(outAlpha/100):'')+';cursor:'+((type!='sm:'&&href)?normCursor:nullCursor)+'" ';}else if(isNS4){str+='<layer id="'+itemID+'" left="'+iX+'" top="'+iY+'" width="'+fW+'" height="'+fH+'" z-index="'+zIndex+'" '+(outCol?(isImg?'background="':'bgcolor="')+outCol+'" ':'');}var evtMN='(\''+mN+'\','+iN+')"';str+='onMouseOver="'+myName+'.over'+evtMN+' onMouseOut="'+myName+'.out'+evtMN+' onClick="'+myName+'.click'+evtMN+'>'+getHTML(mN,iN,0)+(isNS4?'</layer>':'</div>');}var sR=myName+'.setupRef('+(docWrite?1:0)+',"'+mN+'")';if(isOp)setTimeout(sR,1000);var mVis=(isOp&&isRoot)?'visible':'hidden';if(docWrite){var targFr=(eP&&eP.navigator?eP:window);targFr.document.write('<div id="'+myName+'-'+mN+'" style="position:absolute;'+'visibility:'+mVis+';left:-1000px;top:0px;width:'+(menuW+2)+'px;height:'+(menuH+2)+'px;z-index:1000">'+str+extraHTML+'</div>');}else{if(!lyr||!lyr.ref)lyr=setLyr(mVis,menuW,eP);else if(isIE4)setTimeout(myName+'.menu.'+mN+'[0].lyr.sty.width='+(menuW+2),50);with(lyr){sty.zIndex=1000;write(str+extraHTML)}}if(!isOp)setTimeout(sR,100);}}};PmPt.setupRef=function(docWrite,mN){with(this)with(menu[mN][0]){if(docWrite||!lyr||!lyr.ref)lyr=getLyr(myName+'-'+mN,eval(par));for(var i=1;i<menu[mN].length;i++)menu[mN][i].lyr=getLyr(myName+'-'+mN+'-'+i,(isNS4?lyr.ref:eval(par)));menu[mN][0].lyr.clip(0,0,menuW+2,menuH+2);if(menu[mN][0].oncreate)oncreate();}};
// Cascading Popup Menus v5.2 - Single Frame Menu example script.


// If you're upgrading from v5.1, you can paste your existing menu data in, and if you're
// upgrading from v5.0 you need to add 'cursor' settings to your ItemStyles.
//
// And before going ANY further, please make sure you have READ and AGREE TO the script license!
// It can be found in the syntax helpfile.


// 'horizontal Bar' style: menu items that use this ItemStyle are 40px wide, have 10px gaps
// between them, no popout indicator (the ">" in some menus) or popout indicator position,
// 0px padding of the text within items, #336699 background colour, a hover colour of #6699CC,
// 'highText' is the stylesheet class used for the menu text both normally and when highlighted,
// no border styles, 'null' means fully opaque items (set them to numbers between 0 and 100 to
// enable semitranslucency), and the 'hand'/'default' cursors are used for linked/submenu items.
var hBar = new ItemStyle(123, 0, '', 0, 0, '15#336699', '10#6699CC', 'highText', 'highText', '', '',
 null, null, 'hand', 'default');
//var hBar = new ItemStyle(123, 0, '', 0, 0, '15#B5CECE', '10#6699CC', 'highText', 'highText', '', '',
// null, null, 'hand', 'default');

// The 'sub Menu' items: these have popout indicators of "Greater Than" signs ">" 15px from their
// right edge, and CSS borders. Text class also changes on mouseover.
var subM = new ItemStyle(22, 0, '&gt;', -15, 3, '#CCCCDD', '#6699CC', 'lowText', 'highText',
 'itemBorder', 'itemBorder', null, null, 'hand', 'default');

// 'subBlank' is similar, but has an 'off' border the same colour as its background so it
// appears borderless when dim, and 1px spacing between items to show the hover border.
var subBlank = new ItemStyle(22, 1, '&gt;', -15, 3, '#CCCCDD', '#6699CC', 'lowText', 'highText',
 'itemBorderBlank', 'itemBorder', null, null, 'hand', 'default');

// The purplish 'button' style also has 1px spacing to show up the fancy border, and it has
// different colours/text and less padding. They also have translucency set -- these items
// are 80% opaque when dim and 95% when highlighted. It uses the 'crosshair' cursor for items.
var button = new ItemStyle(22, 1, '&gt;', -15, 2, '10#006633', '10#CC6600', 'buttonText', 'buttonHover',
 'buttonBorder', 'buttonBorderOver', 80, 95, 'crosshair', 'default');

//var Base = "http://aaaserver/AAA/AAA_SEGURIDAD/";
//var Base2 = "http://aaaserver/AAA/AAA_SEGURIDAD2/";
var Base = window.location.protocol + "//" + window.location.host + "/AAA/AAA_SEGURIDAD/";
var Base2 = window.location.protocol + "//" + window.location.host + "/AAA/AAA_SEGURIDAD_2_2/";
var pMenu = new PopupMenu('pMenu');
with (pMenu)
{
 	startMenu('root', false, 10,'page.scrollY()', 17, hBar );
	    addItem('&nbsp; Empresa',  				'mEe', 'sm:');
	    addItem('&nbsp; Contabilidad',  		'mCo', 'sm:');
	    addItem('&nbsp; Bancos',        		'mBa', 'sm:');
	    addItem('&nbsp; Inventario',    		'mIn', 'sm:');
	    addItem('&nbsp; Embarques',     		'mEm', 'sm:');
	    addItem('&nbsp; Liquidaciones',     	'mLi', 'sm:');
	    addItem('&nbsp; Administracion', 		'mAd', 'sm:');
	    addItem('&nbsp; SEGURIDAD',     		'mSe', 'sm:');

	startMenu('mEe',             true, 0, 22, 200, subM);
	    addItem('Entrar / Salir del Sistema',               Base + 'Se_Files/Se_Login.phtml',     '');

	startMenu('mCo', true, 0, 22, 150, subM);
	    addItem('Transacciones',        		'CoTr',             'sm:');
	    addItem('Administracion',       		'CoAd',             'sm:');
	    addItem('Estados Financieros',  		'CoEf',             'sm:');
	    addItem('Reportes',             		'mCoRe',           'sm:');

        startMenu('CoTr', true, 90, 10, 170, subM);
            addItem('Ingreso de Comprobantes',      Base + 'In_Files/InTrTr_Cabe.php?pAplic=CO',     '');
            addItem('Consulta de Comprobantes',     Base + 'In_Files/InTrTr.php',          '');
            addItem('Consulta de Cuentas',          Base + 'Co_Files/CoAdCu_search_top.php',          '');
            addItem('Consulta de Movimientos',      Base + 'Co_Files/CoTrTr_movim.php?action=Q',          '');
            addItem('Anexo Transaccional',          'mAt', 'sm:');

    	startMenu('CoAd', true, 90, 10, 170, subM);
    	    addItem('Plan de Cuentas',              Base + 'Co_Files/CoAdCu_top.php',          '');
    	   	addItem('Periodos',                     Base + 'Co_Files/CoAdPe.php?per_Aplicacion=CO',          '');
    	    addItem('Tipos de Transacciones',       Base + 'Co_Files/CoAdTi.php',          '');
/*
    	    addItem('Auxiliares',                   Base + 'Co_Files/CoAdAu.php',          '');
    	    addItem('Auxiliares (Activos y Otros)', Base + 'Co_Files/CoAdAc_mant.php',             '');
*/
    	    addItem('Auxiliares (Personas)',        Base + 'Co_Files/CoAdAu_search2.php',          '');
    	    addItem('Auxiliares (Activos y Otros)', Base + 'Co_Files/CoAdAc_search2.php',             '');
	    addItem('Procesos                    ', Base + 'Co_Files/CoAdPr_ejec.html',             '');

        startMenu('CoEf', true, 90, 10, 170, subM);
            addItem('Balances ',      				Base + 'Co_Files/CoTrEf_Selecc.php',     '');

        startMenu('mCoRe', true, 90, 10, 170, subM);
            addItem('Plan de Cuentas ',      		Base + 'Co_Files/CoAdCu_repcond.php',     '');
            addItem('Movimientos de Cuentas ',      Base + 'Co_Files/CoTrTr_condmov.php',     '');
            addItem('Listado Auxiliares/Activos ',    Base + 'Co_Files/CoAdAu_resgen.rpt.php',     '');
            addItem('Listado Auxiliares/Personas ',    Base + 'Co_Files/CoAdAu_resgenper.rpt.php',     '');
            addItem('Panel de Reportes ',           Base + 'Co_Files/CoTrTr_condcom.php',     '');


       	startMenu('mAt', true, 90, 10, 170, subM);
 	    addItem('Consulta',              	     Base + 'Rt_Files/CoRtTr_cmpvtasearch.php',          '');
    	    addItem('Transacciones',                 Base + 'Rt_Files/CoRtTr_cmpvtamant.php',          '');
    	    addItem('Reportes',                 Base + 'Rt_Files/CoRtTr_condmov.php',          '');

	startMenu('mAd', true, 0, 22, 150, subM);
	    addItem('Parametros Generales',         Base + 'Ge_Files/GePaMa_top.php',          '');
	    addItem('Usuarios',                     Base + 'Se_Files/SeGeUs.php',              '');
	    addItem('Pefiles',                      Base + 'Se_Files/SeGeAt_top.php',          '');
            addItem('Empresas',                     Base + 'Se_Files/SeGeEm.php',              '');

	startMenu('mBa', true, 0, 22, 150, subM);
	    addItem('Transacciones',        		'BaTr',             'sm:');
	    addItem('Conciliacion',             	Base + 'Co_Files/CoTrCl_search.php',             '');
	    addItem('Reportes',             		'BaRe',             'sm:');


	startMenu('BaCo', true, 90, 10, 170, subM);
	    addItem('Conciliacion Internacional',             	Base + 'Co_Files/CoTrCl_mant.php?con_IdRegistro=1', '');
	    addItem('Conciliacion Machala',                 	Base + 'Co_Files/CoTrCl_mant.php?con_IdRegistro=2', '');

    	startMenu('BaTr', true, 90, 10, 170, subM);
    	    addItem('Ingreso de Comprobantes',      Base + 'In_Files/InTrTr_Cabe.php?pAplic=BA',     '');
    	    addItem('Consulta de Comprobantes',     Base + 'In_Files/InTrTr.php',          '');

    	startMenu('BaRe', true, 90, 10, 170, subM);
      	    addItem('Saldos Diarios',         		Base + 'Co_Files/CoTrTr_saldiario.rpt.php',       '');
    	    addItem('Conciliacion',         		'alert("Pendiente");',              'js:');
    	    addItem('lIBRO bANCOS',         		'alert("Pendiente");',              'js:');

	startMenu('mIn', true, 0, 22, 150, subM);
	    addItem('Transacciones',        		'InTr',                 'sm:');
	    addItem('Administracion',       		'InAd',                 'sm:');
	    addItem('Procesos',     Base + 'In_Files/InAdPr_ejec.html?','');
	    addItem('Reportes',             		Base + 'In_Files/InTrTr_condmov.php',              '');
	    addItem('Estadisticas de IB',             	'InEsI',                 'sm:');
	    addItem('Estadisticas de OC',             	'InEsC',                 'sm:');

    	startMenu('InTr', true, 90, 10, 150, subM);
    	    addItem('Ingreso / Modificación',       Base + 'In_Files/InTrTr_Cabe.php?pAplic=IN',                 '');
    	    addItem('Consulta',                     Base + 'In_Files/InTrTr.php',                      '');

    	startMenu('InAd', true, 90, 10, 150, subM);
    	    addItem('Items',                    	Base + 'Co_Files/CoAdAc_mant.php',                 '');
    	    addItem('Periodos',                     Base + 'Co_Files/CoAdPe.php?per_Aplicacion=IN',        '');
    	    addItem('Definicion de Procesos',       Base + 'In_Files/InAdPr_mant.php',                 '');
    	    addItem('Ejecucion  de Procesos',       Base + 'In_Files/InAdPr_ejec.html',                 '');

	startMenu('InEsI', true, 90, 10, 150, subM);
    	    addItem('Precios / Proveedor',          Base + 'In_Files/InTrTr_resprecios.php',                 '');
	    addItem('Precios / Item',               Base + 'In_Files/InTrTr_resprecios.php',                 '');
	    addItem('Compras / Proveedor',          Base + 'In_Files/InTrTr_respreciosanual.php',                 '');
	    addItem('Compras / Item',               Base + 'In_Files/InTrTr_respreciosanual.php',                 '');

	startMenu('InEsC', true, 90, 10, 150, subM);
    	addItem('Precios / Proveedor',          Base + 'In_Files/InTrTr_resprecios.php?pTrans=OC',                 '');
	    addItem('Precios / Item',               Base + 'In_Files/InTrTr_resprecios.php?pTrans=OC',                 '');
	    addItem('Compras / Proveedor',          Base + 'In_Files/InTrTr_respreciosanual.php?pTrans=OC',                 '');
	    addItem('Compras / Item',               Base + 'In_Files/InTrTr_respreciosanual.php?pTrans=OC',                 '');

	startMenu('mEm', true, 0, 22, 150, subM);
	    addItem('Administracion',           	'mEmAd',            'sm:');
	    addItem('Plan de Embarque',         	'mEmEm',            'sm:');
	    addItem('Cartas de Corte',          	'alert("Pendiente");',              'js:');
	    addItem('Tarjas',                   	'mLiTj',             'sm:');
	    addItem('Reportes Generales',       	'mTjRe',              'sm:');
	    addItem('Operaciones',             		Base2 + 'Op_Files/OpTrTr_panelop.php',              '');

        startMenu('mEmEm', true, 90, 10, 200, subM);
	        addItem('Nuevo Embarque',           Base + 'Li_Files/LiEmPe_mant.php',   '');
	        addItem('Embarques, Consulta',        Base + 'Li_Files/LiAdEm_search.php',   '');
	        addItem('Vapores',                  Base + 'Li_Files/LiAdBu_search.php',   '');
	        addItem('Control de Embarques',        Base + 'Op_Files/LiEmPe_cuadrogen.php',   '');


	    startMenu('mEmAd', true, 90, 10, 200, subM);
	        addItem('ESTRUCTURA DE MARCAS',     Base + 'Ut_Files/LiAdMa_tree.php',   '');
	        addItem('Marcas',                   Base + 'Li_Files/LiAdMa_search.php',   '');
	        addItem('Empaques',                 Base + 'Li_Files/LiAdTc_search.php',   '');
	        addItem('Componentes de Empaque',   Base + 'Li_Files/LiAdCo_search.php',   '');
	        addItem('Auxiliares (Activos y Otros)', Base + 'Co_Files/CoAdAc_search2.php',             '');
			addItem('Definicion de Productos',  Base + 'Co_Files/CoAdAc.php',   '');
	        addItem('Listas de Precio',         Base + 'Li_Files/LiAdLp_mant.php',     '');
	   	    addItem('Periodos',                     Base + 'Co_Files/CoAdPe.php?per_Aplicacion=LI',        '');

        startMenu('mLiTj', true, 90, 10, 200, subM);
	        addItem('Nueva Tarja',             Base + 'Li_Files/LiEmTj_mant.php',   '');
	        addItem('Tarjas, Consulta',          Base + 'Li_Files/LiEmTj_search.php',   '');

        startMenu('mTjRe', true, 90, 10, 200, subM);
	        addItem('Reportes de Tarjas',        Base + 'Li_Files/LiEmTj_repcond.php',   '');
	        addItem('Lista General de Dosis',        Base + 'Li_Files/LiAdCo_dosis.rpt.php',   '');

	startMenu('mLi', true, 0, 22, 150, subM);
	    addItem('Administracion',           	'mLiAd',             'sm:');
	    addItem('Procesos',         	        'mLiPr',             'sm:');
	    addItem('Reportes',          	        'mLiRe',              'sm:');
	    addItem('Emisión de Documentos',        'mLiDo',              'sm:');
	    addItem('Consultas',       	            'mLiCo',              'sm:');

    	startMenu('mLiAd', true, 90, 10, 150, subM);
	        addItem('Variables de Cálculo',             Base + 'Li_Files/LiAdVa_search.php',   '');
	        addItem('Rubros de Liquidación',            Base + 'Li_Files/LiAdRu_search.php',   '');
	        addItem('Precios de Material Empq',         Base + 'Li_Files/LiAdLp_mant.php?s_LisPrecios=3',   '');
	        addItem('Config. de Report/Proces',         Base + 'Ut_Files/LiLiRp_conftree2.php',   '');
        	
    	startMenu('mLiPr', true, 90, 10, 150, subM);
	        addItem('Politicas de cobro CXC',           Base + 'Li_Files/LiLiPr_politica.php',   '');    	
	        addItem('Lista de Precios de Fruta',        Base + 'Li_Files/LiLiLp_search.php',   '');
	        addItem('Lotes de Liquidación',             Base + 'Li_Files/LiLiPr_search.php',   '');
	        addItem('Liquidaciones',                    Base + 'Li_Files/LiLiLi_search.php',   '');
        	addItem('Emisión de Docs',                  Base + 'Li_Files/LiLiLo_search.php',   '');

        startMenu('mLiRe', true, 90, 10, 150, subM);
	        addItem('Panel de Reportes',                Base + 'Li_Files/LiLiRp_repcond.php',   '');
        	
	startMenu('mSe', true, 0, 22, 150, subM);
	    addItem('Empresas',             		Base + 'Se_Files/SeGeEm.php', '');
	    addItem('Usuarios',             		Base + 'Se_Files/SeGeUs.php', '');
	    addItem('Perfiles',             		Base + 'Se_Files/SeGeAt_top.php', '');
	    addItem('Módulos',              		Base + 'Se_Files/SeGeMo_top.php', '');

  menu.root.subsOnClick = true;
  menu.root[0].subsOnClick = true;
  menu.root[1].subsOnClick = true;

// HIDE OR SHOW DELAYS (in milliseconds) can be customised. Defaults are:
  showDelay = 400;
  hideDelay = 200;
// Specify hideDelay as zero if you want to disable autohiding, and showDelay as a couple of
// hundred if you don't want the menus showing instantaneously when moused over.

// HIDE MENUS ON DOCUMENT CLICK: Try uncommenting this, and perhaps set hideDelay to zero:
//hideDocClick = true;

// You can assign 'oncreate' events to specific menus. By default, the script has only one for
// the root menu that shows it when it is created. You may wish to change it to something like the
// following, which uses the animation function to show the menu, or delay its show altogether.
//menu.root[0].oncreate = function() { pMenu.doVis('root', true) }

// End of 'with (pMenu)' block. That's one menu object created!

}

// CREATE ANOTHER MENU OBJECT here if you want multiple menus on a page, or you can just
// duplicate this entire file and rename 'pMenu' to something else.
// Every menu object MUST have a menu named 'root' in it, as that's always visible.

//var anotherMenu = new PopupMenu('anotherMenu');
//with (anotherMenu)
//{
// startMenu('root', .....);
// ... make menus here ...
//}

// ******************** MENU EFFECTS ********************
//
// Now you've created a basic menu object, you can add optional effects like borders and
// shadows to specific menus. You can remove this section entirely if you want, the
// functions called are found at the bottom of this file.

// BORDER: Added to all menus in a named object using a specified ItemStyle. The syntax is:
//  addMenuBorder(menuObject, ItemStyle,
//   opacity of border, 'border colour', border width, 'padding colour', padding width);
// Opacity is a number from 0 to 100, or null for solid colour (just like the ItemStyles).

addMenuBorder(pMenu, window.subBlank,
 null, '#666666', 1, '#CCCCDD', 2);

// DROPSHADOW: added to specific ItemStyles again. The syntax is similar, but later on you
// pass arrays [...] for each layer of the shadow you want. I've used two grey layers
// here, but you can use as many or as few as you want. The syntax for the layers is:
//  [opacity, 'layer colour', X offset, Y offset, Width Difference, Height difference]
// Opacity is from 0 to 100 (or null to make it solid), and the X/Y offsets are the
// distance in pixels from the menu's top left corner to that shadow layer's corner.
// The width/height differences are added or subtracted to the current menu size, for
// instance the first layer of this shadow is 4px narrower and shorter than the menu
// it is shadowing.

addDropShadow(pMenu, window.subM,
 [40,"#333333",6,6,-4,-4], [40,"#666666",4,4,0,0]);
addDropShadow(pMenu, window.subBlank,
 [40,"#333333",6,6,-4,-4], [40,"#666666",4,4,0,0]);

// ANIMATION SETTING: We add this to the 'pMenu' menu object for supported browsers.
// IE4/Mac and Opera 5/6 don't support clipping, and Mozilla versions prior to 1.x (such as
// Netscape 6) are too slow to support it, so I'm doing some browser sniffing.
// If you don't want animation, delete this entirely, and the menus will act normally.
// Change the speed if you want... it's the last number, between -100 and 100, and is
// defined as the percentage the animation moves each frame (defaults are 10 and 15).

if ((navigator.userAgent.indexOf('rv:0.')==-1) &&
    !(isOp&&!document.documentElement) && !(isIE4&&!window.external))
{
 pMenu.showMenu = new Function('mN','menuAnim(this, mN, 10)');
 pMenu.hideMenu = new Function('mN','menuAnim(this, mN, -15)');

 // Add animation to other menu objects like this...
 //anotherMenu.showMenu = new Function('mN','menuAnim(this, mN, 10)');
 //anotherMenu.hideMenu = new Function('mN','menuAnim(this, mN, -10)');
}

// ******************** FUNCTIONS CALLED BY THE EFFECTS SECTION ********************

// These can be deleted if you're not using them. Alternatively, if you're using several menu
// data files, you may want to move them to the "core" script file instead.



// This is the "positioning from page anchors" code used by the advanced positioning expressions.
page.elmPos=function(e,p)
{
 var x=0,y=0,w=p?p:this.win;
 e=e?(e.substr?(isNS4?w.document.anchors[e]:getRef(e,w)):e):p;
 if(isNS4){if(e&&(e!=p)){x=e.x;y=e.y};if(p){x+=p.pageX;y+=p.pageY}}
 else if (e && e.focus && e.href && this.MS && /Mac/.test(navigator.platform))
 {
  e.onfocus = new Function('with(event){self.tmpX=clientX-offsetX;' +
   'self.tmpY=clientY-offsetY}');
  e.focus();x=tmpX;y=tmpY;e.blur()
 }
 else while(e){x+=e.offsetLeft;y+=e.offsetTop;e=e.offsetParent}
 return{x:x,y:y};
};

// Animation:
//
// Each menu object you create by default shows and hides its menus instantaneously.
// However you can override this behaviour with custom show/hide animation routines,
// as we have done in the "Menu Effects" section. Feel free to edit this, or delete
// this entire function if you're not using it. Basically, make functions to handle
// menuObj.showAnim() and .hideAnim(), both of which are passed menu names.
//
// Customisers: My lyr.clip() command gets passed the parameters (x1, y1, x2, y2)
// so you might want to adjust the direction etc. Oh, and I'm adding 2 to the dimensions
// to be safe due to different box models in some browsers.
// Another idea: add some if/thens to test for specific menu names...?

function menuAnim(menuObj, menuName, dir)
{
 // The array index of the named menu (e.g. 'mFile') in the menu object (e.g. 'pMenu').
 var mD = menuObj.menu[menuName][0];
 // Add timer and counter variables to the menu data structure, we'll need them.
 if (!mD.timer) mD.timer = 0;
 if (!mD.counter) mD.counter = 0;

 with (mD)
 {
  // Stop any existing animation.
  clearTimeout(timer);

  // If the litNow() array doesn't show this menu as lit, and we're still showing it,
  // force a quick hide (this stops miscellaneous timer errors).
  //if (dir>0 && !menuObj.litNow[menuObj.menu[menuName][0].parentMenu]) dir = -100;

  // If the layer doesn't exist (cross-frame navigation) quit.
  if (!lyr || !lyr.ref) return;
  // This next line is not strictly necessary, but it stops the one-in-a-hundred menu that
  // shows and doesn't hide on very quick mouseovers.
  if (!visNow && dir>0) dir = 0-dir;
  // Show the menu if that's what we're doing.
  if (dir>0) lyr.vis('visible');
  // Also raise showing layers above hiding ones.
  lyr.sty.zIndex = dir>0 ? mD.zIndex + 1 : 1001;

  // Alpha fade in IE5.5+. Mozilla's opacity isn't well suited to this as it's an inheritable
  // property rather than a block-level filter, and it's slow, but uncomment and try it perhaps.
  // WARNING: This looks funny if you're mixing opaque and translucent items e.g. solid menus
  // with dropshadows. If you're going to use it, either disable dropshadows or set the opacity
  // values for your items to numbers instead of null.
  //if (isIE && window.createPopup) lyr.alpha(counter&&(counter<100) ? counter : null);

  // Clip the visible area. Tweak this if you want to change direction/acceleration etc.
  // As you can see, the visibile clipping region is from (0, 0) which is the top left corner,
  // to the right edge of the menu 'menuW+2', and a complicated formula that sets the bottom
  // edge of the clipping region based on the 'counter' variable so it accelerates.
  lyr.clip(0, 0, menuW+2, (menuH+2)*Math.pow(Math.sin(Math.PI*counter/200),0.75) );

  // Increment the counter and if it hasn't reached the end (counter is 0% or 100%),
  // set the timer to call the animation function again in 40ms to contine the animation.
  // Note that we hide the menu div on animation end in that direction.
  counter += dir;
  if (counter>100) { counter = 100; lyr.sty.zIndex = mD.zIndex }
  else if (counter<0) { counter = 0; lyr.vis('hidden') }
  else timer = setTimeout('menuAnim('+menuObj.myName+',"'+menuName+'",'+dir+')', 40);
 }
};

// SELECT BOX HIDING
//
// This must be pasted into the data file *after* your menu data and animation settings,
// and will apply to all menu objects you create with the script.
// For frameset usage put it in the frameset file itself, not the subframes, and also
// copy and paste the 'page.elmPos' function from the standard data file to your frameset.
// This function will have no effect on NS4's form behaviour.

PopupMenu.prototype.elementHide = function(mN, show)
{
 // You should list only the tags you need for best speed.
 var hideTags = ['SELECT', 'IFRAME', 'OBJECT', 'APPLET'];

 with (this.menu[mN][0])
 {
  if (!lyr || !lyr.ref) return;

  var oldFn = show ? 'ehShow' : 'ehHide';
  if (this[oldFn]) this[oldFn](mN);
  else this.menu[mN][0].lyr.vis(show ? 'visible' : 'hidden');
  if (isOp  ? document.documentElement : (!navigator.userAgent.match('rv:0.') && !isIE))  return;
  if (!this.hideElms) this.hideElms = [];
  var hE = this.hideElms;
  if (show)
  {
   var elms = [], w = par?eval(par):self;
   for (var t = 0; t < hideTags.length; t++)
   {
    var tags = isDOM ? w.document.getElementsByTagName(hideTags[t]) :
     isIE ? w.document.all.tags(hideTags[t]) : null;
    for (var i = 0; i < tags.length; i++) elms[elms.length] = tags[i];
   }
   for (var eN = 0; eN < elms.length; eN++)
   {
    var eRef = elms[eN];
    with (w.page.elmPos(eRef)) var eX = x, eY = y;
    if (!(lyr.x()+menuW<eX || lyr.x()>eX+eRef.offsetWidth) &&
        !(lyr.y()+menuH<eY || lyr.y()>eY+eRef.offsetHeight))
    {
     if (!hE[eN]) hE[eN] = { ref: eRef, menus: [] };
     hE[eN].menus[mN] = true;
     eRef.style.visibility = 'hidden';
    }
   }
  }
  else for (var eN in this.hideElms)
  {
   var reShow = 1, eD = hE[eN];
   eD.menus[mN] = false;
   for (var eM in eD.menus) reShow &= !eD.menus[eM];
   if (reShow && eD.ref) eD.ref.style.visibility = 'visible';
  }
 }
 return;
}

for (var p in PopupMenu.list)
{
 var pm = PopupMenu.list[p];
 pm.ehShow = pm.showMenu;
 pm.showMenu = new Function('mN','this.elementHide(mN, true)');
 pm.ehHide = pm.hideMenu;
 pm.hideMenu = new Function('mN','this.elementHide(mN, false)');
}

// Borders and Dropshadows:
//
// Here's the menu border and dropshadow functions we call above. Edit ot delete if you're
// not using them. Basically, they assign a string to pMenu.menu.menuName[0].extraHTML, which
// is written to the document with the menus as they are created -- the string can contain
// anything you want, really. They also adjust the menu dimensions and item positions
// to suit. Dig out the Object Browser script and open up "pMenu" for more info.

function addMenuBorder(mObj, iS, alpha, bordCol, bordW, backCol, backW)
{
 // Loop through the menu array of that object, finding matching ItemStyles.
 for (var mN in mObj.menu)
 {
  var mR=mObj.menu[mN], dS='<div style="position:absolute; background:';
  if (mR[0].itemSty != iS) continue;
  // Loop through the items in that menu, move them down and to the right a bit.
  for (var mI=1; mI<mR.length; mI++)
  {
   mR[mI].iX += bordW+backW;
   mR[mI].iY += bordW+backW;
  }
  // Extend the total dimensions of menu accordingly.
  mW = mR[0].menuW += 2*(bordW+backW);
  mH = mR[0].menuH += 2*(bordW+backW);

  // Set the menu's extra content string with divs/layers underneath the items.
  if (isNS4) mR[0].extraHTML += '<layer bgcolor="'+bordCol+'" left="0" top="0" width="'+mW+
   '" height="'+mH+'" z-index="980"><layer bgcolor="'+backCol+'" left="'+bordW+'" top="'+
   bordW+'" width="'+(mW-2*bordW)+'" height="'+(mH-2*bordW)+'" z-index="990"></layer></layer>';
  else mR[0].extraHTML += dS+bordCol+'; left:0px; top:0px; width:'+mW+'px; height:'+mH+
   'px; z-index:980; '+(alpha!=null?'filter:alpha(opacity='+alpha+'); -moz-opacity:'+(alpha/100):'')+
   '">'+dS+backCol+'; left:'+bordW+'px; top:'+bordW+'px; width:'+(mW-2*bordW)+'px; height:'+
   (mH-2*bordW)+'px; z-index:990"></div></div>';
 }
};

function addDropShadow(mObj, iS)
{
 // Pretty similar to the one above, just loops through list of extra parameters making
 // dropshadow layers (from arrays) and extending the menu dimensions to suit.
 for (var mN in mObj.menu)
 {
  var a=arguments, mD=mObj.menu[mN][0], addW=addH=0;
  if (mD.itemSty != iS) continue;
  for (var shad=2; shad<a.length; shad++)
  {
   var s = a[shad];
   if (isNS4) mD.extraHTML += '<layer bgcolor="'+s[1]+'" left="'+s[2]+'" top="'+s[3]+'" width="'+
    (mD.menuW+s[4])+'" height="'+(mD.menuH+s[5])+'" z-index="'+(arguments.length-shad)+'"></layer>';
   else mD.extraHTML += '<div style="position:absolute; background:'+s[1]+'; left:'+s[2]+
    'px; top:'+s[3]+'px; width:'+(mD.menuW+s[4])+'px; height:'+(mD.menuH+s[5])+'px; z-index:'+
    (a.length-shad)+'; '+(s[0]!=null?'filter:alpha(opacity='+s[0]+'); -moz-opacity:'+(s[0]/100):'')+
    '"></div>';
   addW=Math.max(addW, s[2]+s[4]);
   addH=Math.max(addH, s[3]+s[5]);
  }
  mD.menuW+=addW; mD.menuH+=addH;
 }
};
var scFr=self.PopupMenu?self:(parent.PopupMenu?parent:top);function popEvt(str,each){var PML=scFr.PopupMenu.list,mN;for(var objName in PML)with(PML[objName]){if(scFr!=self&&each)for(mN in menu)with(menu[mN][0]){if(par.substring(par.lastIndexOf('.')+1)==self.name)eval(str);}else eval(str);}};var scrFn,popOL=window.onload,popUL=window.onunload,popOR=window.onresize,popOS=window.onscroll,nsWinW=window.innerWidth,nsWinH=window.innerHeight,nsPX=window.pageXOffset,nsPY=window.pageYOffset;document.popOC=document.onclick;if(scFr.PopupMenu){if(!self.page)var isNS4=scFr.isNS4,page={};if(scFr!=self)for(var f in scFr.page)page[f]=scFr.page[f];page.win=self;popEvt('self[objName]=PML[objName]',0);if(!isNS4)popEvt('update(true,mN)',1);window.onload=function(){if(popOL)popOL();if(isNS4){popEvt('update(false,mN)',1);setInterval(scrFn,50)}window.onunload=new Function('if(popUL)popUL();popEvt("lyr=null",1)');};if(popOS||(''+popOS!='undefined'))window.onscroll=function(){if(popOS)popOS();popEvt('position(mN)',1);};else{scrFn='if(nsPX!=pageXOffset||nsPY!=pageYOffset)'+'{nsPX=pageXOffset;nsPY=pageYOffset;popEvt("position(mN)",1)}';if(!isNS4)setInterval(scrFn,50);}function resizeBugCheck(){if(nsWinW!=innerWidth||nsWinH!=innerHeight)location.reload()};if(scFr.isOp&&!document.documentElement&&!self.opFix)self.opFix=setInterval('resizeBugCheck()',500);window.onresize=function(){if(popOR)popOR();if(isNS4)resizeBugCheck();popEvt('position(mN)',1);};if(isNS4)document.captureEvents(Event.CLICK);document.onclick=function(evt){popEvt('if(isNS4&&overI)click(overM,overI);if(!overI&&hideDocClick)over("root",0)',0);return document.popOC?document.popOC(evt):(isNS4?document.routeEvent(evt):true);};}
