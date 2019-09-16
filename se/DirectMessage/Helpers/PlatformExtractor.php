<?php
namespace Platform\DirectMessage\Helpers;

use Platform\App\Exceptions\SeException;

class PlatformExtractor {

    private $appList = [
                    'customers' => ['Customers', '\App\Customer', 'Platform\Customer\Transformers\MetaCustomerTransformer'],
                    'techpack' => ['Techpack', '\App\Techpack', 'Platform\Techpacks\Transformers\MetaTechpackTransformer'],
                    'line' => ['Line', '\App\Line', 'Platform\Line\Transformers\MetaLineTransformer'],
                    'calendar' => ['Calender', '\Platform\TNA\Models\TNA', 'Platform\TNA\Transformers\MetaCustomerTransformer'],
                    'sample-container' => ['Sample Management', '\App\SampleContainer', 'Platform\SampleContainer\Transformers\MetaSampleContainerTransformer'],
                    'users' => ['Users', '\App\User', 'Platform\Users\Transformers\MetaUserTransformer'],
                    'orders' => ['Orders', '\App\Order', 'Platform\Orders\Transformers\MetaOrderTransformer'],
                    'vendors' => ['Vendors', '\App\Vendor', 'Platform\Vendor\Transformers\MetaVendorTransformer'],
                    'materialLibrary' => ['Material Library', '\App\MaterialLibrary', 'Platform\Materials\Transformers\MetaMaterialLibraryTransformer'],
                    'pomSheets' => ['POM Sheets', '\App\Pom', 'Platform\Pom\Transformers\MetaPomSheetTransformer'],
                    'help' => ['Help', '\App\Help', 'Platform\Help\Transformers\MetaHelpTransformer'],
                ];

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

    /**
     * Get tha meta data of urls
     *
     * @param Array $urls
     * @return Array
     */
	public function getUrlMetaTags($urls = [])
	{
        $data = [];
		foreach ($urls as $key => $url) {
            $mainData = trim(explode('#', $url)[1], '/');
            $mainDataArr = explode('/', $mainData);

            $data[$key]['urlTitle'] = $mainDataArr[0];

            $data[$key]['urlDescription'] = (preg_match('/(\?)(page=)|(item=)/i', $mainData)) 
                ? 'List of '.$data[$key]['urlTitle']
                : 'Details';

            $data[$key]['urlCard'] = $this->getPlatformData($data[$key]['urlTitle'], $this->getId($mainDataArr));

            $data[$key]['url'] = $url;
		}
		return $data;
	}

    /**
     * Extract id from and array
     *
     * @param Array     $urlArray
     * @return String
     */
    private function getId(array $urlArray)
    {
        foreach($urlArray as $value) {
            if(substr_count($value, '-') >= 3  || is_numeric($value)) {
                return $value;
            }
        }
        return $urlArray[count($urlArray) - 1];
    }

    /**
     * To get the data from database by appname and id
     *
     * @param string    $app
     * @param string/integer $id
     * @return array
     */
    private function getPlatformData($app, $id)
    {
        try {
            $dbData = (new $this->appList[$app][1])->find($id);
            if($dbData) {
                return (new $this->appList[$app][2])->transform($dbData);
            } else {
                return 'Show';
            }
        } catch(\Exception $e) {
            return 'Show';
        }
    }
}
