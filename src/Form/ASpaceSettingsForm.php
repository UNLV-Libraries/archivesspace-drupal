<?php

namespace Drupal\archivesspace\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure ArchivesSpace Integration settings for this site.
 */
class ASpaceSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'archivesspace_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'archivesspace.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $state  = \Drupal::state();
    
    $form['archivesspace_base_uri'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('ArchivesSpace API Prefix'),
      '#default_value' => $state->get('archivesspace.base_uri'),
    );

    $form['archivesspace_username'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('ArchivesSpace Username'),
      '#default_value' => $state->get('archivesspace.username'),
    );

    $form['archivesspace_password'] = array(
      '#type' => 'password',
      '#title' => $this->t('ArchivesSpace Password'),
      '#default_value' => '',
      '#description'   => t('Leave blank to make no changes, use an invalid string to disable if need be.')
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
      // Set the provided values in Drupal state.
      $state  = \Drupal::state();
      $state->set('archivesspace.base_uri', $form_state->getValue('archivesspace_base_uri'));
      $state->set('archivesspace.username', $form_state->getValue('archivesspace_username'));
      if (!empty($form_state->getValue('archivesspace_password'))) {
        $state->set('archivesspace.password', $form_state->getValue('archivesspace_password'));
      }

    parent::submitForm($form, $form_state);
  }

}
