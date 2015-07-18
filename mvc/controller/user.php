<?php

class user extends comment{

	public function Auth(){
      if (!$_COOKIE["hash"]){
        view::authform();
      } else {
        $this->verifyUser();
        $this->dashboard();
      }      
    }

    public function dashboard(){
        $hash = $_COOKIE["hash"];
        $uid = $_COOKIE["uid"];
        $this->verifyUser();
        $link = database::connect_mysql();
        $result = model::getUser($link, $hash);
        view::userInfoLeft($result); 
        $result = model::getLicensed($link, $uid);
        $lcount = mysql_num_rows($result);        
        view::titleYourLicense();
        view::rightSideOpen();
        if ($lcount == 0){
          view::emptyLicense();
          view::addLicenseButton();
        } else{
          while($row = mysql_fetch_assoc($result)){
            $lid = $row['id'];
            $authcode = $row['authcode'];
            $regcode = $row['registercode'];
            $pay_class = $row['payment'];
            $reg = $row['reg'];           
            if ($pay_class == 0){
              $pay_class = 'demo';
              view::demo_license($lid, $authcode, $regcode, $pay_class, array('Buy License', 'buy-license'));
            } else {
              if ($reg == 0) { 
                $pay_class = 'demo';    
                view::demo_license($lid, $authcode, $regcode, $pay_class, array('Enter my authcode (paid)', 'enter-authcode&lid='.$lid)); 
              } else {
                $pay_class = 'buy';
                view::paid_up_license($lid, $authcode, $regcode, $pay_class);
              } 
            }
        } 
        view::addLicenseButton(); 
        view::mainWrapperClose();  
      }
    }

    public function registrationUser(){
      if ($_COOKIE["hash"]) {
          $this->verifyUser();
          $this->dashboard();
      } else {
          view::regForm($this->public_key);
      }
    }

    public function logout(){
      setcookie("hash", "", time() - 3600*24*30*12, "/"); 
      setcookie("uid", "", time() - 3600*24*30*12, "/"); 
      view::successMessage('You are logout!');
      header("Location: index.php");   
    }

    public function checkUser(){
      if (isset($_POST['login']) && isset($_POST['password'])):
        $login = $_POST['login'];
        $pass = $_POST['password'];
        $link = $this->connect_mysql();
        $result = model::getUserLogin($link, $login);
          while($row = mysql_fetch_assoc($result)){
            $data_login = $row['login'];
            $data_password = $row['password'];
            $data_id = $row['id'];
          }
          if (md5($pass) ==  $data_password):   //User is in database
            $remember_token = md5($this->generateCode(10)); 
            setcookie("hash", $remember_token, time()+60*60*24*30);  
            setcookie("uid", $data_id, time()+60*60*24*30);
            $ip = $_SERVER['REMOTE_ADDR']; 
            model::AddUserHash($remember_token, $ip, $data_id, $link);
            model::updateUserHash($link, $remember_token, $data_id);
            $result = model::getUser($link, $remember_token);
            header("Location: index.php");            
          else:
            view::errorMessage('Password or login is incorrect!');
            $this->Auth();
          endif;  
      else:
        view::errorMessage('Authentication data is incorrect!');
      endif; 
    }

    public function User(){
      if (!$_COOKIE["hash"]){
        view::userName('Login');
      } else {
        $hash = $_COOKIE["hash"];
        $link = database::connect_mysql();
        $result = model::getUser($link, $hash);
        while($row = mysql_fetch_assoc($result)){
          $data_login = $row['login'];
        }
        $uid = $_COOKIE['uid'];
        $this->verifyUser();
        $result = model::getLicensed($link, $uid);  
        while($row = mysql_fetch_assoc($result)){
          $data_payment = $row['payment'];
        }
        if ($data_payment == '0'){$license = 'demo license';} else {$license = '';}        
        view::userName($data_login, $license);
      }            
    }

    public function RegFormAction(){
      if (isset($_POST['login']) && isset($_POST['mail']) && isset($_POST['password']) && isset($_POST['password_confirm'])):
        //Check Captcha**************
        $recaptcha = $_POST['g-recaptcha-response'];
        if (!empty($recaptcha)):

          include("files/getCurlData.php");
          $google_url="https://www.google.com/recaptcha/api/siteverify";
          $ip=$_SERVER['REMOTE_ADDR'];
          $url=$google_url."?secret=".$this->privat_key."&response=".$recaptcha."&remoteip=".$ip;
          $res=getCurlData($url);
          $res= json_decode($res, true);

            $remember_token = md5($this->generateCode(10));
            $login = $_POST['login'];  
            $email = $_POST['mail'];
            $password = $_POST['password'];  
            $link = $this->connect_mysql();
            //********* is user existing ************************
            $result = model::getUserLogin($link, $login);
            $count = mysql_num_rows($result);
            if ($count > 0): 
              view::errorMessage("User " . $login . " already exist!"); 
              view::regForm($this->public_key);
            else:
            //***************************************************
              if (strlen($password) < 8): 
                view::errorMessage('Password must be more 8 symbols! Please try again!');
                view::regForm($this->public_key); else:
                if ($password == $_POST['password_confirm']):                 
                  model::CreateUser($login, $email, md5($password), $remember_token, $link);
                  view::successMessage('User successfully created! Hash=' . $remember_token);
                  view::regForm($this->public_key);
                else:
                  view::errorMessage("Password incorrect!"); 
                  view::regForm($this->public_key);
                endif;
              endif;   
            endif; 
          //ss}
        else:
          view::errorMessage("You are robot! Please entered captcha!");    
          view::regForm($this->public_key); 
        endif;  
        //******* End check capcha ********************
      else:
        view::errorMessage('Data is not send. Sorry, for error! Please try again!');
        view::regForm($this->public_key);     
      endif;  
    }

    public function verifyUser(){
      $link = $this->connect_mysql();
      $uid = $_COOKIE['uid'];
      $hash = $_COOKIE['hash'];
      $result = model::getUser($link, $hash);
      while($row = mysql_fetch_assoc($result)){
        $data_uid = $row['id'];
      }
      if ($data_uid == $uid){return TRUE;} else{
          view::errorMessage('Something wrong! Data bad! Sory!');
          die();
      }
    }

    public function isUserLogined(){
      if($_COOKIE['hash'] && $_COOKIE['uid']){
        $this->verifyUser();
        return TRUE;
      } else {return FALSE;}
    }

 	public function admin(){
      $uid = $_COOKIE["uid"];
      $link = $this->connect_mysql();
      $g = model::isAdmin($link, $uid);      
      if ($g): 
        return TRUE;
      else: FALSE; endif;
    }

    // function for generated hash code for anonymus user 
    public function generateCode($length=6) 
    {
      $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
      $code = "";
      $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
          $code .= $chars[mt_rand(0,$clen)];
        }
    return $code;
    }

} 