{* Smarty template *}
{* Post list page layout *}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Document</title>
</head>
<body>
    {foreach $posts as $post}
        <section>
            <h1>{$post['title']}</h1>
            <div>{$post['content']}</div>
        </section>
    {/foreach}
</body>
</html>

