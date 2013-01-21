<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>

  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <a onclick="$('#form').submit();" class="button"><?php echo $this->language->get('button_save');?></a>
        <a href="<?php echo $this->url->link('extension/module','token='.$token,'SSL');?>" class="button"><?php echo $this->language->get('button_cancel');?></a>
      </div>
    </div>
    <div class="content">
      <form action="<?php echo $this->url->link('module/blog','token='.$this->session->data['token'],'SSL'); ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tabs" class="htabs">
          <a href="#tab_general"><?php echo $this->language->get('tab_general'); ?></a>
          <a href="#tab_settings"><?php echo $this->language->get('tab_settings'); ?></a>
          <a onclick=" location='<?php echo $this->url->link('module/blog/cats', 'token=' . $token, 'SSL'); ?>' "> <?php echo $this->language->get('tab_categories'); ?> </a>
          <a onclick=" location='<?php echo $this->url->link('module/blog/posts', 'token=' . $token, 'SSL'); ?>' "> <?php echo $this->language->get('tab_posts'); ?></a> 
        </div>

        <div id="tab_general">

          <?php if($error_blog_module): ?>
            <div>
              <span class="error"> <?php echo $error_blog_module; ?> </span>
            </div>  
          <?php endif; ?>

          <table id="module" class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $this->language->get('entry_limit'); ?></td>
                <td class="left"><?php echo $this->language->get('entry_layout'); ?></td>
                <td class="left"><?php echo $this->language->get('entry_position'); ?></td>
                <td class="left"><?php echo $this->language->get('entry_status'); ?></td>
                <td class="left"><?php echo $this->language->get('entry_headline'); ?></td>
                <td class="left"><?php echo $this->language->get('entry_cat'); ?></td>
                <td class="left"><?php echo $this->language->get('entry_numchars'); ?></td>
                <td class="left"><?php echo $this->language->get('entry_blogpage_thumb'); ?></td>
                <td class="right"><?php echo $this->language->get('entry_sort_order'); ?></td>
                <td style="width: 150px;"></td>
              </tr>
            </thead>
              <?php $module_row = 0; ?>
              <?php if(!empty($blog_module)):?>
              <?php foreach ($blog_module as $module) { ?>
              <tbody id="module-row<?php echo $module_row; ?>">
                <tr>
                  <td class="left"><input type="text" name="blog_module[<?php echo $module_row; ?>][limit]" value="<?php echo $module['limit']; ?>" size="1" /></td>
                  <td class="left"><select name="blog_module[<?php echo $module_row; ?>][layout_id]">
                      <?php foreach ($layouts as $layout) { ?>
                      <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                      <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select></td>
                  <td class="left"><select name="blog_module[<?php echo $module_row; ?>][position]">
                      <?php if ($module['position'] == 'content_top') { ?>
                      <option value="content_top" selected="selected"><?php echo $this->language->get('text_content_top'); ?></option>
                      <?php } else { ?>
                      <option value="content_top"><?php echo $this->language->get('text_content_top'); ?></option>
                      <?php } ?>
                      <?php if ($module['position'] == 'content_bottom') { ?>
                      <option value="content_bottom" selected="selected"><?php echo $this->language->get('text_content_bottom'); ?></option>
                      <?php } else { ?>
                      <option value="content_bottom"><?php echo $this->language->get('text_content_bottom'); ?></option>
                      <?php } ?>
                      <?php if ($module['position'] == 'column_left') { ?>
                      <option value="column_left" selected="selected"><?php echo $this->language->get('text_column_left'); ?></option>
                      <?php } else { ?>
                      <option value="column_left"><?php echo $this->language->get('text_column_left'); ?></option>
                      <?php } ?>
                      <?php if ($module['position'] == 'column_right') { ?>
                      <option value="column_right" selected="selected"><?php echo $this->language->get('text_column_right'); ?></option>
                      <?php } else { ?>
                      <option value="column_right"><?php echo $this->language->get('text_column_right'); ?></option>
                      <?php } ?>
                    </select></td>
                  <td class="left"><select name="blog_module[<?php echo $module_row; ?>][status]">
                      <?php if ($module['status']) { ?>
                      <option value="1" selected="selected"><?php echo $this->language->get('text_enabled'); ?></option>
                      <option value="0"><?php echo $this->language->get('text_disabled'); ?></option>
                      <?php } else { ?>
                      <option value="1"><?php echo $this->language->get('text_enabled'); ?></option>
                      <option value="0" selected="selected"><?php echo $this->language->get('text_disabled'); ?></option>
                      <?php } ?>  
                    </select></td>
                  <td class="left"><select name="blog_module[<?php echo $module_row; ?>][headline]">
                      <?php if ($module['headline']) { ?>
                      <option value="1" selected="selected"><?php echo $this->language->get('text_enabled'); ?></option>
                      <option value="0"><?php echo $this->language->get('text_disabled'); ?></option>
                      <?php } else { ?>
                      <option value="1"><?php echo $this->language->get('text_enabled'); ?></option>
                      <option value="0" selected="selected"><?php echo $this->language->get('text_disabled'); ?></option>
                      <?php } ?>
                    </select></td>
                  <td class="left"><select name="blog_module[<?php echo $module_row;?>][cat]">
                    <?php foreach($cats as $cat): ?>

                      <?php if(!empty($cat)): ?>
                      <option value="<?php echo $cat['id']; ?>" <?php echo !empty($module['cat']) && $cat['id'] == $module['cat']?'selected="selected"':''; ?>> <?php echo $cat['title']; ?> </option>
                      <?php endif;?>
                    <?php endforeach; ?>
                  </select>
                  </td>
                  <td class="left"><input type="text" name="blog_module[<?php echo $module_row; ?>][numchars]" value="<?php echo $module['numchars']; ?>" size="3" />

                    <?php if (isset($module['error_numchars'])) { ?>
                    <span class="error"><?php echo $module['error_numchars']; ?></span>
                    <?php } ?></td>
                  <td class="left">
                    <label for="blog_module[<?php echo $module_row;?>][thumb_height]"> <?php echo $this->language->get('blog_thumb_height'); ?> </label>
                    <input type="text" name="blog_module[<?php echo $module_row;?>][thumb_height]" value="<?php echo $module['thumb_height']; ?>"/>
                    <label for="blog_module[<?php echo $module_row; ?>][thumb_width]"> <?php echo $this->language->get('blog_thumb_width'); ?> </label>
                    <input type="text" name="blog_module[<?php echo $module_row;?>][thumb_width]" value="<?php echo $module['thumb_width']; ?>"/>

                    <?php if( isset($module['error_thumb_width']) ): ?>
                     <span class="error"><?php echo $module['error_thumb_width']; ?>, <?php echo $module['error_thumb_height']; ?></span>
                   <?php endif; ?>
                  </td>
                  <td class="right"><input type="text" name="blog_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
                  <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><span><?php echo $this->language->get('button_remove'); ?></span></a></td>
                </tr>
              </tbody>
              <?php $module_row++; ?>
              <?php } ?>
              <?php endif; ?>
              <tfoot>
                <tr>
                  <td colspan="9"></td>
                  <td class="left"><a onclick="addModule();" class="button"><span><?php echo $this->language->get('button_add_module'); ?></span></a></td>
                </tr>
              </tfoot>
          </table>  
        </div>

        <div id="tab_settings">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $this->language->get('entry_headline_chars'); ?></td>
              <td><input type="text" name="blog_headline_chars" value="<?php echo $blog_headline_chars; ?>" size="3" />
                <?php if (isset($error_blog_headline_chars)) { ?>
                <span class="error"><?php echo $error_blog_headline_chars; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $this->language->get('entry_blogpage_thumb'); ?></td>
              <td><input type="text" name="blog_thumb_width" value="<?php echo $blog_thumb_width; ?>" size="3" />
                x
                <input type="text" name="blog_thumb_height" value="<?php echo $blog_thumb_height; ?>" size="3" />
                <?php if ($error_blog_thumb_width) { ?>
                <span class="error"><?php echo $error_blog_thumb_width; ?></span>
                <?php } ?>
                <?php if ($error_blog_thumb_height) { ?>
                <span class="error"><?php echo $error_blog_thumb_height; ?></span>
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $this->language->get('entry_blogpage_thumb'); ?></td>
              <td><input type="text" name="blog_popup_width" value="<?php echo $blog_popup_width; ?>" size="3" />
                <input type="text" name="blog_popup_height" value="<?php echo $blog_popup_height; ?>" size="3" />
                <?php if ($error_blog_popup_width) { ?>
                <span class="error"><?php echo $error_blog_popup_width; ?></span>
                <?php } ?>
                <?php if ($error_blog_popup_height) { ?>
                <span class="error"><?php echo $error_blog_popup_height; ?></span>
                <?php } ?>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $this->language->get('entry_blog_cats_limit'); ?></td>
              <td>
                <input type="text" name="blog_cats_limit" value="<?php echo $blog_cats_limit; ?>" />

                <?php if ( $error_blog_cats_limit ): ?>
                  <span class="error"> <?php echo $error_blog_cats_limit; ?> </span>
                <?php endif; ?>
              </td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $this->language->get('entry_blog_posts_limit'); ?></td>
              <td>
                <input type="text" name="blog_posts_limit" value="<?php echo $blog_posts_limit; ?>" />

                <?php if ( $error_blog_posts_limit ): ?>
                  <span class="error"> <?php echo $error_blog_posts_limit; ?> </span>
                <?php endif; ?>
              </td>
            </tr>
          </table>  
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><input type="text" name="blog_module[' + module_row + '][limit]" value="5" size="1" /></td>';
	html += '    <td class="left"><select name="blog_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="blog_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $this->language->get("text_content_top"); ?></option>';
	html += '      <option value="content_bottom"><?php echo $this->language->get("text_content_bottom"); ?></option>';
	html += '      <option value="column_left"><?php echo $this->language->get("text_column_left"); ?></option>';
	html += '      <option value="column_right"><?php echo $this->language->get("text_column_right"); ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="blog_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $this->language->get("text_enabled"); ?></option>';
    html += '      <option value="0"><?php echo $this->language->get("text_disabled"); ?></option>';
    html += '    </select></td>';
	html += '    <td class="left"><select name="blog_module[' + module_row + '][headline]">';
    html += '      <option value="1" selected="selected"><?php echo $this->language->get("text_enabled"); ?></option>';
    html += '      <option value="0"><?php echo $this->language->get("text_disabled"); ?></option>';
    html += '    </select></td>';
  html += '    <td class="left"><select name="blog_module[' + module_row + '][cat]">';
  <?php foreach($cats as $cat): ?>
    html += '      <option value="<?php echo $cat['id'] ?>"> <?php echo $cat['title']; ?> </option>'
  <?php endforeach; ?>
    html + '   </select></td>';
	html += '    <td class="left"><input type="text" name="blog_module[' + module_row + '][numchars]" value="<?php echo $blog_headline_chars; ?>" size="3" /></td>';
  
  html += '    <td class="left"> '
  html += '     <label for="blog_module[' + module_row + '][thumb_height]"><?php echo $this->language->get("blog_thumb_height"); ?></label>';
  html += '     <input type="text" name="blog_module[' + module_row + '][thumb_height]" value="<?php echo $blog_thumb_height; ?>" />';
  html += '     <label for="blog_module[' + module_row + '][thumb_width]"><?php echo $this->language->get("blog_thumb_width"); ?></label>'
  html += '    <input type="text" name="blog_module[' + module_row + '][thumb_width]" value="<?php echo $blog_thumb_width; ?>" />'
  html += '    </td>';

	html += '    <td class="right"><input type="text" name="blog_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><span><?php echo $this->language->get("button_remove"); ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}
//--></script>

<script type="text/javascript"><!--
 $(function(){
  $('#tabs a').blog_tabs();  
 })
//--></script>
<?php echo $footer; ?>
