{* Smarty template: Admin post editor layout *}

<section class="page-section">
    <div class="container">
        <table style="width:100%">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Tags</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {foreach $posts as $post}
                    {assign var=published value=isset($post['published']) && $post['published'] === 1}

                    <tr>
                        <td>{$post['title']}</td>
                        <td>{$post['timestamp']|date_format}</td>
                        <td>{$post['tags']}</td>
                        <td>{$published ? 'Published' : 'Draft'}</td>
                        <td></td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</section>