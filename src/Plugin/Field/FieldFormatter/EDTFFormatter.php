<?php

namespace Drupal\as2d8\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'EDTFFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "edtf_default",
 *   label = @Translation("EDTF Formatter"),
 *   field_types = {
 *     "edtf"
 *   }
 * )
 */
class EDTFFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    // TODO: Clean this up, simply a WIP proof-of-concept
    $output = array();
    foreach ($items as $delta => $item) {
      if($item->expression){
        $output[$delta] = array('#plain_text' => $item->expression);
      } else {
        $output[$delta] = array('#plain_text' => $item->type . $item->value);
      }
    }

    return $output;
  }
}

 ?>
