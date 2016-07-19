

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
					columnstext+='</tr>';
					varnumrecs = this.grid.store.getCount(); 
					tdvalues=''; 
					for (var i=0; i<varnumrecs; i++){
						tdvalues+='<tr>';
						var testcountfields=this.grid.store.getAt(0).fields.length;  // just the initial record for field count 
						for (var jval=0; jval<testcountfields; jval++){
							if ( jval>(this.grid.getColumnModel().config.length-1) ){		
							
							} else { 	
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
										tmpchkhidden = this.grid.getColumnModel().isHidden(numcolref); 										
									} 
									if ( tmpchkhidden ) { 
										var test=11; 
									} else {
										if ( this.excludefields.indexOf('' + numcolref + ',')>=0 ){ 
											// do nothing its excluded 
										} else { 
											var testrender = this.grid.getColumnModel().getRenderer(numcolref); 
											tdvalues+='<td valign="middle" style="font-size:7px;" class="values" align="' +  this.grid.getColumnModel().config[numcolref].align + '">'; 
											if (this.usenameindex==false){ 
												tdvalues+= testrender.call(this,this.grid.store.getAt(i).data[this.grid.store.getAt(i).fields.items[jval].name])
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