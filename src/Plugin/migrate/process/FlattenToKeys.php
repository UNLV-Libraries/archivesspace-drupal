<?php

namespace Drupal\archivesspace\Plugin\migrate\process;

use Drupal\Component\Utility\NestedArray;
use Drupal\migrate\MigrateException;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Flattens the source value to an array of keys.
 *
 * The flatten process plugin converts an array of associative arrays into a
 * flat array of a single value. For example [['ref'=>1], ['ref'=>2]] becomes
 * [1, 2], assuming the key is set to 'ref'. This is a combination of Flatten
 * and Extract.
 *
 * @see \Drupal\migrate\Plugin\migrate\process\Extract
 * @see \Drupal\migrate\Plugin\migrate\process\Flatten
 *
 * @MigrateProcessPlugin(
 *   id = "flatten_to_keys",
 *   handle_multiples = TRUE
 * )
 */
class FlattenToKeys extends ProcessPluginBase {

  /**
   * Flattens the source value to an array of keys.
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    if (!is_array($value)) {
      throw new MigrateException('Input should be an array.');
    }
    if (!isset($this->configuration['key'])) {
      throw new MigrateException("flatten_to_keys is missing a key.");
    }
    if (is_array($this->configuration['key'])) {
      $key = $this->configuration['key'];
    }
    else {
      $key[] = $this->configuration['key'];
    }
    $references = [];
    foreach ($value as $pos => $item) {
      $new_value = NestedArray::getValue($item, $key, $key_exists);
      if (!$key_exists) {
        if (isset($this->configuration['default'])) {
          $new_value = $this->configuration['default'];
        }
        else {
          throw new MigrateException('Array index missing, extraction failed.');
        }
      }
      $references[] = $new_value;
    }
    return $references;
  }

  /**
   * {@inheritdoc}
   */
  public function multiple() {
    return TRUE;
  }

}
