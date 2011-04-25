<?php
// Oauth PHP class for visualizeus API interaction.
require_once( $GLOBALS['ROOT_DIR'] .'/includes/oauth-php/core/init.php');
include_once( $GLOBALS['ROOT_DIR'] .'/includes/oauth-php/OAuthRequester.php');

class userTokenOauthVisualizeUs{
    var $consumer_key;
    var $consumer_secret;
    var $login_url;       // VisualizeUs url for require login permission
    var $request_token;   // VisualizeUs Request Token
    var $access_token;    // VisualizeUs Access token array(oauth_token, oauth_token_secret)
    var $oauth_token;
    var $oauth_token_secret;
    var $signature_methods= array('HMAC-SHA1','PLAINTEXT');
    var $username;
    var $format = '.json';
    
    var $service_url = 'http://api.visualizeus.com/v2/';
    var $request_token_url = 'http://testingss.visualizeus.com/oauth/request_token';
    var $request_uri  =  'http://testingss.visualizeus.com/oauth/request_uri';
    var $authorize_url = 'http://testingss.visualizeus.com/oauth/authorize';
    var $access_token_url = 'http://api.visualizeus.com/oauth/access_token';
    var $authenticate_url = 'http://api.visualizeus.com/api/authenticate';
    var $read_url = 'http://api.visualizeus.com/api/read';
    var $write_url = 'http://api.visualizeus.com/api/write';
    
        
    var $VisualizeUsOauthObject;
    
    function __construct($params) {
        $this->consumer_key=$params['consumer_key'];
        $this->consumer_secret=$params['consumer_secret'];
        $this->oauth_token=$params['token'];
        $this->oauth_token_secret=$params['token_secret'];
        
        $this->VisualizeUsOauthObject = new OAuth($this->consumer_key,$this->consumer_secret,OAUTH_SIG_METHOD_HMACSHA1,OAUTH_AUTH_TYPE_URI);
        //$this->VisualizeUsOauthObject->enableDebug();
    }
    
    function requestAuthorizationUri(){
        $uri = $request_uri.'/?consumer_key='.$this->consumer_key;
        return $uri;
    }

    function setTokens(){
        $secrets['token']=$this->oauth_token;
        $secrets['token_secret']=$this->oauth_token_secret;
        $secrets['signature_methods']=$this->signature_methods;
        $secrets['consumer_key']=$this->consumer_key;
        $secrets['consumer_secret']=$this->consumer_secret;
        
        return $secrets;
    }
    
    function doRequest($uId, $uri, $method='GET', $params){
        $req = new OAuthRequester($uri, $method, $params);
        $secrets=$this->setTokens();
        $result = $req->doRequest($uId, null, $secrets);
        
        return $result;
    }
    
    function getBookmarks($uId, $params){
        $request_uri = 'users/'.$params['username'].'/bookmarks'.$format;
        $uri = $this->service_url.$request_uri;

        $result = $this->doRequest($uId, $uri, 'GET', $params);

        return $result;
    }

    function getBookmarkDetails($uId, $params){
        $request_uri = 'bookmarks/'.$params['bhash'].$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        
        return $result;
    }
    
    function getRelatedBookmarks($uId, $params){
        /*$params = array(
                'page' => 3,
                'perpage' => 14,
                'bHahs'=>$bhash
            );*/
        $request_uri = 'bookmarks/'.$params['bhash'].'/related'.$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        
        return $result;
    }
    
    function getBookmarkComments($uId, $params){
        $request_uri = 'bookmarks/'.$params['bhash'].'/comments'.$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        
        return $result;
    }

    function addBookmarkComment($uId, $params){
        $request_uri = 'bookmarks/'.$params['bhash'].'/comments'.$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'POST', $params);
                
