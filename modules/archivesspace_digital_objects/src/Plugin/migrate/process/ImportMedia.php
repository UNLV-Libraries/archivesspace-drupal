<?php

namespace Drupal\archivesspace_digital_objects\Plugin\migrate\process;

use Drupal\migrate\MigrateSkipProcessException;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateException;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;
use Drupal\media\Entity\Media;

/**
 * Imports ArchivesSpace Digital Object file_versions.
 *
 * Creates a media entity and returns the entity id.
 *
 * @MigrateProcessPlugin(
 *   id = "archivesspace_import_media"
 * )
 */
class ImportMedia extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (empty($value)) {
      throw new MigrateSkipProcessException();
    }
    if (!is_array($value)) {
      throw new MigrateException(sprintf('ArchivesSpace ImportMedia process failed for destination property (%s): input is not an array.', $destination_property));
    }

    if ($this->configuration['drupal_path'] === TRUE) {
      $files = \Drupal::entityTypeManager()
        ->getStorage('file')
        ->loadByProperties(['uri' => $value['file_uri']]);
      $file = reset($files);
    }
    else {
      if (!$file = system_retrieve_file($value['file_uri'], NULL, TRUE)) {
        throw new MigrateException(sprintf('ArchivesSpace Import Media could not download the file version uri "%s".', $value['file_uri']));
      }
    }
    print(sprintf("New File %s with size %d\n", $value['file_uri'], intval($file->getSize())));
    if (intval($file->getSize()) < 1) {
      $file->delete();
      throw new MigrateException(sprintf('ArchivesSpace Import Media received zero bytes from URI "%s"', $value['file_uri']));
    }
    // We assume image types.
    // TODO: Support multiple Use Statements.
    $media = Media::create([
      'bundle' => 'image',
      'uid' => \Drupal::currentUser()->id(),
      'langcode' => \Drupal::languageManager()->getDefaultLanguage()->getId(),
      'field_media_image' => [
        'target_id' => $file->id(),
        'alt' => $value['caption'],
        'title' => $value['caption'],
      ],
    ]);
    $media->save();
    return $media->id();

  }

}
