<?php

class controller extends blog{    
  
    public $site_name = 'My Site Name';
    
    
    public function insertToHistory(){
      $link = $this->connect_mysql();
      $ip = $_SERVER['REMOTE_ADDR'];
      if (isset($_GET['page'])) { $page = $_GET['page']; } else { $page = 'home';};
      $grant = $this->admin();
      $country = $this->ip_info($ip, "country");
      $city = $this->ip_info($ip, "sity");
      if (!$grant){
        model::addHistorySite($link, $ip, $page, $country, $city);
      }
    } 

    public function sendMail(){
      //mail('tarasdj@rambler.ru', 'Subject', 'Test');
      //view::successMessage('Email sent');
      $this->smtpmail('tarasj@hotmail.com', 'test subject', 'Test content');
      view::successMessage('Email sent smtpmail test');
    }

    public function smtpmail($to, $subject, $content, $attach=false)
    {
      require_once('/files/phpmailer/config.php'); 
      require_once('/files/phpmailer/class.phpmailer.php');
      $mail = new PHPMailer(true);
       
      $mail->IsSMTP();
      try {
        $mail->Host       = $__smtp['host'];
        $mail->SMTPDebug  = $__smtp['debug'];
        $mail->SMTPAuth   = $__smtp['auth'];
        $mail->Port       = $__smtp['port'];
        $mail->Username   = $__smtp['username'];
        $mail->Password   = $__smtp['password'];
        $mail->AddReplyTo($__smtp['addreply'], $__smtp['username']);
        $mail->AddAddress($to);                
        $mail->SetFrom($__smtp['addreply'], $__smtp['username']); 
        $mail->AddReplyTo($__smtp['addreply'], $__smtp['username']);
        $mail->Subject = htmlspecialchars($subject);
        $mail->MsgHTML($content);
        if($attach)  $mail->AddAttachment($attach);
        $mail->Send();
        echo "Message sent Ok!</p>\n";
      } catch (phpmailerException $e) {
        echo $e->errorMessage();
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    }

    public function Contact__(){
      view::contact($this->public_key);
    }

    public function header(){
      if (isset($_GET['page'])) { 
        $page = $_GET['page'];
        $link = $this->connect_mysql();
        if ($page == 'single-post'){$page = $_GET['post'];} 
        $result = model::getPage($link, $page);
        while($row = mysql_fetch_assoc($result)){
          $description = $row['page_description'];
          $title = $row['page_title'];
        }
        $page = $_GET['page'];        
        view::header_view($title, $description);
      } else {
        $description = 'official site Automatic OPC HDA Client';
        view::header_view('Home'.' - '.$this->site_name, $description);
      } 
    }

    public function getAdminPanel(){
      $this->verifyUser();
      $grant = $this->admin();      
      if ($grant): view::adminPanel(); endif;
    }

    public function adminPanelcontroll(){
      $link = $this->connect_mysql();
      $grant = $this->admin();      
      if ($grant): 
        $result = model::getHistory($link);
        view::view_history($result);
        $result = model::getUsers($link);
        view::view_users($result);
        $result = model::getFeedbackList($link);
        view::view_feedbackList($result);
        $result = model::getBugList($link);
        view::view_bugList($result); 
      else:
        view::errorMessage('You not have permission for access this page!');
      endif;  
    }



    public function addFeddback() {
      if (isset($_POST['name']) && isset($_POST['subject']) && isset($_POST['email']) && isset($_POST['message'])):
        $name = urlencode($_POST['name']);
        $subject = urlencode($_POST['subject']);
        $email = urlencode($_POST['email']);
        $message = urlencode($_POST['message']);
        $link = $this->connect_mysql();

        //Check Captcha**************
        $recaptcha = $_POST['g-recaptcha-response'];
        if (!empty($recaptcha)):

          include("files/getCurlData.php");
          $google_url = "https://www.google.com/recaptcha/api/siteverify";
          $ip  = $_SERVER['REMOTE_ADDR'];
          $url = $google_url."?secret=".$this->privat_key."&response=".$recaptcha."&remoteip=".$ip;
          $res = getCurlData($url);
          $res = json_decode($res, true);

            if ($_COOKIE["uid"]): $uid = $_COOKIE["uid"]; else: $uid = 0;  endif; 
            $this->verifyUser();       
            model::AddContactItem($subject, $email, $name, $message, $link, $uid, $ip);
            view::successMessage('Your message is successfully send to admnistrator! We contact you about 1 day!');
            $this->Contact__();

        else:
          view::errorMessage("You are robot! Please entered captcha!");    
          $this->Contact__(); 
        endif;  
        //******* End check capcha ********************

      else:
        view::errorMessage('Data is not send. Sorry, for error! Please try again!');
        $this->Contact__();
      endif;  
    }



    public function feedbackItem(){      
      if(isset($_GET['item'])):
        $cid = $_GET['item'];
        $link = $this->connect_mysql();
        $result = model::getFeedbackItem($link, $cid);
        view::viewFeedbackItem($result);
      else:
        view::errorMessage('Data Error!');
      endif; 
    }

    public function maintenance($st_m){
      if ($st_m){view::viewMaintenance();}      
    }

    public function downloadFile(){
        $link = $this->connect_mysql();
        $file = $_GET['fname'];
        $result = model::getFileIdFromFilename($link, $file);
        $row = mysql_fetch_assoc($result);
        $file_id = $row['id'];
        if (file_exists('files/download/'.$file)) {
            if (ob_get_level()) {
              ob_end_clean();
            }
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename = ' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize('files/download/'.$file));
            readfile('files/download/'.$file);                   
            $result = model::getDownloadIPfromFile($link, $file_id);
            $count_ip = mysql_num_rows($result);
            $ip = $_SERVER['REMOTE_ADDR'];
            if ($count_ip == 0):               
              $result = model::getCountDownloads($link, $file_id);
              while($row = mysql_fetch_assoc($result)){
                $cd = $row['cd'];
              }
              $cd = $cd + 1;
              model::updateDownload($link, $cd, $file_id);             
            endif;
            model::insertDownloadIP($link, $ip, $file_id);
        }
}



    public function systems(){
      view::content_view('systems');
      $this->comment(3);
    }

  //***********************BLOG******************
  
  //********************************************

  function uploadFile($folder)
  {
    //*****************Загрузка файла***********************
    $uploaddir = 'files/'.$folder.'/';
    $file = $uploaddir . basename($this->file);
    move_uploaded_file($this->tmp_file, $file);
    //******************************************************  
  }

  function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
      $output = NULL;
      if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
          $ip = $_SERVER["REMOTE_ADDR"];
          if ($deep_detect) {
              if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
              if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                  $ip = $_SERVER['HTTP_CLIENT_IP'];
          }
      }
      $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
      $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
      $continents = array(
          "AF" => "Africa",
          "AN" => "Antarctica",
          "AS" => "Asia",
          "EU" => "Europe",
          "OC" => "Australia (Oceania)",
          "NA" => "North America",
          "SA" => "South America"
      );
      if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
          $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
          if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
              switch ($purpose) {
                  case "location":
                      $output = array(
                          "city"           => @$ipdat->geoplugin_city,
                          "state"          => @$ipdat->geoplugin_regionName,
                          "country"        => @$ipdat->geoplugin_countryName,
                          "country_code"   => @$ipdat->geoplugin_countryCode,
                          "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                          "continent_code" => @$ipdat->geoplugin_continentCode
                      );
                      break;
                  case "address":
                      $address = array($ipdat->geoplugin_countryName);
                      if (@strlen($ipdat->geoplugin_regionName) >= 1)
                          $address[] = $ipdat->geoplugin_regionName;
                      if (@strlen($ipdat->geoplugin_city) >= 1)
                          $address[] = $ipdat->geoplugin_city;
                      $output = implode(", ", array_reverse($address));
                      break;
                  case "city":
                      $output = @$ipdat->geoplugin_city;
                      break;
                  case "state":
                      $output = @$ipdat->geoplugin_regionName;
                      break;
                  case "region":
                      $output = @$ipdat->geoplugin_regionName;
                      break;
                  case "country":
                      $output = @$ipdat->geoplugin_countryName;
                      break;
                  case "countrycode":
                      $output = @$ipdat->geoplugin_countryCode;
                      break;
              }
          }
      }
      return $output;
  }

}
?>