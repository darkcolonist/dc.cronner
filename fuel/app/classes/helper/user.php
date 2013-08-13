<?php
class Helper_User
{
  /**
   * @return Users
   */
  static function get_logged_in(){
    $id = \Auth\Auth::get_user_id();

    if($id != null || count($id) < 2)
      return false;
    else{
//      $user = Doctrine_Query::create()
//              ->select("username")
//              ->from("Users")
//              ->where("id = 1")
//              ->execute()
//              ->getFirst();

      $user = Doctrine::getTable("Users")->find($id[1]);
      return $user;
    }
  }
}