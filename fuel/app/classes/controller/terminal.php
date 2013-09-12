<?php
class Controller_Terminal extends Fuel\Core\Controller_Template{
  public function before() {
    $this->template = "terminal-ux";

    parent::before();

    $this->template->title = "Terminal";
    $this->template->module = "Execution";

    $this->template->css = "";
    $this->template->css .= \Fuel\Core\Asset::css("bootstrap.css");
    $this->template->css .= \Fuel\Core\Asset::css("jquery.toolbars.css");
    $this->template->css .= \Fuel\Core\Asset::css("bootstrap.icons.css");
    $this->template->css .= \Fuel\Core\Asset::css("jquery.layout.default.1.3.0.css");
    $this->template->css .= \Fuel\Core\Asset::css("jquery.layout.custom-1.1.3.0.css");
    $this->template->css .= \Fuel\Core\Asset::css("terminal.css");

    $this->template->js = "";
    $this->template->js .= \Fuel\Core\Asset::js("jquery-1.9.1.min.js");
    $this->template->js .= \Fuel\Core\Asset::js("moment.min.js");
    $this->template->js .= \Fuel\Core\Asset::js("jquery.toolbar.min.js");
    $this->template->js .= \Fuel\Core\Asset::js("jquery-ui-1.10.3.all.min.js");
    $this->template->js .= \Fuel\Core\Asset::js("jquery.layout.1.3.0.min.js");
    $this->template->js .= \Fuel\Core\Asset::js("cronner.package.js");
  }

  public function action_execute($group_name = null){
    $group = Doctrine::getTable("Groups")->findOneBy("name", $group_name);

    if($group == null || $group_name == null){
      Fuel\Core\Response::redirect("terminal/list");
    }else{
      // lazy load cronnables in group
      $group->Cronnables;

      // render template
      $this->template->group = $group;
    }
  }
}