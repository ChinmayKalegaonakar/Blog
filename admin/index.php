<?php
//include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
$id=$user->getuserid($user->currentuser());
//show message from add / edit page
if(isset($_GET['delpost'])){ 

	$stmt = $db->prepare('DELETE FROM posts WHERE post_ID = :postID') ;
	$stmt->execute(array(':postID' => $_GET['delpost']));

	header('Location: index.php?action=deleted');
	exit;
} 

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Blog Overview</title>
  <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css">
  <script language="JavaScript" type="text/javascript">
  function delpost(id, title)
  {
	  if (confirm("Are you sure you want to delete '" + title + "'"))
	  {
	  	window.location.href = 'index.php?delpost=' + id;
	  }
  }
  </script>
</head>
<body>

	<div id="wrapper">

	<h1>Blog Overview</h1>
<ul id='adminmenu'>
	<li><a href='add-post.php'>Add Post</a></li>
	<li><a href="users.php?id=<?php echo $id ?>">Users</a></li>
	<li><a href="../viewuser.php?id=<?php echo $id ?>"> My Profile</a></li>
	<li><a href="../" target='_blank'>View Website</a></li>
	<li><a href='logout.php'>Logout</a></li>
</ul>
<div class='clear'></div>
<hr />

	<?php 
	//show message from add / edit page
	if(isset($_GET['action'])){ 
		echo '<h3>Post '.$_GET['action'].'.</h3>'; 
	} 
	?>
	<table>
	<form action='' method='post'>
		<p><label>Title</label>
		<input type='text' name='postTitle' value='<?php if(isset($error)){ echo $_POST['postTitle'];}?>'>
		<input type='submit' name='submit' value='Submit'></p>
	</form>

	<tr>
		<th>Title</th>
		<th>Date</th>
		<th>Action</th>
	</tr>
	<?php
	if(isset($_POST['submit'])){
		$_POST = array_map( 'stripslashes', $_POST );
		//collect form data
		extract($_POST);

		//very basic validation
		if($postTitle ==''){
			$error[] = 'Please enter the title.';
		}
		if(!isset($error)){
            try {
                $stmt = $db->query('SELECT * FROM posts WHERE post_Title like "%'.$postTitle.'%"') ;
			//	$stmt->execute(array(':post_Title' => $postTitle));
               while($row=$stmt->fetch()){
              
                    echo '<tr>';
                    echo '<td>'.$row['post_Title'].'</td>';
                    echo '<td>'.date('jS M Y', strtotime($row['post_Date'])).'</td>';
                    echo '<td>';
                    // if($_GET['id']==1){
                    //     //only for root			
                    //     echo '<a href="edit-post.php?id='.$row['post_ID'].'">Edit</a> | ';
                    //     echo '<a href="delete-post.php?delpost='.$row['post_ID'].')"> Delete</a> |';
                    //     echo '<b> likes'.$row['post_likes'].'</b>';
                    // }
                    echo '<a href="../viewpost.php?id='.$row['post_ID'].'"> VIEW</a> ';
                    echo '</td>';
                    echo '</tr>';
    
                }
    
            } catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
	}
	else{
		try {
			
			$stmt = $db->query('SELECT post_ID, post_Title, post_Date,post_member_id,post_likes FROM posts ORDER BY post_ID DESC');
			while($row = $stmt->fetch()){
				
				echo '<tr>';
				echo '<td>'.$row['post_Title'].'</td>';
				echo '<td>'.date('jS M Y', strtotime($row['post_Date'])).'</td>';
				echo '<td>';
				if($_GET['id']==1||$_GET['id']==$row['post_member_id']){
					//only for root			
					echo '<a href="edit-post.php?id='.$row['post_ID'].'">Edit</a> | ';
					echo '<a href="delete-post.php?delpost='.$row['post_ID'].')"> Delete</a> |';
					echo '<b> likes'.$row['post_likes'].'</b>';
				}
				echo '<a href="../viewpost.php?id='.$row['post_ID'].'"> VIEW</a> ';
				echo '</td>';
				echo '</tr>';

			}

		} catch(PDOException $e) {
		    echo $e->getMessage();
		}
	}
	
	?>
	<!-- <a href="post-search.php">Search►</a> -->
	</table>
	
</div>

</body>
</html>
