<?php
// Oauth PHP class for visualizeus API interaction.
require_once( $GLOBALS['ROOT_DIR'] .'/includes/oauth-php/core/init.php');
include_once( $GLOBALS['ROOT_DIR'] .'/includes/oauth-php/OAuthStore.php');
include_once( $GLOBALS['ROOT_DIR'] .'/includes/oauth-php/OAuthRequester.php');

class userTokenVisualizeUs {
    var $consumer_key;
    var $consumer_secret;
    var $login_url;       // VisualizeUs url for require login permission
    var $request_token;   // VisualizeUs Request Token
    var $access_token;    // VisualizeUs Access token array(oauth_token, oauth_token_secret)
    var $oauth_token;
    var $oauth_token_secret;
    var $username;
    
    var $service_url = 'http://api.visualizeus.com/v2/';
    var $request_token_url = 'http://api.visualizeus.com/oauth/request_token';
    var $authorize_url = 'http://api.visualizeus.com/oauth/authorize';
    var $access_token_url = 'http://apivisualizeus.com/oauth/access_token';
    var $authenticate_url = 'http://api.visualizeus.com/api/authenticate';
    var $read_url = 'http://api.visualizeus.com/api/read';
    var $write_url = 'http://api.visualizeus.com/api/write';
    
        
    var $VisualizeUsOauthObject;
    
    function __construct($consumer_key, $consumer_secret) {
        //$this->consumer_key = $GLOBALS['VisualizeUsConsumerKey'];
        //$this->consumer_secret = $GLOBALS['VisualizeUsConsumerSecret'];
        $this->consumer_key = $consumer_key;
        $this->consumer_secret = $consumer_secret;
        $this->VisualizeUsOauthObject = new OAuth($this->consumer_key,$this->consumer_secret,OAUTH_SIG_METHOD_HMACSHA1,OAUTH_AUTH_TYPE_URI);
        //$this->VisualizeUsOauthObject->enableDebug();
    }
    
    function createHashVerification($username){
        $hash = md5($this->consumer_secret.$username.$this->consumer_key);
        return $hash;
    }
    
    function getBookmarks($username, $uId){
        $hash = $this->createHashVerification($username);
        $params = array(
            'username' => $username,
            'hash'     => $hash,
            'page'     => $page,
            'perpage'  => $perpage
        );
        
        $request_uri = 'user/pobreiluso/bookmarks/';
        $parameters='?username='.$params['username'].'&hash='.$params['hash'].'&apikey='.$this->consumer_key.'&page='.$params['page'].'&perpage='.$params['perpage'];
        $uri = $this->service_url.$request_uri.$parameters;
        $res = `wget {$uri}`;
        //$request_uri = 'http://api.visualizeus.com/';
        //$req = new OAuthRequester($this->service_url.$request_uri, 'GET', $params);
        //$result = $req->doRequest($uId);
        
        return $res;
    }

