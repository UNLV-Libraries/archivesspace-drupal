<?php

namespace Drupal\archivesspace\Plugin\migrate\process;

use Drupal\migrate\Plugin\migrate\process\SubProcess;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateSkipRowException;
use Drupal\migrate\Row;

/**
 * Runs an array of arrays through its own process pipeline.
 *
 * The sub_process plugin accepts an array of associative arrays and runs each
 * one through its own process pipeline, producing a newly keyed associative
 * array of transformed values.
 *
 * Available configuration keys:
 *   - process: the plugin(s) that will process each element of the source.
 *   - key: runs the process pipeline for the key to determine a new dynamic
 *     name.
 *
 * @see \Drupal\migrate\Plugin\migrate\process\SubProcess
 *
 * @MigrateProcessPlugin(
 *   id = "sub_process_ext",
 *   handle_multiples = TRUE
 * )
 */
class SubProcessExt extends SubProcess {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    // print("Extracting from: ".print_r($value,true)."\n");.
    $return = [];
    if (is_array($value) || $value instanceof \Traversable) {
      foreach ($value as $key => $new_value) {
        try {
          $new_row = new Row($new_value, []);
          $migrate_executable->processRow($new_row, $this->configuration['process']);
          $destination = $new_row->getDestination();
          if (array_key_exists('key', $this->configuration)) {
            $key = $this->transformKey($key, $migrate_executable, $new_row);
          }
          $return[$key] = $destination;
        }
        catch (MigrateSkipRowException $e) {
          // Do nothing, we want to keep processing the next sub-row.
          // We may re-throw it in the future based on a yet-to-be-determined
          // flag.
        }
      }
    }
    return $return;
  }

}
