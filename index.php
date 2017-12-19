<?php require('includes/config.php'); 
$id=$user->getuserid($user->currentuser());

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Blog</title>
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/main.css">
</head>
<body>

	<div id="wrapper">
	<ul id='adminmenu'>
	<li><a href="admin/index.php?id=<?php echo $id ?>">Archive</a></li>
	<li><a href='admin/add-post.php'>Add Post</a></li>
	<li><a href="admin/users.php?id=<?php echo $id ?>">Users</a></li>
	<li><a href="viewuser.php?id=<?php echo $id ?>"> My Profile</a></li>
	<li><a href='admin/logout.php'>Logout</a></li>
    </ul>
	<div class='clear'></div>
		<h1>Blog</h1>
		<hr />

		<?php
			echo '<div id=likes> logged in as '.$user->currentuser().'</div>';	
			try {
				$stmt = $db->query('SELECT post_ID, post_Title, post_Desc,post_member_id, post_Date,post_likes  FROM posts ORDER BY post_ID DESC');
				while($row = $stmt->fetch()){
					$stmt3 = $db->prepare('SELECT member_name FROM members WHERE member_id = :post_ID');
					$stmt3->execute(array(':post_ID' => $row['post_member_id']));
					$member_name = $stmt3->fetch();
					
					echo '<div id=bor><div id=post>';
						echo '<h1><a href="viewpost.php?id='.$row['post_ID'].'">'.$row['post_Title'].'</a></h1>';
						echo '<div id="likes" ><a href="viewuser.php?id='.$row['post_member_id'].'"> BY '.$member_name['member_name'].'</a> </div>';
						echo '<p>Posted on '.date('jS M Y H:i:s', strtotime($row['post_Date'])).'</p>';
						echo '<p>'.$row['post_Desc'].'</p>';
						echo '<div id="likes" >likes '.$row['post_likes'].' </div>';				
						echo '<p><a href="viewpost.php?id='.$row['post_ID'].'">Read More</a></p>';	
							
					echo '</div></div>';
					
				}

			} catch(PDOException $e) {
			    echo $e->getMessage();
			}
		?>

	</div>


</body>
</html>