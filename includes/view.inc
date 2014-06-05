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

