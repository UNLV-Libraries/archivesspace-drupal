<?php

namespace Drupal\as2d8\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
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
    $properties['portion'] = DataDefinition::create('string')
      ->setLabel(t('Portion'))
      ->setRequired(TRUE);
    $properties['number'] = DataDefinition::create('string')
      ->setLabel(t('Number'))
      ->setRequired(TRUE);
    $properties['extent_type'] = DataDefinition::create('string')
      ->setLabel(t('Type'))
      ->setRequired(TRUE);
    $properties['container_summary'] = DataDefinition::create('string')
      ->setLabel(t('Container Summary'));
    $properties['physical_details'] = DataDefinition::create('string')
      ->setLabel(t('Physical Details'));
    $properties['dimensions'] = DataDefinition::create('string')
      ->setLabel(t('Dimensions'));

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

  /**
   * {@inheritdoc}
   */
   public static function defaultFieldSettings() {
    return [
      'extent_types' => [
        'linear_feet'=>t('Linear Feet'),
        'cubic_feet'=>t('Cubic Feet'),
        'cassettes' => t('Cassettes'),
        'cubic_feet' => t('Cubic Feet'),
        'files' => t('Files'),
        'gigabytes' => t('Gigabytes'),
        'leaves' => t('Leaves'),
        'linear_feet' => t('Linear Feet'),
        'megabytes' => t('Megabytes'),
        'photographic_prints' => t('Photographic Prints'),
        'photographic_slides' => t('Photographic Slides'),
        'reels' => t('Reels'),
        'sheets' => t('Sheets'),
        'terabytes' => t('Terabytes'),
        'volumes' => t('Volumes'),
      ],
      'portion_values' => [
          'whole' => t('Whole'),
          'part' => t('Part'),
      ],
    ] + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $element = [];
    drupal_set_message('Settings: '.print_r($this->getSettings(),TRUE));
    $element['extent_types'] = [
      '#type' => 'textarea',
      '#title' => t('Extent Types'),
      '#default_value' => $this->getSetting('extent_types'), //Parse into something we can use, See ListItemBase (esp extractAllowedValues, validateAllowedValues, and storageSettingsForm for inspiration
      '#required' => TRUE,
      '#min' => 1,
    ];

    return $element;
  }

}

?>
