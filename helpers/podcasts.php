<?php

function podcasts_feed_url($username, $root=true) {
    return ($root ? ROOT_URL : '') . '/' . FEEDS_ROOT . "/$username.rss";
}

function update_podcasts($username, $ids) {

    $subpath = podcasts_feed_url($username, false);

    $feed = new \FeedWriter\RSS2();
    $feed->setTitle("Ressources IFADEM");
    $feed->setLink(ROOT_URL . $subpath);
    $feed->setDescription( 'ressources de l\'IFADEM.' );
    $feed->setChannelElement('pubDate', date(DATE_RSS, time()));
    $feed->setChannelElement('language', 'fr');

    foreach ($ids as $_ => $id) {

        $content = get_ressource($id);
        if (!$content) { continue; }

        $mp3s = get_mp3s($id);

        $item = $feed->createNewItem();

        $item->setTitle($content['title']);
        $item->setLink(ROOT_URL . "/?id=$id"); // not a real URL
        $item->setDate((int)$content['modification_date']);
        $item->setDescription($content['description']);

        // content's PDF
        $item->addEnclosure($content['content'], $content['size'], 'application/pdf');

        foreach ($mp3s as $mp3) {
            $item->addEnclosure($mp3['url'], $mp3['size'], 'audio/mpeg');
        }

        $item->addElement('guid', $content['id'], array('isPermaLink' => 'false'));

        $feed->addItem($item);
    }

    return file_put_contents(ROOT_DIR . $subpath, $feed->generateFeed()) !== FALSE;
}

