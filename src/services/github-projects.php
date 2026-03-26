<?php

namespace jbrowneuk;

final class GithubProjects
{
    public static function getProjectsFromGithub(): array
    {
        $curl = curl_init('https://api.github.com/users/jbrowneuk/repos?sort=updated');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'PHP backend for personal website/1.0');
        $result = curl_exec($curl);
        if ($result === false) {
            return [];
        }

        $obj = json_decode($result);
        if (!is_array($obj)) {
            return [];
        }

        $projects = [];
        foreach ($obj as $rawProject) {
            $parsedDate = \DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, $rawProject->updated_at);
            $project = [
                'name' => $rawProject->name,
                'description' => $rawProject->description,
                'language' => $rawProject->language,
                'license' => isset($rawProject->license) ? $rawProject->license->name : 'None',
                'url' => $rawProject->html_url,
                'archived' => $rawProject->archived,
                'updated' => $parsedDate,
            ];

            $projects[] = $project;
        }

        return $projects;
    }
}

function get_projects_from_github()
{
    return GithubProjects::getProjectsFromGithub();
}
