<?php 
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	include $root."/php/start.php";
	$QL = new mysqli($QL_Host, $QL_User, $QL_Password, $QL_DB);

	function createThumbs($src, $dest, $desired_width) {

		/* read the source image */
		$source_image = imagecreatefromjpeg($src);
		$width = imagesx($source_image);
		$height = imagesy($source_image);
		
		/* find the "desired height" of this thumbnail, relative to the desired width  */
		$desired_height = floor($height * ($desired_width / $width));
		
		/* create a new, "virtual" image */
		$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
		
		/* copy source image at a resized size */
		imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
		
		/* create the physical thumbnail image to its destination */
		if(imagejpeg($virtual_image, $dest) && unlink($src))
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	function convertImageToJpg($originalImage, $outputImage, $quality)
	{
	    // jpg, png, gif or bmp?
	    $exploded = explode('.',$originalImage);
	    $ext = $exploded[count($exploded) - 1]; 

	    if (preg_match('/jpg|jpeg/i',$ext))
	    {
	        $imageTmp=imagecreatefromjpeg($originalImage);
	    }
	    else if (preg_match('/png/i',$ext))
	    {
	        $imageTmp=imagecreatefrompng($originalImage);
	    }
	    else if (preg_match('/gif/i',$ext))
	    {
	        $imageTmp=imagecreatefromgif($originalImage);
	    }
	    else if (preg_match('/bmp/i',$ext))
	    {
	        $imageTmp=imagecreatefrombmp($originalImage);
	    }
	    else
	    {
	        return 0;
	    }

	    // quality is a value from 0 (worst) to 100 (best)
	    imagejpeg($imageTmp, $outputImage, $quality);
	    imagedestroy($imageTmp);

	    return 1;
	}

	if(isset($_POST['id']) && isset($_SESSION['loggedId']))
	{
		if ($_FILES["picture"]["error"] > 0)
		{
			echo "error";
		}
		else
		{
			if ((($_FILES["picture"]["type"] == "image/gif") || ($_FILES["picture"]["type"] == "image/jpeg") || ($_FILES["picture"]["type"] == "image/jpg") || ($_FILES["picture"]["type"] == "image/pjpeg") || ($_FILES["picture"]["type"] == "image/x-png") || ($_FILES["picture"]["type"] == "image/png")))
			{
				if($_FILES["picture"]["size"] < 3145728)
				{
					if(move_uploaded_file($_FILES["picture"]["tmp_name"], $root."/uploads/" . $_FILES["picture"]["name"]))
					{
						if(convertImageToJpg($root."/uploads/" . $_FILES["picture"]["name"], $root.'/uploads/temp'.$_POST['id'].'.jpg', 100))
						{
							if(unlink($root."/uploads/" . $_FILES["picture"]["name"]))
							{
								if(createThumbs($root.'/uploads/temp'.$_POST['id'].'.jpg', $root.'/images/'.$_POST['id'].'.jpg', 700))
								{
									$finished_image = imagecreatefromjpeg($root.'/images/'.$_POST['id'].'.jpg');
									$width = imagesx($finished_image);
									$height = imagesy($finished_image);
									if(isset($_POST['banner']) && $_POST['banner']==1)
									{
										$banner = 1;
										$QL -> query('DELETE FROM images WHERE user='.$_SESSION['loggedId'].' AND banner=1');
									}
									else
									{
										$banner = 0;
									}
									$result = $QL -> query('SELECT COUNT(*) AS ofMain FROM images WHERE user='.$_SESSION['loggedId'].' AND main=1');
									$number = $result -> fetch_assoc();
									if($banner==0 && $number['ofMain']==0)
									{
										$main = 1;
									}
									else
									{
										$main = 0;
									}
									if($QL -> query('INSERT INTO images (file, user, width, height, banner, main) VALUES ("'.$_POST['id'].'.jpg", '.$_SESSION['loggedId'].', '.$width.', '.$height.', '.$banner.', '.$main.')'))
									{
										echo "ok";
									}
									else
									{
										echo "error";
									}
								}
							}
						}
					}
				}
				else
				{
					echo "sizerr";
				}
			}
			else
			{
				echo "typerr";
			}
		}
	}
	else
	{
		//404 Error
		include $root."/php/error404.php";
	}
?>