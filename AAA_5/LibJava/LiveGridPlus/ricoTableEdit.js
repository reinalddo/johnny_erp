Rico.TableEdit = Class.create();

Rico.TableEdit.prototype = {

  initialize: function(liveGrid,options) {
    //alert('TableEdit initialize');
    this.grid=liveGrid;
    liveGrid.options.dataMenuHandler=this.editMenu.bind(this);
    this.editDiv=$(liveGrid.tableId+'_edit');
    if (!this.editDiv) return;
    this.saveMsg=$(liveGrid.tableId+'_savemsg');
    this.timerMsg=$(liveGrid.tableId+'_timer');
    this.responseDiv=$(liveGrid.tableId+'_submitresponse');
    this.responseDialog=$(liveGrid.tableId+'_responsedialog');
    this.form=this.editDiv.getElementsByTagName('form')[0];
    this.action=$(liveGrid.tableId+'_action');
    Event.observe(document,"click", this.clearSaveMsg.bindAsEventListener(this), false);
    this.TEerror=false;
    this.extraMenuItems=new Array();
    this.sessionExpired=false;
    this.shim=new Rico.Shim();
    this.options = {
      canEdit:false,
      canAdd:false,
      CanDelete:false,
      ConfirmDelete:true,
      ConfirmDeleteCol:-1,
      RecordName:'record',
      minYear:1900,
      maxYear:2999
    };
    Object.extend(this.options, options || {});
    if (this.options.TimeOut && this.timerMsg) {
      this.restartSessionTimer();
      liveGrid.options.onAjaxUpdate=this.restartSessionTimer.bind(this);
    }
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
    for (var i=0; i<this.extraMenuItems.length; i++) {
      gridMenu.addMenuItem(this.extraMenuItems[i].menuText,this.extraMenuItems[i].menuAction,this.extraMenuItems[i].enabled);
    }
    if (onBlankRow==false) {
      gridMenu.addMenuItem("Edit this "+this.options.RecordName,this.editRecord.bind(this),this.options.canEdit);
      gridMenu.addMenuItem("Delete this "+this.options.RecordName,this.deleteRecord.bind(this),this.options.CanDelete);
    }
    gridMenu.addMenuItem("Add new "+this.options.RecordName,this.addRecord.bind(this),this.options.canAdd);
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
    this.editDiv.style.display='';
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
    if (!this.options.ConfirmDelete.valueOf || confirm("Are you sure you want to delete " + desc + " ?")) {
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
  }
}
