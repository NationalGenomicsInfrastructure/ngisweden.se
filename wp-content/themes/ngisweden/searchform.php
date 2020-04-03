<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="form-inline" role="form">
  <div class="form-group">
    <label class="sr-only" for="s">Search Term</label>
    <input type="text" class="form-control" name="s" id="s" placeholder="Search Term" />
  </div>
  <input type="submit" class="btn btn-secondary ml-2" name="submit" id="searchsubmit" value="Search" />
</form>
