<?php
class Controller_Api_Cronnables extends Fuel\Core\Controller_Rest
{
  function get_execute($id){
    $cronnable = Doctrine::getTable("Cronnables")->find($id);

    $data = array(
        "cronnable" => $cronnable->toArray()
    );

    $data["response"] = $cronnable->execute();
    $data["timestamp"] = date("Y-m-d H:i:s");
    $data["duration"] = Cronnables::$duration;

    return $this->response($data);
  }
}