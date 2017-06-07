<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function sanitize($input)
{
	if(is_array($input))
	{
		foreach($input as $key => $value)
		{
			$input[$key] = sanitize($value);
		}
		return $input;
	}
	else
	{
		return strip_tags($input);
	}
}

function pre($object, $die = true)
{
	echo "<pre>"; print_r($object); echo "</pre>";
	if($die) die();
}

function str2slug($str)
{
	$str = strtolower(trim($str));
	$str = preg_replace('/[^a-z0-9-]/', '-', $str);
	$str = preg_replace('/-+/', "-", $str);
	return $str;
}


function news_date($news = false, $type = false)
{
	$news = ($news) ? $news : date("y-m-d h:i:s");
	$time = strtotime($news);
	if($type == 'jam') { 
		$return = strftime('%R', $time) . " WIB";
	}else if($type == 'jamdoang') { 
		$return = strftime('%R', $time);
	} else { 
		$return = strftime('%A, %d %B %Y, %R', $time) . " WIB";
	}
	return $return;
}

function news_image($image, $size = '320x177')
{

	$img = array(
		'144x80' => '144x80',
		'282x156' => '282x156',
		'320x177' => '320x177',
		'640x354' => '640x354',
	);
	return STATICPATH . 'media/news/images/'. $img[$size] . '/'. $image;
}

function news_url($news)
{
	/*
	$subdomain = array('sepakbola','nasional','dunia-islam','senggang','gaya-hidup','trendtek','internasional','video','infografis','en');
	$subdomains = array('sepakbola'=>'bola','nasional'=>'nasional','dunia-islam'=>'khazanah','senggang'=>'senggang','gaya-hidup'=>'gayahidup','trendtek'=>'trendtek','internasional'=>'internasional','video'=>'video','infografis'=>'infografis','en'=>'en');
	
	if(in_array($news['channel'][0],$subdomain)) {
		$dom = $subdomains[$news['channel'][0]];	
		return 'http://' . $dom .'.republika.co.id/berita/'.$news['url'] ;
	}
	*/
	return FRONTEND . $news;
	//if(strpos($news, 'http') === 0) return $news['url'];
	//return $news['vhost'] ? 'http://'.$news['channel'][0].'.republika.co.id/berita/'.$news['url'] : site_url('/berita/'.$news['url']);
}

function excerpt($string, $limit = 5)
{
	$string = explode(" ", strip_tags($string), $limit);
	array_pop($string);
	return implode(" ", $string)."...";
}
function slug($string = "", $replace = "-")
{
	$str = explode(" ", strtolower(trim($string)));
	foreach($str as $key => $value)
	{
		$str[$key] = preg_replace("/[^a-zA-Z0-9\s]/", "", $value);
		if($str[$key] == "") unset($str[$key]);
	}
	return implode($replace, $str);
}

function unslug($string = "", $delimiter = "-")
{
	return ucwords(str_replace($delimiter, " ", $string));
}
 function stripHTMLtags($str)
{
    $t = preg_replace('/<[^<|>]+?>/', '', htmlspecialchars_decode($str));
    $t = htmlentities($t, ENT_QUOTES, "UTF-8");
    return $t;
}
 function cari_array($products, $needle = 0)
{
	//print_r($products);
	$needle = $needle;
//array_map(function ($a, $b) { return $a * $b; }, $origarray1, $origarray2);
 //   return array_filter($products, "categoryone");
    return array_filter($products, function($products) use($needle) {  return (is_array($products) && $products['parent']== $needle); });
}

function categoryone($var)
{
    return (is_array($var) && $var['parent']== 0);
}

function slices($array, $n = 3){
    asort($array);
    return array_slice(array_reverse($array, true),0,$n, true);
}