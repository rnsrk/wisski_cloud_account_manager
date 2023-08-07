<?php

namespace Drupal\wisski_cloud_account_manager\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * WissKI cloud opt in settings form.
 */
class WisskiCloudAccountManagerSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'wisski_cloud_account_manager_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'wisski_cloud_account_manager.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    /** @var \Drupal\Core\Config\ImmutableConfig $config */
    $config = $this->config('wisski_cloud_account_manager.settings');

    $form['daemonUrl'] = [
      '#type' => 'url',
      '#title' => $this->t('The WissKI Cloud API Daemon URL'),
      '#description' => $this->t('Provide the complete base URL with protocol, domain (resp. service name in docker), ports and API path, i. e. "http://wisski_cloud_api_daemon:3000/wisski-cloud-daemon/api/v1"'),
      '#default_value' => $config->get('daemonUrl'),
    ];

    $form['userPostUrlPath'] = [
      '#type' => 'url',
      '#title' => $this->t('POST URL path'),
      '#description' => $this->t('Provide the path to the POST endpoint, i. e. "/user"'),
      '#default_value' => $config->get('userPostUrlPath'),
    ];

    $form['userFilterByData'] = [
      '#type' => 'url',
      '#title' => $this->t('Filter by Data URL path'),
      '#description' => $this->t('Provide the path to the Get user by data endpoint, i. e. "/user/by_data"'),
      '#default_value' => $config->get('userFilterByData'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('wisski_cloud_account_manager.settings');

    $config->set('daemonURL', $form_state->getValue('daemonURL'))
      ->set('userPostUrlPath', $form_state->getValue('userPostUrlPath'))
      ->set('userFilterByData', $form_state->getValue('userFilterByData'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
