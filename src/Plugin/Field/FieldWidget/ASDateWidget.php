<?php

namespace Drupal\as2d8\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'as_date_default' widget.
 *
 * TODO: Override validate to validate EDTF format OR be empty with an expression value
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
    // Item of interest
    $item =& $items[$delta];

    //Load up the dropdown options TODO: load from config
    $label_values = [
      'record_keeping' => t('Record Keeping'),
      'broadcast' => t('Broadcast'),
      'copyright' => t('Copyright'),
      'creation' => t('Creation'),
      'deaccession' => t('Deaccession'),
      'agent_relation' => t('Agent Relation'),
      'digitized' => t('Digitized'),
      'existence' => t('Existence'),
      'event' => t('Event'),
      'issued' => t('Issued'),
      'modified' => t('Modified'),
      'publication' => t('Publication'),
      'usage' => t('Usage'),
      'other' => t('Other'),
    ];

    $date_type_values = [
      'inclusive' => t('Inclusive'),
      'bulk' => t('Bulk'),
    ];

    $certainty_values = [
      'approximate' => t('Approximate'),
      'inferred' => t('Inferred'),
      'questionable' => t('Questionable'),
    ];

    $calendar_values = [
      'gregorian' => t('Gregorian'),
    ];

    //Load up the form fields
    $element += array(
      '#type' => 'fieldset',
    );
    $element['label'] = [
      '#title' => t('Label'),
      '#type' => 'select',
      '#options' => $label_values,
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
      '#options' => $date_type_values,
      '#empty_value' => '',
      '#default_value' => isset($item->date_type) ? $item->date_type : '',
    ];
    $element['certainty'] = [
      '#title' => t('Certainty'),
      '#type' => 'select',
      '#options' => $certainty_values,
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
      '#options' => $calendar_values,
      '#empty_value' => '',
      '#default_value' => isset($item->calendar) ? $item->calendar : '',
    ];

    return $element;
  }

}
 ?>
