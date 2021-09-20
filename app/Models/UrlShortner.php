<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;

class UrlShortner extends Model
{
    protected $table = 'url_shortner_config';
    public $timestamps = false;

    public static function shortURL($longURL)
	{
		// data retrive from cache
		$urlShortner = Cache::get('gb-url_shortner');
		// If data not available in cache, retrive from database and put in cache
        if (empty($urlShortner)) {
            $urlShortner = parent::where(['status' => 'Active', 'default' => 'Yes'])->first();
            Cache::put('gb-url_shortner', $urlShortner, 30 * 86400);
        }
        // If data also not availbe in database then return longURL URL
        if ($urlShortner) {
        	// If default config is Bitly
        	if ($urlShortner->type == 'Bitly') {
        		// Open curl request
	        	$curl = curl_init();
			    curl_setopt_array($curl, array(
			        CURLOPT_URL => "https://api-ssl.bitly.com/v3/shorten?access_token=".$urlShortner->secretkey."&longUrl=".$longURL."&format=json",
			        CURLOPT_RETURNTRANSFER => true,
			        CURLOPT_FOLLOWLOCATION => false,
			        CURLOPT_SSL_VERIFYHOST => false,
			        CURLOPT_SSL_VERIFYPEER => false,
			      ));

			      $response = curl_exec($curl);
			      $err = curl_error($curl);
			      // Close curl request
			      curl_close($curl);
			      $response = $response = json_decode($response);
			      // Check curl error, if error found then return longURL
			      $url = $response->status_code == 200 ? $response->data->url : $longURL;
				  return $err ? $longURL : $url;
			      // If default config is TinyURL
        	} else {
        		$curl = curl_init();
        		curl_setopt_array($curl, array(
				  CURLOPT_URL => "https://tinyurl.com/api-create.php?url=".$longURL,
				  CURLOPT_RETURNTRANSFER => true,
				));
				$response = curl_exec($curl);
				$err = curl_error($curl);
				curl_close($curl);

				return $err ? $longURL : $response;
        	}
        } else {
        	return $longURL;
        }
	}
}