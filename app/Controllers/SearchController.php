<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Tour;
use App\Models\Destination;

/**
 * Powers the hero search box. Returns JSON (for AJAX) with matching tours
 * and destinations based on a free-text query.
 */
class SearchController extends BaseController
{
    public function index()
    {
        $q = trim($this->request->str('q'));

        if ($q === '') {
            return $this->ok([
                'tours'        => [],
                'destinations' => [],
            ]);
        }

        $tours        = (new Tour())->search($q, 12);
        $destinations = $this->searchDestinations($q, 8);

        return $this->ok([
            'tours'        => $tours,
            'destinations' => $destinations,
            'query'        => $q,
        ]);
    }

    private function searchDestinations(string $q, int $limit): array
    {
        return \App\Core\Database::all(
            'SELECT id, title, slug, country, image FROM destinations
             WHERE title LIKE ? OR country LIKE ?
             ORDER BY rating DESC LIMIT ' . (int) $limit,
            ['%' . $q . '%', '%' . $q . '%']
        );
    }
}
