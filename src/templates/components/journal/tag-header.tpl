{* Smarty template: post list tag header *}

{if isset($tag)}
    <nav id="tag-info" class="breadcrumbs">
        <div class="container">
            <ol role="navigation">
                <li><a href="/"><i class="las la-home"></i></a></li>
                <li><a href="/journal">Journal</a></li>
                <li><span>Posts tagged <b>{$tag}</b></span></li>
            </ol>
        </div>
    </nav>
{/if}