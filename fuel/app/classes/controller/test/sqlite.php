<?php
class Controller_Test_Sqlite extends Controller_Test
{
	function before()
	{
		parent::before();

    $this->module = "sqlite";
	}

  function action_index(){
    return $this->disp("lol");
  }

  function action_setup(){
    $options = array(

		);

		Doctrine::generateModelsFromDb(
			APPPATH.'classes/model/doctrine',
			array('doctrine'),
			$options
		);

		return $this->disp("attempting to setup and configure doctrine models!");
  }

  function action_test1(){
    $group = Doctrine::getTable("Groups")->findOneBy("name", "loop");
    if($group == null){
      $group = new Groups;
      $group->name = "loop";
      $group->save();
    }
    
    $cronnable = new Cronnables;
    $cronnable->Groups = $group;
    $cronnable->interval = 5;
    $cronnable->url = "http://localhost";
    $cronnable->save();
    
    echo "saved!";
  }
  
  function action_insert(){
    $people = 1000;
    \Fuel\Core\Config::load("people", true);

    $users = new Doctrine_Collection("Users");

    $password = Auth::hash_password("dev");

    for($i = 0 ; $i < $people ; $i++){
      $fn_rand = rand(0, 49);
      $ln_rand = rand(0, 49);

      $genders = array(
          "male", "female"
      );

      $gender = $genders[rand(0,1)];
      
      $user = new Users;
      $user->first_name = Config::get("people.firstname.{$fn_rand}");
      $user->last_name = Config::get("people.lastname.{$ln_rand}");
      $user->gender = $gender;
      $user->birthday =
              rand(1980, 2005)
              . "-" . str_pad(rand(1, 12), 2, 0, STR_PAD_LEFT)
              . "-" . str_pad(rand(1, 28), 2, 0, STR_PAD_LEFT)
              . " 00:00:00";

      $hash = "u". substr(md5(uniqid()), 0, 6);

      $user->username = $hash;
      $user->email = "{$hash}@example.com";
      $user->password = $password;
      $user->created_at = strtotime("now");

      $users->add($user);
    }

    $users->save();

    return "added {$people} people.";
  }

  function action_fetch($id = 1){
    $user = Doctrine::getTable("Users")->find($id);

    Profiler::console($user->toArray());

    return "loaded {$user->full_name()}";
  }

  function action_login($username, $password){
    $result = \Auth\Auth::login($username, $password);

    Profiler::console($result);

    return $result ? "logged in" : "invalid credentials";
  }

  function action_get_logged_in(){
    $user = Helper_User::get_logged_in();

    if($user != null)
      $user = $user->toArray();

    Profiler::console($user);

    return $user ? $user : "no user logged in.";
  }

  function action_logout(){
    \Auth\Auth::logout();

    return "logged out!";
  }
}