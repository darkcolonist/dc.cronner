<?php
class Controller_List extends Controller_Test
{
	function before()
	{
		parent::before();
    
    $this->add_js("jquery-1.9.1.min.js");
    $this->add_js("cronner.list.js");
    
    $this->module = "cronnable list";
	}

  function action_index(){
    $groups = Doctrine_Query::create()
            ->from("Groups")
            ->orderBy("added", "DESC")
            ->execute();

    $disp = \Fuel\Core\View::forge("list", array(
        "groups" => $groups
    ));
    
    return $this->disp($disp);
  }
  
  function get_delete($name){
    $group = Doctrine::getTable("Groups")->findOneBy("name", $name);
      
    if($group != null)
      $group->delete();
      
    Fuel\Core\Session::set_flash("success_message", "group deleted.");
    Fuel\Core\Response::redirect("list");
  }
  
  function post_new(){
    $name = \Fuel\Core\Input::post("name");
    
    if(!empty($name)){
      $group = Doctrine::getTable("Groups")->findOneBy("name", \Fuel\Core\Input::post("name"));
      
      if($group != null){
        Fuel\Core\Session::set_flash("error_message", "group exists!");
        Fuel\Core\Response::redirect("list");
      } 
      
      $group = new Groups;
      $group->name = $name;
      $group->save();
      
      Fuel\Core\Response::redirect("terminal/execute/".\Fuel\Core\Input::post("name"));
    }else{
      Fuel\Core\Session::set_flash("error_message", "empty name!");
      Fuel\Core\Response::redirect("list");
    }
  }
}