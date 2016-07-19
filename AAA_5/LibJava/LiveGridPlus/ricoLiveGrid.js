/**
  *
  *  PORTIONS OF THIS FILE ARE BASED ON RICO LIVEGRID 1.1.2
  *
  *  Copyright 2005 Sabre Airline Solutions
  *
  *  Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
  *  file except in compliance with the License. You may obtain a copy of the License at
  *
  *         http://www.apache.org/licenses/LICENSE-2.0
  *
  *  Unless required by applicable law or agreed to in writing, software distributed under the
  *  License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
  *  either express or implied. See the License for the specific language governing permissions
  *  and limitations under the License.
  *
  *
  *  Enhanced by Matt Brown
  *  May-Aug 2006
  *  Email: dowdybrown@yahoo.com
  *  Web:   dowdybrown.com
  *
  *  Resizable columns
  *  Hide/show columns
  *  Filtering
  *  Enabled dynamic table sizing (accepts <rowcount> tag in xmlhttp response)
  *  Enabled compatibility with Opera, Safari, Konqueror
  *  Can optionally use a textarea to display debug messages about xmlhttprequest traffic
  *
  **/


//-------------------- ricoAjaxEngine.js
Rico.AjaxEngine = Class.create();

Rico.AjaxEngine.prototype = {

   initialize: function() {
      this.ajaxElements = new Array();
      this.ajaxObjects  = new Array();
      this.requestURLS  = new Array();
      this.options = {};
   },

   registerAjaxElement: function( anId, anElement ) {
      if ( !anElement )
         anElement = $(anId);
      this.ajaxElements[anId] = anElement;
   },

   registerAjaxObject: function( anId, anObject ) {
      this.ajaxObjects[anId] = anObject;
   },

   registerRequest: function (requestLogicalName, requestURL) {
      this.requestURLS[requestLogicalName] = requestURL;
   },

   sendRequest: function(requestName, options) {
      // Allow for backwards Compatibility
      if ( arguments.length >= 2 )
       if (typeof arguments[1] == 'string')
         options = {parameters: this._createQueryString(arguments, 1)};
      this.sendRequestWithData(requestName, null, options);
   },

   sendRequestWithData: function(requestName, xmlDocument, options) {
      var requestURL = this.requestURLS[requestName];
      if ( requestURL == null )
         return;

      // Allow for backwards Compatibility
      if ( arguments.length >= 3 )
        if (typeof arguments[2] == 'string')
          options.parameters = this._createQueryString(arguments, 2);

      new Ajax.Request(requestURL, this._requestOptions(options,xmlDocument));
   },

   sendRequestAndUpdate: function(requestName,container,options) {
      // Allow for backwards Compatibility
      if ( arguments.length >= 3 )
        if (typeof arguments[2] == 'string')
          options.parameters = this._createQueryString(arguments, 2);

      this.sendRequestWithDataAndUpdate(requestName, null, container, options);
   },

   sendRequestWithDataAndUpdate: function(requestName,xmlDocument,container,options) {
      var requestURL = this.requestURLS[requestName];
      if ( requestURL == null )
         return;

      // Allow for backwards Compatibility
      if ( arguments.length >= 4 )
        if (typeof arguments[3] == 'string')
          options.parameters = this._createQueryString(arguments, 3);

      var updaterOptions = this._requestOptions(options,xmlDocument);

      new Ajax.Updater(container, requestURL, updaterOptions);
   },

   // Private -- not part of intended engine API --------------------------------------------------------------------

   _requestOptions: function(options,xmlDoc) {
      var requestHeaders = ['X-Rico-Version', Rico.Version ];
      var sendMethod = 'post';
      if ( xmlDoc == null )
        if (Rico.prototypeVersion < 1.4)
        requestHeaders.push( 'Content-type', 'text/xml' );
      else
          sendMethod = 'get';
      (!options) ? options = {} : '';

      if (!options._RicoOptionsProcessed){
      // Check and keep any user onComplete functions
        if (options.onComplete)
             options.onRicoComplete = options.onComplete;
        // Fix onComplete
        if (options.overrideOnComplete)
          options.onComplete = options.overrideOnComplete;
        else
          options.onComplete = this._onRequestComplete.bind(this);
        options._RicoOptionsProcessed = true;
      }

     // Set the default options and extend with any user options
     this.options = {
                     requestHeaders: requestHeaders,
                     parameters:     options.parameters,
                     postBody:       xmlDoc,
                     method:         sendMethod,
                     onComplete:     options.onComplete
                    };
     // Set any user options:
     Object.extend(this.options, options);
     return this.options;
   },

   _createQueryString: function( theArgs, offset ) {
      var queryString = ""
      for ( var i = offset ; i < theArgs.length ; i++ ) {
          if ( i != offset )
            queryString += "&";

          var anArg = theArgs[i];

          if ( anArg.name != undefined && anArg.value != undefined ) {
            queryString += anArg.name +  "=" + escape(anArg.value);
          }
          else {
             var ePos  = anArg.indexOf('=');
             var argName  = anArg.substring( 0, ePos );
             var argValue = anArg.substring( ePos + 1 );
             queryString += argName + "=" + escape(argValue);
          }
      }
      return queryString;
   },

   _onRequestComplete : function(request) {
      if(!request)
          return;
      // User can set an onFailure option - which will be called by prototype
      if (request.status != 200)
        return;

      var response = request.responseXML.getElementsByTagName("ajax-response");
      if (response == null || response.length != 1)
         return;
      this._processAjaxResponse( response[0].childNodes );

      // Check if user has set a onComplete function
      var onRicoComplete = this.options.onRicoComplete;
      if (onRicoComplete != null)
          onRicoComplete();
   },

   _processAjaxResponse: function( xmlResponseElements ) {
      for ( var i = 0 ; i < xmlResponseElements.length ; i++ ) {
         var responseElement = xmlResponseElements[i];

         // only process nodes of type element.....
         if ( responseElement.nodeType != 1 )
            continue;

         var responseType = responseElement.getAttribute("type");
         var responseId   = responseElement.getAttribute("id");

         if ( responseType == "object" )
            this._processAjaxObjectUpdate( this.ajaxObjects[ responseId ], responseElement );
         else if ( responseType == "element" )
            this._processAjaxElementUpdate( this.ajaxElements[ responseId ], responseElement );
         else
            alert('unrecognized AjaxResponse type : ' + responseType );
      }
   },

   _processAjaxObjectUpdate: function( ajaxObject, responseElement ) {
      ajaxObject.ajaxUpdate( responseElement );
   },

   _processAjaxElementUpdate: function( ajaxElement, responseElement ) {
      ajaxElement.innerHTML = RicoUtil.getContentAsString(responseElement);
   }

}

var ajaxEngine = new Rico.AjaxEngine();


//-------------------- ricoLiveGrid.js
// Rico.LiveGridMetaData -----------------------------------------------------

Rico.LiveGridMetaData = Class.create();

Rico.LiveGridMetaData.prototype = {

   initialize: function( pageSize, options ) {
      this.pageSize  = pageSize;
      this.options = options;
   },

   getPageSize: function() {
      return this.pageSize;
   },

   getTotalRows: function() {
      return this.totalRows;
   },

   getRowsNotDisplayed: function() {
      return Math.max(this.totalRows-this.pageSize,0);
   },

   /* should only be called from LiveGrid.setTotalRows */
   setTotalRows: function(n) {
      this.totalRows = n;
   },

   getLargeBufferSize: function() {
      return parseInt(this.options.largeBufferSize * this.pageSize);
   },

   getLimitTolerance: function() {
      return parseInt(this.getLargeBufferSize() * this.options.nearLimitFactor);
   }
};


// Rico.LiveGridBuffer -----------------------------------------------------

Rico.LiveGridBuffer = Class.create();

