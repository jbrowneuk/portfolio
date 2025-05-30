{* Smarty template: post list tag header *}

{if isset($tag)}
    <nav id="tag-info" class="breadcrumbs page-section background-dark">
        <span>Showing posts with the label</span>
        <span class="tag" data-tag>{$tag}</span>
        <a href="/journal">Show all posts</a>
    </nav>
{/if}