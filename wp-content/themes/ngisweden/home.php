<?php
/*
  News archive page
  Lists all of the news posts
*/

get_header();

$title_suffix = '';
if(is_category()){
  $title_suffix = single_cat_title(' &raquo; ', false);
} elseif(is_date()){
  $title_suffix = ' &raquo; '.get_query_var('year');
}

?>

<div class="ngisweden-sidebar-page">
  <div class="container main-page">
    <div class="row">
      <div class="col-sm-9">
        <h1>News<?php echo $title_suffix; ?></h1>
        <hr>
        <?php
        the_archive_description();
        if (have_posts()) {
          while (have_posts()) {
            the_post();
            echo '<h4><a href="'.get_the_permalink().'">'.get_the_title().'</a></h4>';
            if ( has_post_thumbnail() ) {
              echo '<a href="'.get_the_permalink().'" class="alignright">'.get_the_post_thumbnail('thumb').'</a>';
            }
            echo '<p class="small text-muted mb-1">'.get_the_date().' &nbsp;-&nbsp; Categories: <em>'.get_the_category_list(', ').'</em></p>';
            the_excerpt();
            echo '<hr>';
          }
          bootstrap_pagination();
        } else {
          echo '<p class="text-muted lead">No posts found.</p>';
        }
        ?>
      </div>
      <div class="col-sm-3 ngisweden-sidebar-page-sidebar">

        <h5 class="mt-3">Categories</h5>
        <div class="list-group">
          <?php
          $cats = get_categories(array( 'orderby' => 'slug', 'parent' => 0 ));
          if(is_category()){
            $current_cat = get_category(get_query_var('cat'));
          }
          foreach ($cats as $cat ) {
            $cat_url = get_category_link($cat->cat_ID);
            $active = '';
            if(is_category() && $current_cat->cat_ID == $cat->cat_ID){
              $active = 'active';
            }
            echo '<a href="'.$cat_url.'" class="list-group-item list-group-item-action '.$active.'">'.$cat->name.'</a>';
          }
          ?>
        </div>

        <h5 class="mt-3">News Archives</h5>
        <div class="list-group">
          <?php
          if(is_date()){
            $curr_archive_year = get_query_var('year');
          }
          foreach (range(date('Y'), 2018) as $archive_year) {
            $active = '';
            if(is_date() && $curr_archive_year == $archive_year){
              $active = 'active';
            }
            echo '<a href="'.get_year_link( $archive_year ).'" class="list-group-item list-group-item-action '.$active.'">'.$archive_year.'</a>';
          }
          ?>
        </div>

      </div>
    </div>
  </div>
</div>

<?php get_footer();
