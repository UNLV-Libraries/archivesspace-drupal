<?php

namespace Drupal\as2d8\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'ASDateFormatter'.
 *
 * @FieldFormatter(
 *   id = "as_date_default",
 *   label = @Translation("ArchivesSpace Date Formatter"),
 *   field_types = {
 *     "as_date"
 *   }
 * )
 */
class ASDateFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $date_span_separator = ' to ';
    // TODO: Clean this up, simply a WIP proof-of-concept
    $output = array();
    foreach ($items as $delta => $item) {
      $display_value = '';
      if($item->expression){
        $display_value = $item->expression;
      } else {
        if (!empty($item->type)){
          $display_value .= $item->type;
        }
        if (!empty($item->begin)){
          $display_value .= $item->begin;
        }
        if(!empty($item->begin) && !empty($item->end)){
          $display_value .= ' to ';
        }
        if (!empty($item->end)){
          $display_value .= $item->end;
        }
      }
      $output[$delta] = array('#plain_text' => $display_value);
    }

    return $output;
  }
}

 ?>
