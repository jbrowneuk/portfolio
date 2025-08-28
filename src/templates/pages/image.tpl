{* Smarty template: Gallery full image layout *}

{extends file="layout/wrapper.tpl"} 

{block name="page-title"}Jason Browne: Gallery{if isset($image)} - {$image->title}{/if}{/block}

{block name="extra-head-elements"}
    <link href="{$styleRoot}/css/art/image-preview.css" rel="stylesheet">

    {if isset($image)}
        <meta property="og:title" content="{$image->title}" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="{$scriptDirectory}/art/view/{$image->id}" />
        <meta property="og:image" content="{$imageRoot}{$thumbDir}{$image->filename}" />
    {/if}
{/block}

{block name="page-content"}
    {if isset($image)}
        {include file="components/gallery/image-preview.tpl"}
        {include file="components/gallery/image-description.tpl"}
    {else}

<section class="page-section text-center">
    <h1>
        <i class="las la-frown" aria-hidden="true"></i>
        This image does not exist
    </h1>
    <p>Try going <a href="{$scriptDirectory}/art">back to the gallery</a>.</p>
</section>

    {/if}
{/block}