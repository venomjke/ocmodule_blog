<?php

class ControllerModuleBlog extends Controller {

	private $error = array();

	/*
	* Ссылка на модель блога.
	*/
	private $blog;

	/*
	* Ссылка на модель настроек
	*/
	private $setting;

	/*
	* Токен сессии
	*/
	private $token;

	private function init()
	{
		
		$this->load->language('module/blog');

		$this->load->model('blog/blog');
		$this->load->model('setting/setting');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['token'] = $this->session->data['token'];

		$this->blog = & $this->model_blog_blog;
		$this->setting = & $this->model_setting_setting;
		$this->token = $this->session->data['token'];

		$this->data['breadcrumbs'] = array();

 		$this->data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
    		'separator' => false
 		);

 		$this->data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('text_module'),
				'href'   => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
    		'separator' => ' :: '
 		);
	
 		$this->data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('module/blog', 'token=' . $this->session->data['token'], 'SSL'),
    		'separator' => ' :: '
		);

		$this->document->addScript('view/javascript/jquery/blog_tabs.js');

	}

	/*
	* Главная страница модуля. 
	*/
	public function index()
	{
		$this->init();

		if($this->is_post() && $this->module_validation()){
			$this->setting->editSetting('blog',$this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/module','token=' . $this->session->data['token'],'SSL'));
		}

		$fields = array( 'blog_headline_chars', 'blog_thumb_width', 'blog_thumb_height', 'blog_popup_width', 'blog_popup_height', 'blog_module', 'blog_cats_limit', 'blog_posts_limit' );

		foreach($fields as $field){
			if(isset($this->request->post[$field])){
				$this->data[$field] = $this->request->post[$field];
			}else{
				$this->data[$field] = $this->config->get($field);
			}

			if(isset($this->error[$field])){
				$this->data["error_".$field] = $this->error[$field];
			}else{
				$this->data["error_".$field] = "";
			}		
		}

		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		$this->data['cats']		 = $this->blog->getCats();

		$this->template = 'module/blog/index.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	/*
	* Раздел управления категориями
	*/
	public function cats()
	{
		$this->init();

		$act = !empty($this->request->get['act'])?$this->request->get['act']:'index';
		$this->data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('heading_title_cats'),
				'href'      => $this->url->link('module/blog/cats', 'token=' . $this->session->data['token'], 'SSL'),
    		'separator' => ' :: '
		);

		switch($act){
			case 'index':
			default:
				$this->cats_index();
			break;
			case 'add':
				$this->cats_add();
			break;
			case 'edit':
				$this->cats_edit();
			break;
			case 'del':
				$this->cats_del();
			break;
		}
	}

	/*
	* Страница всех категорий
	*/
	private function cats_index()
	{

		$cats_page = 1;

		if ( ! empty($this->request->get['page'])) {
			$cats_page = $this->request->get['page'];
		}

		$cats_limit = $this->config->get('blog_cats_limit');
		$cats = $this->blog->getCats(array( 'limit'=>$cats_limit, 'offset'=>$cats_page ));
		$cats_total = $this->blog->getTotalCats();


		$this->data['cats'] = & $cats;
		$this->data['cats_total'] = & $cats_total;

		$pagination = new Pagination();
		$pagination->total = $cats_total;
		$pagination->page = $cats_page;
		$pagination->limit = $cats_limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('module/blog/cats', 'token=' . $this->token . '&page={page}');
	
		$this->data['pagination'] = $pagination->render();

		$this->template = 'module/blog/cats_index.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());	
	}

	/*
	* Страница добавления новой категории
	*/
	private function cats_add()
	{
		if($this->is_post() && $this->cats_add_validation()){
			$this->blog->addCategory($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('module/blog/cats','token='.$this->session->data['token'],'SSL'));
		}	

		$fields = array('title', 'descr', 'meta_descr', 'status', 'img');

		foreach($fields as $field){
			if(isset($this->request->post[$field])){
				$this->data[$field] = $this->request->post[$field];
			}else{
				$this->data[$field] = '';
			}

			if(isset($this->error[$field])){
				$this->data["error_".$field] = $this->error[$field];
			}else{
				$this->data["error_".$field] = "";
			}
		}

		$this->load->model('tool/image');
		$this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		$this->data['action'] = $this->url->link('module/blog/cats','act=add&token='.$this->token,'SSL');

		$this->template = 'module/blog/cats_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	/*
	* Редактирование категории
	*/
	private function cats_edit()
	{
		$redirectUrl = $this->url->link('module/blog/cats','token='.$this->token,'SSL');
		$catId = !empty($this->request->get['catId'])?$this->request->get['catId']:$this->redirect($redirectUrl);
		$cat = $this->blog->getCategory($catId);
		if(empty($cat)) $this->redirect($redirectUrl);

		if($this->is_post() && $this->cats_edit_validation($cat)){
			$this->blog->editCategory($catId,$this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($redirectUrl);
		}

		$fields = array('title', 'descr', 'meta_descr', 'status', 'img');

		foreach($fields as $field){
			if(isset($this->request->post[$field])){
				$this->data[$field] = $this->request->post[$field];
			} else {
				if(isset($cat[$field])){
					$this->data[$field] = $cat[$field];
				} else {
					$this->data[$field] = '';
				}
			}

			if(isset($this->error[$field])){
				$this->data["error_".$field] = $this->error[$field];
			}else{
				$this->data["error_".$field] = "";
			}		
		}


		$this->load->model('tool/image');

		if ($cat['img'] && file_exists(DIR_IMAGE . $cat['img'])) {
			$this->data['preview'] = $this->model_tool_image->resize($cat['img'], 100, 100);
		} else {
			$this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		$this->data['action'] = $this->url->link('module/blog/cats','act=edit&catId='.$catId.'&token='.$this->token,'SSL');

		$this->template = 'module/blog/cats_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	/*
	* Удаление категории
	*/
	private function cats_del()
	{
		$redirectUrl = $this->url->link('module/blog/cats','token='.$this->token,'SSL');
		$catId = !empty($this->request->get['catId'])?$this->request->get['catId']:$this->redirect($redirectUrl);
		$this->blog->delCategory($catId);
		$this->redirect($redirectUrl);
	}

	/*
	* Раздел управления постами
	*/
	public function posts()
	{
		$this->init();
	
		$act = !empty($this->request->get['act'])?$this->request->get['act']:'index';
		$this->data['breadcrumbs'][] = array(
     		'text'      => $this->language->get('heading_title_posts'),
				'href'      => $this->url->link('module/blog/cats', 'token=' . $this->token, 'SSL'),
    		'separator' => ' :: '
		);


		switch($act){
			case 'index':
			default:
				$this->posts_index();
			break;
			case 'add':
				$this->posts_add();
			break;
			case 'edit':
				$this->posts_edit();
			break;
			case 'del':
				$this->posts_del();
			break;
		}	
	}

	private function posts_index()
	{

		$posts_page = 1;

		if ( ! empty($this->request->get['page'])) {
			$posts_page = $this->request->get['page'];
		}

		$posts_limit = $this->config->get('blog_posts_limit');
		$posts = $this->blog->getPosts(array( 'limit' => $posts_limit, 'offset' => $posts_page ));
		$posts_total = $this->blog->getTotalPosts();


		$this->data['posts'] = & $posts;

		$pagination = new Pagination();
		$pagination->total = $posts_total;
		$pagination->page  = $posts_page;
		$pagination->limit = $posts_limit;
		$pagination->text  = $this->language->get('text_pagination');
		$pagination->url   = $this->url->link('module/blog/posts', 'token=' . $this->token . '&page={page}');
	
		$this->data['pagination'] = $pagination->render();

		$this->template = 'module/blog/posts_index.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}
	/*
	* Страница добавления поста
	*/
	private function posts_add()
	{

		if($this->is_post() && $this->posts_add_validation()){
			$this->blog->addPost($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('module/blog/posts','token='.$this->token,'SSL'));
		}	

		$fields = array('title', 'anons', 'descr', 'post_cats', 'meta_descr', 'status', 'img');

		foreach($fields as $field){
			if(isset($this->request->post[$field])){
				$this->data[$field] = $this->request->post[$field];
			} else {
				if($field != 'post_cats'){
					$this->data[$field] = '';
				} else {
					$this->data[$field] = array();
				}
			}

			if(isset($this->error[$field])){
				$this->data["error_".$field] = $this->error[$field];
			}else{
				$this->data["error_".$field] = "";
			}
		}


		$this->load->model('tool/image');
		$this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		$this->data['cats'] = $this->blog->getCats();

		$this->data['action'] = $this->url->link('module/blog/posts', 'act=add&token=' . $this->token, 'SSL');
		$this->template = 'module/blog/posts_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	/*
	* Страница редактирования поста
	*/
	private function posts_edit()
	{
		$redirectUrl = $this->url->link('module/blog/posts','token='.$this->token,'SSL');
		$postId = !empty($this->request->get['postId'])?$this->request->get['postId']:$this->redirect($redirectUrl);
		$post = $this->blog->getPost($postId);
		if(empty($post)) $this->redirect($redirectUrl);

		if($this->is_post() && $this->posts_edit_validation($post)){
			$this->blog->editPost($postId,$this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($redirectUrl);
		}

		$fields = array('title', 'anons', 'descr', 'post_cats', 'meta_descr', 'status', 'img');

		foreach($fields as $field){
			if(isset($this->request->post[$field])){
				$this->data[$field] = $this->request->post[$field];
			}else{
				if(isset($post[$field])){
					$this->data[$field] = $post[$field];
				}else{
					$this->data[$field] = '';
				}	
			}

			if(isset($this->error[$field])){
				$this->data["error_".$field] = $this->error[$field];
			}else{
				$this->data["error_".$field] = "";
			}
		}

		$this->load->model('tool/image');

		if ($post['img'] && file_exists(DIR_IMAGE . $post['img'])) {
			$this->data['preview'] = $this->model_tool_image->resize($post['img'], 100, 100);
		} else {
			$this->data['preview'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		$this->data['cats'] = $this->blog->getCats();
		$this->data['action'] = $this->url->link('module/blog/posts','act=edit&postId='.$postId.'&token='.$this->token,'SSL');
		$this->template = 'module/blog/posts_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	/*
	* Удаление поста
	*/
	private function posts_del()
	{
		$redirectUrl = $this->url->link('module/blog/posts','token='.$this->token,'SSL');
		$postId = !empty($this->request->get['postId'])?$this->request->get['postId']:$this->redirect($redirectUrl);
		$this->blog->delPost($postId);
		$this->redirect($redirectUrl);
	}

	/*
	* Установка/Удаление модуля
	*/
	public function install()
	{
		$this->init();

		$settings = array(
			'blog_headline_chars' => 150,
			'blog_thumb_width'    => 80,
			'blog_thumb_height'   => 80,
			'blog_popup_width'    => 80,
			'blog_popup_height'   => 80,
			'blog_cats_limit'     => 10,
			'blog_posts_limit'    => 10
		);
		$this->model_setting_setting->editSetting('blog',$settings);
		$this->blog->install();
	}

	public function uninstall()
	{
		$this->init();

		$this->model_setting_setting->deleteSetting('blog');
		$this->blog->uninstall();
	}

	/*
	* Валидация
	*/
	private function module_validation()
	{
		if (!$this->user->hasPermission('modify', 'module/blog')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$fields = array('blog_headline_chars','blog_thumb_height','blog_thumb_width','blog_popup_height','blog_popup_width','blog_cats_limit','blog_posts_limit');

		foreach($fields as $field){
			if(empty($this->request->post[$field]) or !is_numeric($this->request->post[$field]) or $this->request->post[$field] < 0)
				$this->error[$field] = $this->language->get('error_'.$field);
		}

		if(!empty($this->request->post['blog_module'])){
			foreach($this->request->post['blog_module'] as &$module){
				if(empty($module['numchars']) or ! is_numeric($module['numchars'])){
					if(empty($this->error['blog_module'])){
						$this->error['blog_module'] = $this->language->get('error_blog_module');
					}
					$module['error_numchars'] = $this->language->get('error_blog_headline_chars');
				}

				if( empty($module['thumb_width']) or empty($module['thumb_height']) or ! is_numeric($module['thumb_width']) or ! is_numeric($module['thumb_height']) ){
					if(empty($this->error['blog_module'])){
						$this->error['blog_module'] = $this->language->get('error_blog_module');
					}
					$module['error_thumb_height'] = $this->language->get('error_blog_thumb_height');
					$module['error_thumb_width'] = $this->language->get('error_blog_thumb_width');
				}
			}
		}

		if(!empty($this->error)) return false;
		return true;
	}

	private function cats_validation()
	{
		if (!$this->user->hasPermission('modify', 'module/blog')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if(empty($this->request->post['title']) or mb_strlen($this->request->post['title']) > 512){
			$this->error['title'] = $this->language->get('error_title');
		}

			return empty($this->error);
	}

	private function cats_add_validation()
	{
		return $this->cats_validation();
	}

	private function cats_edit_validation()
	{
		return $this->cats_validation();
	}

	private function posts_validation()
	{
		if (!$this->user->hasPermission('modify', 'module/blog')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if(empty($this->request->post['title']) or mb_strlen($this->request->post['title']) > 512){
			$this->error['title'] = $this->language->get('error_title');
		}
		return empty($this->error);
	}
	
	private function posts_add_validation(){

		return $this->posts_validation();
	}

	private function posts_edit_validation(){
		return $this->posts_validation();
	}

	/*
	* Хелперы
	*/
	private function is_post()
	{
		return $this->request->server['REQUEST_METHOD'] == 'POST';
	}
}