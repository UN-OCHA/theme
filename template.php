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
