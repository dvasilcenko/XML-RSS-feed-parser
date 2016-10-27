<?php
require_once 'vendor/autoload.php';

use \Parser\helpers\Logger as Logger;
use \Parser\models\Feed as Feed;
use \Parser\models\Item as Item;

use core\database;

if (empty($argv[1]) || empty($argv[2])) {
    print "Please use:\nphp parser.php http://example.com category_name\n";
    die;
} elseif (filter_var($argv[1], FILTER_VALIDATE_URL)){
    $xml_url = $argv[1];
    $category = $argv[2];
} else {
    echo "Url must be this format http://example.com\n";
    die();
}

@$xml = simplexml_load_file($xml_url);    
if ( !isset($xml->channel) ) {
    die("Url does not contain valid rss\n");
}
$logger = new Logger;
$logger->log('find an existant feed');

$feedModel = new Feed;
$feed = $feedModel->findOneBy(['url' => $xml_url]);
if ( isset($feed['id']) ) {
    $logger->log('delete old feed and items');
    $item = new Item;
    $item->deleteBy(['feed_id' => $feed['id']]);
    $feedModel->deleteBy(['id' => $feed['id']]);
}

$logger->log('create and parse a feed');
$title = $xml->channel->title;
$last_update = $xml->channel->lastBuildDate;
$last_update = date ("Y-m-d H:i:s", strtotime($last_update));

$feedModel->insertIntoBy(['url' => $xml_url,
                          'title' => $title,
                          'last_update' => $last_update,
                          'category' => $category]);
$feed_id = $feedModel->lastInsertId();

$logger->log('parse and insert items');
$item_count = 0;
foreach($xml->channel->item as $val) {
    $item_title = $val->title;
    $item_link = $val->link;
    $item_description = $val->description;
    $item_published = $val->pubDate;
    $item_published = date ("Y-m-d H:i:s", strtotime($item_published));
    
    $item = new Item;
    $item->insertIntoBy(['feed_id' => $feed_id,
                         'title' => $item_title,
                         'link' => $item_link,
                         'description' => $item_description,
                         'published' => $item_published]);
    $item_count++;
}
$logger->log("inserted $item_count items");

?>