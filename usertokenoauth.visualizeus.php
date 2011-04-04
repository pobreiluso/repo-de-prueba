<?php

require_once( $GLOBALS['ROOT_DIR'] .'/includes/oauth-php/core/init.php');
//include_once( $GLOBALS['ROOT_DIR'] .'/includes/oauth-php/OAuthStore.php');
include_once( $GLOBALS['ROOT_DIR'] .'/includes/oauth-php/OAuthRequester.php');
//require($GLOBALS['ROOT_DIR'].'/services/usertokenservice.visualizeus.php');

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
    var $access_token_url = 'http://apivisualizeus.com/oauth/access_token';
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

    
/*    function GetDBAccessTokens($uIdentifier, $uId) {
        $usertokenservice =& ServiceFactory::getServiceInstance('UserTokenService');
        $data = $usertokenservice->GetData($uIdentifier, $uId, 'VisualizeUs');
        $unserializedata=unserialize($data['data']);
        $tokens['oauth_token']=$unserializedata['oauth_token'];
        $tokens['oauth_token_secret']=$unserializedata['oauth_token_secret'];
        
        try {
            #$VisualizeUsObj = new EpiVisualizeUs($this->consumer_key, $this->consumer_secret);
            #$VisualizeUsObj->setToken($tokens['oauth_token'], $tokens['oauth_token_secret']);            
            #$VisualizeUsInfo = $VisualizeUsObj->get_accountVerify_credentials();
            //die();
        }
        catch (Exception $e) {
            # We don't have a good session so
            
            //Borramos los datos de acceso para esa cuenta ya que los que tenemos han sido borrados de la cuenta del user.
            $usertokenservice->DisableDataProviders($uId, 'VisualizeUs');
            //die("Revoked credentials, you must to login your account to VisualizeUs again.");
            //die("NO pue");
            return false;
        }
        
        return $tokens;
    }

    function RequestLogin($consumer_key) {

        $userservice      =& ServiceFactory::getServiceInstance('UserService');
        $currentUserID = $userservice->getCurrentUserID();
        
        $getAuthTokenParams = array(
           'oauth_callback'=>'oob'
        );
        $options = array (
                'oauth_as_header' => false
        );
        
        $token = OAuthRequester::requestRequestToken($this->consumer_key, $currentUserID, $getAuthTokenParams, 'POST', $options );
        
        $callback_uri = 'http://testingss.visualizeus.com/oauth/login/';
        if (!empty($token['authorize_uri']))
        {
            // Redirect to the server, add a callback to our server
            if (strpos($token['authorize_uri'], '?'))
            {
                $uri = $token['authorize_uri'] . '&'; 
            }
            else
            {
                $uri = $token['authorize_uri'] . '?'; 
            }
            $uri .= 'oauth_token='.rawurlencode($token['token']).'&oauth_callback='.rawurlencode($callback_uri);
        }
        else
        {
            // No authorization uri, assume we are authorized, exchange request token for access token
           $uri = $callback_uri . '&oauth_token='.rawurlencode($token['token']);
        }
        //print_r($uri);
        $_SESSION['oauth_token'] = $token['token'];
        //header("Location: {$this->authorize_url}?oauth_token={$token["token"]}&oauth_consumer_key={$this->consumer_key}&user_id=1");
        //header("Location: $uri");
        //exit();
        return $uri;
    }
    
    function CallBackAuth($consumer_key) {
        $userservice      =& ServiceFactory::getServiceInstance('UserService');
        $currentUserID = $userservice->getCurrentUserID();
        #$VisualizeUsObj = new EpiVisualizeUs($this->consumer_key, $this->consumer_secret);
        #die("ASD");
        if (isset($_REQUEST['oauth_token']) 
           || (isset($_SESSION['oauth_token']) && isset($_SESSION['oauth_token_secret'])) ) {

            echo 'Signed In! Sorry, you weren\'t supposed to see this. ';
            echo 'Click <a href="/oauth/login/">here</a> to continue.';

            if( !isset($_SESSION['oauth_token']) || !isset($_SESSION['oauth_token_secret']) ) {
                // user comes from VisualizeUs
                // send token to VisualizeUs
                // make the cookies for tokens
                
                try{
                    $token = OAuthRequester::requestAccessToken($consumer_key, $_REQUEST['oauth_token'], $currentUserID);
                }catch (OAuthException $e){
                    var_dump($e);
                }
                
                $_SESSION['oauth_token'] = $token['oauth_token'];
                $_SESSION['oauth_token_secret'] = $token['oauth_token_secret'];             
                 
                // pass tokens to EpiVisualizeUs object
                #$this->VisualizeUsOauthObject->setToken($token['oauth_token'], $token['oauth_token_secret']);
                #$token->oauth_token = $_SESSION['oauth_token'];
                #$token->oauth_token_secret = $_SESSION['oauth_token_secret'];
            } else { 
                // user switched pages and came back or got here directly, stilled logged in
                // pass tokens to EpiVisualizeUs object
                //$token->oauth_token = $_SESSION['oauth_token'];
                //$token->oauth_token_secret = $_SESSION['oauth_token_secret'];
                $token['oauth_token']=$_SESSION['oauth_token'];
                $token['oauth_token_secret']=$_SESSION['oauth_token_secret'];
                $this->VisualizeUsOauthObject->setToken($token->oauth_token, $token->oauth_token_secret);
            }
        } else {
            $this->RequestLogin($consumer_key);
        }
        
        //At this point we asume the user is correctly authorized for the OAuth application, so
        // we return the VisualizeUs user's data
        $userdata = $userservice->getCurrentUser();
        $response['provider'] = 'VisualizeUs';

        $response['username'] = $userdata['username'];
        $response['name'] = $userdata['name'];
        //$response['email'] = '';
        $response['avatar'] = $userdata[''];
        $response['location'] = $userdata['uLocation'];
        $response['desc'] = $userdata['uLocation'];
        $response['url'] = (string) $xml->tumblelog['uContent'];

        $response['oauth_token'] = $token['oauth_token'];
        $response['oauth_token_secret'] = $token['oauth_token_secret'];
        $response['uIdentifier'] = $userdata['uId'];
  
        return $response;
        
    }
    
    //function SaveToken($token){
        //Almacenados en $data la version serializada de la informac
        
    //}*/
    /*function GetFriendsList($uId) {
        if ($dbtokens=$this->GetDBAccessTokens(0, $uId)) {
            try {
                $VisualizeUsObj = new EpiVisualizeUs($this->consumer_key, $this->consumer_secret);
                $VisualizeUsObj->setToken($dbtokens['oauth_token'], $dbtokens['oauth_token_secret']);
                $VisualizeUsInfo= $VisualizeUsObj->get_statusesFriends();        
            } catch(EpiOAuthException $e) {
              return false;
            }
        } else {
            return false;
        }
        return json_encode($VisualizeUsInfo);
        
    }*/
    /*
    function AutoShareThis($uId, $data) {
        //$VisualizeUsObj = new EpiVisualizeUs($this->consumer_key, $this->consumer_secret);      
        $this->VisualizeUsOauthObject = new OAuth($this->consumer_key,$this->consumer_secret,OAUTH_SIG_METHOD_HMACSHA1,OAUTH_AUTH_TYPE_FORM);
        
        if ($dbtokens=$this->GetDBAccessTokens(0, $uId)) {
            try {
                print_r($dbtokens);
                /$this->VisualizeUsOauthObject->setToken($dbtokens['oauth_token'], $dbtokens['oauth_token_secret']);
                $message = sprintf(T_("I've just found this awesome picture on %s: %s"), $GLOBALS['sitename'], $data['link']);
                //$params = array('status' => $message);
                $params = array(
                    'oauth_token' => $dbtokens['oauth_token'],
                    'type' => 'link',
                    'url'=> $data['link']
                );
                
                $params = array(
                    'oauth_token' => $dbtokens['oauth_token'],
                    'type'      => 'photo',
                    'title'     => 'Probando',
                    'source'    => $data['picture'],
                    'url'       => $data['link'],
                    'caption'      => $message,
                    'click-through-url' =>$data['link'],
                    'generator' => 'Testing VisualizeUs example'
                );
                
                //$VisualizeUsInfo = $this->VisualizeUsOauthObject->getLastResponse();
                //print_r($VisualizeUsInfo);
                //print_r($this->VisualizeUsOauthObject);
                $status=$this->VisualizeUsOauthObject->fetch($this->write_url, $params);
                //$VisualizeUsInfo = $this->VisualizeUsOauthObject->getLastResponse();
                //print_r($VisualizeUsInfo);
                //print_r($this->VisualizeUsOauthObject);
                $result=true;
            } catch(Exception $e) {
                print_r($e);
                $result=false;
                $message='revoked';
            }
        } else {
            $result=false;
            $message='dberror';
        }
        return array(
            'result'    => $result,
            'message'   => $message
        );
    }
    */
    /*function SendTo($dest, $data) {
        $VisualizeUsObj = new EpiVisualizeUs($this->consumer_key, $this->consumer_secret);
        if ($dbtokens=$this->GetDBAccessTokens(0, $uId)) {
            try {
                $VisualizeUsObj->setToken($dbtokens['oauth_token'], $dbtokens['oauth_token_secret']);
                foreach($dest as $uId => $uData) {
                    $link = $this->UrlShortener($data['link']);
                    $message='@'.$uData['username'].' '.$data['message'].': '.$link;
                    $status=$VisualizeUsObj->post_statusesUpdate(array('status' => $message));
                }
                $result=true;
            } catch(Exception $e) {
                $result=false;
                $message='revoked';
            }
        } else {
            $result=false;
            $message='dberror';
        }
        
        return array(
            'result'    => $result,
            'message'   => $message
        );
        //die($status->response);
    }*/
        
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

    
    /*function IsActiveSession($uIdentifier, $userid) {
        $VisualizeUsObj = new EpiVisualizeUs($this->consumer_key, $this->consumer_secret);
        try {
            $token = $VisualizeUsObj->getAccessToken();
            $VisualizeUsObj->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
            $VisualizeUsInfo = $VisualizeUsObj->get_accountVerify_credentials();
            return true;
        } catch(EpiOAuthException $e) {
            return false;
        }
        
    }
    
    function OfflineAccess($userid, $dbtokens) {
        $VisualizeUsObj = new EpiVisualizeUs($this->consumer_key, $this->consumer_secret);        
        $tokens = unserialize($dbtokens['data']);
        $VisualizeUsObj->setToken($tokens['oauth_token'], $tokens['oauth_token_secret']);
        $VisualizeUsInfo = $VisualizeUsObj->get_accountVerify_credentials();        
        return $VisualizeUsInfo;
    }*/
    
}

?>
