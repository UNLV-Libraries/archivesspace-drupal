<?php

namespace Drupal\archivesspace\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateException;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Transforms ArchivesSpace single and multipart notes into TypedNote format.
 *
 * @MigrateProcessPlugin(
 *   id = "archivesspace_notes"
 * )
 */
class ArchivesSpaceNotes extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (!is_array($value)) {
      throw new MigrateException(sprintf('ArchivesSpace Notes process failed for destination property (%s): input is not an array.', $destination_property));
    }

    // Start with the easy attributes.
    $label = (isset($value['lable'])) ? $value['label'] : '';
    $type = (isset($value['type'])) ? $value['type'] : 'odd';
    $content = '';

    // Single or multipart notes?
    if ($value['jsonmodel_type'] == 'note_singlepart') {
      foreach ($value['content'] as $p) {
        $content .= "<p>$p</p>";
      }
    }
    elseif ($value['jsonmodel_type'] == 'note_multipart') {
      foreach ($value['subnotes'] as $subnote) {
        if ($subnote['publish']) {
          $content .= '<p>' . $subnote['content'] . '</p>';
        }
      }
    }

    // Is the note published and does it contain content?
    if (isset($value['publish']) && $value['publish'] && $content) {
      return [
        'label' => $label,
        'note_type' => $type,
        'note' => $content,
      ];
    }
    else {
      return [];
    }

  }

}
