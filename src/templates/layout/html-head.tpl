{* Smarty template: HTML <head> area *}

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{block name="page-title"}Jason Browne{/block}</title>

    <!-- Third-party dependencies -->
    <link href="{$styleRoot}/assets/thirdparty/normalize/normalize.css" rel="stylesheet">

    <!-- Base font -->
    <link href="{$styleRoot}/assets/thirdparty/nunito/nunito.css" rel="stylesheet">

    <!-- Iconography -->
    <link href="{$styleRoot}/assets/thirdparty/la/css/line-awesome.min.css?1.3.0" rel="stylesheet">

    <!-- Theme -->
    <link href="{$styleRoot}/theme/palette.css?v3.5.1" rel="stylesheet">

    <!-- Component library -->
    <link href="{$styleRoot}/theme/styles.css?v3.5.1" rel="stylesheet">

    {block name="extra-stylesheets"}{/block}
</head>