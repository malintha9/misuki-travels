<?php
class BaseController {

    public static $_controlName;
    public static $isSiteProducts;

    /**
     * Process pagination for blog/products controls
     *
     * @param string $content
     * @param string $controlName    blog/products
     * @param bool   $isSiteProducts is our products
     *
     * @return string $content
     */
    public static function processPagination($content, $controlName = 'blog', $isSiteProducts = false) {
        self::$_controlName = $controlName;
        self::$isSiteProducts = $isSiteProducts;
        $content = preg_replace_callback(
            '/<\!--' . self::$_controlName . '_pagination-->([\s\S]+?)<\!--\/' . self::$_controlName . '_pagination-->/',
            function ($paginationMatch) {
                $paginationHtml = $paginationMatch[1];
                $paginationOptions = array();
                if (preg_match('/<\!--' . self::$_controlName . '_pagination_options_json--><\!--([\s\S]+?)--><\!--\/' . self::$_controlName . '_pagination_options_json-->/', $paginationHtml, $matches)) {
                    if (self::isJSON($matches[1])) {
                        $paginationOptions = json_decode($matches[1], true);
                    } else {
                        $matches[1] = preg_replace_callback("#([^{:]*):([^,}]*)#i", 'self::tryToFixJson', $matches[1]);
                        if (self::isJSON($matches[1])) {
                            $paginationOptions = json_decode($matches[1], true);
                            $paginationHtml = preg_replace('/data-pagination-options="[\s\S]*?"/', "data-pagination-options='". json_encode($paginationOptions) . "'", $paginationHtml);
                        } else {
                            return str_replace('data-pagination-options', 'data-pagination-options-1', $paginationHtml);
                        }
                    }
                }
                if (self::$isSiteProducts) {
                    return $paginationHtml;
                }
                return $paginationHtml = self::_createPagination($paginationOptions, self::$_controlName);
            },
            $content
        );
        return $content;
    }

    /**
     * Try to fix broken json
     *
     * @param $match
     *
     * @return string
     */
    public static function tryToFixJson($match){
        $key = trim($match[1]);
        $val = trim($match[2]);

        if ($val[0] == '"') {
            $val = '"' . addslashes(substr($val, 1, -1)) . '"';
        } else if ($val[0] == "'") {
            $val = "'" . addslashes(substr($val, 1, -1)) . "'";
        }
        return $key.": ".$val;
    }

    private static $_siteProductsProcess = false;
    private static $_blogProcess = false;

