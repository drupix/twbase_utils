<?php

namespace Drupal\twbase_utils\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\Element\EntityAutocomplete;
use Drupal\Component\Utility\UrlHelper;

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

    $form['frontpage_options'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Frontpage showcase content'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    ];

    $form['frontpage_options']['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $config->get('title'),
    ];
    $form['frontpage_options']['body'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Body'),
      '#default_value' => $config->get('body.value'),
      '#format' => $config->get('body.format'),
    ];


    //
    // Button options
    //
    $form['frontpage_options']['button_options'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Button link options'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    ];

    $form['frontpage_options']['button_options']['button_infos'] = [
      '#markup' => $this->t('Note that the button will only appear if both fields are filled in.'),
      '#prefix' => '<br/><strong>',
      '#suffix' => '</strong>',
    ];

    // Button text
    $form['frontpage_options']['button_options']['button_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Button text'),
      '#default_value' => $config->get('button.text'),
      '#description' => $this->t('Leave blank to not display the button.'),
    ];

    //
    // Button link
    //

    // Prepare the default value
    $entityStorage = \Drupal::entityTypeManager()->getStorage('node');
    $nid = $config->get('button.link');
    if(is_numeric($nid) && ($node = $entityStorage->load($nid))) {
      $default_value = $node->label() . ' (' . $nid . ')';
    }
    else {
      $default_value = $config->get('button.link');
    }

    $form['frontpage_options']['button_options']['button_link'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'node',
      '#title' => $this->t('Button link'),
      '#attributes' => [
        'data-autocomplete-first-character-blacklist' => '/#?',
      ],
      '#validate_reference' => FALSE,
      '#maxlength' => 1024,
      '#process_default_value' => FALSE,
      '#element_validate' => [], // unset validation as we provide our own
      '#default_value' => $default_value,
      '#description' => $this->t('Start typing the title of a piece of content to select it. You can also enter an internal path such as %add-node or an external URL such as %url.',
                          ['%front' => '<front>', '%add-node' => '/node/add', '%url' => 'http://example.com']) . '<br/>' .
                        $this->t('Leave blank to not display the button.'),
    ];

    $form['frontpage_options']['button_options']['button_target'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Open link in new window'),
      '#default_value' => $config->get('button.target'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $text = trim($form_state->getValue('button_text'));
    $link = trim($form_state->getValue('button_link'));
    if ($text && $link) {
      $entityStorage = \Drupal::entityTypeManager()->getStorage('node');
      $nid = EntityAutocomplete::extractEntityIdFromAutocompleteInput(trim($form_state->getValue('button_link')));
      /** @var \Drupal\Core\Entity\Entity $node */
      if(is_numeric($nid) && ($node = $entityStorage->load($nid))) {
        if(!$node->isPublished()) {
          $form_state->setErrorByName('button_link', $this->t('The node "@label" is not published, the link will not work.', ['@label' => $node->label()]));
          return;
        }
      }

      // @see Drupal\link\Plugin\Field\FieldWidget\LinkWidget->validateUriElement()
      $uri = static::getUserEnteredStringAsUri($link);
      $url_scheme = parse_url($uri, PHP_URL_SCHEME);
      $first_char = substr($link, 0, 1);
      //if ($url_scheme === 'internal' && !in_array($link, ['/', '?', '#'], TRUE) ) {
      if ($url_scheme === 'internal' && !in_array($first_char, ['/', '?', '#'], TRUE) ) {
        $form_state->setErrorByName('button_link', $this->t('Manually entered paths should start with one of the following characters: / ? #'));
        return;
      }

      // Disallow external URLs using untrusted protocols.
      // @see Drupal\link\Plugin\Validation\Constraint\LinkExternalProtocolsConstraintValidator
      if ($url_scheme != 'internal' && !in_array($url_scheme, UrlHelper::getAllowedProtocols())) {
        $form_state->setErrorByName('button_link', $this->t("The path '@uri' is invalid.", ['@uri' => $link]));
        return;
      }

      $pathValidator = \Drupal::service('path.validator');
      if(!$pathValidator->isValid($link)) {
        $form_state->setErrorByName('button_link', $this->t('You must provide a valid url.'));
        return;
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $nid = EntityAutocomplete::extractEntityIdFromAutocompleteInput(trim($form_state->getValue('button_link')));
    if(is_numeric($nid)) {
      $button_link = $nid;
    }
    else {
      $button_link = trim($form_state->getValue('button_link'));
    }

		$this->config('twbase_utils.settings_showcase')
  		->set('title', trim($form_state->getValue('title')))
  		->set('body.value', trim($form_state->getValue('body')['value']))
  		->set('body.format', trim($form_state->getValue('body')['format']))
      ->set('button.text', trim($form_state->getValue('button_text')))
      ->set('button.link', $button_link)
      ->set('button.target', $form_state->getValue('button_target'))
  		->save();

    parent::submitForm($form, $form_state);
  }


  /**
   * Gets the user-entered string as a URI.
   *
   * The following two forms of input are mapped to URIs:
   * - entity autocomplete ("label (entity id)") strings: to 'entity:' URIs;
   * - strings without a detectable scheme: to 'internal:' URIs.
   *
   * @param string $string
   *   The user-entered string.
   *
   * @return string
   *   The URI, if a non-empty $uri was passed.
   *
   * @see Drupal\link\Plugin\Field\FieldWidget\LinkWidget
   */
  protected static function getUserEnteredStringAsUri($string) {
    // By default, assume the entered string is a URI.
    $uri = trim($string);

    // Detect entity autocomplete string, map to 'entity:' URI.
    $entity_id = EntityAutocomplete::extractEntityIdFromAutocompleteInput($string);
    if ($entity_id !== NULL) {
      // @todo Support entity types other than 'node'. Will be fixed in
      //   https://www.drupal.org/node/2423093.
      $uri = 'entity:node/' . $entity_id;
    }
    // Support linking to nothing.
    // elseif (in_array($string, ['<nolink>', '<none>', '<button>'], TRUE)) {
    //   $uri = 'route:' . $string;
    // }
    // Detect a schemeless string, map to 'internal:' URI.
    elseif (!empty($string) && parse_url($string, PHP_URL_SCHEME) === NULL) {
      // @todo '<front>' is valid input for BC reasons, may be removed by
      //   https://www.drupal.org/node/2421941
      // - '<front>' -> '/'
      // - '<front>#foo' -> '/#foo'
      if (strpos($string, '<front>') === 0) {
        $string = '/' . substr($string, strlen('<front>'));
      }
      $uri = 'internal:' . $string;
    }

    return $uri;
  }
}
