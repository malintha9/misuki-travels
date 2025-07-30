<footer class="u-clearfix u-footer u-grey-80" id="footer">
  <div class="u-clearfix u-sheet u-sheet-1">
    <div class="data-layout-selected u-clearfix u-expanded-width u-gutter-30 u-layout-wrap u-layout-wrap-1">
      <div class="u-gutter-0 u-layout">
        <div class="u-layout-row">
          <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-left-cell u-size-20 u-layout-cell-1">
            <div class="u-container-layout u-container-layout-1"><!--position-->
              <?php $sidebar_html = theme_sidebar(array(
            'id' => 'area_1',
            'template' => <<<WIDGET_TEMPLATE
                <div class="u-block">
                  <div class="u-block-container u-clearfix"><!--block_header-->
                    <h5 class="u-block-header u-text">{block_header}</h5><!--/block_header--><!--block_content-->
                    <div class="u-block-content u-text">{block_content}</div><!--/block_content-->
                  </div>
                </div>
WIDGET_TEMPLATE
        )); ?> <div data-position="Widget Area 1" class="u-position"><!--block-->
                <?php if ($sidebar_html) { echo stylingDefaultControls($sidebar_html); } else { ?> <div class="u-block">
                  <div class="u-block-container u-clearfix"><!--block_header-->
                    <h5 class="u-block-header u-text"><!--block_header_content_replacer--><!--block_header_content-->travel <!--/block_header_content--><!--/block_header_content_replacer--></h5><!--/block_header--><!--block_content-->
                    <div class="u-block-content u-text"><!--block_content_content--> Block content. Lorem ipsum dolor sit amet, consectetur adipiscing elit nullam nunc justo sagittis suscipit ultrices. <!--/block_content_content--></div><!--/block_content-->
                  </div>
                </div> <?php } ?><!--/block-->
              </div><!--/position-->
            </div>
          </div>
          <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-size-20 u-layout-cell-2">
            <div class="u-container-layout u-container-layout-2"><!--position-->
              <?php $sidebar_html = theme_sidebar(array(
            'id' => 'area_2',
            'template' => <<<WIDGET_TEMPLATE
                <div class="u-block">
                  <div class="u-block-container u-clearfix"><!--block_header-->
                    <h5 class="u-block-header u-text">{block_header}</h5><!--/block_header--><!--block_content-->
                    <div class="u-block-content u-text">{block_content}</div><!--/block_content-->
                  </div>
                </div>
WIDGET_TEMPLATE
        )); ?> <div data-position="Widget Area 2" class="u-position u-position-2"><!--block-->
                <?php if ($sidebar_html) { echo stylingDefaultControls($sidebar_html); } else { ?> <div class="u-block">
                  <div class="u-block-container u-clearfix"><!--block_header-->
                    <h5 class="u-block-header u-text"><!--block_header_content_replacer--><!--block_header_content-->Services <!--/block_header_content--><!--/block_header_content_replacer--></h5><!--/block_header--><!--block_content-->
                    <div class="u-block-content u-text"><!--block_content_content--><!--/block_content_content--></div><!--/block_content-->
                  </div>
                </div> <?php } ?><!--/block-->
              </div><!--/position-->
              <?php
            ob_start();
            ?><nav class="u-hover-color-var u-menu u-menu-one-level u-offcanvas u-menu-1" role="navigation" data-responsive-from="MD">
                <div class="menu-collapse" style="font-size: 1rem; letter-spacing: 0px; font-weight: 500;">
                  <a class="u-button-style u-custom-active-border-color u-custom-active-color u-custom-border u-custom-border-color u-custom-borders u-custom-hover-border-color u-custom-hover-color u-custom-left-right-menu-spacing u-custom-text-active-color u-custom-text-color u-custom-text-decoration u-custom-text-hover-color u-custom-top-bottom-menu-spacing u-hamburger-link u-nav-link" aria-label="Open menu" aria-controls="ddb2" href="#">
                    <svg class="u-svg-link" viewBox="0 0 24 24"><use xlink:href="#svg-68dd"></use></svg>
                    <svg class="u-svg-content" version="1.1" id="svg-68dd" viewBox="0 0 16 16" x="0px" y="0px" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"><g><rect y="1" width="16" height="2"></rect><rect y="7" width="16" height="2"></rect><rect y="13" width="16" height="2"></rect>
