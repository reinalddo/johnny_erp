//  based on code originally written by Tan Ling Wee on 2 Dec 2001
//  last updated 23 June 2002
//  email: fuushikaden@yahoo.com

//  adapted for use in Rico by Matt Brown
//  June 2006
//  email: dowdybrown@yahoo.com

//  Requires prototype.js and ricoCommon.js

var RicoCalendar = {

  fixedX : -1,        // x position (-1 if to appear below control)
  fixedY : -1,        // y position (-1 if to appear below control)
  startAt : 0,        // 0 - sunday ; 1 - monday
  showWeekNumber : 0, // 0 - don't show; 1 - show
  showToday : 1,      // 0 - don't show; 1 - show

  gotoString : "Go To Current Month",
  todayString : "<font color=white>Today is</font>",
  weekString : "Wk",
  scrollLeftMessage : "Click to scroll to previous month. Hold mouse button to scroll automatically.",
  scrollRightMessage : "Click to scroll to next month. Hold mouse button to scroll automatically.",
  selectMonthMessage : "Click to select a month.",
  selectYearMessage : "Click to select a year.",
  selectDateMessage : "Select [date] as date.", // do not replace [date], it will be replaced by date.

  bPageLoaded:false,
  img : new Array(),
  re : /^\s*(\w+)(\W)(\w+)(\W)(\w+)\s*$/,

  bShow : false,
  HolidaysCounter : 0,
  Holidays : new Array(),
  styleLightBorder:"border:1px solid #666666;",

  addHoliday : function(d, m, y, desc) {
    this.Holidays[this.HolidaysCounter++] = new Array ( d, m, y, desc )
  },

  swapImage : function(srcImg, destImg) {
    $(srcImg).setAttribute("src",Rico.imgDir + destImg);
  },
  
  atLoad : function() {
    var imgsrc = new Array("drop1.gif","drop2.gif","left1.gif","left2.gif","right1.gif","right2.gif");
    for (i=0;i<imgsrc.length;i++) {
      this.img[i] = new Image
      this.img[i].src = Rico.imgDir + imgsrc[i]
    }
    var today = new Date();
    this.dateNow  = today.getDate();
    this.monthNow = today.getMonth();
    this.yearNow  = today.getFullYear();
    this.calobj=document.createElement("div");
    this.calobj.id='calendar';
    this.calobj.onclick=function() { bShow=true };
    this.styleobj=this.calobj.style;
    this.styleobj.zIndex=9999;
    this.styleobj.position='absolute';
    this.styleobj.display='none';
    var w=(this.showWeekNumber==1) ? 250 : 200;
    var sHTML1="<table cellpadding=0 cellspacing=0 width="+w+" style='font-family:arial;font-size:11px;border-width:1px;border-style:solid;border-color: #666666;font-family:arial; font-size:11px;' bgcolor='#ffffff'>";
    var w=(this.showWeekNumber==1) ? 248 : 218;
    sHTML1+="<tr bgcolor='#D4D0C8'><td><table cellpadding=2 cellspacing=0 width='"+w+"' >"
    sHTML1+="<tr><td nowrap style='padding:2px;font-family:arial; font-size:11px;' align=right>"
    sHTML1+="<font color='#000000'><B><span id='caption'></span></B></font>"
    sHTML1+="</td><td nowrap align=right><a href='javascript:RicoCalendar.hideCalendar()' onmouseover='RicoCalendar.popDownYear(); RicoCalendar.popDownMonth();'>"
    sHTML1+="<IMG id=close SRC='"+Rico.imgDir+"close.gif' WIDTH='16' HEIGHT='14' BORDER='0' ALT='Close the Calendar' align='absmiddle'></a>&nbsp;</td></tr></table>"
    sHTML1+="</td></tr><tr><td style='padding:5px' bgcolor=#ffffff><span id='content'></span></td></tr>"

    if (this.showToday==1) {
      sHTML1+="<tr bgcolor=#666666><td style='padding:5px' align=center><span id='lblToday'></span></td></tr>";
    }
    sHTML1+="</table></div>"
    sHTML1+="<div id='selectMonth' style='z-index:+999;position:absolute;visibility:hidden;'></div>"
    sHTML1+="<div id='selectYear' style='z-index:+999;position:absolute;visibility:hidden;'></div>";
    this.calobj.innerHTML=sHTML1;
    document.body.appendChild(this.calobj);

    this.shim=new Rico.Shim();
    this.hideCalendar()
    this.crossMonthObj=$("selectMonth").style;
    this.crossYearObj=$("selectYear").style;
    this.monthConstructed=false;
    this.yearConstructed=false;

    if (this.showToday==1)
    {
      $("lblToday").innerHTML = this.todayString + " <a onmousemove='window.status=\""+this.gotoString+"\"' onmouseout='window.status=\"\"' title='"+this.gotoString+"' style='color: white' href='javascript:RicoCalendar.selectNow()'>"+RicoTranslate.dayNames[(today.getDay()-this.startAt==-1)?6:(today.getDay()-this.startAt)]+", " + this.dateNow + " " + RicoTranslate.monthNames[this.monthNow].substring(0,3) + " " + this.yearNow + "</a>"
    }

    var sHTML1="&nbsp;<span id='spanLeft' style='border-style:solid;border-width:1px;border-color:#D4D0C8;cursor:pointer' onmouseover='RicoCalendar.swapImage(\"changeLeft\",\"left2.gif\");this.style.borderColor=\"#666666\";window.status=\""+this.scrollLeftMessage+"\"; RicoCalendar.popDownYear(); RicoCalendar.popDownMonth();' onclick='javascript:RicoCalendar.decMonth()' onmouseout='clearInterval(RicoCalendar.intervalID1);RicoCalendar.swapImage(\"changeLeft\",\"left1.gif\");this.style.borderColor=\"#D4D0C8\";window.status=\"\"' onmousedown='clearTimeout(RicoCalendar.timeoutID1);RicoCalendar.timeoutID1=setTimeout(\"RicoCalendar.StartDecMonth()\",500)'  onmouseup='clearTimeout(RicoCalendar.timeoutID1);clearInterval(RicoCalendar.intervalID1)'>&nbsp<IMG id='changeLeft' SRC='"+Rico.imgDir+"left1.gif' width=10 height=11 align=middle BORDER=0>&nbsp</span>&nbsp;"
    sHTML1+="&nbsp;<span id='spanMonth' style='width: 82px;border-style:solid;border-width:1px;border-color:#D4D0C8;cursor:pointer' onmouseover='RicoCalendar.swapImage(\"changeMonth\",\"drop2.gif\");this.style.borderColor=\"#666666\";window.status=\""+this.selectMonthMessage+"\"; RicoCalendar.popDownYear();' onmouseout='RicoCalendar.swapImage(\"changeMonth\",\"drop1.gif\");this.style.borderColor=\"#D4D0C8\";window.status=\"\"' onclick='RicoCalendar.popUpMonth()'></span>&nbsp;"
    sHTML1+="&nbsp;<span id='spanYear' style='border-style:solid;border-width:1px;border-color:#D4D0C8;cursor:pointer' onmouseover='RicoCalendar.swapImage(\"changeYear\",\"drop2.gif\");this.style.borderColor=\"#666666\";window.status=\""+this.selectYearMessage+"\"; RicoCalendar.popDownMonth();' onmouseout='RicoCalendar.swapImage(\"changeYear\",\"drop1.gif\");this.style.borderColor=\"#D4D0C8\";window.status=\"\"' onclick='RicoCalendar.popUpYear()'></span>&nbsp;"
    sHTML1+="&nbsp;<span id='spanRight' style='border-style:solid;border-width:1px;border-color:#D4D0C8;cursor:pointer' onmouseover='RicoCalendar.swapImage(\"changeRight\",\"right2.gif\");this.style.borderColor=\"#666666\";window.status=\""+this.scrollRightMessage+"\"; RicoCalendar.popDownYear(); RicoCalendar.popDownMonth();' onmouseout='clearInterval(RicoCalendar.intervalID1);RicoCalendar.swapImage(\"changeRight\",\"right1.gif\");this.style.borderColor=\"#D4D0C8\";window.status=\"\"' onclick='RicoCalendar.incMonth()' onmousedown='clearTimeout(RicoCalendar.timeoutID1);RicoCalendar.timeoutID1=setTimeout(\"RicoCalendar.StartIncMonth()\",500)'  onmouseup='clearTimeout(RicoCalendar.timeoutID1);clearInterval(RicoCalendar.intervalID1)'>&nbsp<IMG id='changeRight' SRC='"+Rico.imgDir+"right1.gif' width=10 height=11  align=middle BORDER=0>&nbsp</span>&nbsp"
    $("caption").innerHTML  = sHTML1

    this.dateParts=new Array();
    if (this.re.exec(RicoTranslate.dateFmt)) {
      this.dateParts[RegExp.$1]=0;
      this.dateParts[RegExp.$3]=1;
      this.dateParts[RegExp.$5]=2;
    }

    this.bPageLoaded=true
  },
  
  selectNow : function() {
    this.monthSelected=this.monthNow;
    this.yearSelected=this.yearNow;
    this.constructCalendar();
  },

  hideCalendar : function() {
    this.shim.hide();
    this.styleobj.display="none"
    if (this.crossMonthObj != null){this.crossMonthObj.visibility="hidden"}
    if (this.crossYearObj != null){this.crossYearObj.visibility="hidden"}
  },

  closeCalendar : function() {
    this.hideCalendar();
    var d=new Date(this.yearSelected,this.monthSelected,this.dateSelected);
    this.ctlToPlaceValue.value = d.formatDate();
  },

  StartDecMonth : function() {
    this.intervalID1=setInterval("RicoCalendar.decMonth()",80)
  },

  StartIncMonth : function() {
    this.intervalID1=setInterval("RicoCalendar.incMonth()",80)
  },

  incMonth : function() {
    this.monthSelected++
    if (this.monthSelected>11) {
      this.monthSelected=0
      this.yearSelected++
    }
    this.constructCalendar()
  },

  decMonth : function() {
    this.monthSelected--
    if (this.monthSelected<0) {
      this.monthSelected=11
      this.yearSelected--
    }
    this.constructCalendar()
  },
  
  selectMonth : function(e,i) {
    this.monthConstructed=false;
    this.monthSelected=i;
    this.constructCalendar();
    this.popDownMonth();
    Event.stop(e);
  },

  constructMonth : function() {
    this.popDownYear()
    if (!this.monthConstructed) {
      var sHTML = ""
      for (var i=0; i<12; i++)
        sHTML += "<tr><td id='m" + i + "' onmouseover='this.style.backgroundColor=\"#CAD2E4\"; this.style.borderColor=\"#666666\"' onmouseout='this.style.backgroundColor=\"\"; this.style.borderColor=\"#FFFFFF\"' style='cursor:pointer; border: 1px solid #ffffff;' bgcolor=#ffffff onclick='RicoCalendar.selectMonth(event,"+i+")'>&nbsp;" + RicoTranslate.monthNames[i].substring(0,3) + "&nbsp;</td></tr>"
      $("selectMonth").innerHTML = "<table width=50 style='font-family:arial; font-size:10px; border-width:1px; border-style:solid; border-color:#666666; border-Top: 0px;' bgcolor='#FFFFFF' cellspacing=0 cellpadding=0 onmouseover='clearTimeout(RicoCalendar.timeoutID1)'  onmouseout='clearTimeout(RicoCalendar.timeoutID1);RicoCalendar.timeoutID1=setTimeout(\"RicoCalendar.popDownMonth()\",100);event.cancelBubble=true'>" + sHTML + "</table>"
      this.monthConstructed=true
    }
  },

  popUpMonth : function() {
    this.constructMonth()
    this.crossMonthObj.visibility = "visible";
    var leftOffset = $("spanMonth").offsetLeft;
    if (RicoUtil.isIE) leftOffset += 1;
    this.crossMonthObj.left = leftOffset + 'px';
    this.crossMonthObj.top = '19px';
    $("spanMonth").style.borderColor='#666666';
  },

  popDownMonth : function() {
    this.crossMonthObj.visibility= "hidden";
    $("spanMonth").style.borderColor='#D4D0C8';
  },

  /*** Year Pulldown ***/

  incYear : function() {
    for (var txtYear,i=0; i<7; i++){
      var newYear = (i+this.nStartingYear)+1
      if (newYear==this.yearSelected)
      { txtYear = "&nbsp;<B>" + newYear + "</B>&nbsp;" }
      else
      { txtYear = "&nbsp;" + newYear + "&nbsp;" }
      $("y"+i).innerHTML = txtYear
    }
    this.nStartingYear++;
    this.bShow=true
  },

  decYear : function() {
    for (var txtYear,i=0; i<7; i++){
      var newYear = (i+this.nStartingYear)-1
      if (newYear==this.yearSelected)
      { txtYear = "&nbsp;<B>" + newYear + "</B>&nbsp;" }
      else
      { txtYear = "&nbsp;" + newYear + "&nbsp;" }
      $("y"+i).innerHTML = txtYear
    }
    this.nStartingYear --;
    this.bShow=true
  },

  selectYear : function(e,nYear) {
    this.yearSelected=parseInt(nYear+this.nStartingYear);
    this.yearConstructed=false;
    this.constructCalendar();
    this.popDownYear();
    Event.stop(e);
  },

  constructYear : function() {
    this.popDownMonth()
    var sHTML = ""
    if (!this.yearConstructed) {

      sHTML = "<tr><td align='center' onmouseover='this.style.backgroundColor=\"#FFFFFF\"; this.style.borderColor=\"#666666\"' onmouseout='this.style.backgroundColor=\"\"; this.style.borderColor=\"#FFFFFF\"' style='cursor:pointer; border: 1px solid #ffffff;'  onmousedown='clearInterval(RicoCalendar.intervalID1);RicoCalendar.intervalID1=setInterval(\"RicoCalendar.decYear()\",30)' onmouseup='clearInterval(intervalID1)'>-</td></tr>"

      var j = 0
      this.nStartingYear = this.yearSelected-3
      for (var i=(this.yearSelected-3); i<=(this.yearSelected+3); i++) {
        sName = i;
        if (i==this.yearSelected){
          sName = "<B>" + sName + "</B>"
        }

        sHTML += "<tr><td id='y" + j + "' onmouseover='this.style.backgroundColor=\"#CAD2E4\"; this.style.borderColor=\"#666666\"' onmouseout='this.style.backgroundColor=\"\"; this.style.borderColor=\"#FFFFFF\"' style='cursor:pointer; border: 1px solid #ffffff;' bgcolor=#ffffff onclick='RicoCalendar.selectYear(event,"+j+");'>&nbsp;" + sName + "&nbsp;</td></tr>"
        j ++;
      }

      sHTML += "<tr><td align='center' onmouseover='this.style.backgroundColor=\"#FFFFFF\"; this.style.borderColor=\"#666666\"' onmouseout='this.style.backgroundColor=\"\"; this.style.borderColor=\"#FFFFFF\"' style='cursor:pointer; border: 1px solid #ffffff;' bgcolor=#ffffff onmousedown='clearInterval(RicoCalendar.intervalID2);RicoCalendar.intervalID2=setInterval(\"RicoCalendar.incYear()\",30)'  onmouseup='clearInterval(intervalID2)'>+</td></tr>"

      $("selectYear").innerHTML = "<table width=44 style='font-family:arial; font-size:11px; border-width:1px; border-style:solid; border-color:#666666; border-Top: 0px;' bgcolor='#FFFFFF' onmouseover='clearTimeout(RicoCalendar.timeoutID2)' onmouseout='clearTimeout(RicoCalendar.timeoutID2);RicoCalendar.timeoutID2=setTimeout(\"RicoCalendar.popDownYear();\",100)' cellspacing=1 cellpadding=0>" + sHTML + "</table>"

      this.yearConstructed = true
    }
  },

  popDownYear : function() {
    clearInterval(this.intervalID1)
    clearTimeout(this.timeoutID1)
    clearInterval(this.intervalID2)
    clearTimeout(this.timeoutID2)
    this.crossYearObj.visibility= "hidden"
    $('spanYear').style.borderColor='#D4D0C8';
  },

  resetColor : function(){
    $('spanMonth').style.borderColor='#D4D0C8';
    $('spanYear').style.borderColor='#D4D0C8';
  },

  popUpYear : function() {
    this.constructYear()
    this.crossYearObj.visibility = "visible";
    var leftOffset = $("spanYear").offsetLeft
    if (RicoUtil.isIE) leftOffset += 1;
    this.crossYearObj.left = leftOffset+'px';
    this.crossYearObj.top = '19px';
    $('spanYear').style.borderColor='#666666';
  },

  /*** calendar ***/
   WeekNbr : function(n) {
      var year = n.getFullYear();
      var month = n.getMonth() + 1;
      var day = (this.startAt == 0) ? n.getDate() + 1 : n.getDate();
      var a = Math.floor((14-month) / 12);
      var y = year + 4800 - a;
      var m = month + 12 * a - 3;
      var b = Math.floor(y/4) - Math.floor(y/100) + Math.floor(y/400);
      var J = day + Math.floor((153 * m + 2) / 5) + 365 * y + b - 32045;
      var d4 = (((J + 31741 - (J % 7)) % 146097) % 36524) % 1461;
      var L = Math.floor(d4 / 1460);
      var d1 = ((d4 - L) % 365) + L;
      var week = Math.floor(d1/7) + 1;
      return week;
   },

  constructCalendar : function() {
    var aNumDays = Array (31,0,31,30,31,30,31,31,30,31,30,31)
    var startDate = new Date (this.yearSelected,this.monthSelected,1)
    var endDate,numDaysInMonth

    if (this.monthSelected==1) {
      endDate = new Date (this.yearSelected,this.monthSelected+1,1);
      endDate = new Date (endDate - (24*60*60*1000));
      numDaysInMonth = endDate.getDate()
    } else {
      numDaysInMonth = aNumDays[this.monthSelected];
    }
    var datePointer = 0
    var dayPointer = startDate.getDay() - this.startAt
    if (dayPointer<0) dayPointer = 6;
    var sHTML = "<table  border='0' style='font-family:verdana;font-size:10px;'><tr>"

    if (this.showWeekNumber==1) {
      sHTML += "<td width='27'><b>" + this.weekString + "</b></td><td width=1 rowspan=7 bgcolor='#d0d0d0' style='padding:0px'><img src='"+Rico.imgDir+"divider.gif' width=1></td>"
    }

    for (i=0; i<7; i++) {
      sHTML += "<td width='27' align='right'><b>"+ RicoTranslate.dayNames[(i+this.startAt) % 7].substring(0,3)+"</b></td>"
    }
    sHTML +="</tr><tr>"

    if (this.showWeekNumber==1) {
      sHTML += "<td align=right>" + WeekNbr(startDate) + "&nbsp;</td>"
    }

    for ( var i=1; i<=dayPointer;i++ ) {
      sHTML += "<td>&nbsp;</td>"
    }

    for ( datePointer=1; datePointer<=numDaysInMonth; datePointer++ ) {
      dayPointer++;
      sHTML += "<td align=right>"
      var sStyle=""
      if ((datePointer==this.odateSelected) && (this.monthSelected==this.omonthSelected) && (this.yearSelected==this.oyearSelected))
        sStyle+=this.styleLightBorder;

      var sHint = ""
      for (var k=0;k<this.HolidaysCounter;k++) {
        if ((parseInt(Holidays[k].d)==datePointer)&&(parseInt(Holidays[k].m)==(this.monthSelected+1)))
        {
          if ((parseInt(Holidays[k].y)==0)||((parseInt(Holidays[k].y)==this.yearSelected)&&(parseInt(Holidays[k].y)!=0)))
          {
            sStyle+="background-color:#FFDDDD;"
            sHint+=sHint==""?Holidays[k].desc:"\n"+Holidays[k].desc
          }
        }
      }

      var regexp= /\"/g
      sHint=sHint.replace(regexp,"&quot;")
      var dateMessage = ""; //"onmousemove='window.status=\""+RicoCalendar.selectDateMessage.replace("[date]",constructDate(datePointer,this.monthSelected,this.yearSelected))+"\"' onmouseout='window.status=\"\"' "
      var dateClass='ricoCalDate';
      if ((datePointer==this.dateNow)&&(this.monthSelected==this.monthNow)&&(this.yearSelected==this.yearNow))
        dateClass='ricoCalToday';
      else if (dayPointer % 7 == (this.startAt * -1)+1)
        dateClass='ricoCalWeekend';
      sHTML += "<a "+dateMessage+" title=\"" + sHint + "\" style='"+sStyle+"' class='"+dateClass+"' href='javascript:RicoCalendar.dateSelected="+datePointer+";RicoCalendar.closeCalendar();'>&nbsp;" + datePointer + "&nbsp;</a>";

      if (dayPointer % 7 == 0) {
        sHTML += "</tr><tr>"
        if ((this.showWeekNumber==1)&&(datePointer<numDaysInMonth))
          sHTML += "<td align=right>" + (WeekNbr(new Date(this.yearSelected,this.monthSelected,datePointer+1))) + "&nbsp;</td>";
      }
    }
    // next 3 lines keep calendar a fixed size
    if (dayPointer % 7 == 0) sHTML += "<td>&nbsp;</td>"
    sHTML += "</tr>"
    if (dayPointer<35) sHTML += "<tr><td>&nbsp;</td></tr>";

    $("content").innerHTML   = sHTML
    $("spanMonth").innerHTML = "&nbsp;" + RicoTranslate.monthNames[this.monthSelected].substring(0,3) + "&nbsp;<IMG id='changeMonth' SRC='"+Rico.imgDir+"drop1.gif' WIDTH='12' HEIGHT='10' BORDER=0 align=absmiddle>"
    $("spanYear").innerHTML = "&nbsp;" + this.yearSelected + "&nbsp;<IMG id='changeYear' SRC='"+Rico.imgDir+"drop1.gif' WIDTH='12' HEIGHT='10' BORDER=0 align=absmiddle>"
  },
  
  // parse date string
  parseDate: function(s) {
    if (this.re.exec(s)) {
      var aDate=new Array(RegExp.$1,RegExp.$3,RegExp.$5);
      var d = parseInt(aDate[RicoCalendar.dateParts['dd']], 10)
      var m = parseInt(aDate[RicoCalendar.dateParts['mm']], 10) - 1
      var y = parseInt(aDate[RicoCalendar.dateParts['yyyy']], 10)
      return {d:d, m:m, y:y};
    } else {
      return {d:NaN, m:NaN, y:NaN};
    }
  },

  toggleCalendar : function(ctl, x) {
    if ( this.styleobj.display == "none" )
      this.popUpCalendar(ctl, x);
    else
      this.hideCalendar();
  },

  popUpCalendar : function(ctl, x) {
    if (!this.bPageLoaded) return;
    if ( this.styleobj.display != "none" ) this.hideCalendar();
    this.ctlToPlaceValue = $(x);
    var hDate=this.parseDate(this.ctlToPlaceValue.value);
    if (isNaN(hDate.d)||isNaN(hDate.m)||isNaN(hDate.y)) {
      this.dateSelected  = this.dateNow
      this.monthSelected = this.monthNow
      this.yearSelected  = this.yearNow
    } else {
      this.dateSelected  = hDate.d;
      this.monthSelected = hDate.m;
      this.yearSelected  = hDate.y;
    }

    this.odateSelected=this.dateSelected
    this.omonthSelected=this.monthSelected
    this.oyearSelected=this.yearSelected
    var offsets=Position.page(ctl);
    var correction=RicoUtil.isIE ? 1 : 2;  // based on a 1px border

    this.styleobj.left = this.fixedX==-1 ? (offsets[0]+correction)+'px' : this.fixedX
    this.styleobj.display="block";
    this.constructCalendar (1, this.monthSelected, this.yearSelected);
    if (this.fixedY==-1) {
      var scrTop=RicoUtil.docScrollTop();
      var newTop=offsets[1] + correction + scrTop;
      //alert('newTop='+newTop+' o.y='+offsets[1]+' correction='+correction);
      var calht=this.calobj.offsetHeight;
      var iconht=ctl.offsetHeight;
      if (newTop+iconht+calht < RicoUtil.windowHeight()+scrTop)
        newTop+=iconht;  // display below icon
      else
        newTop=Math.max(newTop-calht,scrTop);  // display above icon
      this.styleobj.top = newTop+'px';
    } else {
      this.styleobj.top = this.fixedY;
    }
    this.shim.show(this.calobj);
    this.bShow = true;
  }
}

Event.observe(window, 'load', function() { RicoCalendar.atLoad() });
