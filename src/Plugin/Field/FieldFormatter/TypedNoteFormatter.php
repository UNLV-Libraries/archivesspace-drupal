<?php

namespace Drupal\archivesspace\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'TypedNoteFormatter'.
 *
 * @FieldFormatter(
 *   id = "typed_note_default",
 *   label = @Translation("Typed Note Formatter"),
 *   field_types = {
 *     "typed_note"
 *   }
 * )
 */
class TypedNoteFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $output = array();
    foreach ($items as $delta => $item) {
      $note_types = $item->getNoteTypes();
      $note_type = isset($note_types[$item->note_type]) ? $note_types[$item->note_type] : $item->note_type;

      $label = !empty($item->label) ? $item->label : $note_type;

      $note = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['archivesspace_typed_note'],
        ],
        'label' => [
          '#type' => 'container',
          '#attributes' => [
            'class' => ['field__label'],
          ],
          '#markup' => $label,
        ],
        'value' => [
          '#type' => 'container',
          '#attributes' => [
            'class' => ['field__item'],
          ],
          '#markup' => $item->note,
        ],
      ];

      $output[$delta] = $note;
    }

    return $output;
  }
}

 ?>
