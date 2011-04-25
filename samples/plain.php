<?php 

require_once('./oauth/usertokenplain.visualizeus.php');
require_once('./oauth/plain.init.php');
$visualizeusPlain = new userTokenPlainVisualizeus(VISUALIZEUS_API_KEY,VISUALIZEUS_API_SECRET,VISUALIZEUS_API_USER);

$bhash = 'b528340eee08a827c8caa61387d79d0e';
$username = 'pobreiluso';

if ($_GET['plain']==1){
    switch ($_GET['action']){
        case 'getBookmarks':
            $params = array(
                        'username'=>$username,
                        'page'    => '1',
                        'perpage' => '15'
                    );
            $res = $visualizeusPlain->getBookmarks($params); 
        break;
        case 'getBookmarkDetails':
            $params = array(
                        'bhash'=>$bhash,
                    );
            $res = $visualizeusPlain->getBookmarkDetails($params);
        break;
        case 'getRelatedBookmarks':
            $params = array(
                        'page' =>'1',
                        'perpage' => '15',
                        'bhash'=>$bhash,
                    );
            $res = $visualizeusPlain->getRelatedBookmarks($params);
        break;
        case 'getBookmarkComments':
            $params = array(
                        'bhash'=>$bhash,
                    );
            $res = $visualizeusPlain->getBookmarkComments($params);
        break;
        case 'getRecentBookmarks':
            $params = array(
                        'page'    => '1',
                        'perpage' => '15'
                    );
            $res = $visualizeusPlain->getRecentBookmarks($params);
        break;
        case 'getPopularBookmarks':
            $params = array(
                        'page'    => '1',
                        'perpage' => '15'
                    );
            $res = $visualizeusPlain->getPopularBookmarks($params);
        break;
        case 'getTagBookmarks':
            $params = array(
                        'tag'     => 'humor',
                        'page'    => '1',
                        'perpage' => '15'
                    );
            $res = $visualizeusPlain->getTagBookmarks($params);
        break;
        case 'getUserProfile':
            $params = array(
                        'username'     => $username,
                    );
            $res = $visualizeusPlain->getUserProfile($params);
        break;
        case 'getUserFollowing':
            $params = array(
                        'username' => $username
                    );
            $res = $visualizeusPlain->getUserFollowing($params);
        break;
        case 'getUserFollowers':
            $params = array(
                        'username' => $username
                    );
            $res = $visualizeusPlain->getUserFollowers($params);
        break;
        case 'getUserWatchlist':
            $params = array(
                        'username' => $username
                    );
            $res = $visualizeusPlain->getUserWatchlist($params);
        break;
        case 'getUserBookmarks':
            $params = array(
                        'username' => $username,
                        'page'     => '1',
                        'perpage'  => '15'
                    );
            $res = $visualizeusPlain->getUserBookmarks($params);
        break;
        case 'getUserTagsBookmarks':
            $params = array(
                        'username' => $username,
                        'tag'      => 'humor',
                        'page'     => '1',
                        'perpage'  => '15'
                    );
            $res = $visualizeusPlain->getUserTagsBookmarks($params);
        break;
        case 'getUserTags':
            $params = array(
                        'username' => $username,
                        'page'     => '1',
                        'perpage'  => '15'
                    );
            $res = $visualizeusPlain->getUserTags($params);
        break;
        case 'search':
            $params = array(
                        'terms' => 'cars',
                        'range' => 'all',
                        'page'     => '1',
                        'perpage'  => '15'
                    );
            $res = $visualizeusPlain->search($params);
        break;
    }
    print_r($res);die();
}

?>