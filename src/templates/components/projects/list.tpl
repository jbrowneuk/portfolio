{* Smarty template: project list projects container *}

{foreach $projects as $project}
    {include file='components/projects/project.tpl'}
{/foreach}