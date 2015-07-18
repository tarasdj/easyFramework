<?php

class model{
    
    public function CreateUser($login, $email, $password, $remember_token, $link) {
      mysql_set_charset('utf8', $link);
      $sql  = 'insert into users (login, email, password, remember_token, isAdmin, created_at, updated_at) ';
      $sql .='values("'.$login.'","'.$email.'","'.$password.'","'.$remember_token.'","0", NOW(), NOW())';
      $result = mysql_query($sql, $link);
      if (!$result)
      {
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {
      $result = 'ok';
        return $result;
      }
    }

    public function AddContactItem($subject, $email, $name, $message, $link, $uid, $ip) {
      mysql_set_charset('utf8', $link);
      $sql  = 'insert into contact_us (subject, your_name, e_mail, message, uid, date_create, IP) ';
      $sql .='values("'.$subject.'","'.$name.'","'. $email.'","'. $message.'","'.$uid.'", NOW(), "'.$ip.'")';
      $result = mysql_query($sql, $link);
      if (!$result)
      {
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {
      $result = 'ok';
        return $result;
      }
    }

    public function AddUserHash($hash, $ip, $uid, $link) {
      mysql_set_charset('utf8', $link);
      $sql  = 'insert into hashlist (hash, ip, uid, auth) ';
      $sql .='values("'.$hash.'","'.$ip.'","'. $uid.'", NOW())';
      $result = mysql_query($sql, $link);
      if (!$result)
      {
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {
      $result = 'ok';
        return $result;
      }
    }

    public function insertDownloadIP($link, $ip, $file_id){
      mysql_set_charset('utf8', $link);
      $sql  = 'insert into downloadIPs (ip, dday, file_id) ';
      $sql .='values("'.$ip.'", NOW(), "'.$file_id.'")';
      $result = mysql_query($sql, $link);
      if (!$result)
      {
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {
      $result = 'ok';
        return $result;
      } 
    }

    public function insertPages($link, $title, $description, $page){
      mysql_set_charset('utf8', $link);
      $sql  = 'insert into pages (page_name, page_title, page_description) ';
      $sql .='values("'.$page.'", "'.$title.'", "'.$description.'")';
      $result = mysql_query($sql, $link);
      if (!$result)
      {
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {
      $result = 'ok';
        return $result;
      } 
    }

    public function insertComment($link, $uid, $group_id, $subject, $comment){
      mysql_set_charset('utf8', $link);
      $sql  = 'insert into comment_list (comment, comment_day, uid, comment_group_id, subject) ';
      $sql .='values("'.$comment.'", Now(), "'.$uid.'", "'.$group_id.'", "' . $subject . '")';
      $result = mysql_query($sql, $link);
      if (!$result)
      {
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {
      $result = 'ok';
        return $result;
      }       
    }

    public function insertBlogItem($link, $title, $teaser, $text, $view_count) {
      mysql_set_charset('utf8', $link);
      $sql  = 'insert into blog (title, post_date, teaser, blog_text, view_count) ';
      $sql .='values("'.$title.'", Now(), "'.$teaser.'", "'.$text.'", "' . $view_count . '")';
      $result = mysql_query($sql, $link);
      if (!$result)
      {
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {
        $result = mysql_insert_id();
        return $result;
      }        
    }

    public function insertBlogCategoryStructure($link, $bid, $cbid) {
      mysql_set_charset('utf8', $link);
      $sql  = 'insert into blog_structure (category_id, post_id) ';
      $sql .='values("' . $cbid . '", "' . $bid . '")';
      $result = mysql_query($sql, $link);
      if (!$result)
      {
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {
      $result = 'ok';
        return $result;
      }        
    }

    public function insertDownload($link, $file_name, $file_title, $post_id) {
      mysql_set_charset('utf8', $link);
      $sql  = 'insert into download (cd, filename, post_id, file_title) ';
      $sql .='values("0", "' . $file_name . '", "' . $post_id . '", "' . $file_title . '")';
      $result = mysql_query($sql, $link);
      if (!$result)
      {
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {
      $result = 'ok';
        return $result;
      }        
    }

    public function getUser($link, $hash) {
      mysql_set_charset('utf8', $link);
      $sql  = 'select users.login, users.email, users.id, hashlist.auth from hashlist, users where hash = "' . $hash .'" and hashlist.uid = users.id;';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}   
    }

    public function getUserLogin($link, $login) {
      mysql_set_charset('utf8', $link);
      $sql  = 'select * from users where login = "' . $login .'"';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}   
    }

    public function updateUserHash($link, $newHash, $id) {
      mysql_set_charset('utf8', $link);
      $sql  = 'update users set remember_token = "' .  $newHash . '" where id = "' . $id .'"';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}   
    }

    public function UpdateCommentsStatusAllowInBugItem($link, $comment, $allow, $status, $bid){
      mysql_set_charset('utf8', $link);
      if ($status == 1){$sql  = 'update bug_list set comment = "' .  $comment . '", status = "'.$status.'", allowed = "'.$allow.'", date_done_bug = now() where id = "' . $bid .'"';} 
      else{$sql  = 'update bug_list set comment = "' .  $comment . '", status = "'.$status.'", allowed = "'.$allow.'" where id = "' . $bid .'"';}
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}      
    }

    public function UpdatePost($link, $title, $teaser, $blog_text, $post_id){
      mysql_set_charset('utf8', $link);
      $sql  = 'update blog set title = "' .  $title . '", teaser = "'.$teaser.'", blog_text = "'.$blog_text.'" where id = "' . $post_id .'"';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}      
    }

    public function updateDownload($link, $cd, $file_id) {
      mysql_set_charset('utf8', $link);
      $sql  = 'update download set cd = "' .  $cd . '" where id = "' . $file_id . '"';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}   
    }

    public function updateCountView($link, $post_id, $count){
      mysql_set_charset('utf8', $link);
      $sql  = 'update blog set view_count = "' .  $count . '" where id = "' . $post_id . '"';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}       
    }

    public function isAdmin($link, $uid){
      mysql_set_charset('utf8', $link);
      $sql  = 'select isAdmin from users  where id = "'. $uid .'" and isAdmin = "1"';
      $result = mysql_query($sql, $link);
      if (!$result) 
      {
          print 'MySQL Error: ' . mysql_error();
          exit;
      } else
      {
          $count = mysql_num_rows($result);
          if ($count > 0) {return TRUE;} else {return False;}
      }  
    }

    public function getFeedbackList($link) {
      mysql_set_charset('utf8', $link);
      $sql  = 'select * from contact_us order by id desc';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}   
    }

    public function getUsers($link) {
      mysql_set_charset('utf8', $link);
      $sql  = 'select id, login, email, created_at, updated_at from users order by id desc';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}   
    }

