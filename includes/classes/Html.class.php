<?php

class Html {

    public static $js_filename = array();
    public static $css_filename = array();

    function __construct() {
        
    }

    public static function AddJsFile($filename) {
        array_push(self::$js_filename, $filename);
    }

    public static function AddCssFile($filename) {
        array_push(self::$css_filename, $filename);
    }

    public static function AddJavaScript($filename, $filetype) {
        if ($filetype == "component")
            $filenames = SITEURL . "/site/component/com_" . $filename;
        else
            $filenames = SITEURL . "/site/module/mod_" . $filename;

        array_push(self::$js_filename, $filenames);
    }

    public static function AddStylesheet($filename, $filetype) {
        if ($filetype == "component")
            $filenames = SITEURL . "/site/component/com_" . $filename;
        else
            $filenames = SITEURL . "/site/module/mod_" . $filename;

        array_push(self::$css_filename, $filenames);
    }

    public static function Js() {
        $JSdata = "";
        if (isset(self::$js_filename)) {
            foreach (self::$js_filename as $kk) {
                $JSdata.= '<script language="javascript" src="' . $kk . '"></script>';
            }
        }
        return $JSdata;
    }

    public static function Css() {

        $cssdata = "";
        if (isset(self::$css_filename)) {
            foreach (self::$css_filename as $kk) {
                $cssdata.='<link href="' . $kk . '" rel="stylesheet" type="text/css" />';
            }
        }
        return $cssdata;
    }

    public static function CovertSingleArray($arraydata = array(), $keys = "", $itfval = "") {
        $results = array();
        foreach ($arraydata as $itfv)
            $results[$itfv[$keys]] = $itfv[$itfval];

        return $results;
    }

    public static function CovertArrayIndex($arraydata = array(), $keys = "") {
        $results = array();
        foreach ($arraydata as $itfv)
            $results[$itfv[$keys]] = $itfv;

        return $results;
    }

    public static function ComboBox($name = "itfcombo", $arraydata = array(), $dataselecte = "0", $optional = array(), $defaultval = "") {
        $optionaldata = "";
        foreach ($optional as $itfk => $itfv)
            $optionaldata.=$itfk . "=\"" . $itfv . "\" ";

        $htmldata = '<select name ="' . $name . '" id ="' . $name . '" ' . $optionaldata . ' >';
        if (!empty($defaultval))
            $htmldata.='<option value="">' . $defaultval . '</option>';
        foreach ($arraydata as $itfk => $itfv) {
            $selected = ($dataselecte == $itfk) ? ' selected="selected" ' : "";
            $htmldata.='<option value="' . $itfk . '" ' . $selected . ' >' . $itfv . '</option>';
        }

        $htmldata.='</select>';

        return $htmldata;
    }

    public static function seoUrl($string) {
        $string = strtolower($string);
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        $string = preg_replace("/[\s-]+/", " ", $string);
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }

}

?>