Rico.LiveGridBuffer.prototype = {

   initialize: function(liveGrid) {
      this.liveGrid = liveGrid;
      this.startPos = -1;
      this.size     = 0;
      this.rows     = new Array();
      this.updateInProgress = false;
      this.lastOffset = 0;
      this.rcvdRowCount = false;  /* true if an eof element was included in the last xml response */
      this.foundRowCount = false; /* true if an xml response is ever received with eof true */
      this.rowcntContent = "";
      this.rcvdOffset = -1;
      this.rcvdRows = 0;
   },

   setBufferSize: function(metaData) {
      this.metaData = metaData;  /* can't do this in initialize because metaData doesn't exist yet */
      this.maxBufferSize = this.metaData.getLargeBufferSize() * 2;
      this.maxFetchSize = this.metaData.getLargeBufferSize();
   },

   getContent: function(cell) {
      if (cell.innerHTML) return cell.innerHTML;
      switch (cell.childNodes.length) {
        case 0:  return "";
        case 1:  return cell.firstChild.nodeValue;
        default: return cell.childNodes[1].nodeValue;
      }
   },

   loadRows: function(ajaxResponse) {
      this.liveGrid.writeDebugMsg("loadRows");
      var error = ajaxResponse.getElementsByTagName('error');
      if (error.length > 0) {
        alert("Data provider returned an error:\n"+this.getContent(error[0]));
        this.liveGrid.writeDebugMsg("Data provider returned an error:\n"+this.getContent(error[0]));
        return null;
      }
      var rowsElement = ajaxResponse.getElementsByTagName('rows')[0];
      var debugtags = ajaxResponse.getElementsByTagName('debug');
      for (var i=0; i<debugtags.length; i++)
        this.liveGrid.writeDebugMsg("loadRows, debug msg "+i+": "+this.getContent(debugtags[i]));
      var rowcnttags = ajaxResponse.getElementsByTagName('rowcount');
      this.rcvdRowCount = false;
      if (rowcnttags && rowcnttags.length==1) {
        this.rowcntContent = this.getContent(rowcnttags[0]);
        this.rcvdRowCount = true;
        this.foundRowCount = true;
      }
      this.updateUI = rowsElement.getAttribute("update_ui") == "true";
      this.rcvdOffset = rowsElement.getAttribute("offset");
      this.liveGrid.writeDebugMsg("loadRows, rcvdOffset="+this.rcvdOffset);
      return this.dom2jstable(rowsElement);
   },

   loadRowsFromTable: function(tableElement) {
      this.rows = this.dom2jstable(tableElement);
      this.size = this.rows.length;
      this.startPos = 0;
      this.rowcntContent = this.size.toString();
      this.rcvdRowCount = true;
      this.foundRowCount = true;
   },

   sortBuffer: function(colnum,sortdir,coltype) {
      this.sortColumn=colnum;
      var sortFunc;
      switch (coltype) {
        case 'number': sortFunc=this.sortNumeric.bind(this); break;
        default:       sortFunc=this.sortAlpha.bind(this); break;
      }
      this.rows.sort(sortFunc);
      if (sortdir=='DESC') this.rows.reverse();
   },

   sortAlpha: function(a,b) {
      var aa = RicoUtil.getInnerText(a[this.sortColumn]);
      var bb = RicoUtil.getInnerText(b[this.sortColumn]);
      if (aa==bb) return 0;
      if (aa<bb) return -1;
      return 1;
   },

   sortNumeric: function(a,b) {
     var aa = parseFloat(RicoUtil.getInnerText(a[this.sortColumn]));
     if (isNaN(aa)) aa = 0;
     var bb = parseFloat(RicoUtil.getInnerText(b[this.sortColumn]));
     if (isNaN(bb)) bb = 0;
     return aa-bb;
   },

   dom2jstable: function(rowsElement) {
      var newRows = new Array();
      var trs = rowsElement.getElementsByTagName("tr");
      this.rcvdRows=trs.length;
      for ( var i=0 ; i < trs.length; i++ ) {
         var row = newRows[i] = new Array();
         var cells = trs[i].getElementsByTagName("td");
         for ( var j=0; j < cells.length ; j++ ) {
            row[j]=this.getContent(cells[j]);
            //if (i==0) this.liveGrid.writeDebugMsg("dom2jstable r0c"+j+": type="+cells[j].nodeType+" value="+row[j]);
         }
      }
      return newRows;
   },

   update: function(ajaxResponse, start) {
      this.liveGrid.writeDebugMsg("update:"+start);
      var newRows = this.loadRows(ajaxResponse);
      this.liveGrid.writeDebugMsg("update, rcvdRows="+this.rcvdRows+" rowslen="+this.rows.length);
      if (newRows==null) return;
      if (this.rows.length == 0) { // initial load
         this.rows = newRows;
         this.size = this.rows.length;
         this.startPos = start;
         return;
      }
      if (start > this.startPos) { //appending
         if (this.startPos + this.rows.length < start) {
            this.rows =  newRows;
            this.startPos = start;//
         } else {
              this.rows = this.rows.concat( newRows.slice(0, newRows.length));
            if (this.rows.length > this.maxBufferSize) {
               var fullSize = this.rows.length;
               this.rows = this.rows.slice(this.rows.length - this.maxBufferSize, this.rows.length)
               this.startPos = this.startPos +  (fullSize - this.rows.length);
            }
         }
      } else { //prepending
         if (start + newRows.length < this.startPos) {
            this.rows =  newRows;
         } else {
            this.rows = newRows.slice(0, this.startPos).concat(this.rows);
            if (this.rows.length > this.maxBufferSize)
               this.rows = this.rows.slice(0, this.maxBufferSize)
         }
         this.startPos =  start;
      }
      this.size = this.rows.length;
   },

   clear: function() {
      this.rows = new Array();
      this.startPos = -1;
      this.size = 0;
   },

   isOverlapping: function(start, size) {
      return ((start < this.endPos()) && (this.startPos < start + size)) || (this.endPos() == 0)
   },

   isInRange: function(position) {
      return (position >= this.startPos) && (position + this.metaData.getPageSize() <= this.endPos()) && (this.size != 0);
   },

   isNearingTopLimit: function(position) {
      return position - this.startPos < this.metaData.getLimitTolerance();
   },

   endPos: function() {
      return this.startPos + this.rows.length;
   },

   isNearingBottomLimit: function(position) {
      return this.endPos() - (position + this.metaData.getPageSize()) < this.metaData.getLimitTolerance();
   },

   isAtTop: function() {
      return this.startPos == 0;
   },

   isAtBottom: function() {
      return this.endPos() == this.metaData.getTotalRows();
   },

   isNearingLimit: function(position) {
      return ( !this.isAtTop()    && this.isNearingTopLimit(position)) ||
             ( !this.isAtBottom() && this.isNearingBottomLimit(position) )
   },

   getFetchSize: function(offset) {
      var adjustedOffset = this.getFetchOffset(offset);
      var adjustedSize = 0;
      if (adjustedOffset >= this.startPos) { //appending
        var endFetchOffset = this.maxFetchSize  + adjustedOffset;
        if (endFetchOffset > this.metaData.totalRows)
           endFetchOffset = this.metaData.totalRows;
        adjustedSize = endFetchOffset - adjustedOffset;
        this.liveGrid.writeDebugMsg("getFetchSize/append, adjustedSize="+adjustedSize+" adjustedOffset="+adjustedOffset+' endFetchOffset='+endFetchOffset);
        if(adjustedOffset == 0 && adjustedSize < this.maxFetchSize) {
           adjustedSize = this.maxFetchSize;
        }
      } else { //prepending
        var adjustedSize = this.startPos - adjustedOffset;
        if (adjustedSize > this.maxFetchSize)
            adjustedSize = this.maxFetchSize;
      }
      return adjustedSize;
   },

   getFetchOffset: function(offset) {
      var adjustedOffset = offset;
      if (offset > this.startPos)  //apending
         adjustedOffset = (offset > this.endPos()) ? offset :  this.endPos();
      else { //prepending
         if (offset + this.maxFetchSize >= this.startPos) {
            var adjustedOffset = this.startPos - this.maxFetchSize;
            if (adjustedOffset < 0)
               adjustedOffset = 0;
         }
      }
      this.lastOffset = adjustedOffset;
      return adjustedOffset;
   },

   getRows: function(start, count) {
      var begPos = start - this.startPos
      var endPos = begPos + count

      // er? need more data...
      if ( endPos > this.size )
         endPos = this.size

      var results = new Array()
      var index = 0;
      for ( var i=begPos ; i < endPos; i++ ) {
         results[index++] = this.rows[i]
      }
      return results
   }

};


Rico.LiveGridRequest = Class.create();
Rico.LiveGridRequest.prototype = {
   initialize: function( requestOffset, options ) {
      this.requestOffset = requestOffset;
   }
};


var gridMenu = new Rico.Menu("20em");


// Rico.LiveGrid -----------------------------------------------------

Rico.LiveGrid = Class.create();

