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
    $value = isset($items[$delta]->value) ? $items[$delta]->value : '';
    $element += array(
      '#type' => 'fieldset',
    );
    $element['value'] = [ //Got error 'PHP message: ParseError: syntax error, unexpected '$element' (T_VARIABLE) in /var/www/drupalvm/drupal/web/modules/local/as2d8/src/Plugin/Field/FieldWidget/EDTFWidget.php on line 33
      '#title' => t('Value'),
      '#type' => 'textfield',
    ];
    $element['expression'] = [
      '#title' => t('Expression'),
      '#type' => 'textfield',
    ];
    $element['type'] = [
      '#title' => t('Type'),
      '#type' => 'select',
      '#options' => ['inclusive','bulk'], //TODO pull from configuration
      '#empty_value' => '',
    ];
    // Build the element render array.
    return $element;
  }

}
 ?>
