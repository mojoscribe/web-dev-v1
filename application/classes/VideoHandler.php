<?php 
class VideoHandler {
	var $vidPath;
	var $image;
	function __construct($path){
		$path = chop($path,'(');
		$path = chop($path, ')');
		$this->vidPath = $path;
        $this->image = $this->vidPath . ".png";
	}

	function generateThumbnail(){
		
		$video = 'uploads/'.$this->vidPath;
		$output = "uploads/videos/thumbs/" . $this->vidPath . ".png";
		$cmd = "avconv -i " . $video . " -vframes 1 " . $output;
		exec($cmd,$a,$b);

		$this->image = $this->vidPath . ".png";

		return $this->resizeThumbs();
	}

	function resizeThumbs(){
		//Convert for Single Page
		$origPath = "uploads/videos/thumbs/" . $this->image;

		$path = "uploads/images/big/" . $this->image;

		$size = "770x400";
		$cmd = "convert -resize " . $size . " " . $origPath . " -background black -gravity center -extent " . $size . " " . $path;
		exec($cmd);
		$cmd = "composite -dissolve 50% -gravity northeast assets/images/MojoScribe_logo.png ".$path." " . $path;
		exec($cmd,$a,$b);

        //Convert to long
		$path = "uploads/images/long/" . $this->image;
		$size = "375x464";
		$cmd = "convert -resize " . $size . " " . $origPath . " -background black -gravity center -extent " . $size . " " . $path;		
		exec($cmd);
		$cmd = "composite -dissolve 50% -gravity northeast assets/images/MojoScribe_logo.png ".$path." " . $path;
		exec($cmd,$a,$b);
		
		//Convert for Thumbnail
		$path = "uploads/images/thumb/" . $this->image;
		$size = "320x176";
		$cmd = "convert -resize " . $size . " " . $origPath . " -background black -gravity center -extent " . $size . " " . $path;
		exec($cmd);
		$cmd = "composite -dissolve 50% -gravity northeast assets/images/MojoScribe_logo.png ".$path." " . $path;
		exec($cmd,$a,$b);
		
		//Convert for Small
		$path = "uploads/images/small/" . $this->image;
		$size = "345x265";
		$cmd = "convert -resize " . $size . " " . $origPath . " -background black -gravity center -extent " . $size . " " . $path;
		exec($cmd);
		$cmd = "composite -dissolve 50% -gravity northeast assets/images/MojoScribe_logo.png ".$path." " . $path;
		exec($cmd,$a,$b);
		
		//Convert for Newsroom
		$path = "uploads/images/newsroom/" . $this->image;
		$size = "770x245";
		$cmd = "convert -resize " . $size . " " . $origPath . " -background black -gravity center -extent " . $size . " " . $path;
		exec($cmd);
		$cmd = "composite -dissolve 50% -gravity northeast assets/images/MojoScribe_logo.png ".$path." " . $path;
		exec($cmd,$a,$b);
		
		//Convert for Device
		$path = "uploads/images/device/" . $this->image;
		$size = "640x480";
		$cmd = "convert -resize " . $size . " " . $origPath . " -background black -gravity center -extent " . $size . " " . $path;
		exec($cmd);
		$cmd = "composite -dissolve 50% -gravity northeast assets/images/MojoScribe_logo.png ".$path." " . $path;
		exec($cmd,$a,$b);
		

		return array(
				'small' => "uploads/images/small/" . $this->image,
				'thumb' => "uploads/images/thumb/" . $this->image,
				'long' => "uploads/images/long/" . $this->image,
				'big' => "uploads/images/big/" . $this->image,
				'newsroom' => "uploads/images/newsroom/" . $this->image,
				'device'=>"uploads/images/device/".$this->image
			);
	}

