{* Smarty template: Admin post editor layout *}

<section class="page-section">
    <div class="container">
        <table class="post-list">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Tags</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {foreach $posts as $post}
                    {assign var=iconName value=$post->published ? 'check' : 'times'}

                    <tr>
                        <td>{$post->title}</td>
                        <td>{$post->timestamp|date_format}</td>
                        <td>{$post->tags|join:', '}</td>
                        <td><i class="las la-{$iconName}-circle"></i></td>
                        <td></td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</section>