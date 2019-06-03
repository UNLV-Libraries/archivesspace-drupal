<?php

namespace Drupal\archivesspace\Plugin\Field\FieldType;

use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Implements an Archival Physical Instance field.
 *
 * @FieldType(
 *   id = "archival_physical_instance",
 *   label = @Translation("Archival Physical Instance"),
 *   module = "archivesspace",
 *   category = @Translation("ArchivesSpace"),
 *   description = @Translation("Implements an ArchivesSpace instance for physical materials."),
 *   default_formatter = "archival_physical_instance_default",
 *   default_widget = "archival_physical_instance_default",
 *   list_class = "\Drupal\Core\Field\EntityReferenceFieldItemList",
 * )
 */
 class ArchivalPhysicalInstance extends EntityReferenceItem {

   /**
    * {@inheritdoc}
    */
   public static function schema(FieldStorageDefinitionInterface $field_definition) {
     $schema = parent::schema($field_definition);
     $schema['columns']['subcontainer_indicator'] = [
       'type' => 'text',
       'size' => 'small',
     ];

     return $schema;
   }

   /**
    * {@inheritdoc}
    */
   public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
     $properties = parent::propertyDefinitions($field_definition);
     $properties['subcontainer_indicator'] = DataDefinition::create('string')
       ->setLabel(t('Sub-container Indicator'))
       ->setRequired(FALSE);

     return $properties;
   }

   /**
    * {@inheritdoc}
    */
   public function isEmpty() {
     // Both must be empty.
     if (parent::isEmpty() && empty($this->subcontainer_indicator) ) {
       return TRUE;
     }
     return FALSE;
   }

   /**
    * {@inheritdoc}
    */
   public static function defaultFieldSettings() {
     return ['subcontainer_indicator' => ''] + parent::defaultFieldSettings();
   }

   /**
    * {@inheritdoc}
    */
   public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
     $element = parent::fieldSettingsForm($form, $form_state);

     $element['subcontainer_indicator'] = [
       '#type' => 'text',
       '#title' => t('BLAH'),
       '#default_value' => '',
     ];

     return $element;
   }

 }
