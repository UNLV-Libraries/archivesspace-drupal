<?php

namespace Drupal\archivesspace\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Provides a ArchivesSpace-based Date field.
 *
 * TODO: override defaultFieldSettings so users can add date types.
 *
 * @FieldType(
 *   id = "as_date",
 *   label = @Translation("ArchivesSpace Date"),
 *   module = "archivesspace",
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
    return [
      // Columns contains the values that the field will store.
      'columns' => [
        'label' => [
          'type' => 'text',
          'size' => 'tiny',
          'not null' => FALSE,
        ],
        'begin' => [
          'type' => 'text',
          'size' => 'tiny',
          'not null' => FALSE,
        ],
        'end' => [
          'type' => 'text',
          'size' => 'tiny',
          'not null' => FALSE,
        ],
        'date_type' => [
          'type' => 'text',
          'size' => 'tiny',
        ],
        'certainty' => [
          'type' => 'text',
          'size' => 'tiny',
        ],
        'expression' => [
          'type' => 'text',
          'size' => 'tiny',
        ],
        'calendar' => [
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
       ) {
      $is_empty = FALSE;
    }

    return $is_empty;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    return [
      'label_types' => [
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
        'record_keeping' => t('Record Keeping'),
        'usage' => t('Usage'),
        'other' => t('Other'),
      ],
      'date_types' => [
        'inclusive' => t('Inclusive'),
        'bulk' => t('Bulk'),
      ],
      'certainty_types' => [
        'approximate' => t('Approximate'),
        'inferred' => t('Inferred'),
        'questionable' => t('Questionable'),
      ],
      'calendar_types' => [
        'gregorian' => t('Gregorian'),
      ],
    ] + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $element = [];

    $element['label_types'] = [
      '#type' => 'textarea',
      '#title' => t('Date Labels'),
      '#default_value' => $this->encodeTextSettingsField($this->getSetting('label_types')),
      '#element_validate' => [[get_class($this), 'validateValues']],
      '#required' => TRUE,
      '#min' => 1,
      '#description' => '<p>' . t('Enter one value per line, in the format key|label.') .
      '<br/>' . t('The key is the stored value. The label will be used in displayed values and edit forms.') .
      '<br/>' . t('The label is optional: if a line contains a single string, it will be used as key and label.') .
      '</p>',
    ];

    $element['date_types'] = [
      '#type' => 'textarea',
      '#title' => t('Date Types'),
      '#default_value' => $this->encodeTextSettingsField($this->getSetting('date_types')),
      '#element_validate' => [[get_class($this), 'validateValues']],
      '#required' => TRUE,
      '#min' => 1,
      '#description' => '<p>' . t('Enter one value per line, in the format key|label.') .
      '<br/>' . t('The key is the stored value. The label will be used in displayed values and edit forms.') .
      '<br/>' . t('The label is optional: if a line contains a single string, it will be used as key and label.') .
      '</p>',
    ];

    $element['certainty_types'] = [
      '#type' => 'textarea',
      '#title' => t('Date Certainties'),
      '#default_value' => $this->encodeTextSettingsField($this->getSetting('certainty_types')),
      '#element_validate' => [[get_class($this), 'validateValues']],
      '#required' => TRUE,
      '#min' => 1,
      '#description' => '<p>' . t('Enter one value per line, in the format key|label.') .
      '<br/>' . t('The key is the stored value. The label will be used in displayed values and edit forms.') .
      '<br/>' . t('The label is optional: if a line contains a single string, it will be used as key and label.') .
      '</p>',
    ];

    $element['calendar_types'] = [
      '#type' => 'textarea',
      '#title' => t('Calendar Types'),
      '#default_value' => $this->encodeTextSettingsField($this->getSetting('calendar_types')),
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
