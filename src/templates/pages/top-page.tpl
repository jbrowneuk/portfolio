{* Smarty template: Portfolio top/home page *}

{extends file="layout/wrapper.tpl"} 

{block name="extra-stylesheets"}
    <!-- [TODO] fix urls -->
    <link href="./css/super-hero.css" rel="stylesheet">
    <link href="./css/home-about.css" rel="stylesheet">
    <link href="./css/home-rating-bar.css" rel="stylesheet">
{/block}

{block name="page-content"}
    {include file="home/super-hero.tpl"}
    {include file="home/software.tpl"}
    {include file="home/artworks.tpl"}
    {include file="home/about.tpl"}
{/block}