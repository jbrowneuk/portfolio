{* Smarty template: Projects page *}

{extends file="layout/wrapper.tpl"}

{block name="page-title"}Jason Browne: Code{/block}

{block name="page-content"}
    {include file="projects/hero.tpl"}
    {include file="projects/github.tpl"}
    {include file="projects/list.tpl"}
{/block}