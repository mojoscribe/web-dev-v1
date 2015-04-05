<?php 
class ImageHandler {
	var $image = null;

	function __construct($filePath){
		$this->image = $filePath;
	}

	/*
	*	creates and saves images of all required sizes for MojoScribe
	*	Maintains aspect ratios by default
	*	Returns array of paths of all newly created images.
	*/
	function createSizes($maintainAspect = true){
		//exec("convert -resize 500x300 input.jpg -background black -gravity center -extent 500x300 output.jpg");

		//Convert for Single Page
		$origPath = "uploads/" . $this->image;
		$path = "uploads/images/big/" . $this->image;
		$size = "770x400";
		$cmd = "convert -resize " . $size . " " . $origPath . " -background black -gravity center -extent " . $size . " " . $path;		
		exec($cmd);
		$cmd = "composite -dissolve 50% -gravity northeast assets/images/MojoScribe_logo.png ".$path." " . $path;
		exec($cmd);

		//Convert to long
		$path = "uploads/images/long/" . $this->image;
		$size = "375x464";
		$cmd = "convert -resize " . $size . " " . $origPath . " -background black -gravity center -extent " . $size . " " . $path;		
		exec($cmd);
		$cmd = "composite -dissolve 50% -gravity northeast assets/images/MojoScribe_logo.png ".$path." " . $path;
		exec($cmd);

		//Convert for Thumbnail
		$path = "uploads/images/thumb/" . $this->image;
		$size = "320x176";
		$cmd = "convert -resize " . $size . " " . $origPath . " -background black -gravity center -extent " . $size . " " . $path;
		exec($cmd);
		$cmd = "composite -dissolve 50% -gravity northeast assets/images/MojoScribe_logo.png ".$path." " . $path;
		exec($cmd);

		//Convert for Small
		$path = "uploads/images/small/" . $this->image;
		$size = "345x265";
		$cmd = "convert -resize " . $size . " " . $origPath . " -background black -gravity center -extent " . $size . " " . $path;
		exec($cmd);
		$cmd = "composite -dissolve 50% -gravity northeast assets/images/MojoScribe_logo.png ".$path." " . $path;
		exec($cmd);

		//Convert for NewsRoom
		$path = "uploads/images/newsroom/" . $this->image;
		$size = "770x245";
		$cmd = "convert -resize " . $size . " " . $origPath . " -background black -gravity center -extent " . $size . " " . $path;
		exec($cmd);
		$cmd = "composite -dissolve 50% -gravity northeast assets/images/MojoScribe_logo.png ".$path." " . $path;
		exec($cmd);

		//Convert for Device
		$path = "uploads/images/device/" . $this->image;
		$size = "640x480";
		$cmd = "convert -resize " . $size . " " . $origPath . " -background black -gravity center -extent " . $size . " " . $path;
		exec($cmd);
		$cmd = "composite -dissolve 50% -gravity northeast assets/images/MojoScribe_logo.png ".$path." " . $path;
		exec($cmd);

		return array(
				'small' => "uploads/images/small/" . $this->image,
				'thumb' => "uploads/images/thumb/" . $this->image,
				'long' => "uploads/images/long/" . $this->image,
				'newsroom' => "uploads/images/newsroom/" . $this->image,
				'big' => "uploads/images/big/" . $this->image,
				'device'=>"uploads/images/device/".$this->image
			);
	}

	/*
	*	Watermarks all available variations/sizes of $this->image;
	*	
	*/
	function waterMark($userName, $path){

		$userImg = "uploads/users/".$userName.".png";

		//$cmd = "convert -size 300x50 xc:grey00 -background transparent -pointsize 20 -gravity center -draw \"fill black text 1,1 '".$userName."' text 0,0 '".$userName."' fill white text -1,-1 '".$userName."' \" +matte uploads/users/".$userName.".jpg";
		$cmd = "convert -size 300x50 xc:black -pointsize 20 -gravity center -draw \"fill white text 1,1 '".$userName."' text 0,0\" uploads/users/".$userName.".png";
		exec($cmd);

		$small = "uploads/images/small/" . $path;
		$thumb = "uploads/images/thumb/" . $path;
		$long = "uploads/images/long/" . $path;
		$big = "uploads/images/big/" . $path;
		$newsroom = "uploads/images/newsroom/" . $path;
		$device = "uploads/images/device/" . $path;

		$cmd = "composite -dissolve 100% -gravity southwest " . $userImg . " ".$small." " . $small;
		exec($cmd);

		$cmd = "composite -dissolve 100% -gravity southwest " . $userImg . " ".$thumb." " . $thumb;
		exec($cmd);

		$cmd = "composite -dissolve 100% -gravity southwest " . $userImg . " ".$long." " . $long;
		exec($cmd);

		$cmd = "composite -dissolve 100% -gravity southwest " . $userImg . " ".$big." " . $big;
		exec($cmd);

		$cmd = "composite -dissolve 100% -gravity southwest " . $userImg . " ".$newsroom." " . $newsroom;
		exec($cmd);

		$cmd = "composite -dissolve 100% -gravity southwest " . $userImg . " ".$device." " . $device;
		exec($cmd);

	}
} 