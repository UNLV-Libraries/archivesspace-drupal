<?php

namespace Drupal\archivesspace\Plugin\migrate\process;

use Drupal\migrate\MigrateException;
use Drupal\migrate_plus\Plugin\migrate\process\SkipOnValue;

/**
 * If the source evaluates to a configured value, skip processing or whole row.
 *
 * @MigrateProcessPlugin(
 *   id = "skip_on_regex"
 * )
 *
 * Available configuration keys:
 * - value: An single regex or array of regex against which the source value
 *   should be compared.
 * - not_equals: (optional) If set, skipping occurs when values are not equal.
 * - method: What to do if the input value equals to value given in
 *   configuration key value. Possible values:
 *   - row: Skips the entire row.
 *   - process: Prevents further processing of the input property
 *
 * Examples:
 *
 * Example usage with minimal configuration:
 * @code
 *   type:
 *     plugin: skip_on_regex
 *     source: some_field
 *     method: process
 *     value: '/^skipable prefix/'
 * @endcode
 *
 * The above example will skip further processing of the input property if
 * the content_type source field begins with "skipable prefix".
 *
 * Example usage with full configuration:
 * @code
 *   type:
 *     plugin: skip_on_regex
 *     not_equals: true
 *     source: content_type
 *     method: row
 *     value:
 *       - '/articles?/'
 *       - '/testimonial/'
 * @endcode
 *
 * The above example will skip processing any row for which the source row's
 * content type field does not contain "article", "articles", or "testimonial".
 */
class SkipOnRegex extends SkipOnValue {

  /**
   * Compare values to see if they are equal.
   *
   * @param mixed $value
   *   Actual value.
   * @param mixed $skipValue
   *   Value to compare against.
   * @param bool $equal
   *   Compare as equal or not equal.
   *
   * @return bool
   *   True if the compare successfully, FALSE otherwise.
   */
  protected function compareValue($value, $skipValue, $equal = TRUE) {
    if (preg_match($skipValue, NULL) === FALSE) {
      throw new MigrateException("Skip on regex pattern '$skipValue' is invalid.");
    }
    $match = preg_match($skipValue, $value);
    if ($equal) {
      return (bool) $match;
    }

    return !(bool) $match;

  }

}
