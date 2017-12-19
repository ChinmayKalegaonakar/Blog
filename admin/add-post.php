<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
$id=$user->getuserid($user->currentuser());

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Add Post</title>
  <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css">
  <script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
  <script>
          tinymce.init({
              selector: "textarea",
              plugins: [
                  "advlist autolink lists link image charmap print preview anchor",
                  "searchreplace visualblocks code fullscreen",
                  "insertdatetime media table contextmenu paste"
              ],
              toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
          });
  </script>
</head>
<body>

<div id="wrapper">

	<h1>Blog</h1>
	<ul id='adminmenu'>
		<li><a href="index.php?id=<?php echo $id ?>">Previous Posts</a></li>
		<li><a href="users.php?id=<?php echo $id ?>">Users</a></li>
		<li><a href="../viewuser.php?id=<?php echo $id ?>"> My Profile</a></li>
		<li><a href="../" target='_blank'>View Website</a></li>
		<li><a href='logout.php'>Logout</a></li>
	</ul>
	<div class='clear'></div>
	<hr />

	<h2>Add Post</h2>

	<?php

	//if form has been submitted process it
	if(isset($_POST['submit'])){
		$_POST = array_map( 'stripslashes', $_POST );
		//collect form data
		extract($_POST);

		//very basic validation
		if($postTitle ==''){
			$error[] = 'Please enter the title.';
		}

		if($postDesc ==''){
			$error[] = 'Please enter the description.';
		}

		if($postCont ==''){
			$error[] = 'Please enter the content.';
		}

		if(!isset($error)){

			try {

				//insert into database
				$stmt = $db->prepare('INSERT INTO posts (post_Title,post_Desc,post_Cont,post_Date,post_member_id,post_likes) VALUES (:post_Title, :post_Desc, :post_Cont, :post_Date,:pmid,1)') ;
				$stmt->execute(array(
					':post_Title' => $postTitle,
					':post_Desc' => $postDesc,
					':post_Cont' => $postCont,
					':post_Date' => date('Y-m-d H:i:s'),
					':pmid'=>$user->getuserid($user->currentuser())
				));

				//redirect to index page
				header('Location: ../index.php?action=added');
				exit;

			} catch(PDOException $e) {
			    echo $e->getMessage();
			}

		}

	}

	//check for any errors
	if(isset($error)){
		foreach($error as $error){
			echo '<p class="error">'.$error.'</p>';
		}
	}
	?>

	<form action='' method='post'>

		<p><label>Title</label><br />
		<input type='text' name='postTitle' value='<?php if(isset($error)){ echo $_POST['postTitle'];}?>'></p>

		<p><label>Description</label><br />
		<textarea name='postDesc' cols='60' rows='10'><?php if(isset($error)){ echo $_POST['postDesc'];}?></textarea></p>

		<p><label>Content</label><br />
		<textarea name='postCont' cols='60' rows='10'><?php if(isset($error)){ echo $_POST['postCont'];}?></textarea></p>

		<p><input type='submit' name='submit' value='Submit'></p>

	</form>

</div>
