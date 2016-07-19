var global_printer = null;  // it has to be on the index page or the generator page  always
Ext.ux.printmygridGO 		= function (obj){  global_printer.printGrid(obj);	}
Ext.ux.printmygridGOcustom  = function (obj){ global_printer.printCustom(obj);	}
Ext.ux.basic_printGrid = function(pGrid){
		global_printer = new Ext.grid.XPrinter({
			grid:			pGrid,  // grid object
			pathPrinter:	app.extPath + '/ux/printer',  	 // relative to where the Printer folder resides
			//logoURL: 		'ext_logo.jpg', // relative to the html files where it goes the base printing
			pdfEnable: 		true,  // enables link PDF printing (only save as)
			hasrowAction:	false,
			localeData:{
				Title:		pGrid.title,
				subTitle:	Ext.util.JSON.encode(pGrid.getStore().lastOptions),
				footerText:	'',
				pageNo:		'Pag # ',	//page label
				printText:	'Imprimir',  //print document action label
				closeText:	'Cerrar',  //close window action label
				pdfText:	'PDF'
            },useCustom:{  // in this case you leave null values as we dont use a custom store and TPL
				custom:		false,
				customStore:null,
				columns:	[],
				alignCols:	[],
				pageToolbar:null,
				useIt: 		false,
				showIt: 	false,
				location: 	'bbar'
			},
			showdate:		true,// show print date
			showdateFormat:'Y-F-d H:i:s', //
			showFooter:		true,  // if the footer is shown on the pinting html
			styles:			'default' // wich style youre gonna use
		});
		global_printer.prepare(); // prepare the document
}

function basic_printGrid_exclude(){
		global_printer = 	new Ext.grid.XPrinter({
			grid:grid,  	// grid object
			pathPrinter:	'./printer',  	 // relative to where the Printer folder resides
			logoURL: 		'ext_logo.jpg', // relative to the html files where it goes the base printing
			pdfEnable: 		true,  // enables link PDF printing (only save as)
			hasrowAction:	false,
			excludefields:	'4,',  // 0 based index , if it has numberer or action it counts as a column
			localeData:{
				Title:		' ',
				subTitle:	'by XtPrinter',
				footerText:	'Report Footer goes here',
				pageNo:		'Page # ',	//page label
				printText:	'Print',  //print document action label
				closeText:	'Close',  //close window action label
				pdfText:	'PDF'
            }, useCustom:{  // in this case you leave null values as we dont use a custom store and TPL
				custom:		false,
				customStore:	null,
				columns:	[],
				alignCols:	[],
				pageToolbar:null,
				useIt: 		false,
				showIt: 	false,
				location: 	'bbar'
			},
			showdate:		true,// show print date
			showdateFormat:	'Y-F-d H:i:s', //
			showFooter:		true,  // if the footer is shown on the pinting html
			styles:			'default' // wich style youre gonna use
		});
		global_printer.prepare(); // prepare the document
}
