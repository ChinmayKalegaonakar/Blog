<?php require('includes/config.php'); 

$stmt = $db->prepare('SELECT member_ID, member_name,member_email FROM members WHERE member_ID = :post_ID');
$stmt->execute(array(':post_ID' => $_GET['id']));
$row = $stmt->fetch();

$stmt2 = $db->prepare('SELECT member_ID, address,phone,bio FROM details WHERE member_ID = :post_ID');
$stmt2->execute(array(':post_ID' => $_GET['id']));
$row2 = $stmt2->fetch();

$stmt3 = $db->prepare('SELECT member_name FROM members WHERE member_ID IN(SELECT friend_1 from friends WHERE friend_2= :post_ID)');
$stmt3->execute(array(':post_ID' => $_GET['id']));

$frcheck=$db->query('SELECT friend_2 FROM friends  where friend_1='.$_GET['id'].'');
$id=$user->getuserid($user->currentuser());

//if post_ does not exists redirect user.
if($row['member_ID'] == ''){
	header('Location: ./');
	exit;
}

function addfriend($user2){
    $stmt = $db->prepare('INSERT INTO members values(:user1,:user2)') ;
    $stmt->execute(array(':user1' => $_GET['id'],':user2'=>$user2->getuserid($user->currentuser())));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>User - <?php echo $user->currentuser();?></title>
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/main.css">
    <script language="JavaScript" type="text/javascript">

  </script>
</head>
<body>

	<div id="wrapper">

		<h1>Blog</h1>
		<hr />
		<p><a href="./">Back</a></p>
		<?php
		if($user->getuserid($user->currentuser())!==$row['member_ID']){
		echo '<a href="admin/add-friend.php?id='.$row['member_ID'].'"> add friend</a>';
		}
		?>
		<?php	
            
			echo '<div id="post_body">';
                echo '<h1>'.$row['member_name'].'</h1>';
                //echo '<a href=""> add friend' '</a>'   
                echo '<p>'.$row['member_email'].'</p>';	
				echo '<div id=details><p>Address<br>'.$row2['address'].'</p>';
				echo '<p><b>Phone:-'.$row2['phone'].'</b></p>';
				echo '<p><em>bio <br>'.$row2['bio'].'</em></p>';
				echo '</div>';		;	
			echo '</div>';
			echo '<div id=likes>';
			echo 'Follows';
			while($row3 = $stmt3->fetch()){
				echo '<em>  '.$row3['member_name'].'  ,</em>';
			}
			echo '</div>';
	
		?>
		<a href="admin/edit-user.php?id=<?php echo $id?>">EDIT</a>
	</div>
	
</body>
</html>