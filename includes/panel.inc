<?php
/**
 * @file
 * Theme and preprocess functions for panels and panes.
 */

function humanitarianresponse_field__field_documents($variables) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$variables['label_hidden']) {
    $output .= '<div class="field-label"' . $variables['title_attributes'] . '>' . $variables['label'] . ':&nbsp;</div>';
  }

  // Render the items.
  if ($variables['element']['#view_mode'] == 'link') {
    $output .= '<ul class="field-items"' . $variables['content_attributes'] . '>';
    foreach ($variables['items'] as $delta => $item) {
      $classes = 'field-item ' . ($delta % 2 ? 'odd' : 'even');
      $output .= '<li class="' . $classes . '"' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</li>';
    }
    $output .= '</ul>';
  }
  else {
    $output .= '<div class="field-items"' . $variables['content_attributes'] . '>';
    foreach ($variables['items'] as $delta => $item) {
      $classes = 'field-item ' . ($delta % 2 ? 'odd' : 'even');
      $output .= '<div class="' . $classes . '"' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</div>';
    }
    $output .= '</div>';
  }

  // Render the top-level DIV.
  $output = '<div class="' . $variables['classes'] . '"' . $variables['attributes'] . '>' . $output . '</div>';

  return $output;
}
