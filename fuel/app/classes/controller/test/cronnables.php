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

  function action_flush(){
    $cronnables = Doctrine::getTable("Cronnables")->findAll();
    foreach($cronnables as $cronnable){
      CronnableLogs::flush_old($cronnable->id, $cronnable->log_limit);
    }

    return $this->disp("flushed old..");
  }
}