<?php
/** 
* This class is used for public read acces to the VisualizeUs API
* For read/write Access, you must point your brouser to visualizeus.com/user/username/profile/apps/ and
* retrieve one OAuth keyPair.
* 
* @author Antonio Jerez <ajerez@visualizeus.com>
* @version 0.2 
* @access public 
* @copyright VisualizeUs
* 
*/

require_once('./visualizeus/plain/plain.init.php');

class userTokenPlainVisualizeUs {
    var $consumer_key;
    var $consumer_secret;
    var $user;
    var $login_url;       // VisualizeUs url for require login permission
    var $request_token;   // VisualizeUs Request Token
    var $access_token;    // VisualizeUs Access token array(oauth_token, oauth_token_secret)
    var $oauth_token;
    var $oauth_token_secret;
    
    var $service_url = 'http://api.visualizeus.com/v1/';
    
    /** 
    * Creates a new one userTokenPlainVisualizeUs object with the values passed
    * as params.
    * Three params are required.
    * 
    * @return clean if success.
    *         array('error', 'msg') if failed.
    * @access public 
    */ 
    function __construct($apiKey, $apiSecret, $userKey) {
        if (isset($apiKey) && isset($apiSecret) && isset($userKey)){
            $this->consumer_key = $apiKey;
            $this->consumer_secret = $apiSecret;
            $this->user = $userKey;
        }else{
            $res['msg']='Ooops, ApiKey, ApiSecret and UserKey are required values. ';
            $res['error']=true;
            return false;
        }
    }
    
    /**
     * Create a verification hash for plain authentication at VisualizeUs.
     *
     * @return string verification hash
     * 
     */
    
    function createHashVerification(){
        $hash = md5($this->consumer_secret.$this->user.$this->consumer_key);
        return $hash;
    }
    
    /**
     * Set parameters in GET uri format preparing for Request.
     *
     * @param string $uri the end point url for the api call.
     * @param array $params generic params array for all api calls.
     *
     * @return string $uri as result of concatenate uri with request params
     * 
     */
    function setParams($uri, $params){
        $uri.='?apikey='.$this->consumer_key.'&user='.$this->user;
        foreach ($params as $key=>$param){
            $uri.='&'.$key.'='.$param;
        }
        
        return $uri;
    }
    
    /**
     * Function encapsulating the curl_exec action for making requests against the
     * VisualizeUs API
     *
     * @param string $uri the end point url for the api call.
     * @param array $params generic params array for all api calls.
     *
     * @return false and error code if something went wrong
     * @return array which contains the Api call results.
     *
     **/
    function doRequest($uri, $params){
        
        $params['hash']=$this->createHashVerification();
        $uri = $this->setParams($uri,$params);
        
        if (function_exists('curl_init')) {
            // initiate session
            $oCurl = curl_init($uri);
            // set options
            curl_setopt_array($oCurl, array(
               CURLOPT_RETURNTRANSFER => true,
               //CURLOPT_USERAGENT => PHP_DELICIOUS_USER_AGENT,
               CURLOPT_CONNECTTIMEOUT => 10,
               CURLOPT_TIMEOUT => 30,
            ));
            // request URL
            if ($sResult = curl_exec($oCurl)) {
               switch (curl_getinfo($oCurl, CURLINFO_HTTP_CODE)) {
                  case 200:
   	               return $sResult;
                     break;
                  case 503:
                     $this->lastError = '503 Service Unavailable';
                     break;
                  case 401:
                     $this->lastError = '401 Forbidden';
                     break;
                  default:
                     $this->lastError = 'Connection failed, check service URL and params.';
               }
            }
            // close session
            curl_close($oCurl);
            
            return false;
         }else{
            return "curl_init doestn exists";
         }
    }
        
    /**
     * The method bellow, retrieves a user's bookmarks.
     *
     * @param array $params
     *          array(
     *                 'username' string required.
     *                 'page',    int    default 1
     *                 'perpage'  int    default 15 
     *               )
     * @return array with json, xml, print_r demanded output for the api call.
     *
     */
    function getBookmarks($params){
        
        $request_uri = 'user/'.$params['username'].'/bookmarks/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        
        return $result;
    }

    /**
     * Get a bookmark's details using his bhash
     *
     * @param array $params
     *          array(
     *              'bhash' string required
     *          )
     *
     * @return array with json, xml, print_r demanded output for the api call.
     *
     **/
    function getBookmarkDetails($params){
        
        $request_uri = 'bookmarks/'.$params['bhash'];
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        
        return $result;
    }
    
    /**
     * Retrieve related pictures for the given bhash in parameters.
     *
     * @param array $params
     *              array(
     *                 'bhash' string required.
     *                 'page',    int    default 1
     *                 'perpage'  int    default 15 
     *               )
     * @return array with json, xml, print_r output for the api call, inclued
     * error info.
     *
     **/
    function getRelatedBookmarks($params){
        $request_uri = 'bookmarks/'.$params['bhash'].'/related/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        return $result;
    }
    
