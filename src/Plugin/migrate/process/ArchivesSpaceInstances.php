<?php

namespace Drupal\archivesspace\Plugin\migrate\process;

use Drupal\migrate\Plugin\migrate\process\MigrationLookup;
use Drupal\migrate\MigrateException;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Transforms ArchivesSpace instances into field_archival_container objects.
 *
 * @MigrateProcessPlugin(
 *   id = "archivesspace_instances",
 *   handle_multiples = TRUE
 * )
 */
class ArchivesSpaceInstances extends MigrationLookup {

  /**
   * Flattens the source value to an array of keys.
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (!is_array($value)) {
      throw new MigrateException('Input should be an array.');
    }
    $instances = [];
    foreach ($value as $pos => $item) {
      if ($item['instance_type'] === 'digital_object') {
        // Skipping digital objects for now.
        continue;
      }
      $subcontainer = $item['sub_container'];

      $target_id = parent::transform($subcontainer['top_container']['ref'], $migrate_executable, $row, $destination_property);

      $indicator = '';
      if (isset($subcontainer['type_2']) && isset($subcontainer['indicator_2'])) {
        $indicator = $subcontainer['type_2'] . ' ' . $subcontainer['indicator_2'];
      }
      if (isset($subcontainer['type_3']) && isset($subcontainer['indicator_3'])) {
        $indicator .= $subcontainer['type_3'] . ' ' . $subcontainer['indicator_3'];
      }

      $instances[] = [
        'target_id' => $target_id,
        'subcontainer_indicator' => $indicator,
      ];
    }
    return $instances;
  }

  /**
   * {@inheritdoc}
   */
  public function multiple() {
    return TRUE;
  }

}
