<?php

class comment extends database{

	public function comment($comment_group){
      $link = $this->connect_mysql();
      $result = model::getComment($link, $comment_group);
      view::commentList($result);
      if (isset($_COOKIE['uid']) && isset($_COOKIE['hash'])):
        view::commentForm($comment_group);
      else:
        view::warningMessage('You must be registered and logined for add comments!');
        $this->auth();
      endif;  
    }

    public function commentAction(){      
      $link = $this->connect_mysql();
      if (isset($_GET['gid'])):
        $group_id = $_GET['gid'];
        if (isset($_POST['subject']) && isset($_POST['comment'])):
          $comment = urlencode($_POST['comment']);
          $subject = urlencode($_POST['subject']);
          $this->verifyUser();
          $uid = $_COOKIE['uid'];
          model::insertComment($link, $uid, $group_id, $subject, $comment); 
          view::successMessage('Your comment successfully added!');
        else:
          view::errorMessage('Data Error!');
          die();
        endif;  
      endif; 
      view::redirect('home'); 
    }
}