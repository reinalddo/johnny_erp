// @rev	   Gina Franco	20/May/09	this.grid.store.getAt(0).fields.items[numcolref-1].type se aumento esta linea para saber cuando una columna es date y darle el respectivo formato
// @rev	   Gina Franco	20/May/09	se puso validacion para que alinara correctamente las columnas
// @rev	   Gina Franco	20/May/09	se puso validacion para que no mostrara las filas ocultas que ha seleccionado el usuario

Ext.grid.XPrinter = function(config){
	Ext.apply(this,config);
	this.addEvents('afterPrint','closedPrintWindow');	
	Ext.grid.XPrinter.superclass.constructor.call(this);
}
Ext.reg('XPrinter',Ext.grid.XPrinter);
Ext.extend(Ext.grid.XPrinter , Ext.util.Observable,{
	//store: getting the grid store to use for records in report 
	//grid:  
	styles:'default',
	pathPrinter:'./printer',
	pdfEnable:false,
	paperOrientation:'p',
	logoURL:'', 
	wWidth : 750,
	wHeight: 700,
	excludefields:'',
	hasrowAction:false, 
	usenameindex:false,
	localeData:{
		Title:'Title',
		subTitle:'Subtitle',
		footerText:'Page footer', 
		pageNo:'Page #',
		printText:'Print document',
		closeText:'Close document',
		pdfText:''
	},
	useCustom:{ 
		custom:false,
		customStore: null, 
		customTPL: false, 
		TPL: null,  
		columns:[],
		alignCols:[],
		pageToolbar:null,
		useIt: false, 
		showIt: false, 
		location: 'bbar'
	},		
	showdate:true,
	showdateFormat:'d-m-Y',
	showFooter:true,
	footerText:'',
	renderers:null,	
	init: function(){;
		this.htmloutput='';
		this.flagdatachange=false; 
		this.flagrenderedPtool =false; 
	},
	prepare: function(){
		//debugger;
		if (this.paperOrientation=='p' || this.paperOrientation=='l'){
		} else {
			this.paperOrientation='p'; 
		}
		if  (this.useCustom.customTPL){ 
			if (this.pdfEnable){ 
				this.PrinterWindow = window.open( this.pathPrinter + "/base_print_002.php?pdfgen=1&paper="+ this.paperOrientation  ,"PrinterWindow","width=" + this.wWidth + ",height=" + this.wHeight + ",resizable=1,scrollbars=1");			
			} else { 
				this.PrinterWindow = window.open( this.pathPrinter + "/base_print_002.php?paper=" + this.paperOrientation ,"PrinterWindow","width=" + this.wWidth + ",height=" + this.wHeight + ",resizable=1,scrollbars=1");
			} 
		} else {  // normal printing on grid base or store base 
			if (this.pdfEnable){ 
				this.PrinterWindow = window.open( this.pathPrinter + "/base_print_001.php?pdfgen=1&paper="+ this.paperOrientation  ,"PrinterWindow","width=" + this.wWidth + ",height=" + this.wHeight + ",resizable=1,scrollbars=1");			
			} else { 
				this.PrinterWindow = window.open( this.pathPrinter + "/base_print_001.php?paper=" + this.paperOrientation ,"PrinterWindow","width=" + this.wWidth + ",height=" + this.wHeight + ",resizable=1,scrollbars=1");
			} 
		} 
	},
	getDatareport:function(){
		//debugger;
		// header section
		var currdate = new Date();
		if (this.showdate){ 
			datetorpint = currdate.format(this.showdateFormat);
		} else{ 
			datetorpint =''; 
		} 
		
		if (this.pdfEnable){ 
			var mypdftexttorender=this.localeData.pdfText;
		} else{ 
			var mypdftexttorender='';
		} 
		
		var datareport = {
			title: this.localeData.Title,
			subtitle: this.localeData.subTitle,
			date: currdate.format(this.showdateFormat),
			footer: this.localeData.footerText,
			currpage: this.localeData.pageNo,
			valuebody: '{valuebody}',
			pageno: '{pageno}',
			pdfprint: this.pdfEnable,
			pdfTextgen: mypdftexttorender
		}
		
		var bodytpl=new Ext.XTemplate(	
			'<link href="' + this.styles + '.css" rel="stylesheet" type="text/css" media="screen" />',
			'<link href="' + this.styles + '_printer_out.css" rel="stylesheet" type="text/css" media="print"/>',
			'<div class="printer_controls">{pdfprint:this.chkpdf}{pdfTextgen:this.chkPdftext}',
			'<img src="images/printer.png" onclick="javascript:window.print();"/> <a href="javascript:window.print();">' + this.localeData.printText + '</a> | ',
			'<img src="images/cancel.png"  onclick="javascript:window.close();"/> <a href="javascript:window.close();">' + this.localeData.closeText + '</a></div>', 
//			'<div id="myresult" class="printer">',
//			'<table width="95%" border="0" cellspacing="3" cellpadding="0"><tr>',
//		    '<td width="90%" class="date"><div style="font-size:7px;text-align:right;">{date}<div></td><td width="2%" rowspan="3" class="date">',
//			'<div align="left"><img src="' + this.logoURL + '" width="87" height="67" /></div></td></tr>',
//			'<tr><td><div class="title">{title}</div></td></tr>',
//			'{subtitle:this.chksubtitle}',
//			'<tr><td colspan="2"><hr noshade="noshade" width="90%" align="left" /></td></tr>',	
//  			'<tr><td colspan="2">',
//			'{valuebody}',
//      		'</td></tr><tr><td colspan="2"><hr noshade="noshade" width="90%" align="left" /></td>',
//    		'</tr><tr>',
//		    '<td colspan="2" class="footer">{pageno}{footer}</td>',
//		    '</tr></table></div><div id="endreport"></div>',
			'<div id="myresult" class="printer">',
			'<table width="100%" border="0" cellspacing="1" cellpadding="0"><tr>',
			'<td width="88%" class="date"><div style="font-size:7px;text-align:right;">{date}<div></td>',
			'<td width="12%" rowspan="3" class="date"><div align="left">',
			'<img src="' + this.logoURL + '" width="118" height="88"/></div></td></tr><tr>',
			'<td><div class="title">{title}</div></td></tr>',
			'{subtitle:this.chksubtitle}</table>',
			'<hr noshade="noshade" width="90%" align="left"/>',
			'<div>{valuebody}</div>',
			'<table width="100%" border="0" cellspacing="3" cellpadding="0"><tr><td colspan="2" class="subtitle">',
			'<hr noshade="noshade" width="90%" align="left"/></td></tr><tr>',
			'<td colspan="2" class="footer">{pageno}{footer}</td></tr></table>',		
			'</div><div id="endreport"></div>',
			{
				chksubtitle: function(datastr){
					if 	(datastr==''){ 
						return '<tr><td>&nbsp;</td></tr>'; 
					} else { 
						return '<tr><td><div class="subtitle">' + datastr +'</div></td></tr>'; 
					}
				},
				chkpdf: function(datax){
					if (datax){ 
						return '<img src="images/page_white_acrobat.png" width="16" height="16" onclick="genPDF();" /> ';
					} else { 
						return ''; 
					} 
				},
				chkPdftext: function (datax){ 
					if (datax==''){
						return ''; 	
					} else { 
						return   ' <a href="javascript:genPDF();">' + datax + '</a> | '; 
					} 
				} 
			}
		);
		
		var ftext = bodytpl.applyTemplate(datareport);
		if (this.useCustom.custom==false){  //use store grid and grid settings
					var numcols = (this.grid.getColumnModel().getColumnCount());
					columnstext = '<table width="90%" border="0" cellspacing="1" cellpadding="0"><tr>'; 
					for(var i=0; i<numcols; i++){
						var test = this.grid.getColumnModel().config[i].id; 
						if (test=='numberer' || test=='checker'){ 
						}  else {
							if ( this.grid.getColumnModel().isHidden(i) ) { 
							
							} else { 
								if ( this.excludefields.indexOf('' + i + ',')>=0 ){ 
								
								} else { 
									columnstext+= '<td valign="middle"><div class="header" style="font-size:8px;">' + this.grid.getColumnModel().getColumnHeader(i) + '</div></td>'; 
								} 
							} 
						} 
					}
					//debugger;
					columnstext+='</tr>';
					varnumrecs = this.grid.store.getCount(); 
					tdvalues=''; 
					for (var i=0; i<varnumrecs; i++){
						tdvalues+='<tr>';
						var testcountfields=this.grid.store.getAt(0).fields.length;  // just the initial record for field count 
						for (var jval=0; jval<testcountfields; jval++){
							if ( jval>(this.grid.getColumnModel().config.length-1) ){		
							
							} else {
									//debugger;
									var test = this.grid.getColumnModel().config[0].id; 
									var test2= 0; 
									if (test=='numberer' || test=='checker'){ 
										var numcolref = jval + 1; 
										test2 = this.grid.getColumnModel().config.length-1;										
									} else {  //no aditional columns
										var numcolref = jval; 
										test2 = this.grid.getColumnModel().config.length;																														
									} 
									if (this.hasrowAction){ 
										if (numcolref==testcountfields){ 
											if (this.hasrowAction){ 
											} else { 
												break; 
											} 
										} 
									} else { 
										var test=11; 
									} 
									var tmpchkhidden = false;
									if ( numcolref>(test2) ){		
										tmpchkhidden =true;
									} else {
										//debugger;
										//tmpchkhidden = this.grid.getColumnModel().isHidden(numcolref);//no funciona // gf 20-05-09
										if ((numcolref+1) <= (this.grid.getColumnModel().getColumnCount()-1))
											tmpchkhidden = this.grid.getColumnModel().config[numcolref].hidden;//+1].hidden;
									} 
									if ( tmpchkhidden ) { 
										var test=11; 
									} else {
										if ( this.excludefields.indexOf('' + numcolref + ',')>=0 ){ 
											// do nothing its excluded 
										} else {
											//debugger;
											var testrender = this.grid.getColumnModel().getRenderer(numcolref);
											//GF se aumento esta validacion porque no alineaba correctamente unas columnas
											switch (this.grid.store.getAt(0).fields.items[numcolref-1].type){
											    case "string":
											    case "date":
												tdvalues+='<td valign="middle" style="font-size:7px;" class="values" align="left">';
												break;
											    case "int","float":
												tdvalues+='<td valign="middle" style="font-size:7px;" class="values" align="right">';
												break;
											    default:
												tdvalues+='<td valign="middle" style="font-size:7px;" class="values" align="' +  this.grid.getColumnModel().config[numcolref].align + '">';
												break;
											}
											
												
											
											if (this.usenameindex==false){
												//debugger;
												if (this.grid.store.getAt(0).fields.items[numcolref-1].type == 'date'){
													var valueDate = new Date();
													//valueDate = testrender.call(this,this.grid.store.getAt(i).data[this.grid.store.getAt(i).fields.items[jval].name]);
													valueDate = this.grid.store.getAt(i).data[this.grid.store.getAt(i).fields.items[jval].name];
													if ("" != valueDate && "NaN" != Date.parse(valueDate))// && isDate(valueDate))
														tdvalues += valueDate.format("d-M-y");
												}else{
													tdvalues += this.grid.store.getAt(i).data[this.grid.store.getAt(i).fields.items[jval].name];
													//no mostraba bien las columnas string por ej: '$P', en este caso no mostraba nada, fue reemplazada esta linea por la superior
													//tdvalues+= testrender.call(this,this.grid.store.getAt(i).data[this.grid.store.getAt(i).fields.items[jval].name]) 
												}
											} else { 
												tdvalues+= testrender.call(this,this.grid.store.getAt(i).data[this.grid.getColumnModel().config[numcolref].dataIndex])
											} 
											tdvalues+='</td>'; 
										} 
									} 
							} 
						} 
						tdvalues+='</tr>';	
					} 
					tdvalues+='</table>';
					if (this.useCustom.useIt){ 
						if (this.useCustom.location=='bbar'){
							tmppagetool = this.grid.getBottomToolbar(); 
						} else { 
							tmppagetool = this.grid.getTopToolbar(); 
						} 
						var mycurrentpage = tmppagetool.getPageData().activePage; 
						
					} else { 
						var mycurrentpage = 1; 
					}
						
		} else { 
					var numcols = this.useCustom.columns.length;
					columnstext = '<table width="90%" border="0" cellspacing="1" cellpadding="0"><tr>'; 
					for(var i=0; i<numcols; i++){
						columnstext+= '<td class="header">' + this.useCustom.columns[i] + '</td>'; 
					}
					columnstext+='</tr>';
					varnumrecs = this.useCustom.customStore.getCount(); 			
					tdvalues=''; 
					for (var i=0; i<varnumrecs; i++){
						tdvalues+='<tr>';
						var testcountfields = this.useCustom.customStore.getAt(0).fields.length;  // just the initial record for field count 
						for (var jval=0; jval<testcountfields; jval++){
							var numcolref = jval; 
							tdvalues+='<td class="values" align="' +  this.useCustom.alignCols[numcolref]+ '">'; 
							tdvalues+= this.useCustom.customStore.getAt(i).data[this.useCustom.customStore.getAt(i).fields.items[jval].name];
							tdvalues+='</td>';  
						} 
						tdvalues+='</tr>';	
					} 
					tdvalues+='</table>';			
					// at the beggining its always page number 1  
					var mycurrentpage = this.useCustom.pageToolbar.getPageData().activePage; 
		} 
		datareportb = {
			pageno: this.localeData.pageNo + ' ' + mycurrentpage + ' | ',
			valuebody:columnstext + tdvalues
		}
		var bodytplb= new Ext.XTemplate(ftext); 
		var ftextb = bodytplb.applyTemplate(datareportb);	
		return ftextb; 
	}, 
	
	printCustom: function(OBJ){
		//debugger;
		var currdate = new Date();
		if (this.showdate){ datetorpint = currdate.format(this.showdateFormat); } else{ datetorpint =''; } 
		if (this.pdfEnable){ var mypdftexttorender=this.localeData.pdfText; 	} else{ var mypdftexttorender='';		} 
		var datareport = {
			title: this.localeData.Title,
			subtitle: this.localeData.subTitle,
			date: currdate.format(this.showdateFormat),
			footer: this.localeData.footerText,
			currpage: this.localeData.pageNo,
			valuebody: '{valuebody}',
			pageno: '{pageno}',
			pdfprint: this.pdfEnable,
			pdfTextgen: mypdftexttorender
		}
		
		var bodytpl=new Ext.XTemplate(
			'<link href="' + this.styles + '.css" rel="stylesheet" type="text/css" media="screen" />',
			'<link href="' + this.styles + '_printer_out.css" rel="stylesheet" type="text/css" media="print"/>',
			'<div class="printer_controls">{pdfprint:this.chkpdf}{pdfTextgen:this.chkPdftext}',
			'<img src="images/printer.png" onclick="javascript:window.print();"/> <a href="javascript:window.print();">' + this.localeData.printText + '</a> | ',
			'<img src="images/cancel.png"  onclick="javascript:window.close();"/> <a href="javascript:window.close();">' + this.localeData.closeText + '</a></div>', 	
			
			//'<div id="myresult" class="printer">',
			//'<table width="100%" border="0" cellspacing="3" cellpadding="0"><tr>',
		    //'<td width="89%" class="date">{date}</td><td width="11%" rowspan="3" class="date">',
			//'<img src="' + this.logoURL + '" width="87" height="67" /></td></tr>',
			//'<tr><td class="title">{title}</td></tr>',
			//'{subtitle:this.chksubtitle}',
			//'<tr><td colspan="2" class="subtitle"><hr noshade="noshade" width="100%" /></td></tr>',	
  			//'<tr><td colspan="2">',
			//'{valuebody}<div id="cntrptdata"></div>',
      		//'</td></tr><tr><td colspan="2" class="subtitle"><hr noshade="noshade" width="100%" /></td>',
    		//'</tr><tr>',
		    //'<td colspan="2" class="footer">{pageno}{footer}</td>',
		    //'</tr></table></div><div id="endreport"></div>',
			
			'<div id="myresult" class="printer">',
			'<table width="100%" border="0" cellspacing="1" cellpadding="0"><tr>',
			'<td width="88%" class="date"><div style="font-size:7px;text-align:right;">{date}<div></td>',
			'<td width="12%" rowspan="3" class="date"><div align="left">',
			'<img src="' + this.logoURL + '" width="118" height="88"/></div></td></tr><tr>',
			'<td><div class="title">{title}</div></td></tr>',
			'{subtitle:this.chksubtitle}</table>',
			'<hr noshade="noshade" width="90%" align="left"/>',
			'<div>{valuebody}<div id="cntrptdata"></div></div>',
			'<table width="100%" border="0" cellspacing="3" cellpadding="0"><tr><td colspan="2" class="subtitle">',
			'<hr noshade="noshade" width="90%" align="left"/></td></tr><tr>',
			'<td colspan="2" class="footer">{pageno}{footer}</td></tr></table>',		
			'</div><div id="endreport"></div>',			
			
			{
				chksubtitle: function(datastr){
					if 	(datastr==''){ 
						return '<tr><td>&nbsp;</td></tr>'; 
					} else { 
						return '<tr><td class="subtitle">' + datastr +'</td></tr>'; 
					}
				},
				chkpdf: function(datax){
					if (datax){ 
						return '<img src="images/page_white_acrobat.png" width="16" height="16" onclick="genPDF();" /> ';
					} else { 
						return ''; 
					} 
				},
				chkPdftext: function (datax){ 
					if (datax==''){
						return ''; 	
					} else { 
						return   ' <a href="javascript:genPDF();">' + datax + '</a> | '; 
					} 
				} 
			}
		);
		var ftext = bodytpl.applyTemplate(datareport);
		if (this.localeData.pageNo==""){
			var mycurrentpage = "";
		} else { 
			var mycurrentpage = this.localeData.pageNo + ' 1 | ';
		} 
		// creates Footer and page No 
		datareportb = {
			pageno: mycurrentpage
		}
		var bodytplb= new Ext.XTemplate(ftext); 
		var ftextb = bodytplb.applyTemplate(datareportb);	
		this.pageOBJ = OBJ; 
		OBJ.document.title=this.localeData.Title;
		if (this.useCustom.pageToolbar){
			var checkcount = this.useCustom.customStore.getCount();  
			if (checkcount < this.useCustom.pageToolbar.pageSize){ 
			} else { 
				this.useCustom.pageToolbar.render(OBJ.document.body);
				this.flagrenderedPtool =true; 
			} 
		}
		Ext.get(OBJ.document.body).insertHtml('beforeEnd', '<div id="reportcontent">' +  ftextb + '</div>', false);
		// creates the dataview  to render the store 
		var mycustomview = new Ext.DataView({
			store:this.useCustom.customStore, 
			tpl:this.useCustom.TPL, 
			autoHeight:true,
	        multiSelect: false,
    	    overClass:'',
        	itemSelector:'',
	        emptyText: 'No hay Registros'	
		}); 
		// Obtiene el elemento del BODY 
		mycustomview.render( OBJ.document.getElementById('cntrptdata') ) ;  
		this.flagdatachange=true;		
	},
	printGrid:function(OBJ){
		//debugger;
		this.pageOBJ = OBJ; 
		var mydatatoprint = this.getDatareport();
		OBJ.document.title=this.localeData.Title;
		if (this.useCustom.custom){
			var checkcount = this.useCustom.customStore.getCount();  
			if (checkcount< this.useCustom.pageToolbar.pageSize){ 
			
			} else { 
				this.useCustom.pageToolbar.render(OBJ.document.body);
				this.flagrenderedPtool =true; 
			} 
		}
		Ext.get(OBJ.document.body).insertHtml('beforeEnd', '<div id="reportcontent">' +  mydatatoprint + '</div>', false);
		this.flagdatachange=true;
	},
	newPage:function(){	
		var mydatatoprint = this.getDatareport();
		var testxx1 = Ext.get( this.pageOBJ.document.getElementById('reportcontent')); 
		testxx1.update(mydatatoprint)
		this.flagdatachange=true;		
	}
	
});

function isDate(dateStr) {

	var datePat = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
	var matchArray = dateStr.match(datePat); // is the format ok?

	if (matchArray == null) {
		//alert("Please enter date as either mm/dd/yyyy or mm-dd-yyyy.");
		return false;
	}

	month = matchArray[1]; // p@rse date into variables
	day = matchArray[3];
	year = matchArray[5];

	if (month < 1 || month > 12) { // check month range
		//alert("Month must be between 1 and 12.");
		return false;
	}

	if (day < 1 || day > 31) {
		//alert("Day must be between 1 and 31.");
		return false;
	}

	if ((month==4 || month==6 || month==9 || month==11) && day==31) {
		//alert("Month "+month+" doesn`t have 31 days!")
		return false;
	}

	if (month == 2) { // check for february 29th
		var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
		if (day > 29 || (day==29 && !isleap)) {
			//alert("February " + year + " doesn`t have " + day + " days!");
			return false;
		}
	}
	return true; // date is valid
}