Rico.LiveGrid.prototype = {

  initialize: function( tableId, visibleRows, totalRows, url, options, ajaxOptions ) {

    //alert('LiveGrid initialize');
    this.tableId   = tableId;
    this.debugArea  = $(tableId+"_debugmsgs");    // if used, this should be a textarea
    if (this.debugArea) this.debugArea.value="";  // Firefox doesn't clear it on a reload
    this.dataSource = url;

    this.options = {
      cookiePrefix     : 'liveGrid.'+tableId,
      bufferTimeout    : 20000,
      sortAscendImg    : 'sort_asc.gif',
      sortDescendImg   : 'sort_desc.gif',
      filterImg        : 'filtercol.gif',
      resizeBackground : 'resize.gif',
      menuTargetImg    : 'menu.gif',
      allowColResize   : true,  /* allow user to resize columns */
      canSortDefault   : true,  /* can be overridden in the column specs */
      canFilterDefault : url!=null, /* can be overridden in the column specs */
      canHideDefault   : true,      /* can be overridden in the column specs */
      headingSort      : true,      /* make headings a clickable link that will sort column */
      saveColumnInfo   : true,      /* save column hide/show status & width in a cookie */
      rowHighlightColor: '#CCFF9D', /* row highlight background color */
      highltOnMouseOver: true,      /* true=highlight on row mouseover, false=highlight on click */
      onRefreshComplete: null,
      requestParameters: null,
      columnSpecs      : null, /* [ specName1, specName2, ... ] one entry per column, multiple columns can use the same spec */
      dataMenuHandler  : null, /* put custom items on the menu */
      menuEvent        : 'dblclick',   /* event that triggers menus - click, dblclick, contextmenu, or none (no menus) */
      frozenColumns    : 0,
      offset           : 0,   /* first row to be displayed */
      headingRow       : 0,   /* row in the headings table that contains column descriptions */
      bufferExtend     : 100, /* if EOF is not received when expected, extend expected records by this amount */
      rowTag           : 'TR',
      hdrcellTag       : 'TH',
      datacellTag      : 'TD',
      scrollBarWidth   : 19,    // this is the value used in positioning calculations, it does not actually change the width of the scrollbar
      minScrollWidth   : 100,   // min scroll area width when width of frozen columns exceeds window width
      borderWidth      : 1,     // border width on tables
      largeBufferSize  : 7.0,   // 7 pages (this used to be in metadata)
      nearLimitFactor  : 0.2,   // 20% of buffer (this used to be in metadata)
      likeWildcard     : '%',   /* wilcard character used in sql like clauses */
      hdrIconsFirst    : true,  /* true: put sort & filter icons before header text, false: after */
      rowHighlights    : true,  /* enabled row highlighting */
      specDollar       : {type:'number', prefix:'$', decPlaces:2},
      specEuro         : {type:'number', prefix:'&euro;', decPlaces:2},
      specPercent      : {type:'number', suffix:'%', decPlaces:2, multiplier:100},
      specQty          : {type:'number', decPlaces:0},
      specDefault      : {type:"raw", quotes:"'"}
    };
    // other options:
    //   specName1:   {type:'raw|text|date|number', canSort:true, canFilter:true, className:'col1Class', quotes:"'", ...}
    //   sortCol: initial sort column
    //   customFilter: custom query filter (not column related)

    this.options.sortHandler = this.sortHandler.bind(this);
    this.options.filterHandler = this.filterHandler.bind(this);
    this.options.onRefreshComplete = this.bookmarkHandler.bind(this);
    this.options.rowOverHandler = this.rowMouseOver.bindAsEventListener(this);
    this.options.rowOutHandler  = this.rowMouseOut.bindAsEventListener(this);
    Object.extend(this.options, options || {});

    this.ajaxOptions = {parameters: null};
    Object.extend(this.ajaxOptions, ajaxOptions || {});

    this.buffer = new Rico.LiveGridBuffer(this);
    this.highlightEnabled=this.options.highltOnMouseOver;
    this.lastHighlightRow = -1;
    this.lastHighlightCell = null;
    var hdrtableId = tableId + '_header';
    visibleRows=this.createTables(visibleRows,hdrtableId);
    if (visibleRows < 1) {
      alert("ERROR!\n\nEither you need a header table ('"+hdrtableId+"')\nor a thead section in '"+tableId+"'\n\nLiveGrid terminated");
      return;
    }

    this.requestCount=0;
    this.bookmark=$(tableId+"_bookmark");
    this.metaData = new Rico.LiveGridMetaData(visibleRows, this.options);
    this.buffer.setBufferSize(this.metaData);
    this.sizeDivs();
    this.setTotalRows(totalRows);
    this.scrollTimeout = null;
    this.lastScrollPos = 0;
    if (this.options.saveColumnInfo==true) {
      for ( var i = 0 ; i < this.headerColCnt; i++ )
        this.columns[i].retrieveStatus();
    }
    this.sizeDivs();
    this.hideScroll=navigator.userAgent.match(/Macintosh\b.*\b(Firefox|Camino)\b/i) || RicoUtil.isOpera;
    gridMenu.createDiv();
    if (RicoUtil.isSafari)
      gridMenu.div.className='ricoMenuSafari';

    // preload the images...
    new Image().src = Rico.imgDir+this.options.filterImg;
    new Image().src = Rico.imgDir+this.options.sortAscendImg;
    new Image().src = Rico.imgDir+this.options.sortDescendImg;

    this.setSortUI( this.options.sortCol, this.options.sortDir );
    if (this.listInvisible().length==this.columns.length)
      this.columns[0].showColumn();

    this.processingRequest = null;
    this.unprocessedRequest = null;
    this.attachScrollBarEvents();
    Event.observe(window,"resize", this.sizeDivs.bindAsEventListener(this), false);
    this.initData();
  },

  initData: function() {
    if (typeof this.dataSource=='string') {
      ajaxEngine.registerRequest( this.tableId + '_request', this.dataSource );
      ajaxEngine.registerAjaxObject( this.tableId + '_updater', this );
      if (this.options.prefetchBuffer==true) {
        if (this.options.offset)
          this.scrollToRow(this.options.offset);
        this.requestContentRefresh(this.options.offset);
      } else {
        this.scrollToRow(0);
      }
    } else {
      this.writeDebugMsg("initData: using preloaded data, offset="+this.options.offset);
      if (this.options.offset>0) {
        this.scrollToRow(this.options.offset);
        this.refreshContents(this.options.offset);
      } else {
        this.options.sortHandler();
      }
    }
  },

  // transform original tables into a header table and a data table
  // and add a div to contain them
  // returns the number of visible rows (-1 indicates failure)
  createTables: function(visibleRows,hdrtableId) {
    var result   = -1;
    var table    = $(this.tableId);
    var hdrtable = $(hdrtableId);
    if (typeof this.dataSource!='string') {
      if (!table) return result;
      this.buffer.loadRowsFromTable(table.tBodies[0]);
    }
    var insertloc;
    if (hdrtable) {
      insertloc=hdrtable;
      var theads=hdrtable.getElementsByTagName("thead");
      this.hdrSrc=(theads.length == 1) ? theads[0] : hdrtable.tBodies[0];
      this.writeDebugMsg("createTables: using hdrtable, id="+hdrtableId);
    } else if (table) {
      var theads=table.getElementsByTagName("thead");
      if (theads.length != 1) return result;
      this.writeDebugMsg("createTables: using thead section, id="+this.tableId);
      this.hdrSrc=theads[0];
      insertloc=table;
    } else {
      return result;
    }

    // gather info from original headings
    var headerRows = this.hdrSrc.getElementsByTagName(this.options.rowTag);
    this.headerRowCnt=headerRows.length;
    this.hdrSpan=new Array();
    var colWidths = new Array();
    for (r=0; r<headerRows.length; r++) {
      var headerRow = headerRows[r];
      var headerCells=headerRow.getElementsByTagName(this.options.hdrcellTag);
      this.hdrSpan[r]=new Array(headerCells.length);
      for (c=0; c<headerCells.length; c++)
        this.hdrSpan[r][c]=headerCells[c].colSpan || 1;  // Safari & Konqueror return default colspan of 0
      if (r==this.options.headingRow) {
        this.headerColCnt=headerCells.length;
        for ( var i = 0 ; i < this.headerColCnt; i++ )
          colWidths.push(headerCells[i].offsetWidth);
      }
    }
    this.writeDebugMsg("createTables: header rows="+this.headerRowCnt+" columns="+this.headerColCnt);
    //alert("createTables: header rows="+this.headerRowCnt+" columns="+this.headerColCnt);

    // create div structure
    this.outerDiv = document.createElement('div');
    this.outerDiv.id=this.tableId+"_outerDiv";
    this.outerDiv.className='ricoLG_outerDiv';

    this.scrollDiv  = this.createDiv("scroll",this.outerDiv);
    this.shadowDiv  = this.createDiv("shadow",this.scrollDiv);
    this.frozenTabs = this.createDiv("frozenTabs",this.outerDiv);
    this.innerDiv   = this.createDiv("inner",this.outerDiv);
    this.scrollTabs = this.createDiv("scrollTabs",this.innerDiv);
    this.spacerDiv  = this.createDiv("spacer",this.outerDiv);
    this.resizeDiv  = this.createDiv("resize",this.outerDiv);
    this.resizeDiv.style.display="none";
    this.messageDiv = this.createDiv("message",this.outerDiv);
    this.messageDiv.style.display="none";

    // create new tables
    this.tabs=new Array(2);
    this.thead=new Array(2);
    this.tbody=new Array(2);
    this.safariHandler=this.handleSafariMenuClick.bindAsEventListener(this);
    this.menuHandler=this.handleMenuClick.bindAsEventListener(this);
    for (var i=0; i<2; i++) {
      this.tabs[i] = document.createElement("table");
      this.tabs[i].className = "ricoLG_table"+i;
      this.tabs[i].cellSpacing = 0;
      this.tabs[i].cellPadding = 0;
      this.tabs[i].border = this.options.borderWidth;
      this.tabs[i].id = this.tableId+"_tab"+i;
      this.thead[i]=this.tabs[i].createTHead();
      if (this.tabs[i].tBodies.length==0)
        this.tbody[i]=this.tabs[i].appendChild(document.createElement("tbody"));
      else
        this.tbody[i]=this.tabs[i].tBodies[0];
      if (this.options.menuEvent && this.options.menuEvent!='none') {
        if (this.options.menuEvent=='dblclick' && (RicoUtil.isSafari || RicoUtil.isOpera))
          Event.observe(this.tbody[i], 'click', this.safariHandler, false);
        else
          Event.observe(this.tbody[i], this.options.menuEvent, this.menuHandler, false);
      }
      for (var r=this.hdrSrc.firstChild; r!=null; r=r.nextSibling)
        if (r.nodeType==1 && r.nodeName!='COLGROUP' && r.nodeName!='COL')
          this.thead[i].appendChild(r.cloneNode(true));
    }
    this.frozenTabs.appendChild(this.tabs[0]);
    this.scrollTabs.appendChild(this.tabs[1]);

    // format new heading cells
    this.hdrCells=new Array(2);
    for (var t=0; t<2; t++) {
      var hdrRows=this.tabs[t].getElementsByTagName(this.options.rowTag);
      this.hdrCells[t]=new Array(hdrRows.length);
      for (r=0; r<hdrRows.length; r++) {
        this.hdrCells[t][r]=new Array();
        this.hdrCells[t][r].pushHTMLCollection(hdrRows[r].getElementsByTagName(this.options.hdrcellTag));
        for ( var c=0 ; c<this.hdrCells[t][r].length; c++ )
          RicoUtil.wrapChildren(this.hdrCells[t][r][c],'ricoLG_cell');
      }
    }
    
    // create column array
    this.columns = new Array();
    for ( var c=0 ; c < this.headerColCnt; c++ ) {
      var tabidx=c<this.options.frozenColumns ? 0 : 1;
      this.columns.push(new Rico.TableColumn(this, c, colWidths[c], tabidx));
    }
    
    insertloc.parentNode.insertBefore(this.outerDiv,insertloc);
    if (hdrtable) hdrtable.parentNode.removeChild(hdrtable);
    if (table) table.parentNode.removeChild(table);
    result=this.createRows(visibleRows);
    return result;
  },

  createDiv: function(elemName,elemParent) {
    var newdiv = document.createElement("div");
    newdiv.className = "ricoLG_"+elemName+"Div";
    newdiv.id = this.tableId+"_"+elemName+"Div";
    elemParent.appendChild(newdiv);
    return newdiv;
  },

  // size header cells in rows other than the main one
  setOtherHdrCellWidths: function() {
    var hdgrow=this.options.headingRow;
    for (var c=0; c<this.options.frozenColumns; c++)
      this.columns[c].xhide();
    for (var t=0; t<2; t++) {
      for (var r=0; r<this.hdrCells[t].length; r++) {
        if (r==hdgrow) continue;
        var c=i=0;
        while (i<this.headerColCnt && c<this.hdrCells[t][r].length) {
          var origSpan=newSpan=this.hdrSpan[r][c];
          for (var w=j=0; j<origSpan; j++, i++) {
            var tabidx=i<this.options.frozenColumns ? 0 : 1;
            if (this.hdrCells[t][hdgrow][i].style.display=='none' || tabidx!=t)
              newSpan--;
            else if (this.columns[i].hdrdiv[tabidx].style.display!='none')
              w+=parseInt(this.columns[i].hdrdiv[tabidx].style.width);
          }
          var cell=this.hdrCells[t][r][c];
          var div=cell.getElementsByTagName('DIV')[0];
          if (newSpan==0) {
            cell.style.display='none';
          } else if (w==0) {
            div.style.display='none';
            cell.colSpan=newSpan;
          } else {
            cell.style.display='';
            div.style.display='';
            cell.colSpan=newSpan;
            div.style.width=w+'px';
          }
          c++;
        }
      }
    }
  },

  sizeDivs: function() {
    this.setOtherHdrCellWidths();
    if (this.options.frozenColumns==0) {
      this.tabs[0].style.display='none';
      var frzWi=0;
    } else {
      this.tabs[0].style.display='';
      var frzWi=this.tabs[0].offsetWidth;
    }
    var scrWi=this.tabs[1].offsetWidth;
    var dataHt=Math.max(RicoUtil.nan2zero(this.tbody[0].offsetHeight),this.tbody[1].offsetHeight);
    var hdrHt=Math.max(RicoUtil.nan2zero(this.thead[0].offsetHeight),this.thead[1].offsetHeight);
    var totHt=Math.max(RicoUtil.nan2zero(this.tabs[0].offsetHeight),this.tabs[1].offsetHeight);
    //var dataHt=totHt-hdrHt

    var totw=frzWi+scrWi+this.options.scrollBarWidth;
    var odw=this.outerDiv.offsetWidth;
    var pad=0;
    if (totw>odw) {
      totw=odw;
      pad=4;
    }
    this.visibleWidth=totw;
    this.headerHeight=hdrHt;
    totw-=frzWi;
    if (totw<this.options.minScrollWidth)
      totw=Math.min(scrWi+this.options.scrollBarWidth,this.options.minScrollWidth);
    this.writeDebugMsg('sizeDivs: frozen='+frzWi+','+hdrHt+'\nscroll='+scrWi+','+dataHt+'\ntotw='+totw);
    this.rowHeight = dataHt/this.metaData.getPageSize();
    this.writeDebugMsg("sizeDivs: rowHeight="+this.rowHeight+" hdrHt="+hdrHt+" totw="+totw);
    this.scrollDiv.style.width=totw+'px';
    if (scrWi>0 || RicoUtil.isIE || RicoUtil.isSafari)
      dataHt+=this.options.scrollBarWidth
    this.scrollDiv.style.height=dataHt+'px';
    this.scrollDiv.style.left=frzWi+'px';
    this.scrollDiv.style.top=hdrHt+'px';

    this.innerDiv.style.width=(totw-this.options.scrollBarWidth+1)+'px';
    this.innerDiv.style.left=frzWi+'px';
    this.innerDiv.style.height=(totHt+1)+'px';

    this.shadowDiv.style.width=(scrWi+pad)+'px';
    this.spacerDiv.style.height=hdrHt+'px';
    this.resizeDiv.style.height=totHt+'px';

    this.setHorizontalScroll();
  },

  setHorizontalScroll: function() {
    var scrleft=this.scrollDiv.scrollLeft;
    this.scrollTabs.style.left=(-scrleft)+'px';
  },

  updateHeightDiv: function() {
    var notdisp=this.metaData.getRowsNotDisplayed();
    var ht = this.scrollDiv.clientHeight + this.rowHeight * notdisp;
    //if (RicoUtil.isOpera) ht+=this.options.scrollBarWidth-3;
    this.writeDebugMsg("updateHeightDiv, ht="+ht+' scrollDiv.clientHeight='+this.scrollDiv.clientHeight+' rowsNotDisplayed='+notdisp);
    this.shadowDiv.style.height=ht+'px';
  },
  
  /* just for testing purposes */
  createAutoSizeTestDiv: function(ht) {
    var testdiv = this.createDiv("autosize",document.body);
    testdiv.style.position='absolute';
    testdiv.style.top=this.outerDiv.offsetTop+'px';
    testdiv.style.left=this.outerDiv.offsetLeft+'px';
    testdiv.style.width='5px';
    testdiv.style.height=ht+'px';
    testdiv.style.backgroundColor='blue';
    alert('createAutoSizeTestDiv: ht='+ht+' id='+testdiv.id+' od-top='+this.outerDiv.offsetTop);
  },

  createRows: function(numrows) {
    this.useIEevents=typeof this.outerDiv.onmouseenter!='undefined';
    var cellContent="<DIV class='ricoLG_cell'>&nbsp;</DIV>"
    this.writeDebugMsg("createDataTable useIEevents="+this.useIEevents);
    if (numrows < 0) {
      var divPos=Position.page(this.outerDiv);
      var availHt=RicoUtil.windowHeight()-divPos[1];
      availHt-=50;  // allow for scrollbar and some margin
      numrows=0;
      this.writeDebugMsg("createDataTable divPos="+divPos[1]+" availHt="+availHt);
      //this.createAutoSizeTestDiv(availHt);
      do {
        this.appendBlankRow(numrows,cellContent);
        numrows++;
        tabHt=Math.max(this.tabs[0].offsetHeight,this.tabs[1].offsetHeight);
      } while (tabHt < availHt);
      this.writeDebugMsg("createDataTable: created "+numrows+" rows");
    } else {
      for( var r=0; r < numrows; r++ ) {
        this.appendBlankRow(r,cellContent);
      }
    }
    return numrows;
  },

  // on older systems, this can be fairly slow
  appendBlankRow: function(rownum,cellContent) {
    this.writeDebugMsg("appendBlankRow #"+rownum);
    var oRow0 = this.tbody[0].insertRow(-1);
    var oRow1 = this.tbody[1].insertRow(-1);
    var cls=(rownum % 2==0) ? 'ricoLG_evenRow' : 'ricoLG_oddRow';
    oRow0.className=cls;
    oRow1.className=cls;
    for( var c=0; c < this.headerColCnt; c++ ) {
      var oCell0 = oRow0.insertCell(-1);
      var oCell1 = oRow1.insertCell(-1);
      oCell0.innerHTML=cellContent;
      oCell1.innerHTML=cellContent;
      this.columns[c].addCell(oCell0,oCell1);
    }
    if (this.options.rowHighlights==true) {
      this.attachRowEvents(oRow0);
      this.attachRowEvents(oRow1);
    }
  },
  
  clearSafariMenuClick: function() {
    this.safariClick=null;
  },
  
  handleSafariMenuClick: function(e) {
    var elem=Event.element(e);
		if (this.safariClick == elem) {
		  this.handleMenuClick(e);
		} else {
		  this.safariClick = elem;
		  this.safariTimer=setTimeout(this.clearSafariMenuClick.bind(this),300);
		}
  },
  
  handleMenuClick: function(e) {
    var elem=Event.element(e);
    Event.stop(e);
    cell=RicoUtil.getParentByTagName(elem,this.options.datacellTag);
    var a=cell.id.split(/_/);
    var l=a.length;
    var r=parseInt(a[l-2]);
    var c=parseInt(a[l-1]);
    this.rowHighlight(r+this.headerRowCnt);
    this.columns[c].createDataMenu(r);
    gridMenu.showmenu(e,this.closeMenu.bind(this));
    if (this.hideScroll) this.scrollDiv.style.overflow="hidden";
    return false;
  },
  
  closeMenu: function() {
    if (this.hideScroll) this.scrollDiv.style.overflow="";
    this.rowUnhighlight();
  },

  attachRowEvents: function(oRow) {
    if (this.useIEevents) {
      // these seem to be slightly faster
      Event.observe(oRow,"mouseenter", this.options.rowOverHandler, false);
      Event.observe(oRow,"mouseleave", this.options.rowOutHandler,  false);
    } else {
      Event.observe(oRow,"mouseover", this.options.rowOverHandler, false);
      Event.observe(oRow,"mouseout",  this.options.rowOutHandler,  false);
    }
  },

  rowMouseOver: function(e) {
    if (this.highlightEnabled==false || gridMenu.isVisible()) return true;
    var r=Event.element(e);
    if (this.useIEevents==false)
      r=RicoUtil.getParentByTagName(r,this.options.rowTag);
    if (!r) return;
    this.rowHighlight(r.rowIndex);
  },

  rowMouseOut: function(e) {
    if (this.highlightEnabled==false || gridMenu.isVisible()) return true;
    var r=Event.element(e);
    if (this.useIEevents==false) {
      r=RicoUtil.getParentByTagName(r,this.options.rowTag);
      if (!r) return;
      var reltg = (e.relatedTarget) ? e.relatedTarget : e.toElement;
      try {
        while (reltg != null && reltg != r)
          reltg=reltg.parentNode;
      } catch(err) {}
      if (reltg == r) return true;
    }
    this.rowUnhighlight(r.rowIndex);
  },

  rowHighlight: function(rowNum) {
    this.rowUnhighlight(this.lastHighlightRow);
    this.lastHighlightRow=rowNum;
    for(var t=0; t<2; t++)
      this.tabs[t].rows[this.lastHighlightRow].style.backgroundColor=this.options.rowHighlightColor;
  },

  rowUnhighlight: function(rowNum) {
    //if (gridMenu.isVisible()) return;
    if (typeof rowNum=='undefined')
      rowNum=this.lastHighlightRow;
    if (rowNum<0) return;
    for(var t=0; t<2; t++)
      this.tabs[t].rows[rowNum].style.backgroundColor="";
    if (this.lastHighlightRow==rowNum)
      this.lastHighlightRow=-1;
  },

  hideMsg: function() {
    this.messageDiv.style.display="none";
  },

  showMsg: function(msg) {
    this.messageDiv.innerHTML=RicoTranslate.getPhrase(msg);
    this.messageDiv.style.display="";
    var msgWidth=this.messageDiv.offsetWidth;
    var msgHeight=this.messageDiv.offsetHeight;
    var divht=this.outerDiv.offsetHeight;
    this.messageDiv.style.top=parseInt((divht-msgHeight)/2)+'px';
    this.messageDiv.style.left=parseInt((this.visibleWidth-msgWidth)/2)+'px';
    this.writeDebugMsg("showMsg: divwi="+this.visibleWidth+" divht="+divht+" "+msg);
  },

  resetContents: function(resetHt) {
    if (typeof resetHt==undefined || resetHt==true) {
      this.shadowDiv.style.height='0px';
    }
    this.scrollToRow(0);
    this.buffer.clear();
    this.clearRows();
    if (this.bookmark) this.bookmark.innerHTML="&nbsp;";
  },

  setImages: function() {
    for (n=0; n<this.columns.length; n++)
      this.columns[n].setImage();
  },

  setTotalRows: function( newTotalRows ) {
    this.metaData.setTotalRows(newTotalRows);
    this.updateHeightDiv();
  },

  handleTimedOut: function() {
    //server did not respond in __ seconds... assume that there could have been
    //an error or something, and allow requests to be processed again...
    this.writeDebugMsg("Request Timed Out");
    this.showMsg("Request for data timed out!");
    this.processingRequest = null;
    this.processQueuedRequest();
  },

  fetchBuffer: function(offset) {
    this.writeDebugMsg("fetchBuffer, offset="+offset);
    if (this.dataSource==null) return;
    if ( this.buffer.isInRange(offset) ) //&& !this.buffer.isNearingLimit(offset))
       return;
    if (offset >= this.metaData.getTotalRows()) return;
    if (this.processingRequest) {
       this.unprocessedRequest = new Rico.LiveGridRequest(offset);
       return;
    }
    this.writeDebugMsg("fetchBuffer, processing offset="+offset);
    var bufferStartPos = this.buffer.getFetchOffset(offset);
    this.processingRequest = new Rico.LiveGridRequest(offset);
    this.processingRequest.bufferOffset = bufferStartPos;
    var fetchSize = this.buffer.getFetchSize(offset);
    var partialLoaded = false;

    this.showMsg("Waiting for data...");
    this.sendAjaxRequest(bufferStartPos,fetchSize);
    this.timeoutHandler = setTimeout( this.handleTimedOut.bind(this), this.options.bufferTimeout);
  },
  
  sendAjaxRequest: function(startPos,fetchSize) {
    var queryString;
    if (this.options.requestParameters)
       queryString = this._createQueryString(this.options.requestParameters, 0);

    queryString = (queryString == null) ? '' : queryString+'&';
    queryString+= 'id='+this.tableId+'&page_size='+fetchSize+'&offset='+startPos;
    if (typeof this.customFilter=='string') queryString+='&f='+escape(this.customFilter);
    for (n=0; n<this.columns.length; n++) {
      if (this.columns[n].isSorted())
        queryString += '&s'+n+'='+this.columns[n].getSortDirection();
      if (this.columns[n].filterType != Rico.TableColumn.UNFILTERED)
        queryString += '&f'+n+'='+escape(this.columns[n].getFilter());
    }
    this.ajaxOptions.parameters = queryString;
    this.requestCount++;
    this.writeDebugMsg('req '+this.requestCount+':'+queryString);
    ajaxEngine.sendRequest( this.tableId + '_request', this.ajaxOptions );
  },

  setRequestParams: function() {
    this.options.requestParameters = [];
    for ( var i=0 ; i < arguments.length ; i++ )
      this.options.requestParameters[i] = arguments[i];
  },

  requestContentRefresh: function(contentOffset) {
     this.fetchBuffer(contentOffset);
  },

  ajaxUpdate: function(ajaxResponse) {
    if (this.timeoutHandler) clearTimeout( this.timeoutHandler );
    if (this.printWindow)
      this.appendPrintWindow(ajaxResponse);
    else
      this.updateBuffer(ajaxResponse);
  },

  updateBuffer: function(ajaxResponse) {
    try {
      this.writeDebugMsg("ajaxUpdate");
      var offset=this.processingRequest.bufferOffset;
      this.buffer.update(ajaxResponse,offset);
      var totrows=this.metaData.getTotalRows();
      this.writeDebugMsg("ajaxUpdate, size="+this.buffer.size+' rcv cnt type='+typeof(this.buffer.rowcntContent));
      if (this.options.onAjaxUpdate)
        this.options.onAjaxUpdate();
      if (this.buffer.rcvdRowCount==true) {
        this.writeDebugMsg("found row cnt: "+this.buffer.rowcntContent);
        var eofrow=parseInt(this.buffer.rowcntContent);
        if (!isNaN(eofrow) && eofrow!=totrows) {
          this.writeDebugMsg("shortening totrows to "+eofrow);
          this.setTotalRows(eofrow);
          var pgsize=this.metaData.getPageSize();
          var newpos=Math.min(Math.max(this.metaData.getTotalRows()-pgsize,0),offset);
          this.writeDebugMsg("requery, newpos="+newpos);
          //this.lastRowPos=-1;
          this.scrollToRow(newpos);
          this.unprocessedRequest = null;  // cancel any queued requests
          this.processingRequest = null;
          if ( eofrow==0 || (this.buffer.isInRange(newpos) && this.buffer.size>=pgsize) ) {
            this.refreshContents(newpos);
          } else {
            this.requestContentRefresh(newpos);
          }
          return;
        }
      } else {
        var lastbufrow=offset+this.buffer.rcvdRows;
        if (lastbufrow>totrows) {
          var newcnt=lastbufrow+this.options.bufferExtend;
          this.writeDebugMsg("extending totrows to "+newcnt);
          this.setTotalRows(newcnt);
        }
      }
      var newpos=this.pixeltorow(this.scrollDiv.scrollTop);
      this.writeDebugMsg("ajaxUpdate, newpos="+newpos);
      this.refreshContents(newpos);
    }
    catch(err) {
      this.writeDebugMsg("Error in ajaxUpdate:"+err.message);
    }
    this.processingRequest = null;
    this.processQueuedRequest();
  },

  _createQueryString: function( theArgs, offset ) {
    var queryString = ""
    if (!theArgs) return queryString;

    for ( var i = offset ; i < theArgs.length ; i++ ) {
      if ( i != offset )
        queryString += "&";

      var anArg = theArgs[i];

      if ( anArg.name != undefined && anArg.value != undefined ) {
        queryString += anArg.name +  "=" + escape(anArg.value);
      } else {
        var ePos  = anArg.indexOf('=');
        var argName  = anArg.substring( 0, ePos );
        var argValue = anArg.substring( ePos + 1 );
        queryString += argName + "=" + escape(argValue);
      }
    }
    return queryString;
  },

  processQueuedRequest: function() {
    if (this.unprocessedRequest != null) {
      this.requestContentRefresh(this.unprocessedRequest.requestOffset);
      this.unprocessedRequest = null
    }
  },

  setSortUI: function( columnNameOrNum, sortDirection ) {
    var colnum;
    this.writeDebugMsg("setSortUI: "+columnNameOrNum+' '+sortDirection);
    if (typeof sortDirection!='string') return;
    if (typeof columnNameOrNum=='string') {
      for ( var i = 0 ; i < this.columns.length ; i++ ) {
        if ( this.columns[i].fieldName == columnNameOrNum ) {
          colnum=i;
          break;
        }
      }
    } else {
      colnum=columnNameOrNum;
    }
    if (typeof colnum!='number') return;
    this.clearSort();
    this.columns[colnum].setSorted(sortDirection);
    this.setImages();
  },

  // clear sort flag on all columns
  clearSort: function() {
    for (var x=0;x<this.columns.length;x++)
    this.columns[x].setUnsorted();
  },

  sortHandler: function() {
    this.setImages();
    if (this.dataSource==null) {
      for (var n=0; n<this.columns.length; n++)
        if (this.columns[n].isSorted()) {
          this.writeDebugMsg("sortHandler: sorting column "+n);
          this.buffer.sortBuffer(n,this.columns[n].getSortDirection(),this.columns[n].format.type);
          this.clearRows();
          this.scrollDiv.scrollTop = 0;
          this.refreshContents(0);
          return;
        }
      this.refreshContents(0);
    } else {
      this.resetContents(false);
      this.requestContentRefresh(0);
    }
  },

  filterHandler: function() {
    this.setImages();
    this.resetContents(true);
    this.buffer.foundRowCount = false;
    this.metaData.setTotalRows(this.options.bufferExtend);  // reset estimate of number of records
    this.requestContentRefresh(0);
  },

  bookmarkHandler: function(firstrow,lastrow) {
    if (isNaN(firstrow) || !this.bookmark) return;
    var totrows=this.metaData.getTotalRows();
    if (totrows < lastrow) lastrow=totrows;
    if (totrows<=0) {
      var newhtml = RicoTranslate.getPhrase("No matching records");
    } else if (lastrow<0) {
      var newhtml = RicoTranslate.getPhrase("No records");
    } else {
      var newhtml = RicoTranslate.getPhrase("Listing records")+" "+firstrow+" - "+lastrow;
      var totphrase = this.buffer.foundRowCount ? "of" : "of about";
      newhtml+=" "+RicoTranslate.getPhrase(totphrase)+" "+totrows;
    }
    this.bookmark.innerHTML = newhtml;
  },

  // Return an array of column objects which have invisible status
  listInvisible: function() {
    var hiddenColumns=new Array();
    for (var x=0;x<this.columns.length;x++)
      if (this.columns[x].visible==false)
        hiddenColumns.push(this.columns[x]);
    return hiddenColumns;
  },

  // Show all columns
  showAll: function() {
    var invisible=this.listInvisible();
    for (var x=0;x<invisible.length;x++)
      invisible[x].showColumn();
  },

  clearRows: function() {
     if (this.isBlank==true) return;
     for (var c=0; c < this.columns.length; c++)
        this.columns[c].clearColumn();
     this.isBlank = true;
  },

  blankRow: function(r) {
     for (var c=0; c < this.columns.length; c++)
        this.columns[c].clearCell(r);
  },

  refreshContents: function(startPos) {
     var visibleRows=this.metaData.getPageSize();
     this.writeDebugMsg("refreshContents, startPos="+startPos+" lastRowPos="+this.lastRowPos+" isPartialBlank="+this.isPartialBlank+" visibleRows="+visibleRows+" bufsize="+this.buffer.size);
     this.hideMsg();
     if (gridMenu.isVisible()) gridMenu.cancelmenu();
     this.highlightEnabled=this.options.highltOnMouseOver;
     if (startPos == this.lastRowPos && !this.isPartialBlank && !this.isBlank) {
        return;
     }
     this.isBlank = false;
     var viewPrecedesBuffer = this.buffer.startPos > startPos
     var contentStartPos = viewPrecedesBuffer ? this.buffer.startPos: startPos;
     var contentEndPos = (this.buffer.startPos + this.buffer.size < startPos + visibleRows)
                                ? this.buffer.startPos + this.buffer.size
                                : startPos + visibleRows;
     var onRefreshComplete = this.options.onRefreshComplete;

     if ((startPos + visibleRows < this.buffer.startPos)
         || (this.buffer.startPos + this.buffer.size < startPos)
         || (this.buffer.size == 0)) {
        this.clearRows();
        if (onRefreshComplete != null)
            onRefreshComplete(contentStartPos+1,contentEndPos);
        return;
     }

     var rowSize = contentEndPos - contentStartPos;
     var rows = this.buffer.getRows(contentStartPos, rowSize );
     var blankSize = visibleRows - rowSize;
     var blankOffset = viewPrecedesBuffer ? 0: rowSize;
     var contentOffset = viewPrecedesBuffer ? blankSize: 0;

     for (var r=0; r < rows.length; r++) { //initialize what we have
       for (var c=0; c < this.columns.length; c++)
          this.columns[c].cells[r + contentOffset].setValue(rows[r][c]);
     }
     for (var i=0; i < blankSize; i++)     // blank out the rest
       this.blankRow(i + blankOffset);
     this.isPartialBlank = blankSize > 0;
     this.lastRowPos = startPos;
     this.writeDebugMsg("refreshContents complete, startPos="+startPos);
     // Check if user has set a onRefreshComplete function
     if (onRefreshComplete != null)
       onRefreshComplete(contentStartPos+1,contentEndPos);
  },

  attachScrollBarEvents: function() {
     this.scrollEventFunc=this.handleScroll.bindAsEventListener(this);
     this.wheelEventFunc=this.handleWheel.bindAsEventListener(this);
     this.wheelEvent=(RicoUtil.isIE || RicoUtil.isOpera || RicoUtil.isSafari) ? 'mousewheel' : 'DOMMouseScroll';
     this.pluginScroll();
  },

  scrollToRow: function(rowOffset) {
     var tmpPlug=this.scrollPluggedIn;
     if (tmpPlug) this.unplugScroll();
     var p=this.rowToPixel(rowOffset);
     this.writeDebugMsg("scrollToRow, rowOffset="+rowOffset+" pixel="+p);
     this.scrollDiv.scrollTop = p;
     if ( this.options.onscroll )
        this.options.onscroll( this, rowOffset );
     if (tmpPlug) this.pluginScroll();
  },

  pluginScroll: function() {
     if (this.scrollPluggedIn) return;
     this.writeDebugMsg("pluginScroll: wheelEvent="+this.wheelEvent);
     Event.observe(this.scrollDiv,"scroll",this.scrollEventFunc, false);
     for (var t=0; t<2; t++)
       Event.observe(this.tabs[t],this.wheelEvent,this.wheelEventFunc, false);
     this.scrollPluggedIn=true;
  },

  unplugScroll: function() {
     this.writeDebugMsg("unplugScroll");
     Event.stopObserving(this.scrollDiv,"scroll", this.scrollEventFunc , false);
     for (var t=0; t<2; t++)
       Event.stopObserving(this.tabs[t],this.wheelEvent,this.wheelEventFunc, false);
     this.scrollPluggedIn=false;
  },

  scrollUp: function() {
     this.moveRelative(-1);
  },

  scrollDown: function() {
     this.moveRelative(1);
  },

  pageUp: function() {
     this.moveRelative(-this.metaData.getPageSize());
  },

  pageDown: function() {
     this.moveRelative(this.metaData.getPageSize());
  },

  rowToPixel: function(rowOffset) {
     var notdisp=this.metaData.getRowsNotDisplayed();
     if (notdisp == 0) return 0;
     var rowOffset=Math.min(notdisp,rowOffset);
     return rowOffset * this.rowHeight;
     //return parseInt(rowOffset / notdisp * this.shadowDiv.offsetHeight);
  },

  // returns row to display at top of scroll div
  pixeltorow: function(p) {
     var notdisp=this.metaData.getRowsNotDisplayed();
     if (notdisp == 0) return 0;
     var prow=parseInt(p/this.rowHeight);
     return Math.min(notdisp,prow);
  },

  moveRelative: function(relOffset) {
     newoffset=Math.max(this.scrollDiv.scrollTop+relOffset*this.rowHeight,0);
     newoffset=Math.min(newoffset,this.scrollDiv.scrollHeight);
     this.writeDebugMsg("moveRelative, newoffset="+newoffset);
     this.scrollDiv.scrollTop=newoffset;
  },
  
  handleWheel: function(e) {
    var delta = 0;
    if (e.wheelDelta) {
      if (RicoUtil.isOpera)
        delta = e.wheelDelta/120;
      else if (RicoUtil.isSafari)
        delta = -e.wheelDelta/12;
      else
        delta = -e.wheelDelta/120;
    } else if (e.detail) {
      delta = e.detail/3; /* Mozilla/Gecko */
    }
    this.writeDebugMsg("handleWheel, delta="+delta+" wheelDelta="+e.wheelDelta);
    if (delta) this.moveRelative(delta);
    Event.stop(e);
    return false;
  },

  handleScroll: function(e) {
     if ( this.scrollTimeout )
       clearTimeout( this.scrollTimeout );
     this.setHorizontalScroll();
     var scrtop=this.scrollDiv.scrollTop;
     var vscrollDiff = this.lastScrollPos-scrtop;
     if (vscrollDiff != 0.00) {
       var newrow=this.pixeltorow(scrtop);
       this.writeDebugMsg("handleScroll, newrow="+newrow+" scrtop="+scrtop);
       this.requestContentRefresh(newrow);
       this.refreshContents(newrow)

       if ( this.options.onscroll )
          this.options.onscroll( this, newrow );

       this.scrollTimeout = setTimeout(this.scrollIdle.bind(this), 1200 );
       this.lastScrollPos = this.scrollDiv.scrollTop;
     }
  },

  scrollIdle: function() {
     if ( this.options.onscrollidle )
        this.options.onscrollidle();
  },
  
  printVisible: function() {
    this.printVisibleFlag=true;
    this.openPrintWindow();
  },
  
  printAll: function() {
    this.printVisibleFlag=false;
    this.openPrintWindow();
  },
  
  openPrintWindow: function() {
    this.printWindow=window.open("","","height=300,width=500,scrollbars=1,menubar=1,resizable=1");
    setTimeout(this.loadPrintWindow.bind(this),50);
  },
  
  // load data to the print window
  loadPrintWindow: function() {
    var doc=this.printWindow.document;
    doc.write('<html><head>');
    doc.write('\n<link href="'+Rico.cssDir+'ricoLiveGrid.css" type="text/css" rel="stylesheet" />');
    doc.write('\n<title>'+document.title+'</title>');
    doc.write('</head><body>');
    doc.write("\n<table class='ricoLG_table0' cellSpacing='0' cellPadding='2' border="+this.options.borderWidth+">\n<thead style='display: table-header-group;'><tr>");
    for (var c=0; c<this.columns.length; c++)
      if (this.columns[c].visible)
        doc.write("<td>"+this.columns[c].displayName+"</td>");
    doc.write("</tr></thead><tbody>\n");
    
    if (this.printVisibleFlag==true)
      this.loadPrintWindowVisible();
    else
      this.loadPrintWindowAll();
  },
  
  loadPrintWindowEnd: function() {
    var doc=this.printWindow.document;
    doc.write("</tbody></table>\n");
    doc.write('</body></html>');
    doc.close();
    this.printWindow=undefined;
    gridMenu.cancelmenu();
  },
  
  // load visible rows into print window
  loadPrintWindowVisible: function() {
    var doc=this.printWindow.document;
    var limit=Math.min(this.metaData.getTotalRows(),this.metaData.getPageSize());
    for(var r=0; r < limit; r++) {
      doc.write("<tr>");
      for (var c=0; c<this.columns.length; c++) {
        if (this.columns[c].visible)
          doc.write("<td>"+this.columns[c].cells[r].content.innerHTML+"</td>");
      }
      doc.write("</tr>\n");
    }
    this.loadPrintWindowEnd();
  },
  
  // got data back from ajax request, so send it to print window
  appendPrintWindow: function(ajaxResponse) {
    var doc=this.printWindow.document;
    var trs = ajaxResponse.getElementsByTagName("tr");
    this.writeDebugMsg("appendPrintWindow rows="+trs.length);
    for(var r=0; r < trs.length; r++) {
      doc.write("<tr>");
      var cells = trs[r].getElementsByTagName("td");
      for (var c=0; c<this.columns.length; c++) {
        if (this.columns[c].visible)
          doc.write("<td>"+this.columns[c].formatValue(this.buffer.getContent(cells[c]))+"</td>");
      }
      doc.write("</tr>\n");
    }
    if (trs.length==0) {
      this.hideMsg();
      this.loadPrintWindowEnd();
    } else {
      this.printOffset+=trs.length;
      this.sendPrintRequest();
    }
  },
  
  // make ajax request for print window data
  sendPrintRequest: function() {
    this.showMsg("Waiting for data...");
    this.sendAjaxRequest(this.printOffset,200);
    this.timeoutHandler = setTimeout( this.printTimedOut.bind(this), this.options.bufferTimeout);
  },
  
  // send all rows to print window
  // get data from buffer (if pre-populated table) or via ajax
  loadPrintWindowAll: function() {
    if (typeof this.dataSource=='string') {
      this.printOffset=0;
      this.sendPrintRequest();
      return;
    }
    var doc=this.printWindow.document;
    for(var r=0; r < this.buffer.size; r++) {
      doc.write("<tr>");
      for (var c=0; c<this.columns.length; c++) {
        if (this.columns[c].visible)
          doc.write("<td>"+this.columns[c].formatValue(this.buffer.rows[r][c])+"</td>");
      }
      doc.write("</tr>\n");
    }
    this.loadPrintWindowEnd();
  },

  printTimedOut: function() {
    this.writeDebugMsg("Print Request Timed Out");
    this.showMsg("Request for data timed out!");
    this.loadPrintWindowEnd();
  },

  writeDebugMsg: function(msg) {
    if (this.debugArea) this.debugArea.value+=RicoUtil.timeStamp()+msg+"\n";
  }

};


