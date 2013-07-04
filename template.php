<?php

/**
 * @file
 * This file is empty by default because the base theme chain (Alpha & Omega) provides
 * all the basic functionality. However, in case you wish to customize the output that Drupal
 * generates through Alpha & Omega this file is a good place to do so.
 * 
 * Alpha comes with a neat solution for keeping this file as clean as possible while the code
 * for your subtheme grows. Please read the README.txt in the /preprocess and /process subfolders
 * for more information on this topic.
 */
function humanitarianresponse_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];
  if (arg(0) == 'taxonomy' && arg(1) == 'term') {
    $tid = arg(2);
    if ($tid != 'all') {
      $term = taxonomy_term_load($tid);
      $voc = taxonomy_vocabulary_load($term->vid);
      if (($voc->machine_name == 'clusters' || $voc->machine_name == 'funding') && isset($breadcrumb[3])) {
        unset($breadcrumb[3]);
      }
      elseif ($voc->machine_name == 'coordination_hubs' && isset($breadcrumb[4])) {
        unset($breadcrumb[4]);
      }
    }
  }
  elseif (arg(0) == 'search') {
    if (strpos($breadcrumb[0], 'Home') !== FALSE && strpos($breadcrumb[1], 'Home') !== FALSE) {
      unset($breadcrumb[0]);
    }
  }
  
  $items = array();
  foreach ($breadcrumb as $i => $crumb) {
    $tmp = strip_tags($crumb);
    if (empty($tmp)) {
      $items[] = $i;
    }
  }
  
  foreach ($items as $item) {
    unset($breadcrumb[$item]);
  }
  
  if (!empty($breadcrumb)) {
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';

    $output .= '<div class="breadcrumb">' . implode(' » ', $breadcrumb) . '</div>';
    return $output;
  }

  return $breadcrumb;
}

function humanitarianresponse_preprocess_block(&$vars) {  
  if ($vars['block']->module == 'views' && $vars['block']->delta == '-exp-requests-page') {
    $vars['block']->subject = t('Filter Requests');
  }
}

function humanitarianresponse_views_data_export_feed_icon($variables) {
  extract($variables, EXTR_SKIP);
  $url_options = array('html' => true);
  if ($query) {
    $url_options['query'] = $query;
  }
  /*if (in_array(substr($url, -4), array('.csv', '.xls', '.xml'))) {
    return l("", $url, $url_options);
  }
  else {*/
    $path = drupal_get_path('theme', 'humanitarianresponse');
    $ext = strtolower($text);
    $image_path = $path . '/images/files_icons/32px/'.$ext.'.png';
    $image = theme('image', array('path' => $image_path, 'alt' => $text, 'title' => $text));
    return l($image, $url, $url_options);
  //}
}

function humanitarianresponse_views_pdf_icon($vars) {
  $title = $vars['title'];
  $path = $vars['path'];
  $options = $vars['options'];
  $options['html'] = TRUE;
  $options['attributes']['class'] = 'pdf-icon';

  $imagePath = drupal_get_path('theme', 'humanitarianresponse') . '/images/files_icons/32px/pdf.png';

  if ($image = theme('image', array('path' => $imagePath, 'title' => $title, 'alt' => $title))) {
    return l($image, $path, $options);
  }
}

/**
 * Preprocess page: hack to add Highcharts theme that will need to be replaced in the future
 */
function humanitarianresponse_preprocess_page(&$variables) {
  if (module_exists('libraries')) {
    module_load_include('module', 'libraries', 'libraries');
    $path = libraries_get_path('highcharts');
    if (is_dir('./' . $path)) {
      drupal_add_js(drupal_get_path('theme', 'humanitarianresponse').'/js/highcharts/ocha.js', array('scope' => 'footer'));
    }
  }
}

function humanitarianresponse_field__field_media__gallery($variables) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$variables['label_hidden']) {
    $output .= '<div class="field-label"' . $variables['title_attributes'] . '>' . $variables['label'] . ':&nbsp;</div>';
  }

  // Render the items.
  $output .= '<div class="field-items"' . $variables['content_attributes'] . '>';
  foreach ($variables['items'] as $delta => $item) {
    $classes = 'field-item grid-4 ' . ($delta % 2 ? 'odd' : 'even');
    $tmp = $delta % 4;
    if ($tmp == 0) {
      $classes .= ' alpha';
    }
    elseif ($tmp == 3) {
      $classes .= ' omega';
    }
    $output .= '<div class="' . $classes . '"' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</div>';
  }
  $output .= '</div>';

  // Render the top-level DIV.
  $output = '<div class="' . $variables['classes'] . '"' . $variables['attributes'] . '>' . $output . '</div>';

  return $output;
}
