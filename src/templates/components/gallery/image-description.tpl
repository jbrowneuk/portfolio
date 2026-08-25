{* Smarty template: Gallery full image descriptions *}

<div class="page-section post" data-post-loaded>
    <div class="container">
        <header>
            <h2 data-title>{$image->title}</h2>
            <time data-date datetime="{$image->timestamp|date_format:c}">
                <span data-day class="day">{$image->timestamp|date_format:j}</span>
                <span data-month-year class="month">{$image->timestamp|date_format:"M Y"}</span>
            </time>
        </header>
        {$image->description|parsedown}
        <footer>
            <menu data-post-tags class="tags-area">
                <li><i aria-hidden="true" class="las la-tags"></i></li>
                {foreach $image->albums as $album}
                    <li class="tag-container" data-post-tag="{$album->id}">
                        <a class="tag" href="{$scriptDirectory}/art/album/{$album->id}">#{$album->name}</a>
                    </li>
                {/foreach}
            </menu>
        </footer>
    </div>
</div>