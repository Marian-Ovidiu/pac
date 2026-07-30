<?php

declare(strict_types=1);

namespace Pac\Core;

use WP_Error;

final class ProjectRepository
{
    public static function findPublished(int $projectId)
    {
        if ($projectId <= 0) {
            return new WP_Error('missing_project', 'Progetto non valido.');
        }

        $project = get_post($projectId);

        if (!$project || ($project->post_type ?? '') !== 'progetto' || ($project->post_status ?? '') !== 'publish') {
            return new WP_Error('project_not_found', 'Progetto non trovato.');
        }

        return $project;
    }
}
