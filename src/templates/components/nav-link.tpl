{* Smarty template: page header nav links *}
{assign var=activeClass value=$isActive == 1 ? 'active' : ''}

<li><a href="{$url}" class="{$activeClass} {$extraClasses|default}">{$title}</a></li>