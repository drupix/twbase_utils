# TWBase Theme Utilities

[![drupal](https://img.shields.io/badge/drupal-^8.9%20||%20^9-blue.svg?style=flat-square&logo=drupal)](https://drupal.org/)
[![LICENSE](https://img.shields.io/github/license/drupix/twbase_utils?style=flat-square)](https://raw.githubusercontent.com/drupix/twbase_utils/master/LICENSE.txt)

**:warning: WARNING: TWBase Theme Utilities is under development and not yet ready for production 🐞**

## Advices

This module is intended for use with [TWBase Theme](https://github.com/drupix/twbase).

Actually it does two things:

1. it add `prose` (`tw-prose`) wrapper class to the editor `body` that allows tailwindcss-typography to works correctly while editing a node in the admin section
2. it add extra styles for custom colors (`text-primary`, `text-secondary`, `text-success`, `text-danger`, `text-info`) and `dropcap` for a Dropcap (big letter at the beginning of a sentence/word) that you can then define in the theme `src/components/base-text-css` and another extra class `lead` to fully match with tailwindcss-typography

**To have extra classes working you must add the "Styles" button to your CKEditor configuration at `/admin/config/content/formats/manage/full_html`**

## INSTALLATION

* Install as you would normally install a contributed Drupal module. Visit:
  <https://www.drupal.org/node/1897420> for further information.

## MAINTAINERS

Current maintainers:

* [drupix](https://www.drupal.org/u/drupix)

This project has been sponsored by myself!
