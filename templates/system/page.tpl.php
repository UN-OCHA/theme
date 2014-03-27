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
<div id="root">
  <header id="header" class="header" role="header">
    <div class="container">
      <div id="top">
        <nav class="navbar navbar-default">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header top">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
            </div>

            <div class="collapse navbar-collapse">
              <ul id="hr-space-tab" class="nav nav-tabs navbar-left">
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
        </nav>
      </div><!-- #top -->
    </div><!-- .container-fluid -->
    <div class="container header">
      <div id="branding">
          <?php if ($logo): ?>
            <a href="<?php print $front_page; ?>" id="logo">
              <img src="<?php print $logo; ?>" alt="Humanitarianresponse Logo" />
            </a>
          <?php endif; ?>
          <div class="pull-right">
            <div id="header-image"><?php print $og_group_header_image; ?></div>
            <?php print render($page['branding']); ?>
          </div>
      </div>
      <div id="navigation">
        <?php print render($page['navigation']); ?>
      </div><!-- /.navigation -->
    </div> <!-- /.container -->
  </header>

  <div id="main-wrapper">
    <div id="main" class="main">
      <div class="container">
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
          <div id="content" class="col-sm-12 <?php print (!$is_panel) ? 'container' : ''; ?>">
            <?php print render($page['content']); ?>
          </div>
        </div><!-- /#content-wrapper -->
      </div><!-- #container -->
    </div> <!-- /#main -->
  </div> <!-- /#main-wrapper -->

  <div id="root_footer"></div>

  <footer id="footer" class="footer" role="footer">
    <div class="container">
      <div class="col-sm-3">
        <p><a href="http://www.humanitarianresponse.info" target="_blank">HumanitarianResponse.info</a> is provided by UN OCHA to support humanitarian operations globally</p><p><a href="http://www.humanitarianresponse.info/about" target="_blank">Learn more about HumanitarianResponse.info</a></p><p><a href="http://www.unocha.org" target="_blank"><img alt="OCHA logo" src="/sites/all/themes/humanitarianresponse/assets/images/ocha.png"></a></p>
      </div>
      <div class="col-sm-6">
        <div class="col-sm-3">
          <div class="column"><a href="http://fts.unocha.org" target="_blank"><img src="/sites/all/themes/humanitarianresponse/assets/images/footer/logo-fts.png"></a></div>
          <div class="column"><a href="http://www.irinnews.org" target="_blank"><img src="/sites/all/themes/humanitarianresponse/assets/images/footer/logo-irin.png"></a></div>
          <div class="column"><a href="http://www.gdacs.org" target="_blank"><img src="/sites/all/themes/humanitarianresponse/assets/images/footer/logo-gdacs.png"></a></div>
        </div>

        <div class="col-sm-3">
          <div class="column"><a href="http://www.unocha.org/cerf/" target="_blank"><img src="/sites/all/themes/humanitarianresponse/assets/images/footer/logo-cerf.png"></a></div>
          <div class="column"><a href="http://www.unocha.org/cap/" target="_blank"><img src="/sites/all/themes/humanitarianresponse/assets/images/footer/logo-cap.png"></a></div>
          <div class="column"><a href="http://www.reliefweb.int/" target="_blank"><img src="/sites/all/themes/humanitarianresponse/assets/images/footer/logo-footer.png"></a></div>
        </div>

        <div class="col-sm-3">
          <div class="column"><a href="http://www.humanitarianinfo.org/iasc/" target="_blank"><img src="/sites/all/themes/humanitarianresponse/assets/images/footer/logo-iasc.png"></a></div>
          <div class="column"><a href="http://www.redhum.org" target="_blank"><img src="/sites/all/themes/humanitarianresponse/assets/images/footer/logo-redhum.png"></a></div>
          <div class="column"><a href="http://www.preventionweb.net/" target="_blank"><img src="/sites/all/themes/humanitarianresponse/assets/images/footer/logo-pw.png"></a></div>
        </div>

        <div class="col-sm-3">
          <div class="column"><a href="http://www.hewsweb.org" target="_blank"><img src="/sites/all/themes/humanitarianresponse/assets/images/footer/logo-hews.png"></a></div>
          <div class="column"><a href="http://vosocc.unocha.org" target="_blank"><img src="/sites/all/themes/humanitarianresponse/assets/images/footer/logo-vosocc.png"></a></div>
        </div>
      </div>
      <div class="col-sm-3">
        <i class="fa fa-envelope"></i> <a href="mailto:info@humanitarianresponse.info">info@humanitarianresponse.info</a><br />
        <i class="fa fa-question-circle"></i> <a href="mailto:help@humanitarianresponse.info">help@humanitarianresponse.info</a><br />
        <i class="fa fa-info-circle"></i> <a href="http://www.humanitarianresponse.info">humanitarianresponse.info</a><br />
        <i class="fa fa-rss-square"></i> <a href="/feed">RSS feed</a>
      </div>
    </div>
  </footer>
</div><!-- /#root -->