</g></svg>
                  </a>
                </div>
                <div class="u-custom-menu u-nav-container">
                  {menu}
                </div>
                <div id="ddb2" role="region" aria-label="Menu panel" class="u-custom-menu u-nav-container-collapse">
                  <div class="u-black u-container-style u-inner-container-layout u-opacity u-opacity-95 u-sidenav">
                    <div class="u-inner-container-layout u-sidenav-overflow">
                      <div class="u-menu-close" tabindex="-1" aria-controls="ddb2" aria-label="Close menu"></div>
                      {responsive_menu}
                    </div>
                  </div>
                  <div class="u-black u-menu-overlay u-opacity u-opacity-70"></div>
                </div>
                <style class="menu-style">@media (max-width: 939px) {
                    [data-responsive-from="MD"] .u-nav-container {
                        display: none;
                    }
                    [data-responsive-from="MD"] .menu-collapse {
                        display: block;
                    }
                }</style>
              </nav><?php
            $menu_template = ob_get_clean();
            $menuPath = '/template-parts/menu/primary-navigation-2/';
            $processorPath = get_theme_file_path('/template-parts/menu/popups-render.php');
            if (file_exists($processorPath)) {
                include $processorPath;
            }
            echo Theme_NavMenu::getMenuHtml(array(
                'container_class' => 'u-hover-color-var u-menu u-menu-one-level u-offcanvas u-menu-1',
                'menu' => array(
                    'is_mega_menu' => false,
                    'menu_class' => 'u-nav u-spacing-2 u-unstyled u-nav-1',
                    'item_class' => 'u-nav-item',
                    'link_class' => 'u-active-grey-60 u-border-2 u-border-active-palette-1-base u-border-hover-palette-1-light-1 u-border-no-left u-border-no-right u-border-no-top u-button-style u-hover-grey-75 u-nav-link u-text-active-grey-15 u-text-grey-10 u-text-hover-grey-90',
                    'link_style' => 'padding: 10px 20px;',
                    'submenu_class' => 'u-border-1 u-border-grey-30 u-h-spacing-20 u-nav u-unstyled u-v-spacing-10 u-block-4939-55',
                    'submenu_item_class' => 'u-nav-item',
                    'submenu_link_class' => 'u-button-style u-nav-link',
                    'submenu_link_style' => '',
                ),
                'responsive_menu' => array(
                    'is_mega_menu' => false,
                    'menu_class' => 'u-align-center u-nav u-popupmenu-items u-unstyled u-nav-2',
                    'item_class' => 'u-nav-item',
                    'link_class' => 'u-button-style u-nav-link',
                    'link_style' => '',
                    'submenu_class' => 'u-border-1 u-border-grey-30 u-h-spacing-20 u-nav u-unstyled u-v-spacing-10 u-block-4939-57',
                    'submenu_item_class' => 'u-nav-item',
                    'submenu_link_class' => 'u-button-style u-nav-link',
                    'submenu_link_style' => '',
                ),
                'theme_location' => 'primary-navigation-2',
                'template' => $menu_template,
                'mega_menu' => isset($megaMenu) ? $megaMenu : '{}',
            )); ?>
            </div>
          </div>
          <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-right-cell u-size-20 u-layout-cell-3">
            <div class="u-container-layout u-container-layout-3"><!--position-->
              <?php $sidebar_html = theme_sidebar(array(
            'id' => 'area_3',
            'template' => <<<WIDGET_TEMPLATE
                <div class="u-block">
                  <div class="u-block-container u-clearfix"><!--block_header-->
                    <h5 class="u-block-header u-text">{block_header}</h5><!--/block_header--><!--block_content-->
                    <div class="u-block-content u-text">{block_content}</div><!--/block_content-->
                  </div>
                </div>
WIDGET_TEMPLATE
        )); ?> <div data-position="Widget Area 3" class="u-position u-position-3"><!--block-->
                <?php if ($sidebar_html) { echo stylingDefaultControls($sidebar_html); } else { ?> <div class="u-block">
                  <div class="u-block-container u-clearfix"><!--block_header-->
                    <h5 class="u-block-header u-text"><!--block_header_content_replacer--><!--block_header_content-->Contacts <!--/block_header_content--><!--/block_header_content_replacer--></h5><!--/block_header--><!--block_content-->
                    <div class="u-block-content u-text"><!--block_content_content--><!--/block_content_content--></div><!--/block_content-->
                  </div>
                </div> <?php } ?><!--/block-->
              </div><!--/position-->
              <p class="u-text u-text-default u-text-7">address</p><span class="u-icon u-icon-1"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 54.757 54.757" style=""><use xlink:href="#svg-cfe4"></use></svg><svg class="u-svg-content" viewBox="0 0 54.757 54.757" x="0px" y="0px" id="svg-cfe4" style="enable-background:new 0 0 54.757 54.757;"><g><path d="M27.557,12c-3.859,0-7,3.141-7,7s3.141,7,7,7s7-3.141,7-7S31.416,12,27.557,12z M27.557,24c-2.757,0-5-2.243-5-5
		s2.243-5,5-5s5,2.243,5,5S30.314,24,27.557,24z"></path><path d="M40.94,5.617C37.318,1.995,32.502,0,27.38,0c-5.123,0-9.938,1.995-13.56,5.617c-6.703,6.702-7.536,19.312-1.804,26.952
		L27.38,54.757L42.721,32.6C48.476,24.929,47.643,12.319,40.94,5.617z M41.099,31.431L27.38,51.243L13.639,31.4
		C8.44,24.468,9.185,13.08,15.235,7.031C18.479,3.787,22.792,2,27.38,2s8.901,1.787,12.146,5.031
		C45.576,13.08,46.321,24.468,41.099,31.431z"></path>
