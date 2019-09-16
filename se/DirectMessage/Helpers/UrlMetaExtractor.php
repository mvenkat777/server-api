<?php
namespace Platform\DirectMessage\Helpers;

use Platform\App\Exceptions\SeException;

/* To Extract meta-deta from a given url */
class UrlMetaExtractor {

	/**
     * @param  HTTPS/HTTP-LINK $link 
     * @return url_meta_data          
     */
	public function extract($url1){
		try{
	            if(preg_match_all($this->generateUrlRegularExpression(), $url1, $url)){
	            	return $this->getUrlMetaTags($url[0]);
	        	}
        } catch(\Exception $e){
            throw new SeException("Please provide a valid link.", 422, 'SE_9001422');
            
        }
	}

	/**
     * @return urlRegularExpression          
     */
	public function generateUrlRegularExpression(){
		return '/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i';
	}

	public function getUrlMetaTags($url = [])
	{
		$tags= [];
		$data = [];
        foreach ($url as $key => $value) {
            $tags[$key] = get_meta_tags($value);
            if(array_key_exists("twitter:title",$tags[$key])){
                $data[$key]['urlTitle'] = $tags[$key]['twitter:title'];
            }
            if(array_key_exists("title",$tags[$key])){
                $data[$key]['urlTitle'] = $tags[$key]['title'];
            }
            if(array_key_exists("twitter:description",$tags[$key])){
                $data[$key]['urlDescription'] = $tags[$key]['twitter:description'];
            }
            if(array_key_exists("twitter:image:src",$tags[$key])){
                $data[$key]['urlImage'] = $tags[$key]['twitter:image:src'];
            }
            if(array_key_exists("description",$tags[$key])){
                $data[$key]['urlDescription'] = $tags[$key]['description'];
            }
            if(array_key_exists("twitter:card", $tags[$key])){
                $data[$key]['urlCard'] = $tags[$key]['twitter:card'];
            }
            if(array_key_exists("twitter:player",$tags[$key])){
                $data[$key]['url'] = $tags[$key]['twitter:player'];
            }
             else{
                $data[$key]['url'] = $value;
            }
		}
		return $data;
	}
}