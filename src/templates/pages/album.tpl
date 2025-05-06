{* Smarty template: Gallery album images layout *}

{extends file="layout/wrapper.tpl"} 

{block name="page-title"}Jason Browne: Gallery - {$album['name']}{/block}

{block name="extra-stylesheets"}
    <!-- [TODO] fix urls -->
    <link href="./css/art/image-container.css" rel="stylesheet">
    <link href="./css/art/thumbnails.css" rel="stylesheet">
{/block}

{block name="page-content"}
    {include file="gallery/album-hero.tpl"}
    {include file="gallery/image-container.tpl"}
{/block}
