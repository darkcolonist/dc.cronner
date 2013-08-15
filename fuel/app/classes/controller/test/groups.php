<?php
class Controller_Test_Groups extends Controller_Test
{
	function before()
	{
		parent::before();

    $this->module = "groups";
	}

  function action_view($group){
    $group = Doctrine_Query::create()
            ->from("Groups g")
            ->leftJoin("g.Cronnables c")
            ->where("g.name = ?", $group)
            ->execute()
            ->getFirst();

    $disp = "";

    if($group == null){
      $disp = "group not found.";
    }else{
      $disp = Helper_Array::tablify_array($group->toArray());
    }

    return $this->disp($disp);
  }
}