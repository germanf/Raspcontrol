<?php

namespace lib;

class CPU {
  
  public static function cpu() {

    $result = array();

    $getLoad = sys_getloadavg();
    $cpuCurFreq = round(file_get_contents("/sys/devices/system/cpu/cpu0/cpufreq/scaling_cur_freq") / 1000) . "MHz";
    $cpuMinFreq = round(file_get_contents("/sys/devices/system/cpu/cpu0/cpufreq/scaling_min_freq") / 1000) . "MHz";
    $cpuMaxFreq = round(file_get_contents("/sys/devices/system/cpu/cpu0/cpufreq/scaling_max_freq") / 1000) . "MHz";
    $cpuFreqGovernor = file_get_contents("/sys/devices/system/cpu/cpu0/cpufreq/scaling_governor");

    if ($getLoad[0] > 1)
      $result['alert'] = 'danger';
    else
      $result['alert'] = 'success';
      
    $result['loads'] = $getLoad[0];
    $result['loads5'] = $getLoad[1];
    $result['loads15'] = $getLoad[2];
    $result['current'] = $cpuCurFreq;
    $result['min'] = $cpuMinFreq;
    $result['max'] = $cpuMaxFreq;
    $result['governor'] = substr($cpuFreqGovernor, 0, -1);

    return $result;
  }

  private static $MaxTemp = 85;

  public static function heat() {

    $result = array();

    $fh = fopen("/sys/class/thermal/thermal_zone0/temp", 'r');
    $currenttemp = fgets($fh);
    fclose($fh);

    $result['degrees'] = round($currenttemp / 1000);
    $result['percentage'] = round($result['degrees'] / self::$MaxTemp * 100);
    
    if ($result['percentage'] >= '80')
      $result['alert'] = 'danger';
    elseif ($result['percentage'] >= '60')
      $result['alert'] = 'warning';
    else
      $result['alert'] = 'success';

    return $result;
  }

}

?>