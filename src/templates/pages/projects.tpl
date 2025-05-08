{* Smarty template: Projects page *}

{extends file="layout/wrapper.tpl"}

{block name="page-title"}Jason Browne: Code{/block}

{block name="page-content"}
    {include file="components/projects/hero.tpl"}
    {include file="components/projects/github.tpl"}
    {include file="components/projects/list.tpl"}
{/block}