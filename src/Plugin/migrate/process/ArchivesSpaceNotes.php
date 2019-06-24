<?php

namespace Drupal\archivesspace\Plugin\migrate\process;

use Drupal\migrate\MigrateSkipProcessException;
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
    if (empty($value)) {
      throw new MigrateSkipProcessException();
    }
    if (!is_array($value)) {
      throw new MigrateException(sprintf('ArchivesSpace Notes process failed for destination property (%s): input is not an array.', $destination_property));
    }
    if (empty($this->configuration['type']) && !array_key_exists('type', $this->configuration)) {
      throw new MigrateException('ArchivesSpace Notes plugin is missing type configuration.');
    }

    if ($value['type'] === $this->configuration['type'] && $value['publish'] === TRUE) {
      $content = '';
      switch ($value['jsonmodel_type']) {
        case 'note_singlepart':
          foreach ($value['content'] as $line) {
            $content .= "<p>$line</p>";
          }
          break;

        case 'note_multipart':
          foreach ($value['subnotes'] as $subnote) {
            if ($subnote['publish'] === TRUE) {
              $content .= '<p>' . $subnote['content'] . '</p>';
            }
          }
          break;
      }
      if (!empty($content)) {
        return [
          'value' => $content,
          'format' => 'basic_html',
        ];
      }
    }
  }

}
