<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
$stmt = $db->query('SELECT like_id FROM likes WHERE member_ID = '.$_GET['mem'].' and post_ID='.$_GET['id'].'') ;
if($stmt->fetch()==0){
    $stmt = $db->query('INSERT INTO likes(member_ID,post_ID) values('.$_GET['mem'].','.$_GET['id'].')');
    $stmt = $db->query('UPDATE posts set post_likes=post_likes+1 where post_id='.$_GET['id'].'');
    header('location:../viewpost.php?id='.$_GET["id"].'');
}
else{
    //already liked message
    //
    header('location:../viewpost.php?id='.$_GET["id"].'&action=liked');    
}

?>