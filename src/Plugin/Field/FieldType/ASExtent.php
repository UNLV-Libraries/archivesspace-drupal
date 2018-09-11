<?php

namespace Drupal\archivesspace\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Provides a ArchivesSpace-based Extent field.
 *
 * @FieldType(
 *   id = "as_extent",
 *   label = @Translation("ArchivesSpace Extent"),
 *   module = "archivesspace",
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
    return [
      // Columns contains the values that the field will store.
      'columns' => [
        'portion' => [
          'type' => 'text',
          'size' => 'tiny',
          'not null' => TRUE,
        ],
        'number' => [
          'type' => 'text',
          'size' => 'tiny',
          'not null' => TRUE,
        ],
        'extent_type' => [
          'type' => 'text',
          'size' => 'tiny',
          'not null' => TRUE,
        ],
        'container_summary' => [
          'type' => 'text',
          'size' => 'medium',
        ],
        'physical_details' => [
          'type' => 'text',
          'size' => 'medium',
        ],
        'dimensions' => [
          'type' => 'text',
          'size' => 'tiny',
        ],
      ],
    ];
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

    // All must have a value.
    if (
        isset($item['portion'])     && !empty($item['portion']) &&
        isset($item['number'])      && !empty($item['number'])  &&
        isset($item['extent_type']) && !empty($item['extent_type'])
       ) {
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
        'linear_feet' => t('Linear Feet'),
        'cubic_feet' => t('Cubic Feet'),
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

    $element['extent_types'] = [
      '#type' => 'textarea',
      '#title' => t('Extent Types'),
      '#default_value' => $this->encodeTextSettingsField($this->getSetting('extent_types')),
      '#element_validate' => [[get_class($this), 'validateValues']],
      '#required' => TRUE,
      '#min' => 1,
      '#description' => '<p>' . t('Enter one value per line, in the format key|label.') .
      '<br/>' . t('The key is the stored value. The label will be used in displayed values and edit forms.') .
      '<br/>' . t('The label is optional: if a line contains a single string, it will be used as key and label.') .
      '</p>',
    ];

    return $element;
  }

  /**
   * Retrieves extent types settings for formatters to use.
   *
   * @return array
   *   Available extent_types key/value pairs
   */
  public function getExtentTypes() {
    return $this->getSetting('extent_types');
  }

  /**
   * Encodes key/value pairs into pipe-delimited text.
   *
   * @param array $settings
   *   Key/value pairs.
   *
   * @return string
   *   Pipe-delimited key/value pairs
   */
  protected function encodeTextSettingsField(array $settings) {
    $output = '';
    foreach ($settings as $key => $value) {
      $output .= "$key|$value\n";
    }
    return $output;
  }

  /**
   * Extracts pipe-delimited key/value pairs.
   *
   * @param string $string
   *   The raw string to extract values from.
   *
   * @return array|null
   *   The array of extracted key/value pairs, or NULL if the string is invalid.
   *
   * @see \Drupal\options\Plugin\Field\FieldType\ListItemBase::extractAllowedValues()
   */
  protected static function extractPipedValues($string) {
    $values = [];

    $list = explode("\n", $string);
    $list = array_map('trim', $list);
    $list = array_filter($list, 'strlen');

    foreach ($list as $position => $text) {
      // Check for an explicit key.
      $matches = [];
      if (preg_match('/(.*)\|(.*)/', $text, $matches)) {
        // Trim key and value to avoid unwanted spaces issues.
        $key = trim($matches[1]);
        $value = trim($matches[2]);
      }
      // Otherwise use the value as key and value.
      else {
        $key = $value = $text;
      }

      $values[$key] = $value;
    }

    return $values;
  }

  /**
   * Callback for #element_validate.
   *
   * @param array $element
   *   An associative array containing the properties and children of the
   *   generic form element.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form for the form this element belongs to.
   *
   * @see \Drupal\Core\Render\Element\FormElement::processPattern()
   */
  public static function validateValues(array $element, FormStateInterface $form_state) {
    $values = static::extractPipedValues($element['#value']);

    if (!is_array($values)) {
      $form_state->setError($element, t('Allowed values list: invalid input.'));
    }
    else {
      // We may want to validate key values and notify in the future
      // $form_state->setError($element, $error);.
      $form_state->setValueForElement($element, $values);
    }
  }

}
