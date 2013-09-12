<?php
class Controller_Api_Cronnables extends Fuel\Core\Controller_Rest
{
  function get_execute($id){
    $cronnable = Doctrine::getTable("Cronnables")->find($id);

    if($cronnable){
      $data = array(
          "cronnable" => $cronnable->toFormattedArray()
      );

      $data["response"] = $cronnable->execute();
      $data["timestamp"] = date("Y-m-d H:i:s");
      $data["duration"] = Cronnables::$duration;
      $data["log"] = Cronnables::$last_log->toArray();
    }else{
      $data = array("cronnable not found!");
    }

    return $this->response($data);
  }

  function get_mute($id){
    $data = array(
        "status" => 0,
        "method" => "mute",
        "message" => "muted!"
    );
    $cronnable = Doctrine::getTable("Cronnables")->find($id);

    if($cronnable){
      $cronnable->muted = $cronnable->muted == 0 ? 1 : 0;
      $cronnable->save();

      $data["cronnable"] = $cronnable->toArray();
    }else{
      $data = array(
          "status" => 1,
          "message" => "cronnable not found!"
      );
    }

    return $this->response ($data);
  }

  function get_delete($id){
    $data = array(
        "status" => 0,
        "method" => "delete",
        "message" => "deleted!"
    );

    $cronnable = Doctrine::getTable("Cronnables")->find($id);

    if($cronnable){
      $data["cronnable"] = $cronnable->toArray();

      // delete logs first
      Doctrine_Query::create()
              ->delete("CronnableLogs")
              ->where("cronnable_id = ?", $cronnable->id)
              ->execute();

      // delete cronnable
      $cronnable->delete();
    }else{
      $data = array(
          "status" => 1,
          "message" => "cronnable not found!"
      );
    }

    return $this->response ($data);
  }

  function post_add(){
    $data = array(
        "status" => 0,
        "message" => "added new!"
    );

    $validation = Fuel\Core\Validation::forge();

    $validation->add_field("url", "URL", "valid_url");
    $validation->add_field("interval", "Interval", "numeric_between[1,10000]");

    if($validation->run()){
      $group = Doctrine::getTable("Groups")->findOneBy("name", Fuel\Core\Input::post("group_name"));

      if($group != null){
        $cron = new Cronnables;
        $cron->Groups = $group;
        $cron->url = Fuel\Core\Input::post("url");
        $cron->interval = Fuel\Core\Input::post("interval");
        $cron->save();

        $data["cronnable"] = $cron->toFormattedArray();
      }else{
        $data = array(
          "status" => 1,
          "message" => "no group found!"
        );
      }
    }else{
      $data = array(
        "status" => 1,
        "message" => rtrim(strip_tags(str_replace("</li>", "</li>\n", $validation->show_errors())), "\n")
      );
    }

    return $this->response($data);
  }
}