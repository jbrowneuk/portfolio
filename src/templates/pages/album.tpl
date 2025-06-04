{* Smarty template: Gallery album images layout *}

{extends file="layout/wrapper.tpl"}

{block name="page-title"}Jason Browne: Gallery{if isset($album)} - {$album['name']}{/if}{/block}

{block name="extra-stylesheets"}
    <link href="{$styleRoot}/css/art/image-container.css" rel="stylesheet">
    <link href="{$styleRoot}/css/art/thumbnails.css" rel="stylesheet">
{/block}

{block name="page-content"}
    {if isset($album)}
        {include file="components/gallery/album-hero.tpl"}
        {include file="components/gallery/breadcrumbs/album.tpl"}
        {include file="components/gallery/image-container.tpl"}
    {else}

<section class="page-section text-center">
    <h1>
        <i class="las la-frown" aria-hidden="true"></i>
        This album does not exist
    </h1>
    <p>Try going <a href="/art">back to the gallery main page</a>.</p>
</section>

    {/if}
{/block}