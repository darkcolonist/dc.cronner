<?php

class Helper_String {
  /**
   * truncates a string based on length and replaces middle with truncator
   */
  static function truncate_mid($string, $length, $truncator = "&hellip;"){
    $textLength = strlen($string);

    if($textLength <= $length)
      return $string;
    
    $result = substr_replace($string, $truncator, $length/2, $textLength-$length);
    
    return $result;
  }
}