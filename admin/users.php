<?php
//include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
$id=$user->getuserid($user->currentuser());
//show message from add / edit page
if(isset($_GET['deluser'])){ 

	//if user id is 1 ignore
	if($_GET['deluser'] !='1'){

		$stmt = $db->prepare('DELETE FROM members WHERE member_ID = :member_ID') ;
		$stmt->execute(array(':member_ID' => $_GET['deluser']));

		header('Location: users.php?action=deleted');
		exit;

	}
} 

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Users</title>
  <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css">
  <script language="JavaScript" type="text/javascript">
  function deluser(id, title)
  {
	  if (confirm("Are you sure you want to delete '" + title + "'"))
	  {
	  	window.location.href = 'users.php?deluser=' + id;
	  }
  }
  </script>
</head>
<body>

	<div id="wrapper">

	<h1>Blog</h1>
	<ul id='adminmenu'>
	<li><a href="index.php?id=<?php echo $id ?>">Archive</a></li>
	<li><a href='add-post.php'>Add Post</a></li>
	<li><a href="../viewuser.php?id=<?php echo $id ?>"> My Profile</a></li>
	<li><a href="../" target='_blank'>View Website</a></li>
	<li><a href='logout.php'>Logout</a></li>
	</ul>
	<div class='clear'></div>
	<hr />

	<?php 
	//show message from add / edit page
	if(isset($_GET['action'])){ 
		echo '<h3>User '.$_GET['action'].'.</h3>'; 
	} 
	?>

	<table>
	<form action='' method='post'>
		<p><label>Name</label>
		<input type='text' name='postTitle' value='<?php if(isset($error)){ echo $_POST['postTitle'];}?>'>
		<input type='submit' name='submit' value='Submit'></p>
	</form>
	<tr>
		<th>Username</th>
		<th>Email</th>
		<th>Action</th>
	</tr>
	<?php
	if(isset($_POST['submit'])){
		$_POST = array_map( 'stripslashes', $_POST );
		//collect form data
		extract($_POST);

		//very basic validation
		if($postTitle ==''){
			$error[] = 'Please enter the Name.';
		}
		if(!isset($error)){
            try {
                $stmt = $db->query('SELECT * FROM members WHERE member_name like "%'.$postTitle.'%"') ;
			//	$stmt->execute(array(':post_Title' => $postTitle));
               while($row=$stmt->fetch()){
              
                    echo '<tr>';
                    echo '<td>'.$row['member_name'].'</td>';
					echo '<td>'.$row['member_email'].'</td>';
                    echo '<td>';
                  
                    echo '<a href=add-friend.php?id='.$row['member_ID'].'> ADD FRIEND </a>';
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

			$stmt = $db->query('SELECT member_ID, member_name, member_email FROM members ORDER BY member_name');
			while($row = $stmt->fetch()){
				
				echo '<tr>';
				echo '<td>'.$row['member_name'].'</td>';
				echo '<td>'.$row['member_email'].'</td>';
			

				echo '<td>';
				if($_GET['id']== 1){
						echo  '<a href="edit-user.php?id='.$row['member_ID'].'">Edit</a>|'; 
						echo '<a href="javascript:deluser('.$row['member_ID'].','.$row['member_name'].')">Delete</a>';
				}
				echo '<a href=add-friend.php?id='.$row['member_ID'].'> ADD FRIEND </a>';
				echo '</td>';
				echo '</tr>';

			}

		} catch(PDOException $e) {
		    echo $e->getMessage();
		}}
	?>
	</table>

</div>

</body>
</html>
