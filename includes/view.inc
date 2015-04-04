<?php
/**
 * @file
 * Theme and preprocess functions for views
 */

/**
 * Implements template_preprocess().
 */
function humanitarianresponse_preprocess_views_view(&$variables) {
  $variables['feed_icons'] = drupal_get_feeds();
}

function humanitarianresponse_feed_icon($variables) {
  $text = t('Subscribe to !feed-title', array('!feed-title' => $variables['title']));
  return l('<i class="fa fa-rss-square fa-lg"></i>', $variables['url'], array('html' => TRUE, 'attributes' => array('class' => array('feed-icon'), 'title' => $text)));
}

function humanitarianresponse_views_data_export_feed_icon($variables) {
  extract($variables, EXTR_SKIP);
  $url_options = array('html' => TRUE);
  if ($query) {
    $url_options['query'] = $query;
  }
  $image = theme('image', array('path' => $image_path, 'alt' => $text, 'title' => $text));
  return '<li>'.l($text, $url, $url_options).'</li>';
}

/**
 * Theme function for the PDF icon of appended PDFs.
 */
function humanitarianresponse_views_pdf_icon($vars) {
  $title = $vars['title'];
  $path = $vars['path'];
  $options = $vars['options'];
  return '<li>'.l('PDF', $path, $options).'</li>';
}

/**
 * Theme function for ical icon
 */
function humanitarianresponse_date_ical_icon($variables) {
  if (empty($variables['tooltip'])) {
    $variables['tooltip'] = t('Add this event to my calendar');
  }
  return '<li>'.l('ICAL', $variables['url']).'</li>';
}

function _humanitarianresponse_views_get_node_gid($nid) {
  $space_nid = 0;
  $node = node_load($nid);
  if (!in_array($node->type, array('hr_space', 'hr_operation', 'hr_sector'))) {
    $space_nid = $node->og_group_ref[LANGUAGE_NONE][0]['target_id'];
  }
  else {
    $space_nid = $nid;
  }
  return $space_nid;
}
/**
 * More links
 */
function humanitarianresponse_views_more__hr_documents_panes__list($view) {
  $space_nid = _humanitarianresponse_views_get_node_gid(arg(1));
  return '<div class="more-link">'.l(t('more'), 'node/'.$space_nid.'/documents').'</div>';
}

function humanitarianresponse_views_more__hr_infographics_panes__list($view) {
  $space_nid = _humanitarianresponse_views_get_node_gid(arg(1));
  return '<div class="more-link">'.l(t('more'), 'node/'.$space_nid.'/infographics').'</div>';
}

/**
 * Preprocess function for Date pager template.
 * Override the pager for the printable calendar. See http://atrium.humanitarianresponse.info/humanitarian_response/node/4466
 */
function humanitarianresponse_preprocess_date_views_pager(&$vars) {
  $view = $vars['plugin']->view;
  if ($view->name == 'hr_events_calendar' && $view->current_display == 'print_week') {
    $date_info = $view->date_info;
    $format = 'F d, Y';
    $title = t('@mindate to @maxdate', array('@mindate' => date_format_date($date_info->min_date, 'custom', $format), '@maxdate' => date_format_date($date_info->max_date, 'custom', $format)));
    $vars['nav_title'] = $title;
    $vars['prev_url'] = '';
    $vars['next_url'] = '';
  }
}

