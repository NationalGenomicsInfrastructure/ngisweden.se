<?php // Custom Dashboard page

// Check if we have any pages that should me in the main menu and are not
// Get menu page IDs
$menu_locations = get_nav_menu_locations();
$menu = wp_get_nav_menu_object($menu_locations['main-nav']);
$menu_items = wp_get_nav_menu_items($menu->term_id);
$menu_page_ids = [];
foreach($menu_items as $menu_item){
  if($menu_item->object == 'page'){
    $menu_page_ids[] = $menu_item->object_id;
  }
}
// Get all page IDs
$ignore_pages = [get_option('page_on_front')]; // Static homepage page ID
foreach(['applications', 'methods', 'bioinformatics', 'technologies'] as $slug){
  $ignore_page = get_page_by_path($slug);
  if($ignore_page){
    $ignore_pages[] = $ignore_page->ID;
  }
}
$ngi_pages = get_pages(array(
  'hierarchical'=> false,
  'exclude_tree' => [
    // Exclude pages that are children of 'Applications'
    get_page_by_path('applications')->ID,
    // Exclude children of 'News' (the Events pages)
    get_page_by_path('news')->ID,
  ],
  // Ignore pages with same basenames as CPTs
  'exclude' => $ignore_pages,
  //////// DEBUG ONLY
  ///// REMOVE THIS WHEN THE SITE IS GOING LIVE
  'post_status' => 'publish,pending,draft'
));
$missing_pages = [];
foreach($ngi_pages as $ngi_page){
  if(!in_array($ngi_page->ID, $menu_page_ids)){
    $missing_pages[] = $ngi_page;
  }
}


?>

<div class="wrap about-wrap full-width-layout">

  <h1>Welcome to the <?php bloginfo('name'); ?> administration pages</h1>
  <div class="about-text">
    You can manage all of the content on the <?php bloginfo('name'); ?> website from these pages.
  </div>

  <h3>Your Content</h3>
  <p class="description">We assign each web page to a person by setting them as an author.
  Follow these links to see only the content where you are set as author.</p>
  <?php
    echo '<ul style="list-style-type: inherit; margin-left: 2rem;">';
    $pcounts = array(
      'methods' => 0,
      'technologies' => 0,
      'bioinformatics' => 0,
      'page' => 0,
      'post' => 0,
    );
    $posts = get_posts(array(
      'author' => get_current_user_id(),
      'post_type' => array_keys($pcounts),
      'post_status' => array('publish', 'pending', 'draft', 'future', 'private', 'inherit'),
      'posts_per_page' => -1
    ));
    foreach($posts as $post) {
      $pcounts[$post->post_type] += 1;
    }
    foreach($pcounts as $pt => $pcount){
      echo '<li><a href="/wp-admin/edit.php?post_type='.$pt.'&author='.get_current_user_id().'">'.ucfirst($pt).' ('.$pcount.')</a></li>';
    }
    echo '</ul>';
  ?>

  <?php if(count($missing_pages) > 0): ?>
    <h3>Missing menu pages</h3>
    <p class="description">
      Website pages aren't automatically added to the main navigation, they have to be placed in the menu editor.
      The list below shows pages that are not in the top navigation.
      This excludes methods, applications, technologies and bioinformatics.
      <a href="<?php echo admin_url('nav-menus.php'); ?>">Click here to edit the main navigation</a>.
    </p>
    <ul style="list-style-type: inherit; margin-left: 2rem;">
    <?php
    foreach($missing_pages as $missing_page){
      echo '<li><a href="'.$missing_page->guid.'">'.$missing_page->post_title.'</a></li>';
    }
    ?>
    </ul>
  <?php endif; ?>

  <h3>
    <a class="button button-primary" style="float:right; margin-bottom: 10px;" href="https://docs.google.com/document/d/1wXarUg1JlxSmDLZwmhZs5ChV1Kq28pV-dBniaASlPWA/edit?usp=sharing" target="_blank">Open docs in new tab</a>
    Website walkthrough for editors
  </h3>
  <div style="box-shadow: 0px 0px 6px 0px #0000002e;">
    <iframe style="width:100%; height: 600px;" src="https://docs.google.com/document/d/e/2PACX-1vTty9lSxiX0O9dgNO8v6jEftdxilRH-oVGuTpxLqCR5Ta1IULbIgcDKOvoWa-Hft4RADVxaSJhZYrFa/pub"></iframe>
  </div>

  <hr>
  <h3>Administrators</h3>
  <p>The code for the website is available on GitHub:
    <a href="https://github.com/NationalGenomicsInfrastructure/ngisweden.se/" target="_blank">https://github.com/NationalGenomicsInfrastructure/ngisweden.se/</a></p>
  <p>Please contact <a href="mailto:it-support@scilifelab.se">SciLifeLab IT support</a> for help regarding the server.</p>

</div>
<hr style="margin-top:3rem;">
