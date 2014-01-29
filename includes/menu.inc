<?php
/**
 * @file
 * Theme and preprocess functions for menus
 */

function _humanitarianresponse_menu_main_menu(&$variables) {
  return '<nav class="navbar navbar-default" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
            <span class="sr-only">'.t("Toggle navigation").'</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div> <!-- /.navbar-header -->

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar-collapse">
          <ul class="menu nav nav-pills">'.$variables["tree"].'</ul>
        </div><!-- /.navbar-collapse -->
      </nav>';
}

/**
 * Implements theme_menu_tree().
 */
function humanitarianresponse_menu_tree__main_menu(&$variables) {
  return _humanitarianresponse_menu_main_menu($variables);
}

/**
 * Implements theme_menu_tree__menu_block().
 */
function humanitarianresponse_menu_tree__menu_block(&$variables) {
  return '<ul class="menu nav nav-pills">' . $variables['tree'] . '</ul>';
}

function humanitarianresponse_menu_tree__menu_block__tertiary_nav(&$variables) {
  return _humanitarianresponse_menu_main_menu($variables);
}
