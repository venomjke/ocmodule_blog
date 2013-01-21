
  <?php echo $header; ?>
  <?php echo $column_left; ?>
  <?php echo $column_right; ?>
  <script type="text/javascript"><!--
  $(document).ready(function(){
    var options =
    {
      showEffect:'show',
      hideEffect:'fadeout',
      fadeoutSpeed: 'slow',
      title :true,
      lens:true,
      zoomWidth: 350,
      zoomHeight: 350
    }
    $(".jqzoom").jqzoom(options);
  });

  //--></script>
  <div id="content">
    <div class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
      <?php } ?>
    </div>
    <h1> <?php echo $cat['title']; ?> </h1>
    <div class="box">
      <div class="box-content">
        <?php foreach ($posts as $post) { ?>
          <div class="box-post">
            <h3 style="margin-top: 5px;"><?php echo $post['title']; ?></h3>
      <?php
        $date_created = strtotime($cat['created']);
        $date_created_time = date("H:i:s",$date_created);
        $date_year = date("Y/m/d",$date_created);
      ?>
      <div class="blog_date"> <?php echo $date_year; ?> <span class="blog_date_time"> <?php echo $date_created_time; ?> </span></div>
            <?php echo $post['anons']; ?> <a href="<?php echo $post['href']; ?>"><?php echo $this->language->get('text_read_more'); ?></a></p>
          </div>
        <?php } ?>

        <?php if( ! empty($pagination) ): ?>
        <div class="pagination">
          <?php echo $pagination; ?>
        </div>
        <?php endif; ?>
      </div>
      <?php echo $content_bottom; ?>
    </div>
  </div>
<?php echo $footer; ?>