    /**
     * Process categories filter for products controls
     *
     * @param string $content
     * @param array  $options
     * @param string $type
     *
     * @return string $content
     */
    public static function processCategoriesFilter($content, $options, $type) {
        self::$_siteProductsProcess = $type === 'np_products';
        self::$_blogProcess = $type === 'blog';
        if (self::$_siteProductsProcess) {
            $type = 'products';
        }
        $content = preg_replace_callback(
            '/<\!--' . $type . '_categories_filter_select-->([\s\S]+?)<\!--\/' . $type . '_categories_filter_select-->/',
            function ($selectMatch) use ($options) {
                $selectHtml = $selectMatch[1];
                preg_match('/<option[\s\S]*?>[\s\S]+?<\/option>/', $selectHtml, $selectOptions);
                $firstOptionHtml = $selectOptions[0];
                $selectHtml = preg_replace('/<option[\s\S]*?>[\s\S]*<\/option>/', '{categoriesFilterOptions}', $selectHtml);
                if (self::$_blogProcess) {
                    $categories = get_terms(
                        array(
                            'taxonomy' => 'category',
                            'hide_empty' => false,
                        )
                    );
                } else {
                    if (self::$_siteProductsProcess) {
                        $categories = isset($options['productsJson']['categories']) ? $options['productsJson']['categories'] : array();
                    } else {
                        $categories = is_woocommerce_active() ? get_terms('product_cat', array('hide_empty' => false)) : array();
                    }
                }
                $selectOptionsHtml = '';
                // add item all
                $attrName = self::$_blogProcess ? 'postsCategoryId' : 'categoryId';
                $activeFilter = isset($_GET[$attrName]) ? $_GET[$attrName] : false;
                if (get_option('np_theme_appearance') === 'plugin-option' && function_exists('wc_get_product') && (is_shop() || is_product_category())) {
                    if (!self::$_blogProcess && isset($_GET['featured']) && $_GET['featured']) {
                        $activeFilter = add_query_arg('featured', 'true', get_permalink(wc_get_page_id('shop')));
                    }
                }
                if (self::$_blogProcess) {
                    $OptionAllProducts = preg_replace('/value=[\'"][\s\S]*?[\'"]/', 'value=""', $firstOptionHtml);
                } else {
                    if (function_exists('wc_get_product') && (is_shop() || is_product_category())) {
                        $OptionAllProducts = preg_replace('/value=[\'"][\s\S]*?[\'"]/', 'value="' . get_permalink(wc_get_page_id('shop')) . '"', $firstOptionHtml);
                    } else {
                        $OptionAllProducts = preg_replace('/value=[\'"][\s\S]*?[\'"]/', 'value=""', $firstOptionHtml);
                    }
                }

                $OptionAllProducts = preg_replace('/(<option[\s\S]*?>)[\s\S]+?<\/option>/', '$1' . __('All', 'nicepage') . '</option>', $OptionAllProducts);
                $selectOptionsHtml .= $OptionAllProducts;
                if (!self::$_blogProcess) {
                    // add item featured
                    if (get_option('np_theme_appearance') === 'plugin-option' && function_exists('wc_get_product') && (is_shop() || is_product_category())) {
                        $featuredLink = is_woocommerce_active() ? add_query_arg('featured', 'true', get_permalink(wc_get_page_id('shop'))) : '';
                    } else {
                        $featuredLink = 'featured';
                    }

                    $OptionFeaturedProducts = preg_replace('/value=[\'"][\s\S]*?[\'"]/', 'value="' . $featuredLink . '"', $firstOptionHtml);
                    $OptionFeaturedProducts = preg_replace('/(<option[\s\S]*?>)[\s\S]+?<\/option>/', '$1' . __('Featured', 'nicepage') . '</option>', $OptionFeaturedProducts);
                    if (!self::$_siteProductsProcess && $activeFilter && $featuredLink === $activeFilter) {
                        $OptionFeaturedProducts = str_replace('<option', '<option selected="selected"', $OptionFeaturedProducts);
                    }
                    $selectOptionsHtml .= $OptionFeaturedProducts;
                }

                // add all categories with hierarchy
                $selectOptionsHtml .= self::_generate_category_options($categories, $firstOptionHtml);
                $selectHtml = str_replace('{categoriesFilterOptions}', $selectOptionsHtml, $selectHtml);
                return $selectHtml;
            },
            $content
        );
        return $content;
    }

    /**
     * Generate categories filter options with hierarchy
     *
     * @param array  $categories
     * @param string $itemTemplate
     * @param int    $parent
     * @param string $prefix
     *
     * @return string $result
     */
    private static function _generate_category_options( $categories, $itemTemplate, $parent = 0, $prefix = '' ) {
        $result = '';
        $attrName = self::$_blogProcess ? 'postsCategoryId' : 'categoryId';
        $activeFilter = isset($_GET[$attrName]) ? $_GET[$attrName] : false;
        if (get_option('np_theme_appearance') === 'plugin-option' && function_exists('wc_get_product') && (is_shop() || is_product_category())) {
            $term = get_queried_object();
            if ($term && is_a($term, 'WP_Term') && isset($term->term_id)) {
                $activeFilter = $term->term_id;
            }
        }
        foreach ($categories as $category) {
            $parentId = self::$_siteProductsProcess ? $category['categoryId'] : $category->parent;
            $catId = self::$_siteProductsProcess ? $category['id'] : $category->term_id;
            $catName = self::$_siteProductsProcess ? $category['title'] : $category->name;
            if ($parentId == $parent) {
                $doubleOptionHtml = $itemTemplate;
                if (get_option('np_theme_appearance') === 'plugin-option' && function_exists('wc_get_product') && (is_shop() || is_product_category())) {
                    $doubleOptionHtml = preg_replace('/value=[\'"][\s\S]*?[\'"]/', 'value="' . get_term_link($category->term_id, 'product_cat') . '"', $doubleOptionHtml);
                } else {
                    $doubleOptionHtml = preg_replace('/value=[\'"][\s\S]*?[\'"]/', 'value="' . $catId . '"', $doubleOptionHtml);
                }
                $doubleOptionHtml = preg_replace('/(<option[\s\S]*?>)[\s\S]+?<\/option>/', '$1' . $prefix . $catName . '</option>', $doubleOptionHtml);
                if (!self::$_siteProductsProcess && $activeFilter && (int)$catId === (int)$activeFilter) {
                    $doubleOptionHtml = str_replace('<option', '<option selected="selected"', $doubleOptionHtml);
                }
                $result .= $doubleOptionHtml;
                $result .= self::_generate_category_options($categories, $itemTemplate, $catId, $prefix . '-');
            }
        }
        return $result;
    }

