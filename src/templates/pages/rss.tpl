{* Smarty template: Outline for RSS feed *}
<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>Jason Browne’s Journal</title>
        <link>https://jbrowne.io</link>
        <description>Journal feed for Jason Browne’s Personal Journal</description>
        <atom:link href="https://jbrowne.io/rss/journal" rel="self" type="application/rss+xml" />

{foreach $posts as $post}
        <item>
            <title>{$post->title}</title>
            <pubDate>{$post->timestamp|date_format:DateTime::RSS}</pubDate>
            <link>https://jbrowne.io/journal/post/{$post->id}</link>
            <guid>https://jbrowne.io/journal/post/{$post->id}</guid>
            <description>{$post->content|readTime}. Tagged {$post->tags|split:' '|join:', '}</description>
        </item>
{/foreach}

    </channel>
</rss>