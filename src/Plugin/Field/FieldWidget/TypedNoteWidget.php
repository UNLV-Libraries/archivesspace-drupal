<?php

namespace Drupal\archivesspace\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the typed note widget.
 *
 * @FieldWidget(
 *   id = "typed_note_default",
 *   label = @Translation("Typed Note Widget"),
 *   field_types = {
 *     "typed_note"
 *   }
 * )
 */

class TypedNoteWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // Item of interest
    $item =& $items[$delta];
    $settings = $item->getFieldDefinition()->getSettings();

    //Load up the form fields
    $element += array(
      '#type' => 'fieldset',
    );
    $element['note_type'] = [
      '#title' => t('Note Type'),
      '#type' => 'select',
      '#options' => $settings['note_types'],
      '#default_value' => isset($item->note_type) ? $item->note_type : '',
    ];
    $element['label'] = [
      '#title' => t('Label Override'),
      '#type' => 'textfield',
      '#default_value' => isset($item->label) ? $item->label : '',
    ];
    $element['note'] = [
      '#title' => t('Note Text'),
      '#type' => 'text_format',
      '#default_value' => isset($item->note) ? $item->note : '',
    ];

    return $element;
  }


  /**
   * {@inheritdoc}
   *
   * The Typed Note field uses a textarea subfield, but the form element
   * returns an array while we only want a single value. This takes the
   * value we want from the array and discards the rest.
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach($values as $key => $value) {
      $values[$key]['note'] = $value['note']['value'];
    }
    return $values;
  }

}
 ?>
