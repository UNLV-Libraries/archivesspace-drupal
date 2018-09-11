<?php

namespace Drupal\archivesspace\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'as_extent_default' widget.
 *
 * @FieldWidget(
 *   id = "as_extent_default",
 *   label = @Translation("ArchivesSpace Extent Widget"),
 *   field_types = {
 *     "as_extent"
 *   }
 * )
 */
class ASExtentWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // Item of interest.
    $item =& $items[$delta];
    $settings = $item->getFieldDefinition()->getSettings();

    // Load up the form fields.
    $element += [
      '#type' => 'fieldset',
    ];
    $element['portion'] = [
      '#title' => t('Portion'),
      '#type' => 'select',
      '#options' => $settings['portion_values'],
      '#default_value' => isset($item->portion) ? $item->portion : '',
    ];
    $element['number'] = [
      '#title' => t('Number'),
      '#type' => 'textfield',
      '#default_value' => isset($item->number) ? $item->number : '',
    ];
    $element['extent_type'] = [
      '#title' => t('Type'),
      '#type' => 'select',
      '#options' => $settings['extent_types'],
      '#default_value' => isset($item->extent_type) ? $item->extent_type : '',
    ];
    $element['container_summary'] = [
      '#title' => t('Container Summary'),
      '#type' => 'textfield',
      '#default_value' => isset($item->container_summary) ? $item->container_summary : '',
    ];
    $element['physical_details'] = [
      '#title' => t('Physical Details'),
      '#type' => 'textfield',
      '#default_value' => isset($item->physical_details) ? $item->physical_details : '',
    ];
    $element['dimensions'] = [
      '#title' => t('Dimensions'),
      '#type' => 'textfield',
      '#default_value' => isset($item->dimensions) ? $item->dimensions : '',
    ];

    return $element;
  }

}
