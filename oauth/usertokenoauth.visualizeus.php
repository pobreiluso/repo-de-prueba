<?php
// Oauth PHP class for visualizeus API interaction.
require('./visualizeus/oauth/oauth.init.php');
include('./visualizeus/oauth/OAuthRequester.php');


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
    
    var $service_url = 'http://api.visualizeus.com/v2/';
    var $request_token_url = 'http://api.visualizeus.com/oauth/request_token';
    var $authorize_url = 'http://testingss.visualizeus.com/oauth/authorize';
    var $access_token_url = 'http://api.visualizeus.com/oauth/access_token';
    var $authenticate_url = 'http://api.visualizeus.com/api/authenticate';
    var $read_url = 'http://api.visualizeus.com/api/read';
    var $write_url = 'http://api.visualizeus.com/api/write';
    
        
    var $VisualizeUsOauthObject;
    
    function __construct($params) {
        //$this->consumer_key = $GLOBALS['VisualizeUsConsumerKey'];
        //$this->consumer_secret = $GLOBALS['VisualizeUsConsumerSecret'];        
        $this->consumer_key=$params['consumer_key'];
        $this->consumer_secret=$params['consumer_secret'];
        $this->oauth_token=$params['token'];
        $this->oauth_token_secret=$params['token_secret'];
        
        $this->VisualizeUsOauthObject = new OAuth($this->consumer_key,$this->consumer_secret,OAUTH_SIG_METHOD_HMACSHA1,OAUTH_AUTH_TYPE_URI);
        //$this->VisualizeUsOauthObject->enableDebug();
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
        $request_uri = 'user/'.$params['username'].'/bookmarks/';
        $uri = $this->service_url.$request_uri;

        $result = $this->doRequest($uId, $uri, 'GET', $params);

        return $result;
    }

    function getBookmarkDetails($uId, $params){
        $request_uri = 'bookmarks/'.$params['bhash'];
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
        $request_uri = 'bookmarks/'.$params['bhash'].'/related/';
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        
        return $result;
    }
    
    function getBookmarkComments($uId, $params){
        $request_uri = 'bookmarks/'.$params['bhash'].'/comments/';
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        
        return $result;
    }

    function addBookmarkComment($uId, $params){
        $request_uri = 'bookmarks/'.$params['bhash'].'/comments/';
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'POST', $params);
                
        return $result;
    }
    
    function deleteBookmarkComment($uId, $params){
        $request_uri = 'bookmarks/'.$params['bhash'].'/comments/';
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'DELETE', $params);
        
        return $result;
    }


    function getRecentBookmarks($uId, $params){
        $request_uri = 'bookmarks/recent/';
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);

        return $result;
    }

    function getPopularBookmarks($uId, $params){
        $request_uri = 'bookmarks/popular/';
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        
        return $result;
    }
    
    
    function getTagBookmarks($uId, $params){
        $request_uri = 'bookmarks/tag/'.$params['tag'].'/';
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        
        return $result;
    }
    
    function getUserProfile($uId, $params){
        $request_uri = 'user/'.$params['username'].'/';
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);

        return $result;
    }

    function getUserFollowing($uId, $params){
        $request_uri = 'user/'.$params['username'].'/following/';
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        return $result;
    }

    function getUserFollowers($uId, $params){
        $request_uri = 'user/'.$params['username'].'/followers/';
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        
        return $result;
    }

    function getUserWatchlist($uId, $params){
        $request_uri = 'user/'.$params['username'].'/following/bookmarks/';
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        
        return $result;
    }
    

    function addUserWatch($uId, $params){
        $request_uri = 'user/'.$params['username'].'/following/';
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'POST', $params);
        
        return $result;
    }

    function delUserWatch($uId, $params){
        $params = array(
                'username2' => $params['username']
            );
        
        $request_uri = 'user/'.$params['username'].'/following/';
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'DELETE', $params);
        
        return $result;
    }


    function getUserBookmarks($uId, $params){
        $request_uri = 'user/'.$params['username'].'/bookmarks/';
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        
        return $result;
    }    

    function getUserTagsBookmarks($uId, $params){
        $request_uri = 'user/'.$params['username'].'/bookmarks/tag/'.$params['tags'].'/';
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        
        return $result;
    }
    
    function getUserTags($uId, $params){
        $request_uri = 'user/'.$params['username'].'/tags/';
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        
        return $result;
    }



    function renameUserTags($uId, $params){
        $request_uri = 'user/'.$params['username'].'/tags/'.$params['tag'].'/';
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
        
        $request_uri = 'user/'.$params['username'].'/bookmarks/';
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
        $request_uri = 'user/'.$params['username'].'/bookmarks/'.$params['hash'].'/';
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
        $request_uri = 'user/'.$params['username'].'/bookmarks/'.$params['bhash'].'/';
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
        $request_uri = 'user/'.$params['username'].'/bookmarks/'.$params['bhash'].'/';
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
        
        $request_uri = 'search/'.$params['range'].'/'.$params['terms'].'/';
        $uri = $this->service_url.$request_uri;
        
        $result = $this->doRequest($uId, $uri, 'GET', $params);
        return $result;
        
    }
    
}

?>
