<?php
class ControllerModuleBlog extends Controller {
	protected function index($setting) {
		$this->load->language('module/blog');
		$this->load->model('catalog/blog');
		$this->load->model('tool/image');

		$this->id = 'blog';

		$catId = $setting['cat'];
		$category = '';
		$numchars = $setting['numchars'];
		$limit = $setting['limit'];
		$headline = $setting['headline'];
		$thumb_width = $setting['thumb_width'];
		$thumb_height = $setting['thumb_height'];

		$posts = array();

		$filter['limit'] = $limit;
		if(!empty($catId)){
			$category = $this->model_catalog_blog->getCategory($catId);
			$posts = $this->model_catalog_blog->getCategoryPosts($catId, array( 'limit' => $limit ));
		}else{
			$posts = $this->model_catalog_blog->getPosts( array( 'limit' => $limit ) );
		}

		foreach($posts as &$post){
			$post['href'] = $this->url->link('information/blog', 'act=post&postid=' . $post['id'],'','SSL');
			if(!empty($post['anons'])){
				$post['anons'] = $this->truncate_html($post['anons'], $numchars);
			}else{
				$post['anons'] = $this->truncate_html($post['descr'], $numchars);
			}

			$post['thumb'] = $this->model_tool_image->resize($post['img'], $thumb_width, $thumb_height);

		}

		if($headline){
			if(!empty($category)){
				$this->data['box_title'] = $category['title'];
			}else{
				$this->data['box_title'] = $this->language->get('heading_title_blog');
			}
		}else{
			$this->data['box_title'] = '';
		}

		$this->data['posts'] = $posts;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/blog.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/blog.tpl';
		} else {
			$this->template = 'default/template/module/blog.tpl';
		}

		$this->render();
	}

	private function truncate_html($html,$size,$offset=0)
	{
		return mb_substr(strip_tags(html_entity_decode($html, ENT_QUOTES, 'UTF-8')),$offset,$size,'UTF-8').' ...';
	}
}
?>
