<?php
/**
 * Created by PhpStorm.
 * User: GermaniukT
 * Date: 01.12.14
 * Time: 13:57
 */
//*****************Загрузка файла***********************
$uploaddir = './img/img_topic/';
$file = $uploaddir . basename($_FILES['uploadfile']['name']);
move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file);
header('Location: http://test_employe.art/topic.php');
//******************************************************
?>