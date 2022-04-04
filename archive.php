<?php

# Import old likes from twitter archive.

$root = __DIR__;
require_once "$root/setup.php";

dbug('Downloading archived twitter favorites.');
set_time_limit(0);

$problems = check_setup();
if (!empty($problems)) {
	echo "There are some issues you’ll need to sort out before you can start using\nStarmonger:\n\n";
	foreach ($problems as $problem) {
		echo "* $problem\n";
	}
	exit;
}

$stop_id = 1406511425233645569; 
$total = 0; // 4294 
$sorted = array();

$content = file_get_contents('./like.json');
$favs = json_decode($content);

$query = $db->query("
    SELECT id
    FROM twitter_favorite 
");

$existing_ids = $query->fetchAll(PDO::FETCH_COLUMN);

foreach($favs as $k => $v) {
    $tweet_id = $v->like->tweetId;
    if ($tweet_id >= $stop_id) {
        continue;
    }

    $total += 1;
    if (in_array($tweet_id, $existing_ids)) {
        continue;
    }

    $sorted[$tweet_id] = $v->like; 
}

ksort($sorted);

dbug("There are " . count(array_keys($sorted)) . "/" . $total . " favorites to be worked on.");

foreach($sorted as $id => $info) {
    $result = save_archived($info);
    sleep(1);
}

?>
