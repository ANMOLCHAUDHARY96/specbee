<?php

namespace Drupal\insd_specbee\Services;

/**
 * Coment.
 */
class Timezone {

  /**
   * Function to get date time by zone, timestamp, format.
   *
   * @param string $format
   *   Date time format.
   * @param string $timezone
   *   Time zone.
   * @param [type]|null $timestamp
   *   Time stamp.
   *
   * @return mixed
   *   Return date time.
   */
  public function getDateTimeByTimeZone($format, $timezone, $timestamp = NULL) {
    // Date time zone.
    date_default_timezone_set($timezone);
    if ($timestamp) {
      // Convert time stamp into suggested format and return.
      return date($format, $timestamp);
    }
    else {
      // Return current date time by format.
      return date($format);
    }
  }

}
