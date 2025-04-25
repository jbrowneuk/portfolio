{* Smarty template: single post *}

<section class="page-section post">
    <article class="container">
        <header>
            <h1 data-post-title>{$post['title']}</h1>
            <time data-date datetime="{$post['post_date']|date_format:c}">
                <span data-day class="day">{$post['post_date']|date_format:j}</span>
                <span data-month-year class="month">{$post['post_date']|date_format:"M Y"}</span>
            </time>
        </header>
        {$post['content']|parsedown}
        <footer>
            <ul data-post-tags class="tags-area">
                <li><i aria-hidden="true" class="las la-tags"></i></li>
                {foreach $post['tag']|split:' ' as $tag}
                    <li class="tag-container ng-star-inserted" data-post-tag="{$tag}"><a class="tag" href="/journal/tag/{$tag}">#{$tag}</a></li>
                {/foreach}
            </ul>
        </footer>
    </article>
</section>