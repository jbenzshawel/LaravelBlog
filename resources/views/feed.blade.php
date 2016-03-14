<?php
/**
 * Created by PhpStorm.
 * User: addison
 * Date: 3/13/16
 * Time: 5:29 PM
 */


    header("Content-Type: application/rss+xml; charset=ISO-8859-1");

    $rssfeed = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $rssfeed .= '<rss version="2.0">'. "\n";
    $rssfeed .= '<channel>'. "\n";
    $rssfeed .= '<title>Posts for addison.im blog</title>'. "\n";
    $rssfeed .= '<link>http://www.addison.im/blog</link>'. "\n";
    $rssfeed .= '<description>Latest posts for addison.im blog</description>'. "\n";
    $rssfeed .= '<language>en-us</language>'. "\n";

    foreach($posts as $post) {
        $rssfeed .= '<item>'. "\n";
        $rssfeed .= '<title>' . $post->title . '</title>'. "\n";
        $rssfeed .= '<description><![CDATA[' . $post->content . ' ]]></description>'. "\n";
        $rssfeed .= '<link>post/' . $post->id  . '/show/</link>'. "\n";
        $rssfeed .= '<pubDate>' . date("D, d M Y H:i:s O", strtotime($post->created_at)) . '</pubDate>'. "\n";
        $rssfeed .= '</item>'. "\n";
    }

    $rssfeed .= '</channel>'. "\n";
    $rssfeed .= '</rss>'. "\n";

    echo $rssfeed;