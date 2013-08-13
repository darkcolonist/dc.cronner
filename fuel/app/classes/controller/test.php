<?php
abstract class Controller_Test extends Controller_Main
{
	var $title = null;
	var $module = null;
  var $disp = null;

	function before(){
    $return = parent::before();
		Config::load("application", true);
	
		$this->title = Config::get("application.title");
		$this->module = "untitled";
    
		$this->disp = View::forge("test/template");
    
    return $return;
	}

  function reset_disp($template){
		$this->disp = View::forge($template);
  }
	
	function disp($data){
		$this->disp->set("content", $data, false);
		$this->disp->set("title", $this->title);
		$this->disp->set("module", $this->module);
		
		$this->disp = Response::forge($this->disp);
	
		return $this->disp;
	}

  public function action_index(){
		return $this->disp("looks like this test case has yet to be implemented, should you be MAD?");
	}

  protected function add_css($css){
    $append_css = isset($this->disp->css) ? $this->disp->css : "";

    $append_css .= Fuel\Core\Asset::css($css);


    $this->disp->set_safe("css", $append_css);
  }

  protected function add_js($js){
    $append_js = isset($this->disp->js) ? $this->disp->js : "";

    $append_js .= Fuel\Core\Asset::js($js);

    $this->disp->set_safe("js", $append_js);
  }
  
  protected function add_js_scripts($script){
    $append_script = isset($this->disp->js_scripts) ? $this->disp->js_scripts : "";

    $append_script .= $script;

    $this->disp->set_safe("js_scripts", $append_script);
  }
}