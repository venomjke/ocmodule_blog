<?php
class ControllerInformationBlog extends Controller {

	/*
	* Модель блога
	*/
	private $blog;

	public function __construct($registry)
	{
		parent::__construct($registry);

		$this->load->language('information/blog');
		$this->load->model('catalog/blog');

		$this->blog = & $this->model_catalog_blog;

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('information/blog'),
			'text' => $this->language->get('heading_title_blog'),
			'separator' => $this->language->get('text_separator')
		);
		$this->document->addStyle('catalog/view/javascript/jquery/ampliacao/css/jqzoom.css');
		$this->document->addScript('catalog/view/javascript/jquery/ampliacao/jquery.jqzoom1.0.1.js');
		$this->document->addScript('catalog/view/javascript/jquery/ampliacao/jqzoom.pack.1.0.1.js');
	}

	public function index() {
		$act = !empty($this->request->get['act'])?$this->request->get['act']:'index';

		switch($act){
			case 'index':
			default:
				$this->blog_cats();
				break;
			case 'cat':
				$this->blog_category();
				break;
			case 'post':
				$this->blog_post();
				break;
		}
	}

	private function blog_cats()
	{
		$this->document->setTitle($this->language->get('heading_title_blog'));


		$cats_page = 1;

		if ( ! empty($this->request->get['page'])) {
			$cats_page = $this->request->get['page'];
		}

		$this->data['cats'] = $this->blog->loadCategories();

		$cats_limit = $this->config->get('blog_cats_limit');
		$cats = $this->blog->loadCategories(array( 'limit'=>$cats_limit, 'offset'=>$cats_page ));
		$cats_total = $this->blog->getTotalCategories();

		$this->data['cats'] = & $cats;
		$this->data['cats_total'] = & $cats_total;


		$this->load->model('tool/image');

		foreach($this->data['cats'] as &$cat){
			$cat['href'] = $this->url->link('information/blog','act=cat&catid='.$cat['id']);
			$cat['descr'] = $this->truncate_html($cat['descr'],$this->config->get('blog_headline_chars'));

			// $this->data['min_height'] = $this->config->get('blog_thumb_height');

			$this->data['thumb'] = $this->model_tool_image->resize($cat['img'], $this->config->get('blog_thumb_width'), $this->config->get('blog_thumb_height'));
			$this->data['popup'] = $this->model_tool_image->resize($cat['img'], $this->config->get('blog_popup_width'), $this->config->get('blog_popup_height'));
		}		


		$pagination = new Pagination();
		$pagination->total = $cats_total;
		$pagination->page = $cats_page;
		$pagination->limit = $cats_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('information/blog', 'page={page}');
	
		$this->data['pagination'] = $pagination->render();

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/blog/cats.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/information/blog/cats.tpl';
		} else {
			$this->template = 'default/template/information/blog/cats.tpl';
		}

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->response->setOutput($this->render());
	}

	private function blog_category()
	{
		$redirectUrl = $this->url->link('common/home');
		$catId = !empty($this->request->get['catid'])?$this->request->get['catid']:$this->redirect($redirectUrl);

		$cat = $this->blog->getCategory($catId);
		if(empty($cat)) $this->redirect($redirectUrl);


		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('information/blog', 'act=cat&catid=' . $cat['id']),
			'text' => $cat['title'],
			'separator' => $this->language->get('text_separator')
		);

		$this->document->setTitle($cat['title']);
		$this->document->setDescription($cat['meta_descr']);

		$posts_page = 1;

		if( ! empty($this->request->get['page']) ){
			$posts_page = $this->request->get['page'];
		}

		$posts_limit = $this->config->get('blog_posts_limit');
		$posts = $this->blog->getCategoryPosts($cat['id'], array( 'limit' => $posts_limit, 'offset' => $posts_page ));
		$posts_total = $this->blog->getTotalCategoryPosts($cat['id']);

		$this->load->model('tool/image');
		foreach($posts as &$post){
			$post['href'] = $this->url->link('information/blog', 'act=post&postid=' . $post['id']);

			if(!empty($post['anons'])){
				$post['anons'] = $this->truncate_html($post['descr'],$this->config->get('blog_headline_chars'));
			}else{
				$post['anons'] = $this->truncate_html($post['descr'],$this->config->get('blog_headline_chars')); 
			}


			$post['thumb'] = $this->model_tool_image->resize($post['img'], $this->config->get('blog_thumb_width'), $this->config->get('blog_thumb_height'));
			$post['popup'] = $this->model_tool_image->resize($post['img'], $this->config->get('blog_popup_width'), $this->config->get('blog_popup_height'));
		}


		$pagination = new Pagination();
		$pagination->total = $posts_total;
		$pagination->page = $posts_page;
		$pagination->limit = $posts_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('information/blog', 'act=cat&catid=' . $catId . '&page={page}');
	
		$this->data['pagination'] = $pagination->render();

		$this->data['cat'] = & $cat;
		$this->data['posts']= & $posts;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/blog/category.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/information/blog/category.tpl';
		} else {
			$this->template = 'default/template/information/blog/category.tpl';
		}

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->response->setOutput($this->render());
	}

	private function blog_post()
	{	
		$redirectUrl = $this->url->link('common/home');
		$postId = !empty($this->request->get['postid'])?$this->request->get['postid']:$this->redirect($redirectUrl);

		$post = $this->blog->getPost($postId);
		if(empty($post)) $this->redirect($redirectUrl);

		$cats = $this->blog->getCategoryByPostId($post['id']);

		if(!empty($cats)){	
			$this->data['breadcrumbs'][] = array(
				'href' => $this->url->link('information/blog','act=cat&catid='.$cats[0]['id']),
				'text' => $cats[0]['title'], // берем первую категорию
				'separator' => $this->language->get('text_separator')
			);	
		}

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('information/blog','act=post&postid='.$post['id']),
			'text' => $post['title'],
			'separator' => $this->language->get('text_separator')
		);

		$this->document->setTitle($post['title']);
		$this->document->setDescription($post['meta_descr']);

		$this->load->model('tool/image');
		$post['thumb'] = $this->model_tool_image->resize($post['img'], $this->config->get('blog_thumb_width'), $this->config->get('blog_thumb_height'));
		$post['popup'] = $this->model_tool_image->resize($post['img'], $this->config->get('blog_popup_width'), $this->config->get('blog_popup_height'));

		$this->data['min_height'] = $this->config->get('blog_thumb_height');
		$this->data['min_width'] = $this->config->get('blog_thumb_width');

		$this->data['post']= $post;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/blog/post.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/information/blog/post.tpl';
		} else {
			$this->template = 'default/template/information/blog/post.tpl';
		}

		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);

		$this->response->setOutput($this->render());
	}

	private function truncate_html($html,$size=100,$offset=0){
		return mb_substr(strip_tags(html_entity_decode($html, ENT_QUOTES, 'UTF-8')),$offset,$size,'UTF-8');
	}
}
?>
