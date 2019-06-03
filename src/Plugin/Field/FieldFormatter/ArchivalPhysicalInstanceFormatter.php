<?php

namespace Drupal\archivesspace\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceLabelFormatter;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'ArchivalPhysicalInstanceFormatter'.
 *
 * @FieldFormatter(
 *   id = "archival_physical_instance_default",
 *   label = @Translation("Archival Physical Instance Formatter"),
 *   field_types = {
 *     "archival_physical_instance"
 *   }
 * )
 */
class ArchivalPhysicalInstanceFormatter extends EntityReferenceLabelFormatter {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);

    foreach ($items as $delta => $item) {
      $top_container = $elements[$delta];

      if (isset($item->subcontainer_indicator)) {
        $subcontainer_indicator = '';
        if (!empty($top_container)) {
          $subcontainer_indicator .= ', ';
        }
        $subcontainer_indicator .= $item->subcontainer_indicator;

        $elements[$delta] = [
          $top_container,
          [
            '#plain_text' => $subcontainer_indicator,
          ],
        ];
      }
    }
    return $elements;
  }

}
