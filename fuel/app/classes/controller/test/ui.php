<?php
class Controller_Test_UI extends Controller_Test
{
  public function before() {
    parent::before();

    $this->module = "ux/ui";
  }

  public function action_index(){
    $disp = Fuel\Core\View::forge("test/ui/simple");

    $this->add_js("jquery-1.9.1.min.js");
    $this->add_js("jquery-ui-1.10.3.all.min.js");
    $this->add_js("jquery.layout.1.3.0.min.js");
    
    $this->add_js("fuse.init.js");

    return $this->disp($disp);
  }

  public function action_base(){
    $this->reset_disp("template_html");
    $disp = Fuel\Core\View::forge("test/ui/complete");

    $this->add_css("jquery.layout.default.1.3.0.css");
    $this->add_css("jquery.layout.custom-1.1.3.0.css");
    $this->add_css("test.base.css");

    $this->add_js("jquery-1.9.1.min.js");
    $this->add_js("jquery-ui-1.10.3.all.min.js");
    $this->add_js("jquery.layout.1.3.0.min.js");

    $this->add_js("fuse.init.js");

    $options = array(
      "defaults" => array(
        "resizable" => false,
        "closable" => false,
      ), "north" => array(
        "size" => 75
      ), "west" => array(
        "size" => 200,
        "closable" => true
      ), "east" => array(
        "size" => 150,
        "closable" => true
      ), "south" => array(
        "size" => 50
      ), "center" => array(
        "childOptions" => array(
          "defaults" => array(
            "resizable" => false
          ),
          "center" => array(
            "childOptions" => array(
              "defaults" => array(
                "resizable" => true
              )
            )
          )
        )
      ), "stateManagement" => array(
        "enabled" => true,
        "includeChildren" => false,
        "stateKeys" => "west.isClosed,east.isClosed"
      ));
    $options_json = json_encode($options);

    $this->add_js_scripts("fuse.initBodyLayout({$options_json});");

    return $this->disp($disp);
  }

  public function action_advanced(){
    $disp = Fuel\Core\View::forge("test/ui/advanced");

    $this->add_js("jquery-1.9.1.min.js");
    $this->add_js("jquery-ui-1.10.3.all.min.js");
    $this->add_js("jquery.layout.1.3.0.min.js");

    $this->add_js("fuse.init.js");

    return $this->disp($disp);
  }
}