{* Smarty template: Admin post editor layout *}

{extends file="layout/wrapper.tpl"}

{block name="page-title"}Jason Browne: Post Editor{/block}

{block name="extra-head-elements"}
    <link href="{$styleRoot}/css/admin/post-list.css" rel="stylesheet" />
{/block}

{block name="page-content"}
    <nav class="breadcrumbs">
        <div class="container">
            <ol role="navigation">
                <li><a href="{$scriptDirectory}/"><i class="las la-home"></i></a></li>
                <li><a href="{$scriptDirectory}/editor">Editor</a></li>
            </ol>
        </div>
    </nav>

    {include file="components/editor/post-list.tpl"}

    {include file="components/pagination.tpl"}
{/block}