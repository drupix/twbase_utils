<?php

namespace Drupal\twbase_utils\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Settings form for the showcase block.
 */
class ShowcaseBlockSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'twbase_utils.settings_showcase',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'showcase_blocks_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('twbase_utils.settings_showcase');

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $config->get('title'),
    ];
    $form['body'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Body'),
      '#default_value' => $config->get('body.value'),
      '#format' => $config->get('body.format'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
		$this->config('twbase_utils.settings_showcase')
  		->set('title', trim($form_state->getValue('title')))
  		->set('body.value', trim($form_state->getValue('body')['value']))
  		->set('body.format', trim($form_state->getValue('body')['format']))
  		->save();

    parent::submitForm($form, $form_state);
  }

}
