<?php //include config
require_once('../includes/config.php');
//show message from add / edit page
if(isset($_GET['delpost'])){ 
    
        $stmt = $db->prepare('DELETE FROM posts WHERE post_ID = :postID') ;
        $stmt->execute(array(':postID' => $_GET['delpost']));
    
        header('Location: index.php?id='.$_GET['delpost'].'&action=deleted');
        exit;
    } 
?>