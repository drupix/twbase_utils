<?php

/**
 * @file
 * Administration forms for the Scheduler module.
 */

use Drupal\Core\Form\FormStateInterface;
use Drupal\node\NodeTypeInterface;
use Drupal\Core\Url;

/**
 * Helper function for the real hook_form_node_type_form_alter().
 *
 * @see twbase_form_node_type_form_alter()
 */
function _twbase_utils_form_node_type_form_alter(&$form, FormStateInterface $form_state) {
  // Get the TWBase Theme config
  $config = \Drupal::config('twbase.settings');

  /** @var \Drupal\node\NodeTypeInterface $type */
  $type = $form_state->getFormObject()->getEntity();

  $form['#attached']['library'][] = 'twbase_utils/vertical-tabs';

  $form['twbase_theme'] = [
    '#type' => 'details',
    '#title' => t('TWBase Theme'),
    // '#attached' => [
    //   'library' => ['menu_ui/drupal.menu_ui.admin'],
    // ],
    // '#tree' => TRUE,
    '#group' => 'additional_settings',
  ];

	$form['twbase_theme']['showcase'] = [
		'#type' => 'fieldset',
		'#title' => t('Showcase'),
		'#collapsible' => TRUE,
		'#collapsed' => FALSE,
	];

  $route_parameters = ['theme' => 'twbase'];
  $options = ['fragment' => 'edit-showcase-options'];
	$msg_infos = t('These options override the global options defined at the <a href="@link">theme level</a>.',
    ['@link' => Url::fromRoute('system.theme_settings_theme', $route_parameters, $options)->toString()]
  );

  $form['twbase_theme']['showcase']['overriden_infos'] = [
    '#markup' => $msg_infos,
		'#prefix' => '<p>',
		'#suffix' => '</p>',
  ];

  $form['twbase_theme']['showcase']['twbase_utils_showcase_enabled'] = [
    '#type' => 'checkbox',
    '#title' => t('Provide the default showcase'),
    '#default_value' => $type->getThirdPartySetting('twbase_utils', 'showcase_enabled', $config->get('default_showcase_enabled')),
    '#description' => t('Provide the default showcase'),
  ];

  $form['twbase_theme']['showcase']['showcase_options'] = [
    '#type' => 'container',
    '#states' => [
      'invisible' => [
        'input[name="twbase_utils_showcase_enabled"]' => ['checked' => FALSE],
      ],
    ],
  ];

  $form['twbase_theme']['showcase']['showcase_options']['twbase_utils_showcase_display_title'] = [
    '#type' => 'checkbox',
    '#title' => t('Display title'),
    '#default_value' => $type->getThirdPartySetting('twbase_utils', 'showcase_display_title', $config->get('default_showcase_display_title')),
    '#description' => t('Display the node title in the showcase'),
  ];

  $route_parameters = ['node_type' => $type->get('type')];
  $options = ['fragment' => 'edit-display'];
  $form['twbase_theme']['showcase']['showcase_options']['twbase_utils_showcase_display_submitted'] = [
    '#type' => 'checkbox',
    '#title' => t('Display author and date information'),
    '#default_value' => $type->getThirdPartySetting('twbase_utils', 'showcase_display_submitted', $config->get('default_showcase_display_submitted')),
    '#description' => t('Display the node author and the publication date in the showcase.') . '<br>' .
											'<strong>' .
                      t('The <a href="#" class="open-tab-display">Display settings</a> will takes precedence over this option.' .
                      '</strong>'),
  ];

  $form['twbase_theme']['showcase']['showcase_options']['twbase_utils_showcase_display_image'] = [
    '#type' => 'checkbox',
    '#title' => t('Use image field (if exists)'),
    '#default_value' => $type->getThirdPartySetting('twbase_utils', 'showcase_display_image', $config->get('default_showcase_display_image')),
    '#description' => t('Display the image field (field_image) as showcase image'),
  ];

	// Swirl decoration
	$form['twbase_theme']['showcase']['showcase_options']['twbase_utils_showcase_deco'] = [
		'#type' => 'checkbox',
		'#title'         => t('Swirl decoration'),
		'#default_value' => $type->getThirdPartySetting('twbase_utils', 'showcase_deco', $config->get('default_showcase_deco')),
		'#description' => t('If checked a nice swirl is added under the showcase region on <strong>all pages (except the frontpage)</strong>.'),
	];

  // $form['#validate'][] = 'twbase_utils_form_node_type_form_validate';
  $form['#entity_builders'][] = 'twbase_utils_form_node_type_form_builder';
}

/**
 * Entity builder for the node type form with TWbase Theme options.
 */
function twbase_utils_form_node_type_form_builder($entity_type, NodeTypeInterface $type, &$form, FormStateInterface $form_state) {
  $type->setThirdPartySetting('twbase_utils', 'showcase_enabled', $form_state->getValue('twbase_utils_showcase_enabled'));
  $type->setThirdPartySetting('twbase_utils', 'showcase_display_title', $form_state->getValue('twbase_utils_showcase_display_title'));
  $type->setThirdPartySetting('twbase_utils', 'showcase_display_submitted', $form_state->getValue('twbase_utils_showcase_display_submitted'));
  $type->setThirdPartySetting('twbase_utils', 'showcase_display_image', $form_state->getValue('twbase_utils_showcase_display_image'));
  $type->setThirdPartySetting('twbase_utils', 'showcase_deco', $form_state->getValue('twbase_utils_showcase_deco'));
}