        return $result;
    }
    
    function deleteBookmarkComment($uId, $params){
        $request_uri = 'bookmarks/'.$params['bhash'].'/comments'.$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'DELETE', $params);
        
        return $result;
    }


    function getRecentBookmarks($uId, $params){
        $request_uri = 'bookmarks/recent'.$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);

        return $result;
    }

    function getPopularBookmarks($uId, $params){
        $request_uri = 'bookmarks/popular'.$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        
        return $result;
    }
    
    
    function getTagBookmarks($uId, $params){
        $request_uri = 'bookmarks/tags/'.$params['tag'].$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        
        return $result;
    }
    
    function getUserProfile($uId, $params){
        $request_uri = 'users/'.$params['username'].$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);

        return $result;
    }

    function getUserFollowing($uId, $params){
        $request_uri = 'users/'.$params['username'].'/following'.$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        return $result;
    }

    function getUserFollowers($uId, $params){
        $request_uri = 'users/'.$params['username'].'/followers'.$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        
        return $result;
    }

    function getUserWatchlist($uId, $params){
        $request_uri = 'users/'.$params['username'].'/following/bookmarks'.$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        
        return $result;
    }
    

    function addUserWatch($uId, $params){
        $request_uri = 'users/'.$params['username'].'/following'.$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'POST', $params);
        
        return $result;
    }

    function delUserWatch($uId, $params){
        $params = array(
                'username2' => $params['username']
            );
        
        $request_uri = 'users/'.$params['username'].'/following'.$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'DELETE', $params);
        
        return $result;
    }


    function getUserBookmarks($uId, $params){
        $request_uri = 'users/'.$params['username'].'/bookmarks'.$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        
        return $result;
    }    

    function getUserTagsBookmarks($uId, $params){
        $request_uri = 'users/'.$params['username'].'/bookmarks/tags/'.$params['tags'].$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        
        return $result;
    }
    
    function getUserTags($uId, $params){
        $request_uri = 'users/'.$params['username'].'/tags'.$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        
        return $result;
    }



    function renameUserTags($uId, $params){
        $request_uri = 'users/'.$params['username'].'/tags/'.$params['tag'].$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'PUT', $params);
        
        return $result;
    }
    
    function postBookmark($uId, $params){
        /*$params = array(
                'page' => 3,
                'perpage' => 14,
                'tagNewName' => $params['tagNewName'],
                'url' => $params['url'],
                'title' => $params['title'],
                'description' => $params['description'],
                'tags' => $params['tags'],
                'refUrl' => $params['refUrl'],
                'privacy' => $params['privacy'],
                'unsafe' => $params['unsafe'],
                'action' => $params['action'],
                'hash' => $params['bHash']
            );*/
        
        $request_uri = 'users/'.$params['username'].'/bookmarks'.$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'POST', $params);
        
        return $result;
    }
    
    function editBookmark($uId, $params, $username, $bId, $bhash, $url, $title, $description, $tags, $refUrl, $privacy, $unsafe){
        /*$params = array(
                'url' => $params['url'],
                'title' => $params['title'],
                'description' => $params['description'],
                'tags' => $params['tags'],
                'refUrl' => $params['refUrl'],
                'privacy' => $params['privacy'],
                'unsafe' => $params['unsafe'],
                'hash' => $params['bhash'],
                'id' => $params['bId']
            );*/
        $request_uri = 'users/'.$params['username'].'/bookmarks/'.$params['hash'].$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'PUT', $params);
        return $result;
    }
    
    function likeBookmark($uId, $params, $username, $bhash, $bId, $url, $title, $description, $tags, $refUrl, $privacy, $unsafe, $action){
        /*$params = array(
                'tagNewName' => $params['tagNewName'],
                'url' => $params['url'],
                'title' => $params['title'],
                'description' => $params['description'],
                'tags' => $params['tags'],
                'refUrl' => $params['refUrl'],
                'privacy' => $params['privacy'],
                'unsafe' => $params['unsafe'],
                'action' => $params['action'],
                'hash' => $params['bHash']
            );*/
        $request_uri = 'users/'.$params['username'].'/bookmarks/'.$params['bhash'].$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'POST', $params);
        
        return $result;
    }
    

    function deleteBookmark($uId, $username,$bId,  $bhash){
        /*$params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //    'commentId' => $commentId,
                //'page' => 3,
                //'perpage' => 14,
                //'tagNewName' => $tagNewName,
                //'url' => $url,
                //'title' => $title,
                //'description' => $description,
                //'tags' => $tags,
                //'refUrl' => $refUrl,
                //'privacy' => $privacy,
                //'unsafe' => $unsafe,
                //'action' => $action,
                'hash' => $hash,
                'id' => $bId
            );*/
        $request_uri = 'users/'.$params['username'].'/bookmarks/'.$params['bhash'].$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'DELETE', $params);
        return $result;
    }

    function search($uId,$terms,$range='all'){
        /*$params =array (
            'page'=> 1,
            'perpage'=>27,
            'range'=>$range,
            'terms'=>$terms,
        );*/
        
        $request_uri = 'search/'.$params['range'].'/'.$params['terms'].$format;
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        return $result;
        
    }
    
}

?>
