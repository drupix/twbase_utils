<?php
/**
 * @file
 * Contains Drupal\twbase_utils\Form\SettingsForm.
 */
namespace Drupal\twbase_utils\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SettingsForm extends ConfigFormBase {
	/**
	 * {@inheritdoc}
	 */
	protected function getEditableConfigNames() {
		return [
				'twbase_utils.settings',
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFormId() {
		return 'twbase_utils_settings_form';
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state) {
	  $form = parent::buildForm($form, $form_state);

		// $config = $this->config($this->getEditableConfigNames());
		return $form;
	}

	/**
	 * {@inheritdoc}
	 */
	public function submitForm(array &$form, FormStateInterface $form_state) {
	  //$values = $form_state->getValues();

		// $this->config($this->getEditableConfigNames())
  	// 	->set('year', trim($form_state->getValue('year')))
		//   ->save();

		parent::submitForm($form, $form_state);
	}
}
