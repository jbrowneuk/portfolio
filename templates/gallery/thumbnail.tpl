{assign var=promotedClass value=$isPromoted ? 'large' : 'normal'}
{assign var=orientation value=$image['width'] > $image['height'] ? 'horizontal' : 'vertical'}

<a href="/art/view/{$image['image_id']}"
    class="thumbnail {$promotedClass} {$orientation}"
    >
    <img src="{$imageRoot}{$thumbDir}{$image['file']}" loading="lazy" alt="{$image['title']}" data-image />
    <span class="title-area">
        <span class="title-text" data-title>{$image.title}</span>
        <span class="subtitle-text" data-galleries>{$image['albums']|albumNames}</span>
    </span>
    {if isset($image['featured'])}
    <span class="featured-badge" title="Featured">
        <svg>
            <use xlink:href="#sitesheet-logo"></use>
        </svg>
    </span>
    {/if}
</a>