    /**
     * Get a picture's bookmarks using his bhash
     *
     * @param array $params
     *              array(
     *                 'bhash' string required.
     *                 'page',    int    default 1
     *                 'perpage'  int    default 15 
     *               )
     * @return array with json, xml, print_r output for the api call, inclued
     * error info.
     *
     **/
    function getBookmarkComments($params){
        $request_uri = 'bookmarks/'.$params['bhash'].'/comments/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);

        return $result;
    }


    /**
     * Get the VisualizeUs most recent bookmarks.
     *
     * @param array $params
     *              array(
     *                 'page',    int    default 1
     *                 'perpage'  int    default 15 
     *               )
     * @return array with json, xml, print_r output for the api call, inclued
     * error info.
     *
     **/
    function getRecentBookmarks($params){
        /*$params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14
            );*/
        $request_uri = 'bookmarks/recent/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);        
        return $result;
    }


   /**
     * Get VisualizeUs' popular pictures formated as desired.
     *
     * @param array $params
     *              array(
     *                 'page',    int    default 1
     *                 'perpage'  int    default 15 
     *               )
     * @return array with json, xml, print_r output for the api call, inclued
     * error info.
     *
     **/
    function getPopularBookmarks($params){
        $request_uri = 'bookmarks/popular/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        
        return $result;
    }
    
    /**
     * Get all the pictures with $tag asignated.
     *
     * @param array $params
     *              array(
     *                 'tag' string required.
     *                 'page',    int    default 1
     *                 'perpage'  int    default 15 
     *               )
     * @return array with json, xml, print_r output for the api call, inclued
     * error info.
     *
     **/
    function getTagBookmarks($params){
        $request_uri = 'bookmarks/tag/'.$params['tag'].'/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        
        return $result;
    }
    
    /**
     * Get a user profile, with all the details.
     *
     * @param array $params
     *              array(
     *                 'username' string required.
     *               )
     * @return array with json, xml, print_r output for the api call, inclued
     * error info.
     *
     **/
    function getUserProfile($params){
        $request_uri = 'user/'.$params['username'].'/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        
        return $result;
    }

   /**
     * Get users that $params['username'] is following.
     *
     * @param array $params
     *              array(
     *                 'username' string required.
     *                 'page',    int    default 1
     *                 'perpage'  int    default 15 
     *               )
     * @return array with json, xml, print_r output for the api call, inclued
     * error info.
     *
     **/

    function getUserFollowing($params){
        $request_uri = 'user/'.$params['username'].'/following/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);

        return $result;
    }
    
    /**
     * Get followers for $params['username']
     *
     * @param array $params
     *              array(
     *                 'username' string required.
     *                 'page',    int    default 1
     *                 'perpage'  int    default 15 
     *               )
     * @return array with json, xml, print_r output for the api call, inclued
     * error info.
     *
     **/

    function getUserFollowers($params){
        $request_uri = 'user/'.$params['username'].'/followers/';
        
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        return $result;
    }

   /**
     * Get The bookmarks for a user watchlist. i.e: The users a user is following.
     *
     * @param array $params
     *              array(
     *                 'username' string required.
     *                 'page',    int    default 1
     *                 'perpage'  int    default 15 
     *               )
     * @return array with json, xml, print_r output for the api call, inclued
     * error info.
     *
     **/

    function getUserWatchlist($params){
        $request_uri = 'user/'.$params['username'].'/following/bookmarks/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        
        return $result;
    }

   /**
     * Get a user's bookmarks.
     *
     * @param array $params
     *              array(
     *                 'username' string required.
     *                 'page',    int    default 1
     *                 'perpage'  int    default 15 
     *               )
     * @return array with json, xml, print_r output for the api call, inclued
     * error info.
     *
     **/

    function getUserBookmarks($params){
        $request_uri = 'user/'.$params['username'].'/bookmarks/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        
        return $result;
    }    
   /**
     * Get pictures for a user's tag.
     *
     * @param array $params
     *              array(
     *                 'username' string required.
     *                 'tag'      string required.
     *                 'page',    int    default 1
     *                 'perpage'  int    default 15 
     *               )
     * @return array with json, xml, print_r output for the api call, inclued
     * error info.
     *
     **/
    function getUserTagsBookmarks($params){
        $request_uri = 'user/'.$params['username'].'/bookmarks/tag/'.$params['tags'].'/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        
        return $result;
    }
    
   /**
     * Get all the tags for a username given in params.
     *
     * @param array $params
     *              array(
     *                 'username' string required.
     *                 'page',    int    default 1
     *                 'perpage'  int    default 15 
     *               )
     * @return array with json, xml, print_r output for the api call, inclued
     * error info.
     *
     **/    
    function getUserTags($params){
        $request_uri = 'user/'.$params['username'].'/tags/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        
        return $result;
    }

   /**
     * Search for one or various terms and range.
     *
     * @param array $params
     *              array(
     *                 'terms' string required.
     *                 'range'      all|popular|recent
     *                 'page',    int    default 1
     *                 'perpage'  int    default 15 
     *               )
     * @return array with json, xml, print_r output for the api call, inclued
     * error info.
     *
     **/

    function search($params){
        $request_uri = 'search/'.$params['range'].'/'.$params['terms'].'/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        
        return $result;
        
    }
    
}

?>
