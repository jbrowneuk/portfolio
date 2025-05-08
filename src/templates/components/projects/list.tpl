{* Smarty template: project list projects container *}

{foreach $projects as $project}
    {include file='projects/project.tpl'}
{/foreach}