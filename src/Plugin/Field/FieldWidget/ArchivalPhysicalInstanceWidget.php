<?php

namespace Drupal\archivesspace\Plugin\Field\FieldWidget;

use Drupal\Core\Field\Plugin\Field\FieldWidget\EntityReferenceAutocompleteWidget;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the archival physical instance widget.
 *
 * @FieldWidget(
 *   id = "archival_physical_instance_default",
 *   label = @Translation("Archival Physical Instance Widget"),
 *   field_types = {
 *     "archival_physical_instance"
 *   }
 * )
 */
class ArchivalPhysicalInstanceWidget extends EntityReferenceAutocompleteWidget {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    \Drupal::logger('archivesspace')->notice('APIW::formElement parent class: '.get_parent_class());
    $widget = parent::formElement($items, $delta, $element, $form, $form_state);
    $item =& $items[$delta];
    $widget['subcontainer_indicator'] = [
      '#title' => t('Sub-container Indicator'),
      '#type' => 'textfield',
      '#weight' => 1,
      '#default_value' => isset($item->subcontainer_indicator) ? $item->subcontainer_indicator : '',
    ];

    //@TODO: figure out how to override the entity autocomplete visually hidden label.
    $widget['target_id']['#title'] = t('Top Container');

    return $widget;
  }

}
