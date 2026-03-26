<?php

namespace jbrowneuk;

final class GithubProjects
{
    /**
     * Optional test hook for supplying projects without hitting the network.
     *
     * @var (callable(): array)|null
     */
    private static $projectsProvider = null;

    /**
     * Sets an optional projects provider.
     *
     * Intended for unit tests to avoid real HTTP calls.
     */
    public static function setProjectsProvider(?callable $provider): void
    {
        self::$projectsProvider = $provider;
    }

    public static function getProjectsFromGithub(): array
    {
        if (self::$projectsProvider !== null) {
            /** @var callable(): array $provider */
            $provider = self::$projectsProvider;
            return $provider();
        }

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
