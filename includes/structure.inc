<?php
/**
 * @file
 * Theme and preprocess functions for breadcrumbs, messages, tabs..etc
 */

function _humanitarianresponse_get_file_properties($item) {
  $return = array();
  $file = $item['field_file']['#items'][0];
  $language = isset($item['field_language']) ? $item['field_language']['#items'][0] : NULL;
  $return['path'] = file_create_url($file['uri']);
  $return['title'] = empty($file['title']) ? $file['filename'] : $file['title'];
  $return['language'] = !empty($language) ? '(' . $language['name_native'] . ')' : '';
  return $return;
}

/**
 * Implements theme_field()
 */
function humanitarianresponse_field__field_files_collection($variables) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$variables['label_hidden']) {
    $output .= '<div class="field-label"' . $variables['title_attributes'] . '>' . $variables['label'] . ':&nbsp;</div>';
  }

  // Render the items.
  $output .= '<div class="field-items"' . $variables['content_attributes'] . '>';
  if ($variables['element']['#field_type'] == 'field_collection') {
    if (count($variables['items']) > 1) {
      $output .= '<div class="btn-group">';
      $output .= '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
        download <span class="caret"></span>
      </button>';
      $output .= '<ul class="dropdown-menu">';
      foreach ($variables['items'] as $delta => $item) {
        $id = $variables['element']['#items'][$delta]['value'];
        $props = _humanitarianresponse_get_file_properties($item['entity']['field_collection_item'][$id]);
        $output .= '<li>'.l($props['title'].' '.$props['language'], $props['path']).'</a></li>';
      }
      $output .= '</ul>';
      $output .= '</div>';
    }
    else {
      $item = $variables['items'][0];
      $id = $variables['element']['#items'][0]['value'];
      $props = _humanitarianresponse_get_file_properties($item['entity']['field_collection_item'][$id]);
      $output .= l(t('download @language', array('@language' => $props['language'])), $props['path'], array('attributes' => array('class' => array('btn', 'btn-default'))));
    }
  }
  $output .= '</div>';

  // Render the top-level DIV.
  $output = '<div class="' . $variables['classes'] . '"' . $variables['attributes'] . '>' . $output . '</div>';

  return $output;
}

