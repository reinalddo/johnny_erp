<?
/**
*
*  Funxtion to parse errors in a Xml Validatio 
*/

    function libxml_display_error($error, $pSl="\n")
    {
        $return = $pSl;
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
            $return .= ' *   Warning ' . $error->code .' : ';
            break;
            case LIBXML_ERR_ERROR:
            $return .= ' **  Error ' . $error->code .' : ';
            break;
            case LIBXML_ERR_FATAL:
            $return .= ' *** Fatal Error ' . $error->code .' : ';
            break;
        }
        $return .= trim($error->message);
        if ($error->file) {
            $return .=    ' in ' . $error->file . ' ';
        }
        $return .= ' on line ' . $error->line . $pSl;
        return $return;
    }

    function libxml_get_all_errors($pLs="\n") {
        $errors = libxml_get_errors();
        $errMsg = '';
        foreach ($errors as $error) {
            $errMsg .= libxml_display_error($error, $pLs);
        }
        libxml_clear_errors();
        return $errMsg;
    }
    function libxml_get_error_array() {
        $errors = libxml_get_errors();
        $alErr = array();
        foreach ($errors as $error) {
            $alErr[] = $error;
        }
        libxml_clear_errors();
        return $alErr;
    }
?>