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

  function post_add(){
    $data = array(
        "status" => 0,
        "message" => "added new!"
    );

    $validation = Fuel\Core\Validation::forge();

    $validation->add_field("url", "URL", "valid_url");
    $validation->add_field("interval", "Interval", "numeric_between[1,1000]");

    if($validation->run()){
      $group = Doctrine::getTable("Groups")->findOneBy("name", Fuel\Core\Input::post("group_name"));

      if($group != null){
        $cron = new Cronnables;
        $cron->Groups = $group;
        $cron->url = Fuel\Core\Input::post("url");
        $cron->interval = Fuel\Core\Input::post("interval");
        $cron->save();

        $data["cronnable"] = $cron->toArray();
      }else{
        $data = array(
          "status" => 1,
          "message" => "no group found!"
        );
      }
    }else{
      $data = array(
        "status" => 1,
        "message" => "error in the inputs!"
      );
    }

    return $this->response($data);
  }
}