<?php

namespace Drupal\as2d8\Plugin\Field\FieldFormatter;

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
    // TODO: Clean this up, simply a WIP proof-of-concept
    // TODO: Load translated values of controlled fields
    $output = array();
    foreach ($items as $delta => $item) {
      $display_value = $item->number . ' ' . $item->extent_type;
      if($item->container_summary){
        $display_value .= ' ('.$item->container_summary.')';
      }
      if($item->physical_details){
        $display_value .= ' '.$item->physical_details;
      }
      if($item->dimensions){
        $display_value .= ' '.$item->dimensions;
      }
      $output[$delta] = array('#plain_text' => $display_value);
    }

    return $output;
  }
}

 ?>
