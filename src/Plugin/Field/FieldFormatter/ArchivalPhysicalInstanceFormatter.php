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

      // The link returned by the parent includes the manuscript number,
      // but we just want the short version if available since it is usually
      // being displayed in context. May make this configurable later.
      if (!empty($top_container) && !empty($item->entity->field_as_container_short_title->value)) {
        $top_container['#title'] = $item->entity->field_as_container_short_title->value;
      }

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
