{* Smarty template: Portfolio top/home page *}

{extends file="layout/wrapper.tpl"} 

{block name="extra-head-elements"}
    <link href="{$styleRoot}/css/super-hero.css" rel="stylesheet">
    <link href="{$styleRoot}/css/home-about.css" rel="stylesheet">
    <link href="{$styleRoot}/css/home-rating-bar.css" rel="stylesheet">
{/block}

{block name="page-content"}
    {include file="components/home/super-hero.tpl"}
    {include file="components/home/software.tpl"}
    {include file="components/home/artworks.tpl"}
    {include file="components/home/about.tpl"}
{/block}