<?php

namespace Drupal\as2d8\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Provides a ArchivesSpace-based Extent field.
 *
 * @FieldType(
 *   id = "as_extent",
 *   label = @Translation("ArchivesSpace Extent"),
 *   module = "as2d8",
 *   category = @Translation("ArchivesSpace"),
 *   description = @Translation("Implements an ArchivesSpace-based extent field"),
 *   default_formatter = "as_extent_default",
 *   default_widget = "as_extent_default",
 * )
 */

class ASExtent extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return array(
      // columns contains the values that the field will store
      'columns' => array(
        'portion' => array(
          'type' => 'text',
          'size' => 'tiny',
          'not null' => TRUE,
        ),
        'number' => array(
          'type' => 'text',
          'size' => 'tiny',
          'not null' => TRUE,
        ),
        'extent_type' => array(
          'type' => 'text',
          'size' => 'tiny',
          'not null' => TRUE,
        ),
        'container_summary' => array(
          'type' => 'text',
          'size' => 'medium'
        ),
        'physical_details' => array(
          'type' => 'text',
          'size' => 'medium'
        ),
        'dimensions' => array(
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
    $properties['portion'] = DataDefinition::create('string')->setLabel(t('Portion'));
    $properties['number'] = DataDefinition::create('string')->setLabel(t('Number'));
    $properties['extent_type'] = DataDefinition::create('string')->setLabel(t('Type'));
    $properties['container_summary'] = DataDefinition::create('string')->setLabel(t('Container Summary'));
    $properties['physical_details'] = DataDefinition::create('string')->setLabel(t('Physical Details'));
    $properties['dimensions'] = DataDefinition::create('string')->setLabel(t('Dimensions'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $item = $this->getValue();

    // All must have a value
    if (
        isset($item['portion'])     && !empty($item['portion']) &&
        isset($item['number'])      && !empty($item['number'])  &&
        isset($item['extent_type']) && !empty($item['extent_type'])
       )
    {
      return FALSE;
    }

    return TRUE;
  }

}

?>
