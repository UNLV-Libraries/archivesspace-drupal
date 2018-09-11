<?php

namespace Drupal\archivesspace\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'ASExtentFormatter'.
 *
 * @FieldFormatter(
 *   id = "as_extent_default",
 *   label = @Translation("ArchivesSpace Extent Formatter"),
 *   field_types = {
 *     "as_extent"
 *   }
 * )
 */
class ASExtentFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $output = [];
    foreach ($items as $delta => $item) {
      $extent_types = $item->getExtentTypes();
      $extent_type = isset($extent_types[$item->extent_type]) ? $extent_types[$item->extent_type] : $item->extent_type;
      $display_value = $item->number . ' ' . $extent_type;
      if ($item->container_summary) {
        $display_value .= ' (' . $item->container_summary . ')';
      }
      if ($item->physical_details) {
        $display_value .= ' ' . $item->physical_details;
      }
      if ($item->dimensions) {
        $display_value .= ' ' . $item->dimensions;
      }
      $output[$delta] = ['#plain_text' => $display_value];
    }

    return $output;
  }

}
