<?php 
    require_once('./visualizeus/oauth/usertokenoauth.visualizeus.php');
    require_once('./visualizeus/oauth/oauth.init.php');
    $options['consumer_key']=VISUALIZEUS_CONSUMER_KEY;
    $options['consumer_secret']=VISUALIZEUS_CONSUMER_SECRET;
    $options['token']='server-provided-user-token-here';
    $options['token_secret']='server-provided-usert-token-secret-here';
    $currentUserID='uId'; // Visualizeus UserID for the tokens gave.
    
    $bhash = 'b528340eee08a827c8caa61387d79d0e'; // Bhash for the tryout.
    $username='username-here';
    
    $visualizeus = new userTokenOauthVisualizeUs($options);


if ($_GET['oauth']){
    switch ($_GET['action']){    
        
        case 'getBookmarks':
            $params=array(
                'page'=>1,
                'perpage'=>15,
                'username'=>$username
            );
            
            $res = $visualizeus->getBookmarks($currentUserID,$params);
        break;
        
        case 'getBookmarkDetails' :
            
            $params=array(
                'bhash'=>$bhash
            );
            
            $res = $visualizeus->getBookmarkDetails($currentUserID,$params);
        break;
    
        case 'getRelatedBookmarks':
        
            $params=array(
                'bhash'=>$bhash,
                'page'=>1,
                'perpage'=>15
            );
        
            $res = $visualizeus->getRelatedBookmarks($currentUserID, $params);
        break;
    
        case 'getComents':
            $params=array(
                'bhash'=>$bhash,
                'page'=>1,
                'perpage'=>15
            );
            
            $res = $visualizeus->getBookmarkComments($currentUserID, $params);
        break;
    
        
        case 'getRecent':    
            $params=array(      
                'page'=>1,
                'perpage'=>15
            );
            
            $res = $visualizeus->getRecentBookmarks($currentUserID, $params);
        break;
    
        case 'getPopular':
            $params=array(      
                'page'=>1,
                'perpage'=>15
            );
        
            $res = $visualizeus->getPopularBookmarks($currentUserID, $params);
        break;
        
        case 'addComment':
            $params=array(      
                'bhash'=>$bhash,
                'comment'=>'Probando'
            );
        
            $res = $visualizeus->addBookmarkComment($currentUserID,$params);
        break;    
        
        case 'deleteComment':
            $params=array(      
                'bhash'=>$bhash
            );
            
            $res = $visualizeus->deleteBookmarkComment($currentUserID,$params);
        break;    
        
        case 'getTagBookmarks':
            $params=array(      
                'tag'=> 'sexy',
                'page'=> 1,
                'perpage'=> 15
            );
        
            $res = $visualizeus->getTagBookmarks($currentUserID,$params);
        break;
    
        case 'getUserProfile':
            $params=array(      
                'username'=> 'kr0n'        
            );
            
            $res = $visualizeus->getUserProfile($currentUserID,$params);
        break;    
        
        case 'getFollowing':
            $params=array(      
                'username'=> 'kr0n',
                'page'=>1,
                'perpage'=>15
            );
            
            $res = $visualizeus->getUserFollowing($currentUserID,$params);
        break;
    
        
        case 'getFollowers':
            $params=array(      
                'username'=> 'kr0n',
                'page'=>1,
                'perpage'=>15
            );
            
            $res = $visualizeus->getUserFollowers($currentUserID,$params);
        break;
    
        
        case 'getWatchlist':
            $params=array(      
                'username'=> 'kr0n',
                'page'=>1,
                'perpage'=>15
            );
            
            $res = $visualizeus->getUserWatchlist($currentUserID,$params);
        break;
    
        
        case 'getUserBookmarks':
            $params=array(      
                'username'=> 'kr0n',
                'page'=>1,
                'perpage'=>15
            );
            
            $res = $visualizeus->getUserBookmarks($currentUserID, $params);
        break;
    
        
        case 'getUserTagsBookmarks':
            $params=array(      
                'username'=> 'kr0n',
                'tag'=> 'color',
                'page'=>1,
                'perpage'=>15
            );
        
            $res = $visualizeus->getUserTagsBookmarks($currentUserID,$params);
        break;
    
        
        case 'getUserTags':
            $params = array(
                'username' => 'kr0n'
            );
        
            $res = $visualizeus->getUserTags($currentUserID,$params);
        break;
    
        
        case 'renameUserTag':
            $params = array(
                'username' => $username,
                'tag' => 'test00',
                'tagNewName' => 'test01'
            );
            
            $res = $visualizeus->renameUserTags($currentUserID, $params);
        break;
    
        
        
        case 'postBookmark':    
            $params = array(
                'username'=> $username,
                'url'     => 'http://inapcache.boston.com/universal/site_graphics/blogs/bigpicture/volcano_2011/bp1.jpg',
                'title'   => 'The view from the volcanoÕs rim.',
                'description' => '11,380 feet above the ground. At 1,300 feet deep, the lava lake has created one of the wonders of the African continent.',
                'tags' => 'volcano, rim, lava',
                'seenon' => 'http://www.boston.com/bigpicture/2011/02/nyiragongo_crater_journey_to_t.html',
                'privacy' => 0,
                'unsafe' => 0,
                'action' =>'add'
            );
            
            $res = $visualizeus->postBookmark($currentUserID,$params);
        break;
    
        
        //3461136556
        
        case 'likeBookmark':
        
            $params = array(
                'username' => $username,
                'url'      => 'http://29.media.tumblr.com/tumblr_lgft53TnJ31qzate0o1_500.jpg',
                'tags'     => 'bycicle, girl, sunset',
                'bId'      => '3873924',
                'title'    => 'Girl with bycicle in sunset.',
                'description' => 'Beautiful girl with bycicle in sunset.',
                'seenon'   =>'http://birdsbiking.tumblr.com/post/3461136556',
                'bhash'    => $bhash
            );
            
            $res = $visualizeus->likeBookmark($currentUserID,$params);
        break;
    
        
        case 'deleteBookmark':
        
            $params = array(    
                'bId' => '3873924',
                'bhash' => 'a72663b7bd90d37dc11b9dc4c0ef3f15',
                'username' => $username
            );
            
            $res = $visualizeus->deleteBookmark($currentUserID,$params);
        break;
    
        
        case 'editBookmark':
            $params = array(
                'username' => $username,
                'url'      => 'http://dl.ziza.ru/other/032011/02/pics/49.jpg',
                'title'    => 'StormTrooper girl',
                'description' => 'Girl Stormtrooper in comic con',
                'tags'     => 'stormtrooper, girl, comicon, beautiful, star wars',
                'seenon'   => 'http://ziza.qip.ru/others/1299050910-fotopodborka_109_foto.html',
                'privacy'  => 0,
                'unsafe'   => 0,
                'bId'   => '3893357',
                'bhash' => 'd7693542f9a56e8311285e1254327bbf'
            );
            
            $res = $visualizeus->editBookmark($currentUserID,$params);
        break;
    
        
        case 'search':
            
            $params=array(      
                'terms' => 'cars',
                'range' => 'all',
                'page'=>1,
                'perpage'=>15
            );
            
            $res = $visualizeus->search($currentUserID,$params);
        break;
    
    }
print_r($res);
die();
}

?>