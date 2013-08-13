<?php
abstract class Controller_Main extends Controller
{
  private $_doctrine_profiler;

	function before(){
    $return = parent::before();

    // check if we should setup profiler
    if(Config::get("profiling") && Config::get("db.default.profiling")){
      $this->_doctrine_profiler = new Doctrine_Connection_Profiler();
      $conn = Doctrine_Manager::connection();
      $conn->setListener($this->_doctrine_profiler);
    }

    return $return;
	}

//  public function after($response)
//  {
//      $response = parent::after($response); // not needed if you create your own response object
//
//      die('bullshitman');
//
//      return $response; // make sure after() returns the response object
//  }

  function after($response){
    $return = parent::after($response);

    // check if we should setup profiler
    if(Config::get("profiling") && Config::get("db.default.profiling")){
      foreach ($this->_doctrine_profiler as $event) {
        $ignore = array("prepare", "fetch");

        if(!in_array($event->getName(), $ignore)){
          $db_params = $event->getParams();
          $db_params_str = "";

          if(!empty($db_params) && is_array($db_params)){
            $db_params_str = " : ".json_encode($db_params);
          }

          Profiler::start(Config::get("db.default.connection.dsn"), $event->getQuery() . "\n[ "
                  . $event->getName()."{$db_params_str} ]");
//          Profiler::$query["time"] = number_format($event->getElapsedSecs(), 4);
//          Profiler::stop("text", number_format($event->getElapsedSecs(), 5));
          Profiler::stop("text", number_format($event->getElapsedSecs(), 5));
        }

//        $doctrine_dbg .= "<tr>";
//
//        $time += $event->getElapsedSecs();
//        $doctrine_dbg .= "<td>" . $event->getName() . "</td>";
//        if ($event->getName() == "execute") {
//          $doctrine_dbg .= "<td><strong style='font-size: 150%; color: red; text-decoration: underline;'>" . sprintf("%f", $event->getElapsedSecs()) . "</strong></td>";
//          $num_executes += 1;
//        } else {
//          $doctrine_dbg .= "<td>" . sprintf("%f", $event->getElapsedSecs()) . "</td>";
//        }
//        $doctrine_dbg .= "<td><textarea rows='5' cols='100' style='font-size: 100%'>" . $event->getQuery() . "</textarea></td>";
//        $params = $event->getParams();
//        if (!empty($params)) {
//          $doctrine_dbg .= "<td>" . print_r($params, true) . "</td>";
//        } else {
//          $doctrine_dbg .= "<td>n/a</td>";
//        }
//
//        $doctrine_dbg .= "</tr>";
      }
    }

    return $return;
  }
}