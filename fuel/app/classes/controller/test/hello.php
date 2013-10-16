<?php
class Controller_Test_Hello extends Controller_Test
{
  public function action_index(){
    return Response::forge("looks like <h1><blink>it</blink> works!</h1>");
  }

  public function action_json(){
  	$value = array(
  		"frameworks" => array(
  				array(
  					"name" => "Kohana"
  					,"votes" => 1
				),
				array(
  					"name" => "Laravel"
  					,"votes" => 2
				),
				array(
  					"name" => "Codeigniter"
  					,"votes" => 11
				),
				array(
  					"name" => "Fuel"
  					,"votes" => 112
				),
				array(
  					"name" => "Symfony"
  					,"votes" => 12
				)
  			)
  		);

  	return json_encode($value);
  }

  public function action_ajaxform(){
    return $this->disp(View::forge("test/ajaxform"));
  }

  public function post_ajaxpost(){
    return json_encode(array(
      "timestamp" => date("Y-m-d H:i:s"), 
      "name" => Input::post("name")
      ));
  }
}
