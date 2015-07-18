<?php

// include all need file

include 'mvc/controller/controller.php';
include 'mvc/controller/blog.php';
include 'mvc/controller/user.php';
include 'mvc/controller/comment.php';
include 'mvc/controller/database.php';
include 'mvc/view/view.php';
include 'mvc/model/model.php';

class routing extends controller {

    public function main(){              

        if (isset($_GET['type']) && $_GET['type'] == 'ajax'):
          //Call via ajax methods. Return only data without HTML
        else: 
          view::page();
        endif; 

    }

    public function rout(){

      if (isset($_GET['page'])):

        $page = $_GET['page'];
                  
        switch ($page) {

          case 'user':
                user::Auth(); break;

          case 'registration':
                user::registrationUser(); break;  

          case 'contact-action':
                controller::addFeddback(); break;

          case 'reg-form-action':
                user::RegFormAction(); break;

          case 'logout':
                user::logout(); break;

          case 'auth-form-action':
                user::checkUser(); break;

          case 'admin-panel':
                $this->adminPanelcontroll(); break;

          case 'feedbackitem':
                $this->feedbackItem(); break;

          case 'download': 
                $this->downloadFile(); break;

          case 'comment-action': 
                comment::commentAction(); break;

          case 'blog': 
                blog::pageBlog(); break;

          case 'admin-add-blog': 
                blog::pageAddBlogItem(); break;

          case 'action-blog-post': 
                blog::actionBlogItem(); break;

          case 'single-post': 
                blog::singleBlogItem(); break;

          case 'post-category': 
                blog::postCategory(); break;

          case 'edit-post': 
                blog::editPost(); break;

          case 'action-blog-post-edit': 
                blog::actionPostUpdate(); break;

          case 'add-file-post': 
                blog::actionAddPostFiles(); break;

          case 'action-delete-file-from-post': 
                blog::deleteFilePost(); break;

        }

      else:
        $this->home();
      endif;    

    }  
}