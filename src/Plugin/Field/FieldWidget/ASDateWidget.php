<?php

namespace Drupal\archivesspace\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'as_date_default' widget.
 *
 * TODO: Override validate to validate EDTF format OR
 *       be empty with an expression value.
 *
 * @FieldWidget(
 *   id = "as_date_default",
 *   label = @Translation("ArchivesSpace Date Widget"),
 *   field_types = {
 *     "as_date"
 *   }
 * )
 */
class ASDateWidget extends WidgetBase {

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
    $element['label'] = [
      '#title' => t('Label'),
      '#type' => 'select',
      '#options' => $settings['label_types'],
      '#default_value' => isset($item->label) ? $item->label : 'creation',
    ];
    $element['begin'] = [
      '#title' => t('Begin'),
      '#type' => 'textfield',
      '#default_value' => isset($item->begin) ? $item->begin : '',
    ];
    $element['end'] = [
      '#title' => t('End'),
      '#type' => 'textfield',
      '#default_value' => isset($item->end) ? $item->end : '',
    ];
    $element['date_type'] = [
      '#title' => t('Type'),
      '#type' => 'select',
      '#options' => $settings['date_types'],
      '#empty_value' => '',
      '#default_value' => isset($item->date_type) ? $item->date_type : 'inclusive',
    ];
    $element['certainty'] = [
      '#title' => t('Certainty'),
      '#type' => 'select',
      '#options' => $settings['certainty_types'],
      '#empty_value' => '',
      '#default_value' => isset($item->certainty) ? $item->certainty : '',
    ];
    $element['expression'] = [
      '#title' => t('Expression'),
      '#type' => 'textfield',
      '#default_value' => isset($item->expression) ? $item->expression : '',
    ];
    $element['calendar'] = [
      '#title' => t('Calendar'),
      '#type' => 'select',
      '#options' => $settings['calendar_types'],
      '#empty_value' => '',
      '#default_value' => isset($item->calendar) ? $item->calendar : 'gregorian',
    ];

    return $element;
  }

}
