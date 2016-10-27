<?php
namespace Parser\controllers;
   
use \Parser\models\Feed as Feed;
use \Parser\models\Item as Item;
        
class MainController
{
    private $twig;
    
    function __construct() {
        $loader = new \Twig_Loader_Filesystem('src/views');
        $this->twig = new \Twig_Environment($loader);
    }
    
    function launchApp() {
        $page = isset($_GET['page']) ? $_GET['page'] : 'index';
        $actionName = "action" . ucfirst($page); 
        $this->$actionName();
    }
    
    function actionIndex() {
        $feed = new Feed;
        $feeds = $feed->selectDistinctColumn('category');
        echo $this->twig->render('index.views.php', array('feeds' => $feeds));
    }
    
    function actionCategory() {
        if ( !isset($_GET['name']) ) {
            echo 'category name not set!';
            die;
        }
        
        $category = $_GET['name'];
        
        $feed = new Feed;
        $feeds = $feed->findBy(['category' => $category]);
        
        foreach ($feeds as $x => $feed) {
            $item = new Item;
            $feeds[$x]['articles_count'] = $item->countBy(['feed_id' => $feed['id']]);
            $feeds[$x]['recent'] = $item->findByMostRecent(['feed_id' => $feed['id']],
                                                           'published')[0];
        }
        
        echo $this->twig->render('category.views.php', array('feeds' => $feeds));

    }
}