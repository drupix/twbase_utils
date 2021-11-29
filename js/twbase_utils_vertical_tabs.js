/**
 * @file
 * Defines jQuery to provide summary information inside vertical tabs.
 */


(function ($, Drupal) {

  'use strict';

  /**
   * Provide summary information for vertical tabs during content type configuration.
   */
  Drupal.behaviors.twbaseUtilsSettings = {
    attach: function (context) {
      var $context = $(context);
      $context.find('#edit-twbase-theme').drupalSetSummary(function (context) {
        var vals = [];
        if ($(context).find('#edit-twbase-utils-showcase-enabled').is(':checked')) {
          vals.push(Drupal.t('Default showcase enabled.'));

          if ($(context).find('#edit-twbase-utils-showcase-display-title').is(':checked')) {
            vals.push(Drupal.t('Display title.'));
          }
          if ($(context).find('#edit-twbase-utils-showcase-display-submitted').is(':checked')) {
            vals.push(Drupal.t('Display submitted.'));
          }
          if ($(context).find('#edit-twbase-utils-showcase-display-image').is(':checked')) {
            vals.push(Drupal.t('Display image field.'));
          }
          if ($(context).find('#edit-twbase-utils-showcase-deco').is(':checked')) {
            vals.push(Drupal.t('Display swirl decoration.'));
          }
        }
        else {
          vals.push(Drupal.t('Default showcase disabled.'));
        }
        return vals.join('<br/>');
      });

      $context.find('a.open-tab-display').once('a.open-tab-display').on('click', function(event) {
        $(context).find('a[href$="edit-display"]').trigger('click');
      });
    }
  };

})(jQuery, Drupal);
