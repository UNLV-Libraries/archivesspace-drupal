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
            $content .= $this->cleanup($line);
          }
          break;

        case 'note_multipart':
          foreach ($value['subnotes'] as $subnote) {
            if ($subnote['publish'] === TRUE) {
              $content .= $this->cleanup($subnote['content']);
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

  /**
   * Cleans up EAD-specific markup for Basic HTML compliance.
   */
  protected function cleanup($string) {
    // These tags get stripped:
    // - language (part of langmaterial)
    // - date: possibly change to <time datetime='...'> ?
    // - function
    // - occupation
    // - subject
    // - corpname
    // - persname
    // - famname
    // - name
    // - geogname
    // - genreform
    // - title
    // - extref
    // Consider possible replacement.
    // Tag replacements.
    $patterns = [
      '/<emph>([^<]+)<\/emph>/',
      '/<ref ([^<]+)<\/ref>/',
    ];
    $replacements = [
      '<em>$1</em>',
      '<a $1</a>',
    ];
    $string = preg_replace($patterns, $replacements, $string);

    // Replace newlines with paragraph tags.
    // TODO: prevent blockquotes from getting wrapped in paragraphs
    // but still support paragraphs inside blockquotes.
    $paragraphs = '';
    foreach (explode(PHP_EOL, $string) as $line) {
      $trimmed = trim($line);
      if (!empty($trimmed)) {
        $paragraphs .= '<p>' . $trimmed . '</p>';
      }
    }
    return $paragraphs;
  }

}
