<?php
class StringFilter
{
    public static function cleanInput($str)
    {
        return trim(htmlentities($str,ENT_QUOTES,"UTF-8"));
    }

    public static function hash($str)
    {
        return hash("sha256", $str);
    }

    public static function isempty($str)
    {
        return empty($str)&&$str!="0";
    }

    public static function textareaInput($input,$keys=null)
    {
        if(is_array($input)) {
            if(is_array($keys)) {
                $result=array();
                foreach($keys as $key) {
                    $result[]=textareaInput($input[$key]);
                }
                return $result;
            }
            else if(!is_null($keys)) {
                return textareaInput($input[$keys]);
            }
        }
        else if(is_string($input)) {
            return str_replace("\n","<br>",$input);
        }
    }

    public static function textareaOutput($input,$keys=null)
    {
        if(is_array($input)) {
            if(is_array($keys)) {
                $result=array();
                foreach($keys as $key) {
                    $result[]=textareaOutput($input[$key]);
                }
                return $result;
            }
            else if(!is_null($keys)) {
                return textareaOutput($input[$keys]);
            }
        }
        else if(is_string($input)) {
            return str_replace("<br>","\n",$input);
        }
    }
}
