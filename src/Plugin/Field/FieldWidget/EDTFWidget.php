<?php

namespace Drupal\as2d8\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'edtf_default' widget.
 *
 * TODO: Override validate to validate EDTF format OR be empty with an expression value
 *
 * @FieldWidget(
 *   id = "edtf_default",
 *   label = @Translation("EDTF Widget"),
 *   field_types = {
 *     "edtf"
 *   }
 * )
 */

class EDTFWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $item =& $items[$delta];

    $element += array(
      '#type' => 'fieldset',
    );
    $element['value'] = [
      '#title' => t('Value'),
      '#type' => 'textfield',
      '#default_value' => isset($item->value) ? $item->value : '',
    ];
    $element['expression'] = [
      '#title' => t('Expression'),
      '#type' => 'textfield',
      '#default_value' => isset($item->expression) ? $item->expression : '',
    ];
    $element['type'] = [
      '#title' => t('Type'),
      '#type' => 'select',
      '#options' => ['inclusive','bulk'], //TODO pull from configuration
      '#empty_value' => '',
      '#default_value' => isset($item->type) ? $item->type : '',
    ];

    return $element;
  }

}
 ?>
