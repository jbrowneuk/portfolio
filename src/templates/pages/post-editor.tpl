{* Smarty template: Admin post editor layout *}

{extends file="layout/wrapper.tpl"}

{block name="page-title"}Jason Browne: Editing {$post['title']|default:'a new post'}{/block}

{block name="extra-stylesheets"}
    {* [TODO] use {$styleRoot} *}
    <link href="{$scriptDirectory}/css/admin/post-editor.css" rel="stylesheet">
{/block}

{block name="page-content"}
    <nav class="breadcrumbs">
        <div class="container">
            <ol role="navigation">
                <li><a href="{$scriptDirectory}/"><i class="las la-home"></i></a></li>
                <li><a href="{$scriptDirectory}/editor" data-back-button>Editor</a></li>
                {if isset($post)}
                    <li data-title>{$post['title']}</li>
                {else}
                    <li data-title>New post</li>
                {/if}
            </ol>
        </div>
    </nav>

    <section class="page-section">
        <div class="container">
            <form action="{$scriptDirectory}/editor/post" method="post" id="post-editor">
                <div class="form-row">
                    <input type="text" class="post-title" name="title" placeholder="Post title"
                        value="{$post['title']|default:''}" required />
                    {if isset($titleError)}
                        <div class="error">Title is required</div>
                    {/if}
                </div>
                <div class="form-row">
                    <textarea class="post-content" name="content" placeholder="Post content"
                        required>{$post['content']|default:''}</textarea>
                    <div>Markdown supported</div>
                    {if isset($contentError)}
                        <div class="error">Content is required</div>
                    {/if}
                </div>
                <div class="form-row">
                    <input type="text" class="post-id" name="post_id" placeholder="Unique ID"
                        value="{$post['post_id']|default:''}" required />
                    {if isset($idError)}
                        <div class="error">ID is required</div>
                    {/if}
                    <div>Alphanumeric characters and dashes only</div>
                </div>
                <div class="form-row">
                    <input type="text" class="post-tags" name="tags" placeholder="Tags"
                        value="{$post['tags']|default:''}" />
                    <div>Separate tags with spaces</div>
                </div>

                <button class="primary" type="submit">Save</button> <button class="neutral" type="reset">Reset</button>
            </form>
        </div>
    </section>
{/block}