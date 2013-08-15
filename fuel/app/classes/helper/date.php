<?php

class Helper_Date {

  /**
   * date range validator
   * 1. start must be less than end (2013-01-01 00:00:00 < 2013-01-02 00:00:00)
   * 2. the duration between start and end must be at most 2592000 seconds (30 days)
   *
   * @param string $start (yyyy-mm-dd hh:mm:ss)
   * @param string $end   (yyyy-mm-dd hh:mm:ss)
   * @param assoc $rules override any preset rule
   * @param bool $strict if true, it will thrown an exception prior to the validation failure
   */
  static function date_range_validator($start, $end, $rules = array(), $strict = false) {
    $current_validation = true;

    $preset_rules = array(
        "duration" => 2592000
    );

    $timestart = strtotime($start);
    $timeend = strtotime($end);
    $computed = $timeend - $timestart;

    if ($current_validation && $computed > $preset_rules["duration"]) {
      $current_validation = false;
      if ($strict)
        throw new Exception("date duration greater than " . $preset_rules["duration"]);
    }

    if ($current_validation && $computed < 0) {
      $current_validation = false;
      if ($strict)
        throw new Exception("end date is less than start date");
    }

    return $current_validation;
  }

  /**
   * @param string $date_string
   * @param string $timezone_string eg:"Asia/Manila"
   * @param string $convert_to_timezone eg:"Asia/Manila"
   * @return \DateTime
   */
  static function datetime($date_string, $timezone_string = null, $convert_to_timezone = null) {
    if (is_null($timezone_string))
      $timezone_string = date_default_timezone_get();

    $timezone = new DateTimeZone($timezone_string);
    $datetime = new DateTime($date_string, $timezone);

    if (!is_null($convert_to_timezone)) {
      $datetime->setTimezone(new DateTimeZone($convert_to_timezone));
    }

    return $datetime;
  }

  static function microtime() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
  }

  protected static $_timer_start_time = null;
  protected static $_timer_started = false;

  /**
   * returns current timelapse and clears timers
   * @return real
   */
  static function timer_end() {
    if (self::$_timer_start_time == 0)
      return 0.00;

    $total = self::microtime() - self::$_timer_start_time;

    self::$_timer_start_time = null;
    self::$_timer_started = false;

    return number_format($total, 2);
  }

  /**
   * start the timer
   */
  static function timer_start() {
    self::$_timer_start_time = self::microtime();
    self::$_timer_started = true;
  }

  /**
   * returns current timelapse
   * @return real
   */
  static function timer_current() {
    if (self::$_timer_start_time == 0)
      return 0.00;

    $total = self::microtime() - self::$_timer_start_time;

    return number_format($total, 2);
  }

}