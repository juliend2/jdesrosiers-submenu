<?php
/* 
Plugin Name: JDesrosiers Submenu
Plugin URI: 
Description: Plugin to create sub-menus in pages and sub-pages without the need to create custom Menus (in Appearance -> Menus).
Author: Julien Desrosiers
Version: 1.0 
Author URI: http://www.juliendesrosiers.com
*/  

class JDES_Submenu {
  function __construct($page_id) {
    $this->page_id = $page_id;
  }

  // Get the page's submenu pages
  function get_submenu_pages() {
    $parents = get_ancestors($this->page_id, 'page');
    return $this->get_page_children( !empty($parents) ? $parents[0] : $this->page_id );
  }

  // Get the page's submenu parent page
  function get_submenu_parent_page() {
    $parents = get_ancestors($this->page_id, 'page');
    $id = !empty($parents) ? $parents[0] : $this->page_id;
    return get_page( $id );
  }

  // Get the page's children by its ID
  //
  // page_id - ID Integer of the page to get the children from
  function get_page_children($page_id) {
    $pages_wp_query = new WP_Query();
    $all_wp_pages = $pages_wp_query->query(array('post_type' => 'page', 'posts_per_page'=>-1));
    return get_page_children($page_id, $all_wp_pages);
  }

  // Prints out the html of the template
  // Overriding this method is very welcome!
  function html($page, $submenu_pages) {
    ?>
      <h1><?php echo $page->post_title ?> /</h1>
      <ul>
        <?php foreach ($submenu_pages as $p): ?>
          <li><a href="<?php echo $p->guid ?>"><?php echo $p->post_title ?></a></li>
        <?php endforeach ?>
      </ul>
    <?php
  }

  // Echo or Return the HTML submenu
  function to_HTML($echo=true) {
    $page = $this->get_submenu_parent_page();
    $submenu_pages = $this->get_submenu_pages();

    ob_start();
    $this->html($page, $submenu_pages);
    $html = ob_get_contents();
    ob_end_clean();

    if ($echo) { echo $html; }
    else { return $html; }
  }

}



