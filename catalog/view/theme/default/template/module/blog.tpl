<div class="box">
  <div class="box-heading"><?php echo $box_title; ?></div>
  		<div class="box-content">
  			<?php foreach($posts as $post): ?>
  				<div class="box-product" style="overflow:none;">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<?php if($post['thumb']): ?>
								<td valign="top">
									<a href="<?php echo $post['href']; ?>"><img src="<?php echo $post['thumb']; ?>" alt="<?php echo $post['title']; ?>"/></a>
								</td>
  							<?php endif;?>
  							<td width="100%" valign="top">
  								<div class="name">
  									<a href="<?php echo $post['href']; ?>"> <?php echo $post['title']; ?> </a> дата:<span> <?php echo $post['created']; ?>
  								</div>
  								<div class="description">
  									<?php echo $post['anons']; ?>
  								</div>
  							</td>
							</tr>
						</table>
  				</div>
	  		<?php endforeach; ?>
		</div>
</div>