</g></svg></span>
              <a href="" class="u-active-none u-btn u-btn-rectangle u-button-style u-hover-none u-none u-btn-1"><span class="u-icon"><svg class="u-svg-content" viewBox="0 0 405.333 405.333" x="0px" y="0px" style="width: 1em; height: 1em;"><path d="M373.333,266.88c-25.003,0-49.493-3.904-72.704-11.563c-11.328-3.904-24.192-0.896-31.637,6.699l-46.016,34.752    c-52.8-28.181-86.592-61.952-114.389-114.368l33.813-44.928c8.512-8.512,11.563-20.971,7.915-32.64    C142.592,81.472,138.667,56.96,138.667,32c0-17.643-14.357-32-32-32H32C14.357,0,0,14.357,0,32    c0,205.845,167.488,373.333,373.333,373.333c17.643,0,32-14.357,32-32V298.88C405.333,281.237,390.976,266.88,373.333,266.88z"></path></svg></span>&nbsp;+1 (234) 567-8910 
              </a>
              <a href="mailto:info@site.com" class="u-active-none u-btn u-btn-rectangle u-button-style u-hover-none u-none u-text-white u-btn-2"><span class="u-icon u-icon-3"><svg class="u-svg-content" viewBox="0 0 24 16" x="0px" y="0px" style="width: 1em; height: 1em;"><path fill="currentColor" d="M23.8,1.1l-7.3,6.8l7.3,6.8c0.1-0.2,0.2-0.6,0.2-0.9V2C24,1.7,23.9,1.4,23.8,1.1z M21.8,0H2.2
	c-0.4,0-0.7,0.1-1,0.2L10.6,9c0.8,0.8,2.2,0.8,3,0l9.2-8.7C22.6,0.1,22.2,0,21.8,0z M0.2,1.1C0.1,1.4,0,1.7,0,2V14
	c0,0.3,0.1,0.6,0.2,0.9l7.3-6.8L0.2,1.1z M15.5,9l-1.1,1c-1.3,1.2-3.6,1.2-4.9,0l-1-1l-7.3,6.8c0.2,0.1,0.6,0.2,1,0.2H22
	c0.4,0,0.6-0.1,1-0.2L15.5,9z"></path></svg></span>&nbsp;info@site.com 
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="u-border-1 u-border-white u-expanded-width u-line u-line-horizontal u-opacity u-opacity-50 u-line-1"></div>
    <div class="u-social-icons u-social-icons-1">
      <a class="u-social-url" title="facebook" target="_blank" href=""><span class="u-icon u-social-facebook u-social-icon u-icon-4"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xlink:href="#svg-47ae"></use></svg><svg class="u-svg-content" viewBox="0 0 112 112" x="0" y="0" id="svg-47ae"><circle fill="currentColor" cx="56.1" cy="56.1" r="55"></circle><path fill="#FFFFFF" d="M73.5,31.6h-9.1c-1.4,0-3.6,0.8-3.6,3.9v8.5h12.6L72,58.3H60.8v40.8H43.9V58.3h-8V43.9h8v-9.2
      c0-6.7,3.1-17,17-17h12.5v13.9H73.5z"></path></svg></span>
      </a>
      <a class="u-social-url" title="twitter" target="_blank" href=""><span class="u-icon u-social-icon u-social-twitter u-icon-5"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xlink:href="#svg-cf3d"></use></svg><svg class="u-svg-content" viewBox="0 0 112 112" x="0" y="0" id="svg-cf3d"><circle fill="currentColor" class="st0" cx="56.1" cy="56.1" r="55"></circle><path fill="#FFFFFF" d="M83.8,47.3c0,0.6,0,1.2,0,1.7c0,17.7-13.5,38.2-38.2,38.2C38,87.2,31,85,25,81.2c1,0.1,2.1,0.2,3.2,0.2
      c6.3,0,12.1-2.1,16.7-5.7c-5.9-0.1-10.8-4-12.5-9.3c0.8,0.2,1.7,0.2,2.5,0.2c1.2,0,2.4-0.2,3.5-0.5c-6.1-1.2-10.8-6.7-10.8-13.1
      c0-0.1,0-0.1,0-0.2c1.8,1,3.9,1.6,6.1,1.7c-3.6-2.4-6-6.5-6-11.2c0-2.5,0.7-4.8,1.8-6.7c6.6,8.1,16.5,13.5,27.6,14
      c-0.2-1-0.3-2-0.3-3.1c0-7.4,6-13.4,13.4-13.4c3.9,0,7.3,1.6,9.8,4.2c3.1-0.6,5.9-1.7,8.5-3.3c-1,3.1-3.1,5.8-5.9,7.4
      c2.7-0.3,5.3-1,7.7-2.1C88.7,43,86.4,45.4,83.8,47.3z"></path></svg></span>
      </a>
      <a class="u-social-url" title="instagram" target="_blank" href=""><span class="u-icon u-social-icon u-social-instagram u-icon-6"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xlink:href="#svg-8f59"></use></svg><svg class="u-svg-content" viewBox="0 0 112 112" x="0" y="0" id="svg-8f59"><circle fill="currentColor" cx="56.1" cy="56.1" r="55"></circle><path fill="#FFFFFF" d="M55.9,38.2c-9.9,0-17.9,8-17.9,17.9C38,66,46,74,55.9,74c9.9,0,17.9-8,17.9-17.9C73.8,46.2,65.8,38.2,55.9,38.2
      z M55.9,66.4c-5.7,0-10.3-4.6-10.3-10.3c-0.1-5.7,4.6-10.3,10.3-10.3c5.7,0,10.3,4.6,10.3,10.3C66.2,61.8,61.6,66.4,55.9,66.4z"></path><path fill="#FFFFFF" d="M74.3,33.5c-2.3,0-4.2,1.9-4.2,4.2s1.9,4.2,4.2,4.2s4.2-1.9,4.2-4.2S76.6,33.5,74.3,33.5z"></path><path fill="#FFFFFF" d="M73.1,21.3H38.6c-9.7,0-17.5,7.9-17.5,17.5v34.5c0,9.7,7.9,17.6,17.5,17.6h34.5c9.7,0,17.5-7.9,17.5-17.5V38.8
      C90.6,29.1,82.7,21.3,73.1,21.3z M83,73.3c0,5.5-4.5,9.9-9.9,9.9H38.6c-5.5,0-9.9-4.5-9.9-9.9V38.8c0-5.5,4.5-9.9,9.9-9.9h34.5
      c5.5,0,9.9,4.5,9.9,9.9V73.3z"></path></svg></span>
      </a>
      <a class="u-social-url" title="pinterest" target="_blank" href=""><span class="u-icon u-social-icon u-social-pinterest u-icon-7"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xlink:href="#svg-6855"></use></svg><svg class="u-svg-content" viewBox="0 0 112 112" x="0" y="0" id="svg-6855"><circle fill="currentColor" cx="56.1" cy="56.1" r="55"></circle><path fill="#FFFFFF" d="M61.1,76.9c-4.7-0.3-6.7-2.7-10.3-5c-2,10.7-4.6,20.9-11.9,26.2c-2.2-16.1,3.3-28.2,5.9-41
      c-4.4-7.5,0.6-22.5,9.9-18.8c11.6,4.6-10,27.8,4.4,30.7C74.2,72,80.3,42.8,71,33.4C57.5,19.6,31.7,33,34.9,52.6
      c0.8,4.8,5.8,6.2,2,12.9c-8.7-1.9-11.2-8.8-10.9-17.8C26.5,32.8,39.3,22.5,52.2,21c16.3-1.9,31.6,5.9,33.7,21.2
      C88.2,59.5,78.6,78.2,61.1,76.9z"></path></svg></span>
      </a>
    </div>
    <?php $logo = theme_get_logo(array(
            'default_src' => "/images/default-logo.png",
            'default_url' => "#"
        )); $url = stripos($logo['url'], 'http') === 0 ? esc_url($logo['url']) : $logo['url']; ?><a <?php if (is_customize_preview()) echo 'data-default-src="' . esc_url($logo['default_src']) . '" '; ?>href="<?php echo $url; ?>" class="u-image u-logo u-image-1 custom-logo-link">
      <img <?php if ($logo['svg']) { echo 'style="width:'.$logo['width'].'px"'; } ?>src="<?php echo esc_url($logo['src']); ?>" class="u-logo-image u-logo-image-1">
    </a>
  </div>
</footer>