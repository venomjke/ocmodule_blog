<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if (!empty($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $this->language->get('heading_title'); ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $this->language->get('button_save'); ?></span></a><a onclick="location = '<?php echo $this->url->link('module/blog','token='.$token,'SSL'); ?>';" class="button"><span><?php echo $this->language->get('button_cancel'); ?></span></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs"><a href="#tab_general"><?php echo $this->language->get('tab_general'); ?></a><a href="#tab_data"><?php echo $this->language->get('tab_data'); ?></a></div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab_general">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $this->language->get('entry_title'); ?></td>
              <td><input name="title" value="<?php echo $title; ?>" />
                <?php if ($error_title) { ?>
                <span class="error"><?php echo $error_title; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $this->language->get('entry_meta_description'); ?></td>
              <td><textarea name="meta_descr" cols="40" rows="5"><?php echo $meta_descr; ?></textarea></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $this->language->get('entry_cat_description'); ?></td>
              <td><textarea name="descr" id="descr"><?php echo $descr  ?></textarea>
                <?php if ($error_descr) { ?>
                <span class="error"><?php echo $error_descr; ?></span>
                <?php } ?></td>
            </tr>
          </table>
        </div>
        <div id="tab_data">
          <table class="form">
            <tr>
              <td><?php echo $this->language->get('entry_status'); ?></td>
              <td><select name="status">
                  <?php if ($status) { ?>
                  <option value="1" selected="selected"><?php echo $this->language->get('text_enabled'); ?></option>
                  <option value="0"><?php echo $this->language->get('text_disabled'); ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $this->language->get('text_enabled'); ?></option>
                  <option value="0" selected="selected"><?php echo $this->language->get('text_disabled'); ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $this->language->get('entry_image'); ?></td>
              <td valign="top"><input type="hidden" name="img" value="<?php echo $img; ?>" id="img" />
                <img src="<?php echo $preview; ?>" alt="" id="preview" class="image" onclick="image_upload('img', 'preview');" /></td>
            </tr>
          </table>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
CKEDITOR.replace('descr', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
//--></script> 
<script type="text/javascript"><!--
function image_upload(field, preview) {
	$('#dialog').remove();

	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

	$('#dialog').dialog({
		title: '<?php echo $this->language->get('text_image_manager'); ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>',
					data: 'image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" class="image" onclick="image_upload(\'' + field + '\', \'' + preview + '\');" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 700,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script> 
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
//--></script> 
<?php echo $footer; ?>
