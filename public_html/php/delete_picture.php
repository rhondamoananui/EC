<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$QL = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_POST['id']) && isset($_SESSION['loggedId']))
	{
		$imageRaw = $QL -> query('SELECT * FROM images WHERE id='.$_POST['id'].' AND banner=0 AND user='.$_SESSION['loggedId']);
		if($image = $imageRaw -> fetch_array(MYSQLI_ASSOC))
		{
			if($image['main']==1)
			{
				$nonMainRaw = $QL -> query('SELECT * FROM images WHERE banner=0 AND main=0 AND user='.$_SESSION['loggedId']);
				if($nonMain = $nonMainRaw -> fetch_array(MYSQLI_ASSOC))
				{
					if($QL -> query('UPDATE images SET main=1 WHERE id='.$nonMain['id']) && $QL -> query('DELETE FROM images WHERE id='.$_POST['id']))
					{
						echo "ok";
					}
					else
					{
						echo "error";
					}
				}
				else if($QL -> query('DELETE FROM images WHERE id='.$_POST['id']))
				{
					echo "ok";
				}
			}
			else if($QL -> query('DELETE FROM images WHERE id='.$_POST['id']))
			{
				echo "ok";
			}
			else
			{
				echo "error";
			}
		}
		else
		{
			echo "error";
		}
	}
	else
	{
		//error 404
		include $root."/php/error404.php";
	}
?>