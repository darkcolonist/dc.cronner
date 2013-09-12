<?php
class Controller_List extends Controller_Test
{
	function before()
	{
		parent::before();

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
}