Rico.TableCell = Class.create();

Rico.TableCell.prototype = {
  initialize: function(fCell,sCell,objColumn) {
    this.column=objColumn;
    this.cells=[fCell,sCell];
    this.initialized=[false,false];
    fCell.className=this.column.cellClass;
    sCell.className=this.column.cellClass;
    this.setActiveCell();
    var divs=this.cell.getElementsByTagName('DIV');
    this.content=this.container=divs[0];
    this.setWidth();
    this.value=null;
  },
  
  setActiveCell: function() {
    this.cell=this.cells[this.column.tabIdx];
    this.xCell=this.cells[1-this.column.tabIdx];
    if (this.column.tabIdx==1)
      this.cells[0].style.display='none';
  },

  xhide: function() {
    this.xCell.style.display='none';
  },

  hide: function() {
    this.cell.style.display='none';
    this.container.style.display='none';
  },

  show: function() {
    this.cell.style.display='';
    this.container.style.display='';
  },

  setValue: function(s) {
    this.value=typeof s=='string' ? s : '';
    this.content.innerHTML=this.formatValue();
  },

  setWidth: function() {
    this.container.style.width=this.column.colWidth;
  },

  clear: function() {
    this.value=null;
    this.content.innerHTML='&nbsp;';
  },

  getValue: function() {
    return this.value;
  },

  formatValue: function() {
    return this.column.formatValue(this.value);
  }

};




