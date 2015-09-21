<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$data = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);
	if(isset($_SESSION['loggedId']) && isset($_POST['opw']))
	{
		$result = $data -> query('SELECT COUNT(*) AS ofAccounts FROM advertisers WHERE id='.$_SESSION['loggedId'].' AND password="'.md5($_POST['opw']).'"');
		$number = $result -> fetch_assoc();
		if($number['ofAccounts']==1)
		{
			if(isset($_POST['deleteMembership']) && $_POST['deleteMembership']==1)
			{
				$usersRaw = $data -> query('SELECT * FROM advertisers WHERE parent='.$_SESSION['loggedId']);
				while($user = $usersRaw -> fetch_array(MYSQLI_ASSOC))
				{
					$data -> query('DELETE FROM membership WHERE user='.$user['id']);
					$data -> query('UPDATE advertisers SET parent=0, featured=2147483648 WHERE id='.$user['id']);
				}
				if($data -> query('DELETE FROM membership WHERE user='.$_SESSION['loggedId']))
				{
					$_SESSION['loggedId']=0;
					session_destroy();
					echo "ok";
				}
				else
				{
					echo "error";
				}
			}
			else if(isset($_POST['deleteAccount']) && $_POST['deleteAccount']==1)
			{
				$usersRaw = $data -> query('SELECT * FROM advertisers WHERE parent='.$_SESSION['loggedId']);
				while($user = $usersRaw -> fetch_array(MYSQLI_ASSOC))
				{
					$data -> query('DELETE FROM membership WHERE user='.$user['id']);
					$data -> query('UPDATE advertisers SET parent=0 WHERE id='.$user['id']);
				}
				$imagesRaw = $data -> query('SELECT * FROM images WHERE user = '.$_SESSION['loggedId']);
				while($image = $imagesRaw -> fetch_array(MYSQLI_ASSOC))
				{
					/*
					GETS A BLOODY 500 ERROR
					if(file_exists($root."/images/".$image['file']))
					{
						unlink($root."/images/".$image['file']);
					}*/
					$data -> query('DELETE FROM images WHERE id='.$image['id']);
				}
				if($data -> query('DELETE FROM membership WHERE user='.$_SESSION['loggedId']) && $data -> query('DELETE FROM advertisers_availability WHERE advertiser='.$_SESSION['loggedId']) && $data -> query('DELETE FROM advertisers_favourites WHERE advertiser='.$_SESSION['loggedId']) && $data -> query('DELETE FROM advertisers_services WHERE advertiser='.$_SESSION['loggedId']) && $data -> query('DELETE FROM rates WHERE user='.$_SESSION['loggedId']) && $data -> query('DELETE FROM advertisers WHERE id='.$_SESSION['loggedId']))
				{
					$_SESSION['loggedId']=0;
					session_destroy();
					echo "ok";
				}
				else
				{
					echo "error";
				}
			}
			else if(isset($_POST['nickname']) && isset($_POST['npw']))
			{
				$result = $data -> query('SELECT COUNT(*) AS ofNicknames FROM advertisers WHERE nickname="'.$_POST['nickname'].'" AND id!='.$_SESSION['loggedId']);
				$number = $result -> fetch_assoc();
				if($number['ofNicknames']==0)
				{
					$query = 'UPDATE advertisers SET nickname="'.$_POST['nickname'].'"';
					if($_POST['npw']!='')
					{
						$query .= ', password="'.md5($_POST['npw']).'"';
					}
					$query .= ' WHERE id='.$_SESSION['loggedId'];
					if($data -> query($query))
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
					echo "nt";
				}
			}
			else 
			{
				echo "error";
			}
		}
		else
		{
			echo "ip";
		}
	}
	else
	{
		//error 404
		include $root."/php/error404.php";
	}
?>