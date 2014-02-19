<?php
/**
 * @file
 * Theme functions
 */

require_once dirname(__FILE__) . '/includes/structure.inc';
require_once dirname(__FILE__) . '/includes/comment.inc';
require_once dirname(__FILE__) . '/includes/form.inc';
require_once dirname(__FILE__) . '/includes/menu.inc';
require_once dirname(__FILE__) . '/includes/node.inc';
require_once dirname(__FILE__) . '/includes/panel.inc';
require_once dirname(__FILE__) . '/includes/user.inc';
require_once dirname(__FILE__) . '/includes/view.inc';

/**
 * Implements hook_css_alter().
 */
function humanitarianresponse_css_alter(&$css) {
  $radix_path = drupal_get_path('theme', 'radix');

  // Radix now includes compiled stylesheets for demo purposes.
  // We remove these from our subtheme since they are already included 
  // in compass_radix.
  unset($css[$radix_path . '/assets/stylesheets/radix-style.css']);
  unset($css[$radix_path . '/assets/stylesheets/radix-print.css']);
}

/**
 * Implements template_preprocess_page().
 */
function humanitarianresponse_preprocess_page(&$variables) {
  $tree = menu_tree_page_data('main-menu', 1);
  $main_menu_dropdown = menu_tree_output($tree);
  $main_menu_dropdown['#theme_wrappers'] = array();
  $variables['main_menu_dropdown'] = $main_menu_dropdown;
  $variables['og_group'] = '';
  if (module_exists('og_context')) {
    $gid = og_context_determine_context('node');
    if (!empty($gid)) {
      $og_group = entity_load('node', array($gid));
      $og_group = $og_group[$gid];
      $uri = entity_uri('node', $og_group);
      $variables['og_group'] = l($og_group->title, $uri['path'], $uri['options']);
    }
  }
  // Add copyright to theme.
  if ($copyright = theme_get_setting('copyright')) {
    $variables['copyright'] = check_markup($copyright['value'], $copyright['format']);
  }
}