	function waterMarkImage($userName){

		$path = $this->image;

		$userImg = "uploads/users/".$userName.".png";

		$cmd = "convert -size 300x50 xc:black -pointsize 20 -gravity center -draw \"fill white text 1,1 '".$userName."' text 0,0\" uploads/users/".$userName.".png";
		exec($cmd,$a,$b);
        exec("chmod 777 " . $userImg);



		$small = "uploads/images/small/" . $path;
		$thumb = "uploads/images/thumb/" . $path;
		$long = "uploads/images/long/" . $path;
		$big = "uploads/images/big/" . $path;
		$newsroom = "uploads/images/newsroom/" . $path;
		$device = "uploads/images/device/". $path;

		$cmd = "composite -dissolve 50% -gravity southwest " . $userImg . " ".$small." " . $small;
		exec($cmd,$a,$b);

		$cmd = "composite -dissolve 50% -gravity southwest " . $userImg . " ".$thumb." " . $thumb;
		exec($cmd,$a,$b);

		$cmd = "composite -dissolve 50% -gravity southwest " . $userImg . " ".$long." " . $long;
		exec($cmd,$a,$b);

		$cmd = "composite -dissolve 50% -gravity southwest " . $userImg . " ".$big." " . $big;
		exec($cmd,$a,$b);

		$cmd = "composite -dissolve 50% -gravity southwest " . $userImg . " ".$newsroom." " . $newsroom;
		exec($cmd,$a,$b);

		$cmd = "composite -dissolve 50% -gravity southwest " . $userImg . " ".$device." " . $device;
		exec($cmd,$a,$b);

	}

	function process($userName = ""){
		
		//Watermark video		
		$source = 'uploads/'.$this->vidPath;
		$watermarked = "uploads/videos/watermarked/" . $this->vidPath;
		$watermarkedUser = "uploads/videos/watermarked/user/" . $this->vidPath;
		$resized = "uploads/videos/temp/".$this->vidPath;


		//Resize Video
		$cmd = "avconv -y -i ". $source ." -filter:v \"scale=iw*min(700/iw\,400/ih):ih*min(700/iw\,400/ih), pad=700:400:(700-iw*min(700/iw\,400/ih))/2:(400-ih*min(700/iw\,400/ih))/2\" -strict -2 ".$resized;
		exec($cmd,$a,$b);

		print_r($a);
		print_r($b);
		echo "<br/>";
		echo "Resize";
		echo $cmd;
		
		//Watermark Logo
		$cmd = "avconv -y -i ". $resized ." -threads 0 -vf \"movie=assets/images/MojoScribe_logo.png [watermark]; [in][watermark] overlay=main_w-overlay_w-10:10 [out]\" -r 23.967 -strict -2 " . $watermarked;
		echo $cmd . "<br/>";
		exec($cmd,$a,$b);
		print_r($a);
		print_r($b);

		if("" != $userName) {

			echo "<pre>";
			print_r($userName);

            // For this process, we use the gif instead of png. The generated png causes problems in watermarking

			$userImg = "uploads/users/" . $userName . ".gif";
            $cmd = "convert -background black -fill white -pointsize 20 -gravity center label:" . $userName . " " . $userImg;

			exec($cmd,$a,$b);
			chmod($userImg, 0777);

            $cmd = "avconv -i " . $watermarked . " -vf \"movie=" . $userImg . " [watermark]; [in][watermark] overlay=10:main_h-overlay_h-10 [out]\" -r 23.967 -strict -2 " . $watermarkedUser;

			exec($cmd,$a,$b);
			print_r($a);
			print_r($b);

			$watermarked = $watermarkedUser;
		}
		
		//convert to all formats
		$cmd = "avconv -i " . $watermarked . " -threads 0 -f ogg -r 23.967 -strict -2 uploads/videos/ogg/" . $this->vidPath . ".ogg -f webm -r 23.967 -strict -2 uploads/videos/webm/" . $this->vidPath . ".webm -f mp4 -r 23.967 -strict experimental uploads/videos/mp4/" . $this->vidPath . ".mp4";
		echo "<br/>";
		echo $cmd;
		exec($cmd,$a,$videoOkay);
		print_r($a);
		print_r($b);


		//chmod($watermarkedUser, 0777);

		//Delete Extra videos
		$rmCmd = "rm " . $watermarkedUser;
		
		echo $rmCmd;
		echo "<br/>";
		exec($rmCmd,$a,$b);
		print_r($a);
		print_r($b);


		echo $rmCmd;
		echo "<br/>";
		$rmCmd = "rm " . $watermarked;
		exec($rmCmd,$a,$b);
		print_r($a);
		print_r($b);

		// if($videoOkay == 0){
			return array(
				'mp4' => "uploads/videos/mp4/" . $this->vidPath . ".mp4",
				'webm' => "uploads/videos/webm/" . $this->vidPath . ".webm",
				'ogg' => "uploads/videos/ogg/" . $this->vidPath . ".ogg",
			);
		// }else{
		// 	return array(
		// 		'mp4' => null,
		// 		'webm' => null,
		// 		'ogg' => null,
		// 	);
		// }
		
	}

}