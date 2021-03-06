<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if (!empty($success)) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $this->language->get('heading_title_posts'); ?></h1>
      <div class="buttons">
        <a href="<?php echo $this->url->link("module/blog/posts","act=add&token=".$token,"SSL");?>" class="button"><?php echo $this->language->get("button_insert");?></a>
        <a href="<?php echo $this->url->link("module/blog/posts","act=del&token=".$token,"SSL");?>" class="button"><?php echo $this->language->get("button_delete"); ?></a>
      </div>
    </div>
    <div class="content">
      <form action="<?php  ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tabs" class="htabs">
          <a href="<?php echo $this->url->link('module/blog', 'token=' . $token, 'SSL'); ?>" onclick=" location = $(this).attr('href');"><?php echo $this->language->get('tab_general'); ?></a>
          <a href="<?php echo $this->url->link('module/blog', 'token=' . $token, 'SSL'); ?>" onclick=" location = $(this).attr('href');"><?php echo $this->language->get('tab_settings'); ?></a>
          <a onclick=" location='<?php echo $this->url->link('module/blog/cats', 'token=' . $token, 'SSL'); ?>' "> <?php echo $this->language->get('tab_categories'); ?> </a>
          <a href="#tab_posts" class="tab_posts"> <?php echo $this->language->get('tab_posts'); ?></a> 
        </div>

        <div id="tab_posts">
          <table class="list">
          <thead>
            <tr>
              <td width="1" align="center"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php echo $this->language->get('column_title'); ?></td>
              <td class="left"><?php echo $this->language->get('column_post_category');?></td>
              <td class="left"><?php echo $this->language->get('column_created'); ?></td>
              <td class="right"><?php echo $this->language->get('column_status'); ?></td>
              <td class="right"><?php echo $this->language->get('column_action'); ?></td>
            </tr>
          </thead>
          <?php if( ! empty($pagination) ): ?>
          <tfoot>
            <tr>
              <td colspan="6" >
                <?php echo $pagination; ?>            
              </td>
            </tr>
          </tfoot>
          <?php endif; ?>
          <tbody>
            <?php if ($posts) { ?>
            <?php $class = 'odd'; ?>
            <?php foreach ($posts as $post) { ?>
            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
            <tr class="<?php echo $class; ?>">
              <td align="center">
                <input type="checkbox" name="selected[]" value="<?php echo $post['id']; ?>" />
              </td>
              <td class="left"><?php echo $post['title']; ?></td>
              <td class="left">
                <?php $i = 0;?>
                <?php foreach($post['post_cats'] as $cat): ?>
                  <?php if( $i == 0 ): $i = 1; else: ?> , <?php endif;?> 
                  <a href="<?php echo $this->url->link("module/blog/cats","act=edit&catId=".$cat['id']."&token=".$token,"SSL");?>"><?php echo $cat['title']; ?></a>
                <?php endforeach;?>
              </td>
              <td class="left"><?php echo $post['created']; ?></td>
              <td class="right"><?php echo $post['status'] == 1?$this->language->get('text_enabled'):$this->language->get('text_disabled'); ?></td>
              <td class="right">
                [<a href="<?php echo $this->url->link('module/blog/posts','act=edit&postId='.$post['id'].'&token='.$token,'SSL'); ?>"><?php echo $this->language->get('button_edit');?></a>]
                [<a href="<?php echo $this->url->link('module/blog/posts','act=del&postId='.$post['id'].'&token='.$token,'SSL'); ?>"><?php echo $this->language->get('button_delete');?></a>]
              </td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr class="even">
              <td class="center" colspan="6"><?php echo $this->language->get('text_no_results'); ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        </div> 
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(function(){
    $('#tabs a').blog_tabs({ selected: '#tabs a.tab_posts'});
  })
</script>
<?php echo $footer; ?>
