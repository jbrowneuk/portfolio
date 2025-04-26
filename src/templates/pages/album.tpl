{* Smarty template: Gallery album images layout *}

{extends file="layout/wrapper.tpl"} 

{block name="page-title"}Jason Browne: Gallery - {$albumName}{/block}

{block name="page-content"}
    {include file="gallery/album-hero.tpl"}
    {include file="gallery/image-container.tpl"}
{/block}
