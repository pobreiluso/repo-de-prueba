<?php
// Oauth PHP class for visualizeus API interaction.
require_once( $GLOBALS['ROOT_DIR'] .'/includes/oauth-php/core/init.php');
include_once( $GLOBALS['ROOT_DIR'] .'/includes/oauth-php/OAuthRequester.php');

class userTokenPlainVisualizeUs {
    var $consumer_key;
    var $consumer_secret;
    var $username;
    var $login_url;       // VisualizeUs url for require login permission
    var $request_token;   // VisualizeUs Request Token
    var $access_token;    // VisualizeUs Access token array(oauth_token, oauth_token_secret)
    var $oauth_token;
    var $oauth_token_secret;
    
    var $service_url = 'http://api.visualizeus.com/v1/';
    
    function __construct($consumer_key, $consumer_secret, $username) {
        $this->consumer_key = $consumer_key;
        $this->consumer_secret = $consumer_secret;
        $this->username = $username;
    }
    
    function createHashVerification(){
        $hash = md5($this->consumer_secret.$this->username.$this->consumer_key);
        return $hash;
    }
    
    function setParams($uri, $params){
        $uri.='?apikey='.$this->consumer_key.'&username='.$this->username;
        foreach ($params as $key=>$param){
            $uri.='&'.$key.'='.$param;
        }
        
        return $uri;
    }
    
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
        
    
    function getBookmarks($params){
        /*$hash = $this->createHashVerification($username);
        $params = array(
            'username' => $username,
            'hash'     => $hash,
            'page'     => $page,
            'perpage'  => $perpage
        );*/
        
        $request_uri = 'user/'.$params['username'].'/bookmarks/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        
        return $result;
    }

    function getBookmarkDetails($params){
        /*$params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
                'page' => 3,
                'perpage' => 14
            );*/
        
        $request_uri = 'bookmarks/'.$params['bhash'];
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        return $result;
    }
    
    function getRelatedBookmarks($params){
        /*$params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
                'page' => 3,
                'perpage' => 14
            );*/
        
        $request_uri = 'bookmarks/'.$params['bhash'].'/related/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        return $result;
    }
    
    function getBookmarkComments($params){
        /*$params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
                'page' => 3,
                'perpage' => 14
            );*/
        
        $request_uri = 'bookmarks/'.$params['bhash'].'/comments/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        return $result;
    }

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

    function getPopularBookmarks($params){
        /*$params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14
            );*/
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'bookmarks/popular/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        return $result;
    }
    
    
    function getTagBookmarks($params){
        /*$params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14
            );*/
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'bookmarks/tag/'.$params['tag'].'/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        return $result;
    }
    
    function getUserProfile($params){
        /*$params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14
            );*/
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$params['username'].'/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        return $result;
    }

    function getUserFollowing($params){
        /*$params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14
            );*/
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$params['username'].'/following/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        return $result;
    }

    function getUserFollowers($params){
        /*$params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14
            );*/
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$params['username'].'/followers/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        return $result;
    }

    function getUserWatchlist($params){
        /*$params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14
            );*/
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$params['username'].'/following/bookmarks/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        
        return $result;
    }

    function getUserBookmarks($params){
        /*$params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14
            );*/
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$params['username'].'/bookmarks/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        
        return $result;
    }    

    function getUserTagsBookmarks($params){
        /*$params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14,
            );*/
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$params['username'].'/bookmarks/tag/'.$params['tags'].'/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        
        return $result;
    }
    
    function getUserTags($params){
        /*$params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14
            );*/
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$params['username'].'/tags/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        
        return $result;
    }

    function search($params){
        /*$params =array (
            'page'=> 1,
            'perpage'=>27,
            'range'=>$range,
            'terms'=>$terms,
        );*/
        
        $request_uri = 'search/'.$params['range'].'/'.$params['terms'].'/';
        $result = $this->doRequest($this->service_url.$request_uri, $params);
        
        return $result;
        
    }
    
}

?>
