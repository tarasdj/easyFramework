<?php

class database extends view{

	public $host = 'localhost';
	public $database = 'h76_globalmusicmix';
	public $user = 'h76_admin';
	public $password = 'aXZm1mRP'; 

	public function connect_mysql(){
      $link = mysql_connect($this->host, $this->user, $this->password);
      mysql_select_db($this->database, $link);
      if (!$link) {
        view::errorMessage('Connection Error: ' . mysql_error());
      }
        return $link;
    } 

}