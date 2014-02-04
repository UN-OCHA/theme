<?php

/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 * @see html.tpl.php
 */
?>
<header id="header" class="header" role="header">
  <div class="container-fluid">
    <div id="top">
      <nav class="navbar navbar-default">
        <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>

          <div class="collapse navbar-collapse">
            <ul class="nav nav-tabs navbar-left">
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#"> HR <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <?php print render($main_menu_dropdown); ?>
                </ul>
              </li>
              <?php if ($og_group): ?>
                <li class="active"><?php print $og_group; ?></li>
              <?php endif; ?>
            </ul>
            <div class="navbar-right">
              <?php print render($page['top']); ?>
            </div>
          </div><!-- .navbar-collapse -->
        </div><!-- .container -->
      </nav>
    </div><!-- #top -->
  </div><!-- .container-fluid -->
  <div class="container">
    <div id="branding">
        <?php if ($logo): ?>
          <a href="<?php print $front_page; ?>" id="logo">
            <img src="<?php print $logo; ?>" alt="Humanitarianresponse Logo" />
          </a>
        <?php endif; ?>
        <?php print render($page['branding']); ?>
    </div>
    <div id="navigation">
      <?php print render($page['navigation']); ?>
    </div><!-- /.navigation -->
  </div> <!-- /.container -->
</header>

<div id="main-wrapper">
  <div id="main" class="main">
    <div class="container">
      <?php if ($breadcrumb): ?>
        <div id="breadcrumb" class="visible-desktop">
          <?php print $breadcrumb; ?>
        </div>
      <?php endif; ?>
      <?php if ($messages): ?>
        <div id="messages">
          <?php print $messages; ?>
        </div>
      <?php endif; ?>
      <?php if (!empty($page['sidebar_first'])): ?>
        <aside id="sidebar-first" class="col-sm-3" role="complementary">
          <?php print render($page['sidebar_first']); ?>
        </aside>
      <?php endif; ?>
      <div id="content-wrapper" <?php if(!empty($page['sidebar_first'])) print 'class="col-sm-9"'; ?>>
        <div id="page-header">
          <?php if ($title): ?>
            <div class="page-header">
              <h1 class="title"><?php print $title; ?></h1>
            </div>
          <?php endif; ?>
          <?php if ($tabs): ?>
            <div class="tabs">
              <?php print render($tabs); ?>
            </div>
          <?php endif; ?>
          <?php if ($action_links): ?>
            <ul class="action-links">
              <?php print render($action_links); ?>
            </ul>
          <?php endif; ?>
        </div>
        <div id="content" class="<?php print (!$is_panel) ? 'container' : ''; ?>">
          <?php print render($page['content']); ?>
        </div>
      </div><!-- /#content-wrapper -->
    </div><!-- #container -->
  </div> <!-- /#main -->
</div> <!-- /#main-wrapper -->

<footer id="footer" class="footer" role="footer">
  <div class="container">
    <?php render($page['footer']); ?>
  </div>
</footer>
