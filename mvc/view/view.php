<?php

class view extends model {

    public function header_view($title, $description){
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/header.phtml');
    }

    public function content_view($page) {
      if (file_exists($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/' . $page . '.phtml')):
        return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/' . $page . '.phtml');
      else:
        return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/page-not-found.phtml');
      endif;  
    }

    public function main_menu() {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/main-menu.phtml');
    }

    public function page_not_found() {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/page-not-found.phtml');
    }

    public function regForm($public_key) {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/reg-form.phtml');
    }

    public function authForm() {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/auth-form.phtml');
    }

    public function userInfoLeft($result) {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/user-information-left.phtml');
    }

    public function footer() {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/footer__ter.phtml');
    }

    public function successMessage($message) {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/success_message.phtml');
    }

    public function errorMessage($message) {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/error_message.phtml');
    }

    public function Contact($public_key) {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/contact.phtml');
    }

    public function userName($username, $license){
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/view-username.phtml');
    }

    public function adminPanel() {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/admin-panel.phtml');
    }

    public function view_contact_us($result) {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/view_contact_us.phtml');
    }

    public function view_users($result) {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/view_users.phtml');
    }

    public function view_feedbackList($result) {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/view_feedbacklist.phtml');
    }

    public function viewFeedbackItem($result){
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/view_feedback_item.phtml');
    }

    public function viewMaintenance(){
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/view_maintenance.phtml');
    }

    public function commentList($result){
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/view_comment_list.phtml');
    }

    public function commentForm($comment_group){
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/form_comment.phtml');
    }

    public function warningMessage($message){
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/warning_message.phtml');
    }

    public function view_history($result){
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/view_history.phtml');
    }

    public function formAddBlog($dataset, $pid){
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/form_blog_add_page.phtml');
    }

    public function redirect($page) {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/redirect.phtml');
    }

    public function ajaxImgGif() {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/ajax-img-loader.phtml');
    }

    public function view_blog_item($body, $categories) {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/view_blog_item.phtml');
    }

    public function mainWrapperOpen() {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/main_wrapper_open.phtml');
    }

    public function mainWrapperClose() {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/main_wrapper_close.phtml');
    }

    public function blogHeader() {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/blog_top_header.phtml');
    }

    public function singlePostItem($result, $categories, $download) {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/view_blog_single_item.phtml');
    }

    public function link($link, $link_title) {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/link.phtml');
    }

    public function formUpdatePost($title, $teaser, $body, $post_id) {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/form_update_post.phtml');
    }

    public function formAddFilesToPost($post_id) {
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/form_add_file_post.phtml');
    }

    public function downloadFilesInPost($download){
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/download_file_in_post.phtml');
    }

    public function rightSideOpen(){
      return require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/right_side_open.phtml');
    }

  }
?>