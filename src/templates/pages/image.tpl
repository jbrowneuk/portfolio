{* Smarty template: Gallery full image layout *}

{extends file="layout/wrapper.tpl"} 

{block name="page-title"}Jason Browne: Gallery{if isset($image)} - {$image['title']}{/if}{/block}

{block name="extra-stylesheets"}
    <link href="{$styleRoot}/css/art/image-preview.css" rel="stylesheet">
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