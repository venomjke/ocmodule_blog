<?php

class ModelBlogBlog extends Model{

	/*
	* Добавление категории
	*/
	public function addCategory($data)
	{
		$this->db->query("INSERT INTO `".DB_PREFIX."blog_categories`(title,descr,meta_descr,img,status)VALUES('" . $this->db->escape($data['title']) . "','" . $this->db->escape($data['descr']) . "','" . $this->db->escape($data['meta_descr'])."','" . $this->db->escape($data['img']) ."','".(int)$data['status']."')");
	}

	/*
	* Выбор категории
	*/
	public function getCategory($id)
	{
		$result = $this->db->query("SELECT * FROM `".DB_PREFIX."blog_categories` WHERE id='".(int)$id."' LIMIT 1");
		return $result->row;
	}

	/*
	* Выбор сразу большого числа категорий
	*/
	public function getCats($args = array())
	{
		$query = "SELECT * FROM `".DB_PREFIX."blog_categories` ORDER BY id ASC ";

		$this->setLimit($query, $args);

		$result = $this->db->query($query);
		return $result->rows;
	}

	public function getTotalCats()
	{
		$result = $this->db->query("SELECT COUNT(*) as cnt FROM `" . DB_PREFIX . "blog_categories`");
		if( ! empty($result->row['cnt']) ){
			return $result->row['cnt'];
		}
		return 0;
	}

	/*
	* Удаление категории
	*/
	public function delCategory($id)
	{
		$this->db->query("DELETE FROM `".DB_PREFIX."blog_categories` WHERE id='".(int)$id."'");
		$this->db->query("DELETE FROM `".DB_PREFIX."blog_posts_cats` WHERE catId='".(int)$id."'");
	}

	/*
	* Редактирование категории
	*/
	public function editCategory($id,$data)
	{
		$this->db->query("UPDATE `".DB_PREFIX."blog_categories` SET title='".$this->db->escape($data['title'])."',descr='".$this->db->escape($data['descr'])."',meta_descr='".$this->db->escape($data['meta_descr'])."',img='".$this->db->escape($data['img'])."',status='".$this->db->escape($data['status'])."' WHERE id='".(int)$id."'");
	}

	/*
	* Добавление записи блога
	*/
	public function addPost($data)
	{
		$this->db->query("INSERT INTO `" .DB_PREFIX . "blog_posts`(title,descr,anons,meta_descr,img,status)VALUES('".$this->db->escape($data['title'])."','".$this->db->escape($data['descr']) . "','" . $this->db->escape($data['anons']) . "','" . $this->db->escape($data['meta_descr'])."','".$this->db->escape($data['img'])."','".$data['status']."')");
		$postId = $this->db->getLastId();
		if(!empty($data['post_cats'])){
			foreach($data['post_cats'] as $catId){
				$this->db->query("INSERT INTO `".DB_PREFIX."blog_posts_cats`(postId,catId)VALUES('".(int)$postId."','".(int)$catId."')");
			}
		}
	}

	/*
	* Выбор поста
	*/
	public function getPost($id)
	{
		$result = $this->db->query("SELECT * FROM `".DB_PREFIX."blog_posts` WHERE id='".(int)$id."' LIMIT 1");
		if(!empty($result->row)){
			$result_cats = $this->db->query("SELECT * FROM `".DB_PREFIX."blog_posts_cats` WHERE postId='".(int)$id."'");
			$result->row['post_cats'] = array();
			foreach($result_cats->rows as $row){
				$result_cat = $this->getCategory($row['catId']);
				$result->row['post_cats'][] = $result_cat['id'];
			}
			return $result->row;
		}
		return array();
	}

	/*
	* Подсчет общего числа постов
	*/
	public function getTotalPosts()
	{
		$result = $this->db->query("SELECT COUNT(*) as cnt FROM `" . DB_PREFIX . "blog_posts`");
		if( ! empty($result->row['cnt']) ){
			return $result->row['cnt'];
		}
		return 0;
	}

	/*
	* Выбор постов
	*/
	public function getPosts($args = array())
	{
		$query = "SELECT * FROM `".DB_PREFIX."blog_posts` ";
		
		$this->setLimit($query,$args);

		$result = $this->db->query($query);

		foreach($result->rows as &$row){
			$row['post_cats'] = array();
			$result_cats = $this->db->query("SELECT * FROM `".DB_PREFIX."blog_posts_cats` WHERE postId='".(int)$row['id']."'");
			foreach($result_cats->rows as $cat_row){
				$result_cat = $this->getCategory($cat_row['catId']);
				$row['post_cats'][] = $result_cat;
			}
		}
		return $result->rows;
	}

	/*
	* Удаление записи блога
	*/
	public function delPost($id)
	{
		$this->db->query("DELETE FROM `".DB_PREFIX."blog_posts` WHERE id = '".(int)$id."'");
		$this->db->query("DELETE FROM `".DB_PREFIX."blog_posts_cats` WHERE postId = '".(int)$id."'");
	}

	/*
	* Редактирование записи блога
	*/
	public function editPost($id,$data)
	{
		$this->db->query("UPDATE `".DB_PREFIX."blog_posts` SET title='".$this->db->escape($data['title'])."',descr='".$this->db->escape($data['descr'])."',anons='".$this->db->escape($data['anons'])."',meta_descr='".$this->db->escape($data['meta_descr'])."',img='".$this->db->escape($data['img'])."',status='".$data['status']."' WHERE id='".(int)$id."'");
		$this->db->query("DELETE FROM `".DB_PREFIX."blog_posts_cats` WHERE postId='".(int)$id."'");
		if(!empty($data['post_cats'])){
			foreach($data['post_cats'] as $catId){
				$this->db->query("INSERT INTO `".DB_PREFIX."blog_posts_cats`(postId,catId)VALUES('".(int)$id."','".(int)$catId."')");
			}
		}	
	}

	private function setLimit(& $query,$args)
	{	
		if( ! empty($args['limit']) ){
			$query .= "LIMIT " . $args['limit'];

			if( ! empty($args['offset']) ){
				$query .= " OFFSET " . ($args['limit'] * ($args['offset']-1)); 
			}
		}
	}

	/*
	* Установка модуля
	*/
	public function install()
	{
		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."blog_categories` (
  		`id` int(11) NOT NULL AUTO_INCREMENT,
  		`title` varchar(512) NOT NULL,
  		`descr` text NOT NULL,
  		`meta_descr` text NOT NULL,
  		`img` varchar(512) NOT NULL,
  		`status` tinyint(4) NOT NULL,
  		`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  		PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."blog_posts` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`title` varchar(512) NOT NULL,
  		`descr` text NOT NULL,
  		`anons` mediumtext NOT NULL,
  		`meta_descr` text NOT NULL,
  		`img` varchar(512) NOT NULL,
  		`status` tinyint(4) NOT NULL,
  		`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
		$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."blog_posts_cats` (
		  `postId` int(11) NOT NULL,
		  `catId` int(11) NOT NULL,
		  KEY `postId` (`postId`,`catId`),
		  KEY `catId` (`catId`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
	}

	/*
	* Удаление модуля
	*/
	public function uninstall()
	{
		$this->db->query("DROP TABLE `".DB_PREFIX."blog_categories`");
		$this->db->query("DROP TABLE `".DB_PREFIX."blog_posts`");
		$this->db->query("DROP TABLE `".DB_PREFIX."blog_posts_cats`");
	}

}