<?
/*****************************************************************
 Page        : dbClass.php
 Description : Routines to access MySQL database  
 Date        : 25 May 2006
 Authors     : Matt Brown (dowdybrown@yahoo.com)
 Copyright (C) 2006 Matt Brown

dbClass.php is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

dbClass.php is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
General Public License for more details.

You should have received a copy of the GNU General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
******************************************************************/

class dbClass_mysql
{
  function dbClass_mysql($conn) { $this->conn=$conn; }
  function HasError() { return mysql_errno()!=0; }
  function ErrorMsg() { return mysql_error(); }
  function Close() { return mysql_close($this->conn); }
  function FreeResult($rsLookUp) { return mysql_free_result($rsLookUp); }
  function RunQuery($sqltext) { return mysql_query($sqltext,$this->conn); }
  function NumFields($rsMain) { return mysql_num_fields($rsMain); }
  function NumRows($rsMain) { return mysql_num_rows($rsMain); }
  function FieldType($rsMain,$i) { return mysql_field_type($rsMain,$i); }
  function FetchRow($rsMain,&$result) { $result=mysql_fetch_row($rsMain); return ($result==false) ? false : true; }
  function Seek($rsMain,$offset) { return mysql_data_seek($rsMain,$offset); }
}

class dbClass_odbc
{
  function dbClass_odbc($conn) { $this->conn=$conn; }
  function HasError() { return odbc_error()!=''; }
  function ErrorMsg() { return odbc_errormsg(); }
  function Close() { return odbc_close($this->conn); }
  function FreeResult($rsLookUp) { return odbc_free_result($rsLookUp); }
  function RunQuery($sqltext) { return odbc_exec($this->conn,$sqltext); }
  function NumFields($rsMain) { return odbc_num_fields($rsMain); }
  function NumRows($rsMain) { return odbc_num_rows($rsMain); }
  function FieldType($rsMain,$i) { return odbc_field_type($rsMain,$i+1); }
  function FetchRow($rsMain,&$result) { $rc=odbc_fetch_into($rsMain,$result); return ($rc==false) ? false : true; }
  function Seek($rsMain,$offset) { 
    for($i=0; $i<$offset; $i++) odbc_fetch_row($rsMain);
  }
}

class dbClass
{

  var $debug,$ConnTimeout,$CmdTimeout,$LockTimeout,$Provider;
  var $ErrMsgFmt;
  var $DisplayErrors;
  var $LastErrorMsg;
  // these are private:
  var $dbMain,$DisplayFunc,$dbDefault;

// -------------------------------------------------------------
// Class Constructor
// -------------------------------------------------------------
  function dbClass()
  {
    $this->Provider="localhost";
    $this->debug=false;
    $this->ConnTimeout=30; // seconds
    $this->LockTimeout=5000; // milliseconds
    $this->DisplayErrors=true;
    $this->CmdTimeout=120; // 2 minutes for asp pages
    $this->ErrMsgFmt="HTML";
    $this->DisplayFunc="echo";
  }

// -------------------------------------------------------------
// Class Destructor
// -------------------------------------------------------------
  function Class_Terminate()
  {
    $this->dbClose();
  }

  function DefaultDB()
  {
    return $this->dbDefault;
  }

//********************************************************************************************************
// If the database is down, then an explanation can be placed here
//********************************************************************************************************
  function MaintenanceMsg()
  {
    return "";
  }

  function DisplayMsg($msg)
  {
    if (!empty($this->DisplayFunc))
    {
      if ($this->ErrMsgFmt=="HTML" && substr($msg,0,1)!="<")
      {
        $msg="<p>".htmlspecialchars(str_replace("\n","<br>",$msg));
      }
        else
      {
        $msg=str_replace("\n"," ",$msg);
      }
      eval($this->DisplayFunc."(\"".$msg."\");");
    }
  }

