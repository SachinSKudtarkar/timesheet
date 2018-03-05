<?php

/**
 * Description of RSSFeedReader
 *
 * @author Pratik Gotmare <pratikgotmare@ocatalog.com>
 */
class RSSFeedReader {

    public function read($url) {
        $xml = $url;
        $xmlDoc = new DOMDocument();
        $xmlDoc->load($xml);

        $channel = $xmlDoc->getElementsByTagName('channel')->item(0);
        $channel_title = $channel->getElementsByTagName('title')
                        ->item(0)->childNodes->item(0)->nodeValue;
        $channel_link = $channel->getElementsByTagName('link')
                        ->item(0)->childNodes->item(0)->nodeValue;
        $channel_desc = $channel->getElementsByTagName('description')
                        ->item(0)->childNodes->item(0)->nodeValue;


        $html = "<p><a href='" . $channel_link
                . "'>" . $channel_title . "</a>";
        $html .= "<br>";
        $html .= $channel_desc . "</p>";

        $x = $xmlDoc->getElementsByTagName('item');
        for ($i = 0; $i <= 2; $i++) {
            $item_title = $x->item($i)->getElementsByTagName('title')
                            ->item(0)->childNodes->item(0)->nodeValue;
            $item_link = $x->item($i)->getElementsByTagName('link')
                            ->item(0)->childNodes->item(0)->nodeValue;
            $item_desc = $x->item($i)->getElementsByTagName('description')
                            ->item(0)->childNodes->item(0)->nodeValue;
            $html .= "<p><a href='" . $item_link
                    . "'>" . $item_title . "</a>";
            $html .= "<br>";
            $html .= $item_desc . "</p>";
        }
        return $html;
    }

}
