<?php

namespace Drupal\as2d8\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Provides a ArchivesSpace-based Date field.
 * TODO: override defaultFieldSettings so users can add date types
 *
 * @FieldType(
 *   id = "as_date",
 *   label = @Translation("ArchivesSpace Date"),
 *   module = "as2d8",
 *   category = @Translation("ArchivesSpace"),
 *   description = @Translation("Implements an ArchivesSpace-based date field"),
 *   default_formatter = "as_date_default",
 *   default_widget = "as_date_default",
 * )
 */

class ASDate extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return array(
      // columns contains the values that the field will store
      'columns' => array(
        'label' => array(
          'type' => 'text',
          'size' => 'tiny',
          'not null' => FALSE,
        ),
        'begin' => array(
          'type' => 'text',
          'size' => 'tiny',
          'not null' => FALSE,
        ),
        'end' => array(
          'type' => 'text',
          'size' => 'tiny',
          'not null' => FALSE,
        ),
        'date_type' => array(
          'type' => 'text',
          'size' => 'tiny',
        ),
        'certainty' => array(
          'type' => 'text',
          'size' => 'tiny'
        ),
        'expression' => array(
          'type' => 'text',
          'size' => 'tiny'
        ),
        'calendar' => array(
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
    $properties['label'] = DataDefinition::create('string')->setLabel(t('Label'));
    $properties['begin'] = DataDefinition::create('string')->setLabel(t('Begin'));
    $properties['end'] = DataDefinition::create('string')->setLabel(t('End'));
    $properties['date_type'] = DataDefinition::create('string')->setLabel(t('Date Type'));
    $properties['certainty'] = DataDefinition::create('string')->setLabel(t('Certainty'));
    $properties['expression'] = DataDefinition::create('string')->setLabel(t('Expression'));
    $properties['calendar'] = DataDefinition::create('string')->setLabel(t('Calendar'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $item = $this->getValue();

    $is_empty = TRUE;

    if (
        isset($item['begin']) && !empty($item['begin']) ||
        isset($item['end']) && !empty($item['end']) ||
        isset($item['expression']) && !empty($item['expression'])
       )
    {
      $is_empty = FALSE;
    }

    return $is_empty;
  }

}

?>