  function HandleError($msg)
  {
    $this->LastErrorMsg=$msg;
    if ($this->DisplayErrors)
    {
      $this->DisplayMsg($this->LastErrorMsg);
    }
  }

//********************************************************************************************************
// Checks if an error has occurred, and if so, displays a message & returns true
//********************************************************************************************************
  function CheckForError($msg)
  {
    if (!$this->db->HasError()) return false;
    if (empty($this->ErrMsgFmt)) return true;
    $this->HandleError($this->FormatErrorMsg($msg));
    return true;
  }

//********************************************************************************************************
// Attempts to connect to the Database. Returns true on success.
//********************************************************************************************************
  function MySqlLogon($DefDB,$userid,$pw)
  {
    $this->dbDefault = $DefDB;
    $this->dbMain = mysql_connect($this->Provider,$userid,$pw);
    mysql_select_db($DefDB,$this->dbMain);
    $this->db =& new dbClass_mysql($this->dbMain);
    if ($this->CheckForError("opening connection"))
    {
      return false;
    }
    return true;
  }

//********************************************************************************************************
// Attempts to connect to the Database. Returns true on success.
//********************************************************************************************************
  function OdbcLogon($dsn,$DefDB,$userid,$pw)
  {
    $this->dbDefault = $DefDB;
    $this->dbMain = odbc_connect($dsn,$userid,$pw);
    $this->db =& new dbClass_odbc($this->dbMain);
    if ($this->CheckForError("opening connection"))
    {
      return false;
    }
    return true;
  }

//********************************************************************************************************
// Close database connection
//********************************************************************************************************
  function dbClose()
  {
    if ($this->dbMain)
    {
        $this->db->Close();
    }
    return true;
  }

//********************************************************************************************************
// Return a string containing an error message
// String format is based on ErrMsgFmt
//********************************************************************************************************
  function FormatErrorMsg($ContextMsg)
  {
    switch ($this->ErrMsgFmt)
    {
      case "HTML":
        $function_ret="<p class=dberror id=dbError>Error! " . $this->db->ErrorMsg() ."</p>".
          "<p class=dberror id=dbErrorDetail><u>Operation that caused the error:</u><br>".$ContextMsg."</p>";
        break;
      case "MULTILINE":
        $function_ret="Error! " . $this->db->ErrorMsg() ."\n\nOperation that caused the error:\n".$ContextMsg;
        break;
      case "1LINE":
        $function_ret="Error! " . $this->db->ErrorMsg() ."  (".$ContextMsg.")";
        break;
    }
    return $function_ret;
  }

//********************************************************************************************************
// Runs a query and moves to the first record.
// Use only for queries that return records (no updates or deletes).
// If the query generated an error then Nothing is returned, otherwise it returns a new recordset object.
//********************************************************************************************************
  function RunQuery($sqltext)
  {
    $rsLookUp=$this->db->RunQuery($sqltext);
    if ($this->CheckForError($sqltext)) return null;
    if ($this->debug)
    {
      $this->DisplayMsg($sqltext);
    }
    return $rsLookUp;
  }


//********************************************************************************************************
// Safely close a recordset
//********************************************************************************************************
  function rsClose(&$rsLookUp)
  {
    if ($rsLookUp)
    {
        $this->db->FreeResult($rsLookUp);
        $rsLookUp=null;
    }
  }

//********************************************************************************************************
// Runs a query and returns results from the first record in dicData.
// Returns true if dicData is modified (ie. a record exists).
// If the query generates an error then dicData is left unchanged
// returns dicData as an array
//********************************************************************************************************
  function SingleRecordQuery($strSqlText,&$dicData)
  {
    $rsMain=$this->RunQuery($strSqlText);
    if (!$rsMain) return false;
    $dicData=mysql_fetch_array($rsMain);
    $this->rsClose($rsMain);
    return true;
  }


//********************************************************************************************************
// Runs a query where no result set is expected (updates, deletes, etc)
//   - returns the number of records affected by the action query
//********************************************************************************************************
  function RunActionQuery($sqltext)
  {
    $this->db->RunQuery($sqltext);
    if ($this->CheckForError($sqltext))
    {
      return 0;
    }
      else
    if ($this->debug)
    {
      $this->DisplayMsg($sqltext);
    }
    return mysql_affected_rows();
  }


//********************************************************************************************************
// Runs a query where no result set is expected (updates, deletes, etc)
//   - if an error occurs, then the message is returned in errmsg
//********************************************************************************************************
  function RunActionQueryReturnMsg($sqltext,&$errmsg)
  {
    $tmpDisplayErrors=$this->DisplayErrors;
    $this->DisplayErrors=false;
    $this->LastErrorMsg="";
    $function_ret=$this->RunActionQuery($sqltext);
    if (!empty($this->LastErrorMsg))
    {
      $errmsg=$this->LastErrorMsg;
    }
    $this->DisplayErrors=$tmpDisplayErrors;
    return $function_ret;
  }


//********************************************************************************************************
// Takes a sql create (table or view) statement and performs:
//   1) a conditional drop (if it already exists)
//   2) the create
//   3) grants select access to public (if not a temp table)
//
// for views, all actions must occur on the default database for the connection
//********************************************************************************************************
  function DropCreate($sqlcreate)
  {
    $parsed=explode(" ",$sqlcreate);
    if (count($parsed) < 3) return;  // error
    $sqltext="DROP ".$parsed[1]." ".$parsed[2];
    $this->RunActionQueryReturnMsg($sqltext,$dropmsg);
    $this->RunActionQuery($sqlcreate);
    //$arName=explode(".",$parsed[2]);
    //$shortname=$arName[count($arName)-1];
    //if (substr($shortname,0,1)!="#")
    //  RunActionQuery("GRANT SELECT ON ".$parsed[2]." TO public");
  }

//********************************************************************************************************
// Returns a sql statement that will enumerate the columns in a table or view
// objname may be a fully qualified object name
//********************************************************************************************************
  function EnumColumns($objname)
  {
    return $this->RunQuery("SHOW COLUMNS FROM ".$objname);
  }


//********************************************************************************************************
// Safely add a column to a table
//********************************************************************************************************
  function AddColumnIfMissing($TableName,$ColumnName,$ColumnType)
  {
    $arTableName=explode(".",$TableName);
    $db=(count($arTableName)==2) ? $arTableName[0] : $this->dbDefault;
    $ShortName=$arTableName[count($arTableName)-1]; // the last element is the unqualified table name
    $sqltext="IF NOT EXISTS (SELECT c.name FROM ".$db.".dbo.syscolumns c, ".$db.".dbo.sysobjects o "."\n".
      "WHERE c.id = o.id AND (o.xtype='U') AND (o.name='".$ShortName."') AND (c.name='".$ColumnName."')) "."\n".
      "ALTER TABLE ".$TableName." ADD ".$ColumnName." ".$ColumnType;
    $this->RunActionQuery($sqltext);
  }


//********************************************************************************************************
// Returns a comma-separated list of column names that make up the primary key
// Returns empty if no primary key has been defined
//********************************************************************************************************
  function PrimaryKey($TableName)
  {
    if ($this->debug) DisplayMsg("Getting primary key for: ".$TableName);
    $rsMain=$this->EnumColumns($TableName);
    if (!$rsMain) return null;
    $result="";
    while($row = mysql_fetch_assoc($rsMain))
    {
      if ($row["Key"]=="PRI") $result.=",".$row["Field"];
    } 
    $this->rsClose($rsMain);
    return substr($result,1);
  }


//********************************************************************************************************
// Rebuilds a SQL select statement that was parsed by ParseSqlSelect
//********************************************************************************************************
  function UnparseSqlSelect($arSelList,$FromClause,$WhereClause,$arGroupBy,$HavingClause,$arOrderBy)
  {
    if (is_array($arSelList)==false || count($arSelList)==0)
    {
      $this->HandleError("ERROR: expected list of columns in UnparseSqlSelect()");
      return null;
    }
    $sqltext="SELECT ".implode(",",$arSelList)." FROM ".$FromClause;
    if (!empty($WhereClause)) $sqltext.=" WHERE ".$WhereClause;
    if (is_array($arGroupBy) && count($arGroupBy)>0) $sqltext.=" GROUP BY ".implode(",",$arGroupBy);
    if (!empty($HavingClause)) $sqltext.=" HAVING ".$HavingClause;
    if (is_array($arOrderBy) && count($arOrderBy)>0) $sqltext.=" ORDER BY ".implode(",",$arOrderBy);
    return $sqltext;
  }


//********************************************************************************************************
// Add a condition to a where or having clause
//********************************************************************************************************
  function AddCondition(&$WhereClause,$NewCondition)
  {
    if (empty($WhereClause))
      $WhereClause="(".$NewCondition.")";
    else
      $WhereClause.=" AND (".$NewCondition.")";
  }


//********************************************************************************************************
// Parse a SQL select statement into its major components
// Does not handle:
// 1) union queries
// 2) select into
// 3) more than one space between "group" and "by", or "order" and "by"
// If distinct is specified, it will be part of the first item in arSelList
//********************************************************************************************************
  function ParseSqlSelect($sqltext,&$arSelList,&$FromClause,&$WhereClause,&$arGroupBy,&$HavingClause,&$arOrderBy)
  {
    $arSelList=array();
    $arGroupBy=array();
    $arOrderBy=array();
    $sqltext=str_replace("\n"," ",$sqltext);
    $sqltext=" ".str_replace("\r"," ",$sqltext)." SELECT "; // SELECT suffix forces last curfield to be saved
    $l=strlen($sqltext);
    $parencnt=0;
    $inquote=false;
    $curfield="";
    $clause="";
    for ($i=0; $i<$l; $i++)
    {
      $ch=substr($sqltext,$i,1);
      if ($inquote)
      {
        if ($ch=="'")
        {
          if (substr($sqltext,$i,2)=="''")
          {
            $curfield.="'";
            $i++;
          }
            else
          {
            $inquote=false;
          }
        }
        $curfield.=$ch;
      }
        else
      if ($ch=="'")
      {
        $inquote=true;
        $curfield.=$ch;
      }
        else
      if ($ch=="(")
      {
        $parencnt++;
        $curfield.=$ch;
      }
        else
      if ($ch==")")
      {
        if ($parencnt==0) return false;  // sql statement has a syntax error
        $parencnt--;
        $curfield.=$ch;
      }
        else
      if ($parencnt>0)
      {
        $curfield.=$ch;
      }
        else
      if ($ch==",")
      {
        switch ($clause)
        {
          case "SELECT":
            $this->SetParseField($arSelList,$curfield);
            break;
          case "GROUP BY":
            $this->SetParseField($arGroupBy,$curfield);
            break;
          case "ORDER BY":
            $this->SetParseField($arOrderBy,$curfield);
            break;
          default:
            $curfield==$curfield.$ch;
            break;
        }
      }
        else
      if ($ch==" ")
      {
        $j=strpos($sqltext," ",$i+1);
        if ($j===false)
        {
          $curfield.=$ch;
        }
          else
        {
          if (strtoupper(substr($sqltext,$j+1,3))=="BY ") $j+=3;
          $nexttoken=strtoupper(substr($sqltext,$i+1,$j-$i-1));
          switch ($nexttoken)
          {
            case "SELECT":
            case "INTO":
            case "FROM":
            case "WHERE":
            case "GROUP BY":
            case "HAVING":
            case "ORDER BY":
              switch ($clause)
              {
                case "SELECT":
                  $this->SetParseField($arSelList,$curfield);
                  break;
                case "FROM":
                  $this->SetParseField($FromClause,$curfield);
                  break;
                case "WHERE":
                  $this->SetParseField($WhereClause,$curfield);
                  break;
                case "GROUP BY":
                  $this->SetParseField($arGroupBy,$curfield);
                  break;
                case "HAVING":
                  $this->SetParseField($HavingClause,$curfield);
                  break;
                case "ORDER BY":
                  $this->SetParseField($arOrderBy,$curfield);
                  break;
              }
              $clause=$nexttoken;
              $i=$j;
              break;
            default:
              if ($curfield!="")
              {
                $curfield.=$ch;
              }
              break;
          }
        }
      }
        else
      {
        $curfield.=$ch;
      }
    }
    return true;
  }


  function SetParseField(&$f,&$newvalue)
  {
    if (is_array($f))
      $f[count($f)]=$newvalue;
    else
      $f=$newvalue;
    $newvalue="";
  }

}

?>

