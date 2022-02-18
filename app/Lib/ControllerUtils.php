<?php

namespace App\Lib;

class ControllerUtils
{
    /**
     * Parse query parameters relationships
     *
     * @param array $query
     *
     * @return array
     */
    public static function getRequestRelationships(array $query): array
    {
        try {
            if (!isset($query['relationships'])) {
                return [];
            }

            $relationships = self::getExplodedParams($query['relationships']);

            return $relationships;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Retrieve an array of relationships from a string. [relationship1,relationship2, ...]
     *
     * @param string $params
     *
     * @return array
     */
    private static function getExplodedParams(String $params): array
    {
        $params = preg_replace('/[^a-zA-Z0-9 ,]/', '', $params);
        $params = explode(',', $params);

        return $params;
    }
}
