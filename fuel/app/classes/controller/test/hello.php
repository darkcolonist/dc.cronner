<?php
class Controller_Test_Hello extends Controller_Test
{
  public function action_index(){
    return Response::forge("looks like <h1><blink>it</blink> works!</h1>");
  }
}
