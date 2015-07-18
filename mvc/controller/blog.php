<?php

class blog extends user{
	public function pageBlog(){
    $link = $this->connect_mysql();
    $result = model::getBlogList($link);
    view::mainWrapperOpen();
    view::blogHeader();
    while ($row = mysql_fetch_assoc($result)) {
      $bid = $row['id'];
      $body = model::getBlogItem($link, $bid);
      $categories = model::getCategoryBlogItem($link, $bid);
      view::view_blog_item($body, $categories);      
    }
    view::mainWrapperClose();
  }

  public function pageAddBlogItem(){
    $link = $this->connect_mysql();
    $category_dataset = model::getBlogCategories($link);
    view::formAddBlog($category_dataset, $pid);
  }

  public function actionBlogItem() {
    if (isset($_POST['title']) && isset($_POST['teaser']) && isset($_POST['blogcontent']) && isset($_POST['option'])):
      if ($this->admin()):
        $this->verifyUser();
        $title = urlencode($_POST['title']);
        $teaser = urlencode($_POST['teaser']);
        $text = urlencode($_POST['blogcontent']);
        $view_count = 0;
        $link = $this->connect_mysql();
        $bid = model::insertBlogItem($link, $title, $teaser, $text, $view_count); 
        model::insertPages($link, $_POST['title'], $_POST['teaser'], $bid);       
        foreach($_POST['option'] as $check) {
          model::insertBlogCategoryStructure($link, $bid, $check); 
        }
        view::successMessage('Blog item successfully added!');
      else:
        view::errorMessage('Validation error!');
      endif;  
    else:
      view::errorMessage('Data error. Somthing wrong!');
    endif;
    view::redirect('admin-add-blog'); 
  }

  public function singleBlogItem(){
    if (isset($_GET['post'])):
      $bid = $_GET['post'];
      $this->incCountView($bid);
      $link = $this->connect_mysql();
      $result = model::getBlogItem($link, $bid);
      $categories = model::getCategoryBlogItem($link, $bid);
      $download = model::getDownloadList($link, $bid);
      view::singlePostItem($result, $categories, $download);
      $grant = $this->admin();
      $this->verifyUser();
      if ($grant):
        view::link('?page=edit-post&post='.$bid, 'Edit Post');
      endif;
      $this->comment($bid);
    else:
      view::errorMessage('Data error. Somthing wrong!');
      view::redirect('blog');
    endif;  
  }

  public function postCategory() {
    if (isset($_GET['category'])):
      $category = $_GET['category'];
      $link = $this->connect_mysql();
      $result = model::getPostCategory($link, $category);
      view::mainWrapperOpen();
      view::blogHeader();
      while ($row = mysql_fetch_assoc($result)) {
        $bid = $row['id'];
        $body = model::getBlogItem($link, $bid);
        $categories = model::getCategoryBlogItem($link, $bid);
        view::view_blog_item($body, $categories);      
      }
      view::mainWrapperClose();
    else:
      view::errorMessage('Data error. Somthing wrong!');
      view::redirect('blog');
    endif;   
  }

  public function incCountView($post_id){
    $link = $this->connect_mysql();
    $result = model::getBlogItem($link, $post_id);
    while ($row = mysql_fetch_assoc($result)) {
      $count = $row['view_count'];      
    }
    $count = $count + 1;
    model::updateCountView($link, $post_id, $count);  
  }

  public function editPost(){
    if (isset($_GET['post'])):
      $link = $this->connect_mysql();
      $post_id = $_GET['post'];
      $result = model::getBlogItem($link, $post_id); 
      while ($row = mysql_fetch_assoc($result)) {
        $title = urldecode($row['title']);
        $teaser = urldecode($row['teaser']); 
        $blog_text = urldecode($row['blog_text']);  
      }  
      view::formUpdatePost($title, $teaser, $blog_text, $post_id);
      view::formAddFilesToPost($post_id); 
      $download = model::getDownloadList($link, $post_id);   
      view::downloadFilesInPost($download);
      view::ajaxImgGif();    
    endif;
  }

  public function actionPostUpdate() {
    if (isset($_GET['post'])):
      $post_id = $_GET['post'];
      if (isset($_POST['title']) && isset($_POST['teaser']) && isset($_POST['body'])):
        $title = urlencode($_POST['title']);
        $teaser = urlencode($_POST['teaser']);
        $body = urlencode($_POST['body']);
        $link = $this->connect_mysql();
        model::UpdatePost($link, $title, $teaser, $body, $post_id);
        view::successMessage('Post successfully updated!');
        view::redirect('single-post&post='.$post_id);
      else: 
        view::errorMessage('Data update error! Something wrong!');
        view::redirect('blog');
      endif; 
    else:
        view::errorMessage('Item data error! Something wrong!');
        view::redirect('blog');
    endif; 
  }

  public function actionAddPostFiles() {
    if (isset($_POST['filetitle']) && isset($_FILES['uploadfile'])):
      $post_id = $_GET['post'];
      $file_title = $_POST['filetitle'];
      $this->file = $_FILES['uploadfile']['name'];
      $this->tmp_file = $_FILES['uploadfile']['tmp_name'];
      $this->uploadFile('download');
      $link = $this->connect_mysql();    
      model::insertDownload($link, $this->file, $file_title, $post_id);
      header("Location: /?page=edit-post&post=".$post_id);
    endif;  
  }

  public function deleteFilePost(){
    $file_id = $_GET['file'];
    $post_id = $_GET['post'];
    $link = $this->connect_mysql();
    model::deletePostFile($link, $file_id); 
    header("Location: /?page=edit-post&post=".$post_id);
  }

}