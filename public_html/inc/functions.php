<?php 
function get_youtube_video_image($youtube_code)
{
	// get the video code if this is an embed code	(old embed)
	preg_match('/youtube\.com\/v\/([\w\-]+)/', $youtube_code, $match);
 
	// if old embed returned an empty ID, try capuring the ID from the new iframe embed
	if($match[1] == '')
		preg_match('/youtube\.com\/embed\/([\w\-]+)/', $youtube_code, $match);
 
	// if it is not an embed code, get the video code from the youtube URL	
	if($match[1] == '')
		preg_match('/v\=(.+)&/',$youtube_code ,$match);
		
	// If it is not above embebeded code, if url like https://www.youtube.com/watch?v=qeYeiXeuNMk (Youtube video URL)
	if($match[1] == '')
		parse_str( parse_url( $youtube_code, PHP_URL_QUERY ) );
		$match[1]= $v; // this will output oHg5SJYRHA0	
 
	// get the corresponding thumbnail images	
	$full_size_thumbnail_image = "http://img.youtube.com/vi/".$match[1]."/0.jpg";
	$small_thumbnail_image1 = "http://img.youtube.com/vi/".$match[1]."/1.jpg";
	$small_thumbnail_image2 = "http://img.youtube.com/vi/".$match[1]."/2.jpg";
	$small_thumbnail_image3 = "http://img.youtube.com/vi/".$match[1]."/3.jpg";
 
	// return whichever thumbnail image you would like to retrieve
	//return $full_size_thumbnail_image;	
	//return $small_thumbnail_image1;	
	return $small_thumbnail_image2;
	//return $small_thumbnail_image3;			
}

// GET THE CODE OF EMBEDED YOUTUBE VALUE
function get_youtube_video_value($youtube_code)
{
	// get the video code if this is an embed code	(old embed)
	preg_match('/youtube\.com\/v\/([\w\-]+)/', $youtube_code, $match);
 
	// if old embed returned an empty ID, try capuring the ID from the new iframe embed
	if($match[1] == '')
		preg_match('/youtube\.com\/embed\/([\w\-]+)/', $youtube_code, $match);
 
	// if it is not an embed code, get the video code from the youtube URL	
	if($match[1] == '')
		preg_match('/v\=(.+)&/',$youtube_code ,$match);
		
	// If it is not above embebeded code, if url like https://www.youtube.com/watch?v=qeYeiXeuNMk (Youtube video URL)
	if($match[1] == '')
		parse_str( parse_url( $youtube_code, PHP_URL_QUERY ) );
		$match[1]= $v; // this will output oHg5SJYRHA0	
 
	
	return $match[1];		
}


?>