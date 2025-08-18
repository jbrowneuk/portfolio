{* Smarty template: Gallery full image presenter *}

<div class="presenter container zoomed-out">
    <div class="image-area">
        <img src="{$imageRoot}{$imageDir}{$image->filename}" alt="{$image->title}" class="responsive" />
        {if $image->featured}
            <span class="featured-badge" title="Featured">
                <svg>
                    <use xlink:href="#sitesheet-logo"></use>
                </svg>
            </span>
        {/if}
    </div>
</div>