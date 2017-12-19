<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
$stmt = $db->prepare('INSERT INTO friends values(:user1,:user2)') ;
$stmt->execute(array(':user1' => $_GET['id'],':user2'=>$user->getuserid($user->currentuser())));
header('Location: ../index.php');
?>