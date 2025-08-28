{* Smarty template: Projects page *}

{extends file="layout/wrapper.tpl"}

{block name="page-title"}Jason Browne: Code{/block}

{block name="breadcrumbs"}
    <li data-title>Projects</li>
{/block}

{block name="page-content"}
    {include file="components/breadcrumbs.tpl"}
    {include file="components/projects/hero.tpl"}
    {include file="components/projects/github.tpl"}
    {include file="components/projects/list.tpl"}
{/block}