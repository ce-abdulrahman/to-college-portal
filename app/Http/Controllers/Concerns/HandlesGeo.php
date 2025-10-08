<?php

namespace App\Http\Controllers\Concerns;

trait HandlesGeo
{
    private function resolveGeojsonInput(?string $text, $file): array
    {
        $raw = null;
        if ($file) {
            $raw = @file_get_contents($file->getRealPath());
        } elseif ($text !== null) {
            $raw = $text;
        }
        if (!is_string($raw) || trim($raw) === '') {
            return [];
        }

        $raw = $this->stripUtf8Bom(trim($raw));
        $json = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'geojson' => 'GeoJSON نادروستە: ' . json_last_error_msg(),
            ]);
        }
        if (!$this->looksLikeGeojson($json)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'geojson' => 'GeoJSON نادروستە. تکایە Polygon/MultiPolygon/Feature/FeatureCollection بنێرە.',
            ]);
        }
        return $this->toFeatureCollection($json);
    }

    private function looksLikeGeojson($json): bool
    {
        if (!is_array($json) || empty($json['type'])) {
            return false;
        }
        $type = $json['type'];

        if (in_array($type, ['Polygon', 'MultiPolygon'], true)) {
            return !empty($json['coordinates']) && is_array($json['coordinates']);
        }
        if ($type === 'Feature') {
            return !empty($json['geometry']) && $this->looksLikeGeojson($json['geometry']);
        }
        if ($type === 'FeatureCollection') {
            if (empty($json['features']) || !is_array($json['features'])) {
                return false;
            }
            foreach ($json['features'] as $f) {
                if (is_array($f) && !empty($f['type'])) {
                    if ($f['type'] === 'Feature' && !empty($f['geometry']) && $this->looksLikeGeojson($f['geometry'])) {
                        return true;
                    }
                    if (in_array($f['type'], ['Polygon', 'MultiPolygon'], true) && !empty($f['coordinates'])) {
                        return true;
                    }
                }
            }
            return false;
        }
        return false;
    }

    private function toFeatureCollection(array $gj): array
    {
        if ($gj['type'] === 'FeatureCollection') {
            return $gj;
        }
        if ($gj['type'] === 'Feature') {
            return ['type' => 'FeatureCollection', 'features' => [$gj]];
        }
        if (in_array($gj['type'], ['Polygon', 'MultiPolygon'], true)) {
            return [
                'type' => 'FeatureCollection',
                'features' => [
                    [
                        'type' => 'Feature',
                        'properties' => new \stdClass(),
                        'geometry' => $gj,
                    ],
                ],
            ];
        }
        return $gj;
    }

    private function stripUtf8Bom(string $s): string
    {
        return substr($s, 0, 3) === "\xEF\xBB\xBF" ? substr($s, 3) : $s;
    }
}
