<?php
class Controller_Test_Cronnables extends Controller_Test
{
	function before()
	{
		parent::before();

    $this->module = "cronnables";
	}

  function action_execute($id){
    $cronnable = Doctrine::getTable("Cronnables")->find($id);

    $disp = "";

    if($cronnable == null){
      $disp = "cronnable not found.";
    }else{
      $disp = $cronnable->execute();
    }

    return $this->disp($disp);
  }
}