    public function getPage($link, $page) {
      mysql_set_charset('utf8', $link);
      $sql  = 'select * from pages where page_name = "'.$page.'"';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}   
    }

    public function getFeedbackItem($link, $cid){
      mysql_set_charset('utf8', $link);
      $sql  = 'select * from contact_us where id = "' .$cid. '"';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}      
    }

    public function getCountDownloads($link, $file_id){
      mysql_set_charset('utf8', $link);
      $sql  = 'select * from download where id = "'.$file_id.'"';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}         
    }

    public function getDownloadIPfromFile($link, $file_id){
      mysql_set_charset('utf8', $link);
      $sql  = 'select * from downloadIPs where file_id = "'.$file_id.'"';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}         
    }

    public function getFileIdFromFilename($link, $file_name){
      mysql_set_charset('utf8', $link);
      $sql  = 'select * from download where filename = "'.$file_name.'"';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}         
    }

    public function getDownloadList($link, $post_id) {
      mysql_set_charset('utf8', $link);
      $sql  = 'select * from download where post_id = "'.$post_id.'" order by id asc';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}       
    }

    public function getHistory($link){
      mysql_set_charset('utf8', $link);
      $sql  = 'select * from history_site order by id desc limit 30';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}         
    } 

    public function getBlogList($link){
      mysql_set_charset('utf8', $link);
      $sql  = 'select * from blog order by id desc';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}         
    }

    public function getBlogCategories($link){
      mysql_set_charset('utf8', $link);
      $sql  = 'select * from blog_categories order by id desc';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}         
    }

    public function getBlogItem($link, $bid){
      mysql_set_charset('utf8', $link);
      $sql  = 'select * from blog where id = "' . $bid . '" order by id desc';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}         
    }

    public function getCategoryBlogItem($link, $bid){
      mysql_set_charset('utf8', $link);
      $sql  = 'select category, blog_categories.id from blog_structure, blog_categories where blog_structure.post_id = "' . $bid . '" and blog_structure.category_id = blog_categories.id';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}         
    }   

    public function getComment($link, $comment_group){
      mysql_set_charset('utf8', $link);
      $sql  = 'select subject, comment, comment_day, login from comment_list, users where comment_group_id = "'.$comment_group.'" and comment_list.uid = users.id order by comment_list.id desc';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}        
    }   

    public function addHistorySite($link, $ip, $page, $country, $city){
      mysql_set_charset('utf8', $link);
      $sql  = 'insert into history_site (hdate, ip, page, country, city) ';
      $sql .='values(Now(),"'.$ip.'", "'.$page.'", "' .$country. '", "'.$city.'")';
      $result = mysql_query($sql, $link);
      if (!$result)
      {
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {
      $result = 'ok';
        return $result;
      } 
    }

    public function getPostCategory($link, $category){
      mysql_set_charset('utf8', $link);
      $sql  = 'select title, teaser, post_date, view_count, blog.id from blog, blog_structure where blog_structure.category_id = "' . $category . '" and blog_structure.post_id = blog.id';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}     
    }


    public function deletePostFile($link, $file_id) {
      mysql_set_charset('utf8', $link);
      $sql  = 'delete from download where id = "' . $file_id . '"';
      $result = mysql_query($sql, $link);
      if (!$result){
        print 'MySQL Error: ' . mysql_error();
        exit;
      } else {return $result;}     
    }


  }
?>