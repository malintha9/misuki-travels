<header class="u-clearfix u-color-var u-header u-header" id="header">
  <div class="u-clearfix u-sheet u-valign-middle-lg u-valign-middle-md u-valign-middle-sm u-valign-middle-xs u-sheet-1">
    <?php $logo = theme_get_logo(array(
            'default_src' => "/images/default-logo.png",
            'default_url' => "#"
        )); $url = stripos($logo['url'], 'http') === 0 ? esc_url($logo['url']) : $logo['url']; ?><a <?php if (is_customize_preview()) echo 'data-default-src="' . esc_url($logo['default_src']) . '" '; ?>href="<?php echo $url; ?>" class="u-image u-logo u-image-1 custom-logo-link">
      <img <?php if ($logo['svg']) { echo 'style="width:'.$logo['width'].'px"'; } ?>src="<?php echo esc_url($logo['src']); ?>" class="u-logo-image u-logo-image-1">
    </a>
    <?php
            ob_start();
            ?><nav class="u-menu u-menu-one-level u-offcanvas u-menu-1" role="navigation" aria-label="Menu navigation">
      <div class="menu-collapse u-custom-font u-font-playfair-display" style="font-size: 1rem; letter-spacing: 0px;">
        <a class="u-button-style u-custom-left-right-menu-spacing u-custom-padding-bottom u-custom-text-active-color u-custom-text-color u-custom-text-hover-color u-custom-top-bottom-menu-spacing u-hamburger-link u-nav-link u-text-active-palette-1-base u-text-hover-palette-2-base" href="#" tabindex="-1" aria-label="Open menu" aria-controls="bef1">
          <svg class="u-svg-link" viewBox="0 0 24 24"><use xlink:href="#menu-hamburger"></use></svg>
          <svg class="u-svg-content" version="1.1" id="menu-hamburger" viewBox="0 0 16 16" x="0px" y="0px" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"><g><rect y="1" width="16" height="2"></rect><rect y="7" width="16" height="2"></rect><rect y="13" width="16" height="2"></rect>
</g></svg>
        </a>
      </div>
      <div class="u-custom-menu u-nav-container">
        {menu}
      </div>
      <div class="u-custom-menu u-nav-container-collapse" id="bef1" role="region" aria-label="Menu panel">
        <div class="u-black u-container-style u-inner-container-layout u-opacity u-opacity-95 u-sidenav">
          <div class="u-inner-container-layout u-sidenav-overflow">
            <div class="u-menu-close" tabindex="-1" aria-label="Close menu"></div>
            {responsive_menu}
          </div>
        </div>
        <div class="u-black u-menu-overlay u-opacity u-opacity-70"></div>
      </div>
    </nav><?php
            $menu_template = ob_get_clean();
            $menuPath = '/template-parts/menu/primary-navigation-1/';
            $processorPath = get_theme_file_path('/template-parts/menu/popups-render.php');
            if (file_exists($processorPath)) {
                include $processorPath;
            }
            echo Theme_NavMenu::getMenuHtml(array(
                'container_class' => 'u-menu u-menu-one-level u-offcanvas u-menu-1',
                'menu' => array(
                    'is_mega_menu' => false,
                    'menu_class' => 'u-custom-font u-font-playfair-display u-nav u-unstyled u-nav-1',
                    'item_class' => 'u-nav-item',
                    'link_class' => 'u-button-style u-nav-link u-text-active-custom-color-1 u-text-hover-white u-text-white',
                    'link_style' => 'padding: 10px 20px;',
                    'submenu_class' => 'u-nav u-unstyled u-block-5abb-21',
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
                    'submenu_class' => 'u-nav u-unstyled u-block-5abb-23',
                    'submenu_item_class' => 'u-nav-item',
                    'submenu_link_class' => 'u-button-style u-nav-link',
                    'submenu_link_style' => '',
                ),
                'theme_location' => 'primary-navigation-1',
                'template' => $menu_template,
                'mega_menu' => isset($megaMenu) ? $megaMenu : '{}',
            )); ?>
  </div>
</header>