    /**
     * Create pagination for blog/products controls
     *
     * @param array $options
     * @param array $controlName blog/products
     *
     * @return string $paginationHtml
     */
    private static function _createPagination($options, $controlName = 'blog') {
        if ($controlName === 'blog') {
            global $blog_control_query;
            $control_query = $blog_control_query;
        } else {
            global $products_control_query;
            $control_query = $products_control_query;
            if (get_option('np_theme_appearance') === 'plugin-option' && function_exists('wc_get_product') && (is_shop() || is_product_category())) {
                global $wp_query;
                $control_query = $wp_query;
            }
        }

        if (isset($control_query->max_num_pages) && $control_query->max_num_pages < 1) {
            return '';
        }

        $ul_classes_and_styles = isset($options) && isset($options['ul']) ? $options['ul'] : 'class="responsive-style1 u-pagination u-unstyled"';
        $li_classes_and_styles = isset($options) && isset($options['li']) ? $options['li'] : 'class="u-nav-item u-pagination-item"';
        $link_classes_and_styles = isset($options) && isset($options['link']) ? $options['link'] : 'style="padding: 16px 28px;" class=$1$2 u-button-style u-nav-link';
        $pagination_links = paginate_links(
            array(
                'base' => str_replace(999999999, '%#%', get_pagenum_link(999999999, false)),
                'format' => '',
                'current' => max(1, get_query_var('paged')),
                'total' => $control_query->max_num_pages,
                'type' => 'array',
                'prev_text' => __('&#x3008;', 'nicepage'),
                'next_text' => __('&#x3009;', 'nicepage'),
                'end_size' => 1,
                'mid_size' => 1,
            )
        );
        if (is_array($pagination_links) > 0 ) {
            ob_start();
            echo '<ul ' . $ul_classes_and_styles . '>';
            foreach ($pagination_links as $idx => &$link) {
                if (strpos($link, 'aria-current=') !== false) {
                    $active_idx = $idx;
                }
                $link = preg_replace(
                    array(
                        '/class=(["\'])(.*?)["\']/is',
                    ),
                    array(
                        $link_classes_and_styles,
                    ),
                    $link
                );
            }

            $li_params = explode(' class="', $li_classes_and_styles);
            foreach ($pagination_links as $idx => &$link) {
                $li_style = isset($li_params[0]) ? $li_params[0] : '';
                $li_class_string = isset($li_params[1]) ? $li_params[1] : 'class="u-nav-item u-pagination-item"';
                $li_class_string = str_replace('class=', '', $li_class_string);
                $li_class = str_replace('"', '', $li_class_string);
                if ($idx === $active_idx) {
                    $li_class .= ' active';
                }
                if (strpos($link, 'class="prev') !== false) {
                    $li_class .= ' prev';
                }
                if (strpos($link, 'class="next') !== false) {
                    $li_class .= ' next';
                }
                if (strpos($link, 'dots"') !== false) {
                    $li_class .= ' u-pagination-separator';
                }
                echo '<li ' . $li_style . ' class="' . $li_class . '">' . $link . '</li>';
            }
            echo '</ul>';
            return ob_get_clean();
        } else {
            return '';
        }
    }

    /**
     * Check json
     *
     * @param string $string
     *
     * @return bool
     */
    public static function isJSON($string){
        return is_string($string) && is_array(json_decode($string, true)) ? true : false;
    }
}