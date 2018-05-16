<?php

namespace Drupal\as2d8\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Provides a field type of edtf.
 * TODO: override defaultFieldSettings so users can add date types
 *
 * @FieldType(
 *   id = "edtf",
 *   label = @Translation("Extended Date/Time field"),
 *   module = "as2d8",
 *   description = @Translation("Implements an Extended Date/Time field with date type and alternate expression"),
 *   default_formatter = "edtf_default",
 *   default_widget = "edtf_default",
 * )
 */

class EDTF extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return array(
      // columns contains the values that the field will store
      'columns' => array(
        // List the values that the field will save. This
        // field will only save a single value, 'value'
        'value' => array(
          'type' => 'text',
          'size' => 'tiny',
          'not null' => FALSE,
        ),
        'type' => array(
          'type' => 'text',
          'size' => 'tiny',
        ),
        'expression' => array(
          'type' => 'text',
          'size' => 'tiny'
        )
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties = [];
    $properties['value'] = DataDefinition::create('string')->setLabel(t('Normalized Date Value'));
    $properties['type'] = DataDefinition::create('string')->setLabel(t('Date Type'));
    $properties['expression'] = DataDefinition::create('string')->setLabel(t('Date Expression'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('value')->getValue();
    $expression = $this->get('expression')->getValue();
    // Both the value and expression must be empty
    if (empty($value) && empty($expression)){
      return TRUE;
    } else {
      return FALSE;
    }
  }

}

?>
