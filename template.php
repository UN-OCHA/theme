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

function humanitarianresponse_preprocess_node(&$variables) {
  $node = $variables['node'];
  switch ($node->type) {
    case 'crf_request':

      // Get list of clusters
      $voc = taxonomy_vocabulary_machine_name_load('clusters');
      $query = new EntityFieldQuery();
      $result = $query
        ->entityCondition('entity_type', 'taxonomy_term')
        ->propertyCondition('vid', $voc->vid)
        ->execute();
      $clusters =  taxonomy_term_load_multiple(array_keys($result['taxonomy_term']));
    
      // Get list of content types checked
      $content_types = $node->field_crf_req_contents[LANGUAGE_NONE];
      $ctypes = array();
      $headers = array('');
      $rows = array();
      foreach ($content_types as $ctype) {
        $tmp = node_type_load(str_replace('_', '-', $ctype['value']));
        $ctypes[] = $tmp;
        $headers[] = $tmp->name;
      }
      
      foreach ($clusters as $cluster) {
        $row = array($cluster->name);
        foreach ($ctypes as $ctype) {
          $query = new EntityFieldQuery();
          $result = $query
            ->entityCondition('entity_type', 'node')
            ->entityCondition('bundle', $ctype->type)
            ->fieldCondition('field_cluster', 'tid', array($cluster->tid))
            ->fieldCondition('field_crf_request', 'target_id', array($node->nid))
            ->execute();
          if (empty($result)) {
            $row[] = l(t('Add @ct', array('@ct' => $ctype->name)), 'node/add/'.str_replace('_', '-', $ctype->type), 
              array('query' => 
                array(
                  array('edit' => 
                    array(
                      'field_crf_request' => array(LANGUAGE_NONE => $node->nid),
                      'field_cluster' => array(LANGUAGE_NONE => $cluster->tid)),
                  ),
                ),
              )
            );
          }
          else {
            $nodes = node_load_multiple(array_keys($result['node']));
            $content_node = reset($nodes);
            $workflow = workflow_get_workflow_states_by_sid($content_node->workflow);
            switch ($workflow->state) {
              case 'Save Draft':
                $txt = 'In Progress';
                $class = 'in-progress';
                break;
              case 'Submit':
                $txt = 'Submitted';
                $class = 'submitted';
                break;
              case 'Approve':
                $txt = 'Approved';
                $class = 'approved';
                break;
            }
            $row[] = array('data' => l($txt, 'node/'.$content_node->nid), 'class' => $class);
          }
        }
        $rows[] = $row;
      }
    
      $variables['crf_request_table'] = theme('table', array(
        'header' => $headers,
        'rows' => $rows,
        'attributes' => array(),
        'caption' => '',
        'colgroups' => array(),
        'sticky' => array(),
        'empty' => array(),
      ));

      break;
  }
}

function humanitarianresponse_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];
  if (arg(0) == 'taxonomy' && arg(1) == 'term') {
    $tid = arg(2);
    $term = taxonomy_term_load($tid);
    $voc = taxonomy_vocabulary_load($term->vid);
    if (($voc->machine_name == 'clusters' || $voc->machine_name == 'funding') && isset($breadcrumb[3])) {
      unset($breadcrumb[3]);
    }
    elseif ($voc->machine_name == 'coordination_hubs' && isset($breadcrumb[4])) {
      unset($breadcrumb[4]);
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
  $highcharts_config->chart->defaultSeriesType = $options['format']['chart_type'];
  $highcharts_config->chart->width = '960';
  $highcharts_config->title->text = $options['format']['title'];
  $highcharts_config->subtitle->text = $options['format']['subtitle'];

  if (strtolower($options['format']['legend_enabled']) == "yes") {
    $highcharts_config->legend->enabled = TRUE;
    $highcharts_config->legend->align = 'right';
    $highcharts_config->legend->verticalAlign = 'top';
    $highcharts_config->legend->x = 0;
    $highcharts_config->legend->y = 100;
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
  debug($vars);
}
