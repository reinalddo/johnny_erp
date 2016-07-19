Rico.TableEdit = Class.create();

Rico.TableEdit.prototype = {

  initialize: function(liveGrid,options) {
    //alert('TableEdit initialize');
    this.grid=liveGrid;
    this.grid.lastEditedRow = -1;
    this.grid.saveMode=false;
    liveGrid.options.dataMenuHandler=this.editMenu.bind(this); 
    this.editDiv=$(liveGrid.tableId+'_edit');
    if (!this.editDiv) return;
    this.saveMsg=$(liveGrid.tableId+'_savemsg');
    this.timerMsg=$(liveGrid.tableId+'_timer');
    this.responseDiv=$(liveGrid.tableId+'_submitresponse');
    this.grid.responseDiv=this.responseDiv;
    this.responseDialog=$(liveGrid.tableId+'_responsedialog');
    this.form=this.editDiv.getElementsByTagName('form')[0];
    this.action=$(liveGrid.tableId+'_action');
    Event.observe(document,"click", this.clearSaveMsg.bindAsEventListener(this), false);
    Event.observe(this.form,"submit", this.TESubmit.bindAsEventListener(this), false);
    this.TEerror=false;
    this.extraMenuItems=new Array();
    this.sessionExpired=false;
    this.shim=new Rico.Shim();
    this.grid.saveRow= this.saveRow;
    this.options = {
      canEdit:false,
      canAdd:false,
      canDelete:false,
      canEditCols      : [],    /* array of editable cols*/
      ConfirmDelete:true,
      ConfirmDeleteCol:-1,
      NonBlanks: new Array(),
      RecordName:'record',
      minYear:1900,
      maxYear:2999,
      deleteUrl:this.grid.dataSource,
      updateUrl:this.grid.dataSource
    };
    Object.extend(this.options, options || {});
    if (this.options.TimeOut && this.timerMsg) {
      this.restartSessionTimer();
      liveGrid.options.onAjaxUpdate=this.restartSessionTimer.bind(this);
    }
    this.grid.options.deleteUrl=this.options.deleteUrl;
    this.grid.options.updateUrl=this.options.updateUrl;
    //alert('TableEdit initialize complete');
  },
  
  restartSessionTimer: function() {
    if (this.sessionExpired==true) return;
    this.timeRemaining=this.options.TimeOut+1;
    if (this.sessionTimer) clearTimeout(this.sessionTimer);
    this.updateSessionTimer();
  },
  
  updateSessionTimer: function() {
    if (--this.timeRemaining<=0) {
      this.displaySessionTimer("EXPIRED");
      this.timerMsg.style.backgroundColor="red";
      this.sessionExpired=true;
    } else {
      this.displaySessionTimer(this.timeRemaining);
      this.sessionTimer=setTimeout(this.updateSessionTimer.bind(this),60000);
    }
  },
  
  displaySessionTimer: function(msg) {
    this.timerMsg.innerHTML='&nbsp;'+msg+'&nbsp;';
  },
  
  clearSaveMsg: function() {
    if (this.saveMsg) this.saveMsg.innerHTML="";
  },
  
  addMenuItem: function(menuText,menuAction,enabled) {
    this.extraMenuItems.push({menuText:menuText,menuAction:menuAction,enabled:enabled});
  },

  editMenu: function(cell,onBlankRow) {
    this.clearSaveMsg();
    if (this.sessionExpired==true || this.grid.buffer.startPos<0) return;
    this.menuCell=cell;
    this.rowIdx=cell.rowIndex;
    this.TEkey=this.grid.columns[0].cell(this.rowIdx).getValue();
    //window.status=cell.cell.id;
    window.status=this.TEkey;
    var elemTitle=$('pageTitle');
    var pageTitle=elemTitle ? elemTitle.innerHTML : document.title;
    gridMenu.addMenuHeading(pageTitle);
    if (this.options.dataMenuHandler) {  // fah Añadir opciones de menu en edicion
       this.options.dataMenuHandler(cell,onBlankRow);
    }
    for (var i=0; i<this.extraMenuItems.length; i++) {
      gridMenu.addMenuItem(this.extraMenuItems[i].menuText,this.extraMenuItems[i].menuAction,this.extraMenuItems[i].enabled);
    }
    if (onBlankRow==false) {
      gridMenu.addMenuItem("Editar este "+this.options.RecordName,this.editRecord.bind(this),this.options.canEdit);
      gridMenu.addMenuItem("Eliminar este "+this.options.RecordName,this.deleteRecord.bind(this),this.options.CanDelete);
    }
    gridMenu.addMenuItem("Agregar nuevo "+this.options.RecordName,this.addRecord.bind(this),this.options.canAdd);
    return true;
  },

  cancelEdit: function(e) {
    Event.stop(e);
    if (typeof RicoCalendar=='object') RicoCalendar.hideCalendar();
    this.makeFormInvisible();
    this.grid.highlightEnabled=true;
    return false;
  },

  setField: function(fldid,fldvalue) {
    var e=$(fldid);
    if (!e) return;
    switch (e.tagName.toUpperCase()) {
      case 'DIV':
        var elems=e.getElementsByTagName('INPUT');
        for (var i=0; i<elems.length; i++)
          elems[i].checked=(elems[i].value==fldvalue);
        break;
      case 'INPUT':
        if (e.readOnly) return;
        switch (e.type.toUpperCase()) {
          case 'TEXT':
            e.value=fldvalue;
            return;
          case 'HIDDEN':
            if (e.name=='k') e.value=fldvalue;
            break;
        }
        break;
      case 'SELECT':
        //alert('setField SELECT: '+e.id+'\n'+fldvalue)
        var opts=e.options;
        for (var o=0; o<opts.length; o++)
          opts[o].selected=(opts[o].value==fldvalue);
        return;
      case 'TEXTAREA':
        e.value=fldvalue;
        //alert('setField TEXTAREA: '+e.id+'\n'+fldvalue)
        return;
    }
  },
  
  hideResponse: function(msg) {
    this.responseDiv.innerHTML=msg;
    this.responseDialog.style.display='none';
  },
  
  showResponse: function() {
    var offset=Position.page(this.grid.outerDiv);
    this.responseDialog.style.top=offset[1]+"px";
    this.responseDialog.style.display='';
  },
  
  processResponse: function() {
    var ch=this.responseDiv.childNodes;
    for (var i=ch.length-1; i>=0; i--) {
      if (ch[i].nodeType==1 && ch[i].nodeName!='P' && ch[i].nodeName!='DIV' && ch[i].nodeName!='BR')
        this.responseDiv.removeChild(ch[i]);
    }
    var responseText=this.responseDiv.innerHTML;
    if (responseText.toLowerCase().indexOf('error')==-1) {
      this.hideResponse('');
      this.grid.resetContents();
      this.grid.requestContentRefresh(this.grid.lastRowPos);
      if (this.saveMsg) this.saveMsg.innerHTML='&nbsp;'+responseText.stripTags()+'&nbsp;';
    }
  },
  
  editRecord: function() {
    gridMenu.hidemenu();
    if (this.menuCell)          //fah
        var row=RicoUtil.getParentByTagName(this.menuCell.cell,this.grid.options.rowTag);
    var inicio = this.grid.options.frozenColumns-1;
    var isFirst=true;           // first input field
    for (var i= inicio; i<this.grid.columns.length; i++) {
        isFirst= this.editCell(this.grid.columns[i].cell(this.rowIdx).cell, isFirst );
    }
    this.grid.outerDiv.style.cursor = 'auto';
    this.grid.lastEditedRow=this.rowIdx;
//    Element.addClassName(row, "editRow");
/**
    this.grid.highlightEnabled=false;
    gridMenu.hidemenu();
    this.hideResponse('Saving...');
    var row=RicoUtil.getParentByTagName(this.menuCell.cell,this.grid.options.rowTag);
    var odOffset=Position.page(this.grid.outerDiv);
    var rowOffset=Position.page(row);
    this.editDiv.style.left=Math.max(odOffset[0]+1,0)+'px';
    var newTop=rowOffset[1];
    var rowht=row.offsetHeight
    //var totht=this.grid.outerDiv.clientHeight-19;
    this.grid.outerDiv.style.cursor = 'auto';
    if (this.action) this.action.value="upd";
    for (var i=0; i<this.grid.columns.length; i++) {
      var fldid=this.grid.tableId+'_form_'+i;
      this.setField(fldid,this.grid.columns[i].cell(this.rowIdx).getValue());
    }
    this.editDiv.style.display='';
    var editht=this.editDiv.offsetHeight;
    window.status="newTop="+newTop+" rowht="+rowht+" editht="+editht;
    var scrTop=RicoUtil.docScrollTop();
    newTop+=scrTop;
    if (newTop+rowht+editht < RicoUtil.windowHeight()+scrTop)
      newTop+=rowht;
    else
      newTop=Math.max(newTop-editht,scrTop);
    //alert(newTop);
    this.editDiv.style.top=newTop+'px';
    this.makeFormVisible();
    **/
  },

  addRecord: function() {
    gridMenu.hidemenu();
    this.hideResponse('Saving...');
    this.form.reset();
    this.action.value="ins";
    var offset=Position.page(this.grid.outerDiv);
    this.editDiv.style.top=(offset[1]+this.grid.headerHeight+RicoUtil.docScrollTop())+"px";
    this.makeFormVisible();
  },
  
  makeFormVisible: function() {
    this.editDiv.style.display='block';
    var offset=Position.page(this.editDiv);
    var winWi=RicoUtil.windowWidth();
    var divWi=this.editDiv.offsetWidth;
    if (divWi+offset[0] > winWi)
      this.editDiv.style.left=(winWi-divWi)+'px';
    else
      this.editDiv.style.left=offset[0]+'px';
    this.shim.show(this.editDiv);
    if (this.options.formOpen) this.options.formOpen();
  },

  makeFormInvisible: function() {
    if (this.options.formClose) this.options.formClose();
    this.shim.hide();
    this.editDiv.style.display='none';
  },

  getConfirmDesc: function() {
    var idx=(this.options.ConfirmDeleteCol < 0) ? 1 : this.options.ConfirmDeleteCol+1;
    return this.grid.columns[idx].cell(this.rowIdx).content.innerHTML.stripTags();
  },

  deleteRecord: function() {
    var desc;
    if (this.options.ConfirmDeleteCol < 0) {
      desc="this "+this.options.RecordName;
    } else {
      desc=this.getConfirmDesc();
      if (desc.length>50) desc=desc.substring(0,50)+'...';
      desc='\"' + desc + '\"'
    }
    if (!this.options.ConfirmDelete.valueOf || confirm("Realmente Desea Eliminar " + desc + " ?")) {
      this.hideResponse('Deleting...');
      this.showResponse();
      new Ajax.Updater(this.responseDiv, window.location.pathname, {parameters:"a=del&k="+this.TEkey,onComplete:this.processResponse.bind(this)});
    }
  },

  TESubmit: function(e) {
    var i,lbl;
    
    if (!e) e=window.event;
    Event.stop(e);
    // check fields that are supposed to be non-blank
    //alert("checking form for blanks");
    for (i = 0; i < this.options.NonBlanks.length; i++) {
      //alert("nonblank check: " + this.options.NonBlanks[i]);
      if (document.getElementsByName(this.options.NonBlanks[i])[0].value.length == 0) {
        lbl="lbl_" + this.options.NonBlanks[i];
        alert("Please enter a value for \"" + document.getElementById(lbl).innerHTML + "\"");
        //setTimeout("TableEditSelect(document." + this.form.name + "." + this.options.NonBlanks[i] + ")",2000);
        return false;
      }
    }
    // recheck any elements on the form with an onchange event
    var InputFields = this.form.getElementsByTagName("input");
    this.TEerror=false;
    for (i=0; i < InputFields.length; i++) {
      if (InputFields[i].type=="text" && InputFields[i].onchange) {
        InputFields[i].onchange();
        if (this.TEerror) return false;
      }
    }
    this.makeFormInvisible();
    this.showResponse();
    this.grid.writeDebugMsg("TESubmit:"+Form.serialize(this.form));
    new Ajax.Updater(this.responseDiv, window.location.pathname, {parameters:Form.serialize(this.form),onComplete:this.processResponse.bind(this)});
    return false;
  },
  
  TableEditChkSelect: function(n,newval) {
    var newstyle=(document.getElementsByName(n)[0].value==newval) ? "" : "none";
    document.getElementById("labelnew__" + n).style.display=newstyle
    document.getElementsByName("textnew__" + n)[0].style.display=newstyle
  },

  TableEditSelect: function() {
    this.selectObj.focus();
    this.selectObj.select();
  },

  TableEditCheckDate: function(TxtObj) {
    this.TEerror=false;
    if (TxtObj.value.length==0) return false;  // this is handled by the non-blank test, if necessary
    var msg='"' + $("lbl_"+TxtObj.name).innerHTML + '" must be in the '+RicoTranslate.dateFmt+' format';
    var hDate=RicoCalendar.parseDate(TxtObj.value);
    if (isNaN(hDate.d)||isNaN(hDate.m)||isNaN(hDate.y)) {
      this.TEerror=true;
    } else if (hDate.m < 0 || hDate.m > 11) {
      msg="Invalid month! " + msg;
      this.TEerror=true;
    } else if (hDate.d < 1 || hDate.d > 31) {
      msg="Invalid day! " + msg;
      this.TEerror=true;
    } else if (hDate.y < this.options.minYear || hDate.y > this.options.maxYear) {
      msg="Invalid year! " + msg;
      this.TEerror=true;
    }
    if (this.TEerror) {
      alert(msg);
      this.selectObj=TxtObj;
      setTimeout(this.TableEditSelect.bind(this),0);
    }
  },

  TableEditCheckInt: function(TxtObj) {
    var val=TxtObj.value;
    if (val=='') return;
    if (val!=parseInt(val)) {
      alert("Please enter an integer value for \"" + document.getElementById("lbl_"+TxtObj.name).innerHTML + "\"");
      setTimeout(this.TableEditSelect.bind(this),0);
      this.TEerror=true;
    }
  },

  TableEditCheckPosInt: function(TxtObj) {
    var val=TxtObj.value;
    if (val=='') return;
    if (val!=parseInt(val) || val<0) {
      alert("Please enter an positive integer value for \"" + $("lbl_"+TxtObj.name).innerHTML + "\"");
      setTimeout(this.TableEditSelect.bind(this),0);
      this.TEerror=true;
    }
  },

  TECalendarClick: function(e,btnobj,txtbox) {
    //var p=Event.findElement(e,'div');
    RicoCalendar.toggleCalendar(btnobj,txtbox);
  },
/**
*   @par    row     object      Row at event
*   @par    string  action      Actionto be performed
*   @par    string  rowPos      Position of the row in the table (rowIndex)
**/
  saveRow: function(row, action, rowPos){ // el indicador this se refiere al grid
    if (this.saveMode) return;
    this.saveMode=true;
    if (this.options.saveHandler)
        return this.saveHandler(this, rowPos);
    var alFields = document.getElementsByClassName("editCell", document);
    var slParam="action=" + action + "&id=" + this.tableId;
    for (var i=0; i< this.columns.length; i++) {
        var slValue = "";
        var slField =  this.tableId + "_" + this.columns[i].fieldName;
        if (i<this.options.frozenColumns){
            var olCol=this.tbody[0].childNodes[this.lastEditedRow].childNodes[i];
            slValue = RicoUtil.getInnerText(olCol);
        }
        else {
            var olCol=this.tbody[1].childNodes[this.lastEditedRow].childNodes[i];
            var olNodes=$A(olCol.childNodes);
            var olNode =  olNodes.find(function(olNode){ return (olNode.className == "editCell")} ); //devolver el nodo editcell
            if (!olNode) slValue = RicoUtil.getInnerText(olCol); // si no tiene editcell
            else slValue = RicoUtil.getInnerText(olNode.value);
        }
        slParam += "&" + slField + "=" + slValue;
    }
	new Ajax.Updater(this.responseDiv, this.options.updateUrl, {parameters:slParam,onComplete:processUpdateResponse.bind(this)});
  },

  editCell: function (pCelda, isFirst){
	//alert (obj.currentStyle.width);
	if (this.options.canEditCols.length <1) return isFirst;
    if (this.options.canEditCols[pCelda.cellIndex] == false) return isFirst;
    var slField =  this.grid.tableId + "_" + pCelda.cellIndex;
    var txtColumn = document.createElement("input");
    txtColumn.id= pCelda.id+'_edit';
    txtColumn.name= pCelda.id;
//  txtColumn.value= obj.lastChildNodes[0].innerHTML;
    txtColumn.value= RicoUtil.getInnerText(pCelda);
    txtColumn.style.width=Element.getDimensions(pCelda).width;
    txtColumn.className = "editCell";
    txtColumn.style.border=0;
    txtColumn.style.borderColor="transparent";
    txtColumn.style.backgroundColor="transparent";
    txtColumn.style.tabIndex=999;
    txtColumn.style.color="blue";
    /**
    divEditor.style.width= Element.getDimensions(obj).width;
    divEditor.width= Element.getDimensions(obj).width;
    divEditor.style.border=0;
    divEditor.style.borderColor="transparent";
    divEditor.appendChild(txtColumn);
	**/
	                                               // Si no es texto, ocultar elemento
//    pCelda = RicoUtil.getParentByTagName(pCelda, "TD");
	if (pCelda.childNodes[0].nodeType != 3 )
        Element.hide(pCelda.childNodes[0]);
    else {
        pCelda.childNodes[0].data="";               // Vaciar texto.
    }
    pCelda.appendChild(txtColumn);
//    pCelda.appendChild(divEditor);
    if (isFirst){
         if(objCh =$(pCelda.id +"_edit")){
            try{objCh.focus()}  catch (err){};
            try{objCh.select()} catch (err){};
            }
         isFirst=false;
    }
    objRow = RicoUtil.getParentByTagName(pCelda, "TR");
    objRow.style.backgroundColor="yellow";
    return isFirst;
   },
   
   finishEditMode: function(){
        /**/
    var alFields=document.getElementsByClassName("editCell");
    this.saveMode=false;
    for (var i=0; i < alFields.length; i++){
        var olTd=RicoUtil.getParentByTagName(alFields[i],this.grid.options.datacellTag);
//        olTd.childNodes[0].innerHTML=alFields[i].value;
//        olTd.childNodes[0].textContent=alFields[i].value;
        Element.update(olTd.childNodes[0], alFields[i].value);
        Element.show(olTd.childNodes[0]);
        Element.remove(olTd.childNodes[1]);
    }
    /**/
    this.lastEditedRow=-1;
    }
}                                                   //----------------------------  End TableEdit class

function fFocusCell(e){
    objCell =Event.element(e);
    Event.element(e).style.color="blue";
    if(!Element.hasClassName(objCell, "editCell")) Element.addClassName(objCell, "editCell");
}

function processUpdateResponse() {
    var ch=this.responseDiv.childNodes;
    for (var i=ch.length-1; i>=0; i--) {
      if (ch[i].nodeType==1 && ch[i].nodeName!='P' && ch[i].nodeName!='DIV' && ch[i].nodeName!='BR')
        this.responseDiv.removeChild(ch[i]);
    }
    this.finishEditMode();
    var responseText=this.responseDiv.innerHTML;
    if (responseText.toLowerCase().indexOf('error')==-1) { // Resultado OK.
      this.hideResponse('');
      this.grid.resetContents();
      this.grid.requestContentRefresh(this.grid.lastRowPos);
      if (this.saveMsg) this.saveMsg.innerHTML='&nbsp;'+responseText.stripTags()+'&nbsp;';
      if (this.options.onSuccesSaveHandler) this.options.onSuccesSaveHandler(this);
    } else {                                            // Resultado con error
      if (this.options.onFailSaveHandler)   this.options.onFailSaveHandler(this);
          this.grid.lastEditedRow=-1;
  }
}
