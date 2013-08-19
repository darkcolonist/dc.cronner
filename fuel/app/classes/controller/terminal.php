<?php
class Controller_Terminal extends Fuel\Core\Controller_Template{
  public function before() {
    $this->template = "terminal";

    parent::before();

    $this->template->title = "Terminal";
    $this->template->module = "Execution";

    $this->template->css = "";
    $this->template->css .= \Fuel\Core\Asset::css("bootstrap.css");
    $this->template->css .= \Fuel\Core\Asset::css("terminal.css");

    $this->template->js = "";
    $this->template->js .= \Fuel\Core\Asset::js("jquery-1.9.1.min.js");
    $this->template->js .= \Fuel\Core\Asset::js("moment.min.js");
  }

  public function action_execute($group_name){
    $group = Doctrine::getTable("Groups")->findOneBy("name", $group_name);

    if($group == null){
      die("group cannot be found!");
    }else{
      $this->template->group = $group;
    }
  }
}