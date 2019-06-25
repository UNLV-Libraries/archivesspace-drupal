<?php

namespace Drupal\archivesspace;

use Drupal\rdf\CommonDataConverter;

/**
 * {@inheritdoc}
 */
class ArchivesSpaceDataConverter extends CommonDataConverter {

  /**
   * Converts an ArchivesSpace Date into an ISO 8601 time interval format value.
   *
   * See: https://en.wikipedia.org/wiki/ISO_8601#Time_intervals.
   *
   * @param array $data
   *   The array containing the 'value' element.
   *
   * @return string
   *   The ISO 8601 time interval format value.
   */
  public static function iso8601Interval(array $data) {
    if (empty($data['begin'])) {
      if (empty($data['end'])) {
        return $data['expression'];
      }
      return implode('/', ['..', $data['end']]);
    }
    else {
      if (empty($data['end'])) {
        return $data['begin'];
      }
      return implode('/', [$data['begin'], $data['end']]);
    }
  }

}
