<?php

class Helper_Array {

  /**
   * presents a two-dimensIONal array into a table
   *
   * converts:
   * {
   *   "name" : "cris",
   *   "age" : 18,
   *   "gender" : "male"
   * }
   *
   * into:
   * <table class="tbl-data">
   * 	<tr><td class="row-label">name</td><td class="row-value">cris</td></tr>
   * 	<tr><td class="row-label">age</td><td class="row-value">18</td></tr>
   * 	<tr><td class="row-label">gender</td><td class="row-value">male</td></tr>
   * </table>
   *
   * var $callbacks:
   * - "field" => "callback"
   * - do some additional functions to field using predefined callback
   * - there must be an existing function "callback"
   * - eg: "name" => "Util::format_name"
   * - process: Util::format_name("name")
   *
   * var $options: (classes, defaults)
   * - table: "tbl-data"
   * - label: "row-label"
   * - value: "row-value"
   *
   * var $omit:
   * - fields to skip
   */
  static function tablify_array($arr, $callbacks = array(), $options = array(), $omit = array()) {
    $final_options = array(
        "table" => empty($options["table"]) ? "tbl-data" : $options["table"]
        , "label" => empty($options["label"]) ? "row-label" : $options["label"]
        , "value" => empty($options["value"]) ? "row-value" : $options["value"]
    );

    if (count($arr) == 0)
      return 1;

    $tablified = "";
    $tablified .= "<table class='{$final_options["table"]}'>";

    foreach ($arr as $key => $value) {
      $result = array_search($key, $omit);

      if (array_search($key, $omit) !== false)
        continue;

      $tablified .= "<tr>";

      if (count($callbacks) > 0) {
        if (!empty($callbacks[$key])) {
          if (!is_callable($callbacks[$key]))
            return "error: function {$callbacks[$key]} is not callable.";
          eval("\$value = $callbacks[$key](\"" . htmlspecialchars($value) . "\");");
        }
      }

      $tablified .= "<td class='{$final_options["label"]}'>";
      $tablified .= $key;
      $tablified .= "</td>";

      $tablified .= "<td class='{$final_options["value"]}'>";

      if (is_array($value)) {
        $tablified .= self::tablify_array($value, $callbacks, $options, $omit);
      } else {
        $tablified .= $value;
      }

      $tablified .= "</td>";

      $tablified .= "</tr>";
    }

    $tablified .= "</table>";

    return $tablified;
  }

}