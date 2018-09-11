<?php

namespace Drupal\archivesspace\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Implements a Typed Note field.
 *
 * @FieldType(
 *   id = "typed_note",
 *   label = @Translation("Typed Note"),
 *   module = "archivesspace",
 *   category = @Translation("ArchivesSpace"),
 *   description = @Translation("Implements a typed note field"),
 *   default_formatter = "typed_note_default",
 *   default_widget = "typed_note_default",
 * )
 */
class TypedNote extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      // Columns contains the values that the field will store.
      'columns' => [
        'note_type' => [
          'type' => 'text',
          'size' => 'tiny',
          'not null' => TRUE,
        ],
        'label' => [
          'type' => 'text',
          'size' => 'tiny',
        ],
        'note' => [
          'type' => 'text',
          'size' => 'big',
          'not null' => TRUE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties = [];
    $properties['note_type'] = DataDefinition::create('string')
      ->setLabel(t('Type'))
      ->setRequired(TRUE);
    $properties['label'] = DataDefinition::create('string')
      ->setLabel(t('Label'));
    $properties['note'] = DataDefinition::create('string')
      ->setLabel(t('Note Text'))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $item = $this->getValue();

    // All must have a value.
    if (isset($item['note']) && !empty($item['note'])) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    return [
      'note_types' => [
        'accessrestrict' => t('Conditions Governing Access'),
        'accruals' => t('Accruals'),
        'acqinfo' => t('Immediate Source of Acquisition'),
        'altformavail' => t('Existence and Location of Copies'),
        'appraisal' => t('Appraisal'),
        'arrangement' => t('Arrangement'),
        'bioghist' => t('Biographical / Historical'),
        'custodhist' => t('Custodial History'),
        'dimensions' => t('Dimensions'),
        'fileplan' => t('File Plan'),
        'legalstatus' => t('Legal Status'),
        'odd' => t('General'),
        'originalsloc' => t('Existence and Location of Originals'),
        'otherfindaid' => t('Other Finding Aids'),
        'phystech' => t('Physical Characteristics and Technical Requirements'),
        'prefercite' => t('Preferred Citation'),
        'processinfo' => t('Processing Information'),
        'relatedmaterial' => t('Related Materials'),
        'scopecontent' => t('Scope and Contents'),
        'separatedmaterial' => t('Separated Materials'),
        'userestrict' => t('Conditions Governing Use'),
      ],
    ] + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $element = [];

    $element['note_types'] = [
      '#type' => 'textarea',
      '#title' => t('Note Types'),
      '#default_value' => $this->encodeTextSettingsField($this->getSetting('note_types')),
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
   * Retrieves the note types setting for formatters.
   *
   * @return array
   *   Key/value pairs of note type codes and display values
   */
  public function getNoteTypes() {
    return $this->getSetting('note_types');
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
      // We may want to validate key values in the future...
      $form_state->setValueForElement($element, $values);
    }
  }

}