    function getBookmarkDetails($uId, $bhash){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
                'page' => 3,
                'perpage' => 14
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'bookmarks/'.$bhash;
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'GET', $params);
        $result = $req->doRequest($uId);
        return $result;
    }
    
    function getRelatedBookmarks($uId, $bhash){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
                'page' => 3,
                'perpage' => 14
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'bookmarks/'.$bhash.'/related/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'GET', $params);
        $result = $req->doRequest($uId);
        return $result;
    }
    
    function getBookmarkComments($uId, $bhash){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
                'page' => 3,
                'perpage' => 14
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'bookmarks/'.$bhash.'/comments/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'GET', $params);
        $result = $req->doRequest($uId);
        return $result;
    }

    function addBookmarkComment($uId, $bhash, $comment){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
                'comment' => $comment,
                'perpage' => 14
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'bookmarks/'.$bhash.'/comments/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'POST', $params);
        $result = $req->doRequest($uId);
        return $result;
    }
    
    function deleteBookmarkComment($uId, $bhash, $commentId){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
                'commentId' => $commentId,
                'perpage' => 14
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'bookmarks/'.$bhash.'/comments/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'DELETE', $params);
        $result = $req->doRequest($uId);
        return $result;
    }


    function getRecentBookmarks($uId){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'bookmarks/recent/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'GET', $params);
        $result = $req->doRequest($uId);
        return $result;
    }

    function getPopularBookmarks($uId){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'bookmarks/recent/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'GET', $params);
        $result = $req->doRequest($uId);
        return $result;
    }
    
    
    function getTagBookmarks($uId, $tag){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'bookmarks/tag/'.$tag.'/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'GET', $params);
        $result = $req->doRequest($uId);
        return $result;
    }
    
    function getUserProfile($uId, $username){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$username.'/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'GET', $params);
        $result = $req->doRequest($uId);
        return $result;
    }

    function getUserFollowing($uId, $username){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$username.'/following/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'GET', $params);
        $result = $req->doRequest($uId);
        return $result;
    }

    function getUserFollowers($uId, $username){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$username.'/followers/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'GET', $params);
        $result = $req->doRequest($uId);
        return $result;
    }

    function getUserWatchlist($uId, $username){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$username.'/following/bookmarks/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'GET', $params);
        $result = $req->doRequest($uId);
        return $result;
    }
    

    function addUserWatch($uId, $username){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14,
                'username2' => $username
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$username.'/following/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'POST', $params);
        $result = $req->doRequest($uId);
        return $result;
    }

    function delUserWatch($uId, $username){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14,
                'username2' => $username
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$username.'/following/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'DELETE', $params);
        $result = $req->doRequest($uId);
        return $result;
    }


    function getUserBookmarks($uId, $username){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$username.'/bookmarks/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'GET', $params);
        $result = $req->doRequest($uId);
        return $result;
    }    

    function getUserTagsBookmarks($uId, $username, $tags){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14,
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$username.'/bookmarks/tag/'.$tags.'/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'GET', $params);
        $result = $req->doRequest($uId);
        return $result;
    }
    
    function getUserTags($uId, $username){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$username.'/tags/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'GET', $params);
        $result = $req->doRequest($uId);
        return $result;
    }



    function renameUserTags($uId, $username, $tag, $tagNewName){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14,
                'tagNewName' => $tagNewName
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$username.'/tags/'.$tag.'/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'PUT', $params);
        $result = $req->doRequest($uId);
        return $result;
    }
    
    function postBookmark($uId, $username, $url, $title, $description, $tags, $refUrl, $privacy, $unsafe, $action){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14,
                'tagNewName' => $tagNewName,
                'url' => $url,
                'title' => $title,
                'description' => $description,
                'tags' => $tags,
                'refUrl' => $refUrl,
                'privacy' => $privacy,
                'unsafe' => $unsafe,
                'action' => $action,
                'hash' => $hash
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$username.'/bookmarks/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'POST', $params);
        $result = $req->doRequest($uId);
        return $result;
    }
    
    function editBookmark($uId, $username, $bId, $bhash, $url, $title, $description, $tags, $refUrl, $privacy, $unsafe){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'url' => $url,
                'title' => $title,
                'description' => $description,
                'tags' => $tags,
                'refUrl' => $refUrl,
                'privacy' => $privacy,
                'unsafe' => $unsafe,
                'hash' => $bhash,
                'id' => $bId
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$username.'/bookmarks/'.$bhash.'/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'PUT', $params);
        $result = $req->doRequest($uId);
        return $result;
    }
    
    function likeBookmark($uId, $username, $bhash, $bId, $url, $title, $description, $tags, $refUrl, $privacy, $unsafe, $action){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
            //    'commentId' => $commentId,
                'page' => 3,
                'perpage' => 14,
                'tagNewName' => $tagNewName,
                'url' => $url,
                'title' => $title,
                'description' => $description,
                'tags' => $tags,
                'refUrl' => $refUrl,
                'privacy' => $privacy,
                'unsafe' => $unsafe,
                'action' => $action,
                'hash' => $hash
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$username.'/bookmarks/a72663b7bd90d37dc11b9dc4c0ef3f15/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'POST', $params);
        $result = $req->doRequest($uId);
        return $result;
    }
    

    function deleteBookmark($uId, $username,$bId,  $bhash){
        $params = array(
            //'oauth_token' => $_SESSION['oauth_token'],
            //'redirect_url' => '/pobreiluso/',
            //'destination_url' => '/pobreiluso/'
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
            );
        //$request_uri = 'bookmarks/0f2e6aebf0510e6444ca81dce8aa91ff/related/';
        $request_uri = 'user/'.$username.'/bookmarks/a72663b7bd90d37dc11b9dc4c0ef3f15/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'DELETE', $params);
        $result = $req->doRequest($uId);
        return $result;
    }

    function search($uId,$terms,$range='all'){
        $params =array (
            'page'=> 1,
            'perpage'=>27,
            'range'=>$range,
            'terms'=>$terms,
        );
        
        $request_uri = 'search/'.$range.'/'.$terms.'/';
        //$request_uri = 'http://api.visualizeus.com/';
        $req = new OAuthRequester($this->service_url.$request_uri, 'GET', $params);
        $result = $req->doRequest($uId);
        return $result;
        
    }
    
}

?>
