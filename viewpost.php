<?php require('includes/config.php'); 

$stmt = $db->prepare('SELECT post_ID,post_Title,post_Cont,post_Date,post_member_id,post_likes FROM posts WHERE post_ID = :post_ID');
$stmt->execute(array(':post_ID' => $_GET['id']));
$row = $stmt->fetch();


//if post_ does not exists redirect user.
if($row['post_ID'] == ''){
	header('Location: ./');
	exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Blog - <?php echo $row['post_Title'];?></title>
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/main.css">
</head>
<body>

	<div id="wrapper">
	<?php 
	//show message from add / edit page
	if(isset($_GET['action'])){ 
		echo '<h3>post already '.$_GET['action'].'.</h3>'; 
	} 
	?>
		<h1>Blog</h1>
		<hr />
		<p><a href="./">Back</a></p>
		
		<?php	
			$postid=$_GET['id'];
			//$mid=$row['post_member_id'];
		//	$mname=$db->query('SELECT member_name from members where member_id='.$mid.'');
			$stmt2=$db->query('SELECT comment_text,comment_member_id FROM comments Where comment_post_id='.$postid.' ');
			echo '<div id="post_body">';
				echo '<h1>'.$row['post_Title'].'</h1>';
				//echo '<div id=likes>BY  '.$mname['member_name'].' </div>';
				echo '<p>posted on '.date('jS M Y', strtotime($row['post_Date'])).'</p>';
				echo '<p>'.$row['post_Cont'].'</p>';				
			echo '</div>';
			echo '<div id="post_footer">';
			echo '<hr> <h3>Comments </h3> ';
			echo '<div id="likes" ><a href="admin/like-post.php?id='.$row['post_ID'].'&mem='.$user->getuserid($user->currentuser()).'">(Y)</a>  likes '.$row['post_likes'].' </div>';
				//echo'<a href="admin/add-comment.php?id=" '.$row['post_ID'].'>Add comment</a>';
				while($row_comments=$stmt2->fetch()){
					$stmt = $db->prepare('SELECT member_name FROM members WHERE member_ID = :member_ID');
					$stmt->execute(array(':member_ID' => $row_comments['comment_member_id']));
					$row2 = $stmt->fetch();
					echo '<b> '.$row2['member_name'].'</b>--'.$row_comments['comment_text'].'<br><br> ';
				}//.$row_comments['comment_likes'].' ';
			echo '</div>';
		?>
		<a href="admin/add-comment.php?id=<?php echo $row['post_ID'];?>">Add comment</a>
	</div>

</body>
</html>