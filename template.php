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

function humanitarianresponse_preprocess_views_highcharts(&$vars) {  
  $node = menu_get_object();
  
  $options = $vars['options'];
  module_load_include("module", "libraries", "libraries");
  if ($path = libraries_get_path("highcharts")) {
    drupal_add_js($path . '/js/highcharts.js');
    drupal_add_js($path . '/js/modules/exporting.js');
  }
  drupal_add_js(drupal_get_path('module', 'views_highcharts') . '/js/views_highcharts.js');
  module_load_include('inc', 'uuid', 'uuid');
  $chart_id = "views-highcharts-" . uuid_generate();
  $view = $vars['view'];
  $fields = $view->get_items("field");
  $data = array();
  $type = ($options['format']['chart_type'] == "pie") ? "pie" : "bar";
  $highcharts_config = json_decode(file_get_contents(drupal_get_path("module", "views_highcharts") . "/defaults/bar-basic.json"));
  $highcharts_config->colors = array('#B91222', '#B95222', '#B99222');
  $highcharts_config->chart->defaultSeriesType = $options['format']['chart_type'];
  $highcharts_config->chart->backgroundColor = '#fff';
  
  if (isset($node->field_crf_request)) {
    $request = node_load($node->field_crf_request['und'][0]['target_id']);
    $indicator_data_type = t('Performance-related');
    if ($view->name == 'situational_indicator_data_batch') {
      $indicator_data_type = t('Situational');
    }
    $highcharts_config->title->text = t('@type Indicator Data for Request @title', array('@type' => $indicator_data_type, '@title' => $request->title));
  }
  else {
    $highcharts_config->title->text = $options['format']['title'];
  }
  
  $highcharts_config->title->style->color = '#000';
  $highcharts_config->title->style->font = '16px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif';
  $highcharts_config->subtitle->text = $options['format']['subtitle'];

  if (strtolower($options['format']['legend_enabled']) == "yes") {
    $highcharts_config->legend->enabled = TRUE;
    $highcharts_config->legend->align = 'right';
    $highcharts_config->legend->verticalAlign = 'top';
    $highcharts_config->legend->x = 0;
    $highcharts_config->legend->y = 10;
    $highcharts_config->legend->borderWidth = 0;
    $highcharts_config->legend->shadow = FALSE;
  }
  else {
    $highcharts_config->legend->enabled = FALSE;
  }
	if ($options['format']['chart_type']!= "pie") {
		$highcharts_config->yAxis->title->text = $options['y_axis']['title'];
	  $highcharts_config->yAxis->title->align = $options['y_axis']['title_align'];
	  if ($options['x_axis']["reversed"] != FALSE) {
		  $highcharts_config->xAxis->reversed = TRUE;
	  }
	  if ($options['y_axis']["reversed"] != FALSE) {
		  $highcharts_config->yAxis->reversed = TRUE;
	  }
	  if ($options['format']['swap_axes'] != FALSE) {
		  $highcharts_config->chart->inverted = TRUE;
	  }
	}

  
  $highcharts_config->chart->renderTo = $chart_id;
  $highcharts_config->series = array();
  $highcharts_config->xAxis->categories = array();
  if (is_array($options)
    && is_array($options['x_axis']['dataset_data'])
    && is_array($fields)
  ) {
    foreach ($options['x_axis']['dataset_data'] as $key) {
      if ($key != FALSE) {
        $vars['fields'][$key] = $fields[$key];
      }
	  
    }
  }


  $highcharts_config->xAxis->categories = array();
  foreach ($view->style_plugin->render_tokens as $result_index => $row) {
    foreach ($row as $field_name => $field) {
		  $check = str_split($field_name);
      if ($check[0] !==  '%' && $check[0] !== '!') {
        $f = str_replace(array('[',']'), '', $field_name);
        if ($options['x_axis']['dataset_data'][$f]) {
          $data[$f][] = (float)$field;
        }
      }
    }
  	if (!empty($options['x_axis']['dataset_label'])) {
  		$highcharts_config->xAxis->categories[] = $row["[".$options['x_axis']['dataset_label']."]"];		
  	}
  }

  $highcharts_config->xAxis->labels->style->color = '#000';
  $highcharts_config->xAxis->labels->style->font = 'normal 10px Arial, Verdana, sans-serif';
  $highcharts_config->xAxis->title->style->font = 'normal 10px Arial, Verdana, sans-serif';
  
  $highcharts_config->yAxis->labels->style->color = '#000';
  $highcharts_config->yAxis->labels->style->font = 'normal 10px Arial, Verdana, sans-serif';
  $highcharts_config->yAxis->title->style->font = 'normal 10px Arial, Verdana, sans-serif';

  // Assign field labels
  foreach (array_keys($vars['fields']) as $field_name) {
    if (array_key_exists($field_name, $data)) {
      $info = field_info_instance('node', $field_name, 'indicator_data');
      if (empty($info)) {
        $info = field_info_instance('node', $field_name, 'humanitarian_profile');
      }      
      $vars['fields'][$field_name]['label'] = $info['label'];
    }
  }

	if (function_exists("highcharts_series_" . $options['format']['chart_type'])) {
		//if there's a specialized data writer, return data from data writer
		$highcharts_config->series = array(call_user_func("highcharts_series_".$options['format']['chart_type'], $data, $fields, $options));
	} else {
		//else get a standard series
		$highcharts_config->series = highcharts_series($data, $vars['fields']);
	}

  drupal_add_js(array("views_highcharts" => array($chart_id => $highcharts_config)), "setting");
  $vars['chart_id'] = $chart_id;
}

function humanitarianresponse_preprocess_block(&$vars) {  
  if ($vars['block']->module == 'user' && $vars['block']->delta == 'login') {
    $vars['content'] = humanitarianresponse_persona_login_button();
  }
  else if ($vars['block']->module == 'views' && $vars['block']->delta == '-exp-requests-page') {
    $vars['block']->subject = t('Filter Requests');
  }
  elseif ($vars['block']->module == 'persona' && $vars['block']->delta == 'buttons') {
    $content = $vars['content'];
    $patterns[0] = '/persona-sign-in/';
    $patterns[1] = '/persona-sign-out/';
    $replacements[0] = 'persona-sign-in persona-button humanitarianresponse';
    $replacements[1] = 'persona-sign-out persona-button humanitarianresponse';
    $vars['content'] = preg_replace($patterns, $replacements, $content);
  }
}

function humanitarianresponse_persona_login_button() {
  $path = drupal_get_path('theme', 'humanitarianresponse');
  $vars = array('width' => 79, 'height' => 22, 'alt' => t('Sign in with Persona'), 'attributes' => array('class' => array('persona-login persona-sign-in'), 'style' => 'cursor: pointer;'));
  $img = theme('image', $vars + array('path' => $path . '/images/sign_in_red.png'));
  return $img;
}

function humanitarianresponse_views_data_export_feed_icon($variables) {
  extract($variables, EXTR_SKIP);
  $url_options = array('html' => true);
  if ($query) {
    $url_options['query'] = $query;
  }
  if (in_array(substr($url, -4), array('.csv', '.xls', '.xml'))) {
    return l("", $url, $url_options);
  }
  else {
    $path = drupal_get_path('theme', 'humanitarianresponse');
    $ext = strtolower($text);
    if ($ext == 'csv') {
      $ext = 'xls';
    }
    $image_path = $path . '/images/files_icons/icon_'.$ext.'.gif';
    $image = theme('image', array('path' => $image_path, 'alt' => $text, 'title' => $text));
    return l($image, $url, $url_options);
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
