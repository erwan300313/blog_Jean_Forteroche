<?php
require_once('controller.php');

Class CommentController{

    private $postManager;
    private $commentManager;

    public function __construct(){
        $this->postManager = new PostManager();
        $this->commentManager = new CommentManager();
    }

    public function getComments($id){
        $getExtractPost = $this->postManager->getExtractpost($id);
        $getComments = $this->commentManager->getComments($id);
        $view = new ViewManager('comments');
        $view->generate(array('getExtractPost' => $getExtractPost, 'getComments' => $getComments));
    }

    public function getEditComment($comment_id){
        if($_SESSION['pseudo'] == $_GET['author'] OR $_SESSION['id_status'] == 1){
            $getComment = $this->commentManager->getComment($comment_id);
            $view = new ViewManager('editComment');
            $view->generate(array('getComment' => $getComment));
        }else{
            throw new Exception('Vous devez être l\'auteur du commentaire pour le modifier.');
        }  
    }

    public function editComment($comment_id, $editComment){
        $this->commentManager->editComment($comment_id, $editComment);
        header('Location: index.php?action=comments&id=' . $_GET['post_id']);
    }

    public function addCommentView($post_id){
        if(isset($_SESSION['pseudo'])){
            $getExtractPost = $this->postManager->getExtractpost($post_id);
            $view = new ViewManager('addComment');
            $view->generate(array('getExtractPost' => $getExtractPost));
        }else{
            throw new Exception('Vous devez être enregistret et connecter pour ajouter un commentaire.');
        }  
    }

    public function addComment($post_id, $newComment, $author){
        $this->commentManager->addComment($post_id, $newComment, $author);
        header('Location: index.php?action=comments&id=' . $_GET['post_id']);
    }

    public function deleteCommentView($comment_id){
        if($_SESSION['pseudo'] == $_GET['author'] OR $_SESSION['id_status'] == 1){
            $getComment = $this->commentManager->getComment($comment_id);
            $view = new ViewManager('deleteComment');
            $view->generate(array('getComment' => $getComment));
        }else{
            throw new Exception('Vous devez être l\'auteur du commentaire pour le supprimer.');
        }  
    }

    public function deleteComment($comment_id){
        $this->commentManager->deleteComment($comment_id);
        header('Location: index.php?action=comments&id=' . $_GET['post_id']);
    }

    public function reportComment($comment_id, $report){
        $report = $report + 1;
        $this->commentManager->reportComment($comment_id, $report);
        header('Location: index.php?action=comments&id=' . $_GET['post_id']);
    }


}