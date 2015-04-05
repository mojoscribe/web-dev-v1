<?php 

if(!function_exists('watermarkVideo')){
	function watermarkVideo($videoPath,$userName,$slug){
		$video = $videoPath;
		$outputvideo = "uploads/".$slug.".mp4";
		$watermarkLogo = "uploads/logo.png";

		$cmd = 'ffmpeg -i '.$video.' -vf "movie='.$watermarkLogo.' [watermark]; [in][watermark] overlay=main_w-overlay_w-10:10 [out]" -strict experimental '. $outputvideo;

		echo "<pre>";
		print_r($cmd);
		die();

		exec($cmd);

		return $outputvideo;
	}
}
 ?>