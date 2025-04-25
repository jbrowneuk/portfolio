<a href="/art/view/{$image.id}"
    class="thumbnail"
    data-class-large="isPromoted"
    data-class-horizontal="isPromoted">
    <img src="{$image.thumbnail}" loading="lazy" alt="{$image.title}" data-image />
    <span class="title-area">
        <span class="title-text" data-title>{$image.title}</span>
        <span class="subtitle-text" data-galleries>{$image.containingAlbums | galleryFormat}</span>
    </span>
    {if image.featured}
    <span class="featured-badge" title="Featured">
        <svg>
            <use xlink:href="#sitesheet-logo"></use>
        </svg>
    </span>
    {/if}
</a>