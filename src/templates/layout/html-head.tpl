{* Smarty template: HTML <head> area *}

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>{block name="page-title"}Jason Browne{/block}</title>

  <!-- [TODO] fix urls to stylesheets -->
  <!-- Third-party dependencies -->
  <link href="{$styleDirectory}/jblog/src/assets/thirdparty/normalize/normalize.css" rel="stylesheet">

  <!-- Base font -->
  <link href="{$styleDirectory}/jblog/src/assets/thirdparty/nunito/nunito.css" rel="stylesheet">

  <!-- Iconography -->
  <link href="{$styleDirectory}/jblog/src/assets/thirdparty/la/css/line-awesome.min.css?1.3.0" rel="stylesheet">

  <!-- Theme -->
  <link href="{$styleDirectory}/style-bundle/palette.css?v3.1.1" rel="stylesheet">

  <!-- Component library -->
  <link href="{$styleDirectory}/style-bundle/styles.css?v3.1.1" rel="stylesheet" />

  {block name="extra-stylesheets"}{/block}
</head>