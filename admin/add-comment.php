<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Add comment</title>
  <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css">
</head>
<body>

<div id="wrapper">

	<?php include('menu.php');?>


	<h2>Add Comment</h2>

	<?php
    
	//if form has been submitted process it
	if(isset($_POST['submit'])){

		//collect form data
		extract($_POST);

		//very basic validation
		if($text ==''){
			$error[] = 'Please enter some Text';
		}

		if(!isset($error)){
			try {

				//insert into database
				$stmt = $db->prepare('INSERT INTO comments (comment_member_ID,comment_post_ID,comment_text) VALUES (:mid, :pid, :txt)') ;
				$stmt->execute(array(
					':mid' => $user->getuserid($user->currentuser()),
					':pid' => $_GET['id'],
					':txt' => $text
				));

				//redirect to index page
				header('Location: ../viewpost.php?id='.$_GET['id'].'');
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

		<p><label>Enter Text</label><br />
		<textarea name='text' cols='60' rows='10'><?php if(isset($error)){ echo $_POST['text'];}?></textarea></p>
		<p><input type='submit' name='submit' value='Add comment'></p>

	</form>

</div>
