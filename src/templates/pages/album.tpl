{* Smarty template: Gallery album images layout *}

{extends file="layout/wrapper.tpl"} 

{block name="page-title"}Jason Browne: Gallery - {$album['name']}{/block}

{block name="extra-stylesheets"}
    <link href="{$styleRoot}/css/art/image-container.css" rel="stylesheet">
    <link href="{$styleRoot}/css/art/thumbnails.css" rel="stylesheet">
{/block}

{block name="page-content"}
    {include file="components/gallery/album-hero.tpl"}
    {include file="components/gallery/image-container.tpl"}
{/block}