Rico.TableColumn = Class.create();

Rico.TableColumn.UNFILTERED   = 0;
Rico.TableColumn.SYSTEMFILTER = 1;  /* system-generated filter, not shown to user */
Rico.TableColumn.USERFILTER   = 2;

Rico.TableColumn.UNSORTED   = 0;
Rico.TableColumn.SORT_ASC   = "ASC";
Rico.TableColumn.SORT_DESC  = "DESC";
Rico.TableColumn.MINWIDTH   = 10; // min column width when user is resizing

Rico.TableColumn.prototype = {
  initialize: function(liveGrid,colIdx,wi) {
    liveGrid.writeDebugMsg("TableColumn.init index="+colIdx+" wi="+wi);
    this.hdr       = new Array(2);
    this.hdrdiv    = new Array(2);
    this.imgSort   = new Array(2);
    this.imgFilter = new Array(2);
    this.cells     = new Array();
    this.liveGrid  = liveGrid;
    this.index     = colIdx;
    this.hideWidth = RicoUtil.isKonqueror || RicoUtil.isSafari || liveGrid.headerRowCnt>1 ? 5 : 2;  // column width used for "hidden" columns. Anything less than 5 causes problems with Konqueror. Best to keep this greater than padding used inside cell.
    this.colWidth  = typeof(wi)=='number' ? wi+'px' : wi;
    this.options   = liveGrid.options;
    this.tabIdx    = colIdx<this.options.frozenColumns ? 0 : 1; // this column is currently displayed in tabs[tabIdx]
    for (var t=0; t<2; t++)
      this.hdr[t] = liveGrid.hdrCells[t][this.options.headingRow][colIdx];
    this.displayName  = this.getDisplayName(this.hdr[this.tabIdx]); // The column display name
    if (this.tabIdx==1) this.hdr[0].style.display='none';
    this.cellClass = liveGrid.tableId+'_col'+colIdx;

    this.mouseDownHandler= this.handleMouseDown.bindAsEventListener(this);
    this.mouseMoveHandler= this.handleMouseMove.bindAsEventListener(this);
    this.mouseUpHandler  = this.handleMouseUp.bindAsEventListener(this);
    this.mouseOutHandler = this.handleMouseOut.bindAsEventListener(this);

    //this.fieldName    = 'fld_'+this.displayName.replace(/#/g,'Number').replace(/\W/g,'');
    this.fieldName    = 'col'+this.index;
    var specName      = liveGrid.options.columnSpecs ? liveGrid.options.columnSpecs[colIdx] : 0;
    switch (typeof specName) {
      case 'object':
        this.format=specName;
        specName='inline';
        break;
      case 'string':
        this.format=typeof liveGrid.options[specName]=='object' ? liveGrid.options[specName] : liveGrid.options['specDefault'];
        break;
      default:
        specName='specDefault';
        this.format=liveGrid.options['specDefault'];
        break;
    }
    if (typeof this.format.type!='string') this.format.type='raw';
    if (typeof this.format.quotes != 'string')
      this.format.quotes=this.format.type=='number' ? "" : "'";
    if (this.format.quotes.length < 2) {
      this.openLiteral=this.format.quotes;
      this.closeLiteral=this.format.quotes;
    } else {
      // only if more complicated quoting is required
      var arEncl=this.format.quotes.split(/text/);
      this.openLiteral=arEncl[0];
      this.closeLiteral=arEncl[1];
    }
    if (this.format.type=='lookup') {
      this.lookupCtl=$(this.format.lookupCtl);
      this.lookupValues=new Array();
      switch (this.lookupCtl.tagName.toLowerCase()) {
        case 'select':
          for (var i=0; i<this.lookupCtl.length; i++) {
            var o=this.lookupCtl.options[i];
            this.lookupValues[o.value]=o.text;
          }
          break;
        case 'div':
          var elems=this.lookupCtl.getElementsByTagName("INPUT");
          for (var i=0; i<elems.length; i++) {
            // find label node, which contains the description
            var lbl=elems[i];
            var desc='';
            for (var j=0; j<5 && lbl!=null; j++, lbl=lbl.nextSibling) {
              if (lbl.tagName && lbl.tagName.toLowerCase()=='label') {
                desc=lbl.innerHTML;
                break;
              }
            }
            //alert(this.lookupCtl.id+" "+i+": v="+elems[i].value+" d="+desc);
            this.lookupValues[elems[i].value]=desc;
          }
          break;
      }
    }
    liveGrid.writeDebugMsg("TableColumn.init index="+colIdx+" fieldName="+this.fieldName+" specName="+specName+' type='+this.format.type+' tabIdx='+this.tabIdx);
    this.sortable     = typeof this.format.canSort=='boolean' ? this.format.canSort : liveGrid.options.canSortDefault;
    this.currentSort  = Rico.TableColumn.UNSORTED;
    this.filterable   = typeof this.format.canFilter=='boolean' ? this.format.canFilter : liveGrid.options.canFilterDefault;
    this.filterType   = Rico.TableColumn.UNFILTERED;
    this.filterSuffix = '';
    this.hideable     = typeof this.format.canHide=='boolean' ? this.format.canHide : liveGrid.options.canHideDefault;
    this.isNullable   = /number|date/.test(this.format.type);
    this.isText       = /raw|text/.test(this.format.type);
    liveGrid.writeDebugMsg(" sortable="+this.sortable+" filterable="+this.filterable+" hideable="+this.hideable+" isNullable="+this.isNullable+' isText='+this.isText);
    this.fixHeaders( liveGrid.tableId, this.options.hdrIconsFirst );
  },

  // get the display name of a column
  getDisplayName: function(el) {
    var anchors=el.getElementsByTagName("A");
    //Check the existance of A tags
    if (anchors.length > 0)
      return anchors[0].innerHTML;
    else
      return el.innerHTML.stripTags();
  },

  dataTable: function() {
    return this.liveGrid.tabs[this.tabIdx];
  },

  fixHeaders: function( prefix, iconsfirst ) {
    for (t=0; t<2; t++) {
      this.hdrdiv[t]=this.hdr[t].getElementsByTagName('DIV')[0];
      if (this.sortable==true && this.options.headingSort==true) {
        var a=RicoUtil.wrapChildren(this.hdrdiv[t],'ricoSort',undefined,'a')
        a.href = "#";
        a.onclick = this.toggleSort.bindAsEventListener(this);
      }
      this.imgFilter[t] = document.createElement('img');
      this.imgFilter[t].style.display='none';
      this.imgFilter[t].src=Rico.imgDir+this.options.filterImg;
      this.imgSort[t] = document.createElement('img');
      this.imgSort[t].style.display='none';
      if (iconsfirst) {
        this.hdrdiv[t].insertBefore(this.imgSort[t],this.hdrdiv[t].firstChild);
        this.hdrdiv[t].insertBefore(this.imgFilter[t],this.hdrdiv[t].firstChild);
        this.imgSort[t].style.paddingRight='3px';
      } else {
        this.imgFilter[t].style.paddingLeft='3px';
        this.hdrdiv[t].appendChild(this.imgFilter[t]);
        this.hdrdiv[t].appendChild(this.imgSort[t]);
      }
      if (this.options.allowColResize) {
        var resizer=this.hdrdiv[t].appendChild(document.createElement('div'));
        resizer.className='ricoLG_Resize';
        if (this.options.resizeBackground)
           resizer.style.backgroundImage='url('+Rico.imgDir+this.options.resizeBackground+')';
        Event.observe(resizer,"mousedown", this.mouseDownHandler, false);
      }
      this.hdrdiv[t].style.width=this.colWidth;
    }
  },

  addCell: function(cell0,cell1) {
    var newlen=this.cells.push(new Rico.TableCell(cell0,cell1,this));
    var idx=newlen-1;
    this.cells[idx].rowIndex=idx;
    cell0.id=this.liveGrid.tableId+'_0_'+idx+'_'+this.index;
    cell1.id=this.liveGrid.tableId+'_1_'+idx+'_'+this.index;
  },

  clearCell: function(r) {
    this.cells[r].clear();
  },

  clearColumn: function() {
    for (var r=0; r<this.cells.length; r++)
      this.cells[r].clear();
  },

  cell: function(r) {
    return this.cells[r];
  },

  formatValue: function(v) {
    if (v=='') return '&nbsp;';
    switch (this.format.type) {
      case 'raw':
        return v;
      case 'text':
        switch (this.format.markup) {
          case 'stripTags':
            /* remove all markup */
            return v.stripTags();
          case 'showTags':
            /* make markup visible */
            return v.replace(/&/g, '&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
          default:
            alert("invalid value for 'markup' in column "+this.index);
            return v;
        }
      case 'number':
        return v.formatNumber(this.format);
      case 'date':
        return v.formatDate(this.format);
      case 'lookup':
        var desc=this.lookupValues[v];
        return (typeof desc=='undefined') ? v : desc;
      default:
        alert("invalid value for 'type' in column "+this.index);
        return v;
    }
  },

  setColWidth: function(wi) {
    if (parseInt(wi) < Rico.TableColumn.MINWIDTH) return;
    if (typeof wi=='number') wi=wi+'px';
    this.colWidth=wi;
    this.setHdrWidth(wi);
    for (r=0; r<this.cells.length; r++)
      this.cells[r].setWidth();
    this.setWidthCookie(wi);
  },

  setHdrWidth: function(wi) {
    if (parseInt(wi) < Rico.TableColumn.MINWIDTH) return;
    if (typeof wi=='number') wi=wi+'px';
    this.hdrdiv[this.tabIdx].style.width=wi;
  },

  pluginMouseEvents: function() {
    if (this.mousePluggedIn==true) return;
    Event.observe(document.body,"mousemove", this.mouseMoveHandler, false);
    Event.observe(document.body,"mouseup",   this.mouseUpHandler  , false);
    Event.observe(document.body,"mouseout",  this.mouseOutHandler , false);
    this.mousePluggedIn=true;
  },

  unplugMouseEvents: function() {
    Event.stopObserving(document.body,"mousemove", this.mouseMoveHandler, false);
    Event.stopObserving(document.body,"mouseup",   this.mouseUpHandler  , false);
    Event.stopObserving(document.body,"mouseout",  this.mouseOutHandler , false);
    this.mousePluggedIn=false;
  },

  handleMouseDown: function(e) {
    this.resizeStart=e.clientX;
    this.origWidth=parseInt(this.colWidth);
    var p=Position.positionedOffset(this.hdr[this.tabIdx]);
    this.rtEdge=p[0]+this.hdr[this.tabIdx].offsetWidth-this.liveGrid.scrollDiv.scrollLeft;
    if (this.tabIdx>0) this.rtEdge+=RicoUtil.nan2zero(this.liveGrid.tabs[0].offsetWidth);
    //alert('rtEdge='+this.rtEdge+' p[0]='+p[0]+' offsetW='+this.liveGrid.tabs[0].offsetWidth);
    this.liveGrid.resizeDiv.style.left=this.rtEdge+"px";
    this.liveGrid.resizeDiv.style.display="";
    this.liveGrid.outerDiv.style.cursor='e-resize';
    this.tmpHighlight=this.liveGrid.highlightEnabled;
    this.liveGrid.highlightEnabled=false;
    this.pluginMouseEvents();
    Event.stop(e);
    return false;
  },

  handleMouseMove: function(e) {
    var delta=e.clientX-this.resizeStart;
    var newWidth=this.origWidth+delta;
    if (newWidth < Rico.TableColumn.MINWIDTH) return;
    this.liveGrid.resizeDiv.style.left=(this.rtEdge+delta)+"px";
    this.colWidth=newWidth;
    Event.stop(e);
    return false;
  },

  handleMouseUp: function(e) {
    this.unplugMouseEvents();
    this.liveGrid.outerDiv.style.cursor='';
    this.liveGrid.resizeDiv.style.display="none";
    this.setColWidth(this.colWidth);
    this.liveGrid.highlightEnabled=this.tmpHighlight;
    this.liveGrid.sizeDivs();
    Event.stop(e);
    return false;
  },

  handleMouseOut: function(e) {
    var reltg = (e.relatedTarget) ? e.relatedTarget : e.toElement;
    while (reltg != null && reltg.nodeName != 'BODY')
      reltg=reltg.parentNode;
    if (reltg!=null && reltg.nodeName == 'BODY') return true;
    this.handleMouseUp(e);
    return true;
  },

  xhide: function() {
    this.hdr[1-this.tabIdx].style.display='none';
    for (r=0; r<this.cells.length; r++)
      this.cells[r].xhide();
  },
  
  // recalcTableWidth defaults to true
  hideColumn: function(recalcTableWidth) {
    this.hdr[this.tabIdx].style.display='none';
    this.hdrdiv[this.tabIdx].style.display='none';
    for (r=0; r<this.cells.length; r++)
      this.cells[r].hide();
    gridMenu.cancelmenu();
    this.visible=false;
    this.setWidthCookie("hidden");
    if (arguments.length==0 || recalcTableWidth)
      this.liveGrid.sizeDivs();
  },

  showColumn: function() {
    this.hdr[this.tabIdx].style.display='';
    this.hdrdiv[this.tabIdx].style.display='';
    for (r=0; r<this.cells.length; r++)
      this.cells[r].show();
    gridMenu.cancelmenu();
    this.visible=true;
    this.setWidthCookie(this.colWidth);
    this.liveGrid.sizeDivs();
  },

  // Create context menu for data table. Contains dynamic context menus.
  createDataMenu: function(r) {
    gridMenu.clearMenu();
    var onBlankRow=r >= this.liveGrid.metaData.getTotalRows();
    //window.status="createDataMenu row="+r;
    if (this.options.dataMenuHandler) {
       var showDefaultMenu=this.options.dataMenuHandler(this.cells[r],onBlankRow);
       if (showDefaultMenu==false) return;
    }
    if (this.sortable) {
      gridMenu.addMenuHeading(RicoTranslate.getPhrase("Sort by")+": "+this.displayName,false);
      gridMenu.addMenuItem("Ascending", this.sortAsc.bind(this), true);
      gridMenu.addMenuItem("Descending", this.sortDesc.bind(this), true);
    }

    // menu items for filtering
    if (this.canFilter()) {
      gridMenu.addMenuHeading(RicoTranslate.getPhrase("Filter by")+": "+this.displayName);
      this.userFilter=this.cells[r].getValue();
      if (this.filterType == Rico.TableColumn.USERFILTER) {
        if (this.getFilter().substring(0,4)=='LIKE' && !onBlankRow)
          gridMenu.addMenuItem("Change keyword...", this.setFilterKW.bind(this), true);
        if (this.getFilter().substring(0,6)=='NOT IN' && !onBlankRow)
          gridMenu.addMenuItem("Exclude this value\t also", this.addFilterNE.bind(this), true);
        gridMenu.addMenuItem("Remove filter", this.setUnfiltered.bind(this), true);
      } else if (!onBlankRow) {
        gridMenu.addMenuItem("Include only this value", this.setFilterEQ.bind(this), true);
        gridMenu.addMenuItem("Greater than or equal to this value", this.setFilterGE.bind(this), this.userFilter!='');
        gridMenu.addMenuItem("Less than or equal to this value", this.setFilterLE.bind(this), this.userFilter!='');
        if (this.isText)
          gridMenu.addMenuItem("Contains keyword...", this.setFilterKW.bind(this), true);
        gridMenu.addMenuItem("Exclude this value", this.setFilterNE.bind(this), true);
      }
    }

    // menu items for Print/Export
    gridMenu.addMenuHeading("Print\t/Export");
    gridMenu.addMenuItem("Visible rows", this.liveGrid.printVisible.bind(this.liveGrid), true);
    gridMenu.addMenuItem("All rows", this.liveGrid.printAll.bind(this.liveGrid), true);

    // menu items for hide/unhide
    var hiddenCols=this.liveGrid.listInvisible();
    if (hiddenCols.length > 0 || this.canHideShow()) {
      gridMenu.addMenuHeading('Hide\t/Show');
      var visibleCnt=this.liveGrid.columns.length-hiddenCols.length;
      var enabled=(visibleCnt>1 && this.visible && this.canHideShow());
      gridMenu.addMenuItem(RicoTranslate.getPhrase('Hide')+': '+this.displayName, this.hideColumn.bind(this), enabled);
      for (var cnt=0,x=0;x<hiddenCols.length;x++) {
        if (hiddenCols[x].canHideShow()) {
          if (cnt++==0) gridMenu.addMenuBreak();
          gridMenu.addMenuItem(RicoTranslate.getPhrase('Show')+': '+hiddenCols[x].displayName, hiddenCols[x].showColumn.bind(hiddenCols[x]));
        }
      }
      if (hiddenCols.length > 1)
        gridMenu.addMenuItem(RicoTranslate.getPhrase('Show All'), this.liveGrid.showAll.bind(this.liveGrid));
    }
  },

  setImage: function() {
    if ( this.currentSort == Rico.TableColumn.SORT_ASC ) {
       this.imgSort[this.tabIdx].style.display='';
       this.imgSort[this.tabIdx].src=Rico.imgDir+this.options.sortAscendImg;
    } else if ( this.currentSort == Rico.TableColumn.SORT_DESC ) {
       this.imgSort[this.tabIdx].style.display='';
       this.imgSort[this.tabIdx].src=Rico.imgDir+this.options.sortDescendImg;
    } else {
       this.imgSort[this.tabIdx].style.display='none';
    }
    if (this.filterType == Rico.TableColumn.USERFILTER) {
       this.imgFilter[this.tabIdx].style.display='';
       this.imgFilter[this.tabIdx].title=this.getFilter();
    } else {
       this.imgFilter[this.tabIdx].style.display='none';
    }
  },

   canHideShow: function() {
      return this.hideable;
   },

   canFilter: function() {
      return this.filterable;
   },

   getFilter: function() {
      return this.filterValue+this.filterSuffix;
   },

   setUnfiltered: function() {
      this.filterType = Rico.TableColumn.UNFILTERED;
      this.filterSuffix = '';
      if (this.removeFilterFunc)
         this.removeFilterFunc();
      if (this.options.filterHandler)
         this.options.filterHandler();
   },

   setFilterEQ: function() {
    if (this.userFilter=='' && this.isNullable)
      this.setUserFilter(' IS NULL');
    else
      this.setUserFilter('=');
   },
   setFilterNE: function() {
    if (this.userFilter=='' && this.isNullable)
      this.setUserFilter(' IS NOT NULL');
    else {
      this.filterSuffix = ')';
      this.setUserFilter('NOT IN (');
    }
   },
   addFilterNE: function() {
      this.filterValue += ','+this.openLiteral+this.userFilter+this.closeLiteral;
      if (this.options.filterHandler)
         this.options.filterHandler();
   },
   setFilterGE: function() { this.setUserFilter('>='); },
   setFilterLE: function() { this.setUserFilter('<='); },
   setFilterKW: function() {
      var keyword=prompt(RicoTranslate.getPhrase("Enter keyword to search for")+RicoTranslate.getPhrase(" (use * as a wildcard):"),'');
      if (keyword!='' && keyword!=null) {
        var wildcard=this.options.likeWildcard;
        if (keyword.indexOf('*')==-1)
          keyword=wildcard+keyword+wildcard;
        else
          keyword=keyword.replace(/\*/g,wildcard);
        this.setFilter('LIKE',keyword,Rico.TableColumn.USERFILTER);
      } else {
        gridMenu.cancelmenu();
      }
   },

   setUserFilter: function(relop) {
      this.setFilter(relop,this.userFilter,Rico.TableColumn.USERFILTER);
   },

   setSystemFilter: function(relop,filter) {
      this.setFilter(relop,filter,Rico.TableColumn.SYSTEMFILTER);
   },

   setFilter: function(relop,filter,type,removeFilterFunc) {
      var quotedfilter=relop.indexOf(" NULL")!=-1 ? filter : this.openLiteral+filter+this.closeLiteral;
      this.filterValue = relop+quotedfilter;
      this.filterType = type;
      this.removeFilterFunc=removeFilterFunc;
      if (this.options.filterHandler)
         this.options.filterHandler();
   },

   sortAsc: function() {
      this.setColumnSort(Rico.TableColumn.SORT_ASC);
   },

   sortDesc: function() {
      this.setColumnSort(Rico.TableColumn.SORT_DESC);
   },

   setColumnSort: function(direction) {
      this.liveGrid.clearSort();
      this.setSorted(direction);
      if (this.options.sortHandler)
         this.options.sortHandler();
   },

   isSortable: function() {
      return this.sortable;
   },

   isSorted: function() {
      return this.currentSort != Rico.TableColumn.UNSORTED;
   },

   getSortDirection: function() {
      return this.currentSort;
   },

   toggleSort: function() {
      if ( this.currentSort == Rico.TableColumn.UNSORTED || this.currentSort == Rico.TableColumn.SORT_DESC )
         this.sortAsc();
      else if ( this.currentSort == Rico.TableColumn.SORT_ASC )
         this.sortDesc();
   },

   setUnsorted: function() {
      this.setSorted(Rico.TableColumn.UNSORTED);
   },

   setSorted: function(direction) {
      // direction must be one of Rico.TableColumn.UNSORTED, .SORT_ASC, or .SORT_DESC...
      this.currentSort = direction;
   },

  // Read width and visible information from cookies
  retrieveStatus: function() {
    var c=this.getCookie(this.options.cookiePrefix+"."+this.fieldName);
    if (c==null) {
      this.visible=(typeof this.format.visible=='boolean') ? this.format.visible : true;
    } else if (c=='hidden') {
      this.visible=false;
    } else {
      this.visible=true;
      this.setColWidth(c);
    }
    if (this.visible==false) this.hideColumn(false);
  },

  getCookieVal: function(offset) {
    var endstr = document.cookie.indexOf (';', offset);
    if (endstr == -1)
      endstr = document.cookie.length;
    return unescape(document.cookie.substring(offset, endstr));
  },

  // Gets the value of the specified cookie.
  getCookie: function(name) {
    var arg = name + '=';
    var alen = arg.length;
    var clen = document.cookie.length;
    var i = 0;
    while (i < clen) {
      var j = i + alen;
      if (document.cookie.substring(i, j) == arg)
        return this.getCookieVal (j);
      i = document.cookie.indexOf(' ', i) + 1;
      if (i == 0) break;
    }
    return null;
  },

  // Write width information to cookies
  setWidthCookie: function(saveValue) {
    if (this.options.saveColumnInfo==false) return;
    if (arguments.length==0) saveValue=this.colWidth;
    document.cookie= this.options.cookiePrefix+"."+this.fieldName+"="+saveValue;
  }

};

