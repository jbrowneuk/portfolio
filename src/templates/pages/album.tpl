{* Smarty template: Gallery album images layout *}

{extends file="layout/wrapper.tpl"}

{assign var=hasAlbum value=isset($album)}

{block name="page-title"}Jason Browne: Gallery{if $hasAlbum} - {$album->name}{/if}{/block}

{block name="extra-stylesheets"}
    <link href="{$styleRoot}/css/art/image-container.css" rel="stylesheet">
    <link href="{$styleRoot}/css/art/thumbnails.css" rel="stylesheet">
{/block}

{block name="breadcrumbs"}
    <li><a href="{$scriptDirectory}/art" data-back-button>Art</a></li>

    {if $hasAlbum}
        <li data-title>{$album->name}</li>
    {/if}
{/block}

{block name="page-content"}

    {if $hasAlbum}
        {include file="components/gallery/album-hero.tpl"}
    {/if}

    {include file="components/breadcrumbs.tpl"}

    {if $hasAlbum}
        {include file="components/gallery/image-container.tpl"}
    {else}

<section class="page-section text-center">
    <h1>
        <i class="las la-frown" aria-hidden="true"></i>
        This album does not exist
    </h1>
    <p>Try going <a href="{$scriptDirectory}/art">back to the gallery main page</a>.</p>
</section>

    {/if}
{/block}