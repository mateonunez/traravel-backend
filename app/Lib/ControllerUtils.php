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
     * Parse query parameters filters
     *
     * @param array $query
     *
     * @return array
     */
    public static function getRequestFilters(array $query): array
    {
        try {
            if (!isset($query['filters'])) {
                return [];
            }

            $filters = self::getExplodedParams($query['filters']);
            $filtersArray = self::getFiltersArray($filters);

            return $filtersArray;
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
        $params = str_replace('[', '', $params);
        $params = str_replace(']', '', $params);
        $params = str_replace(' ', '', $params);
        $params = explode(',', $params);

        return $params;
    }

    /**
     * Retrieve an array of filters from a string. [filter1=value1,filter2=value2, ...]
     *
     * @param array $filters
     *
     * @return array
     */
    private static function getFiltersArray(array $filters): array
    {
        $operators = self::getOperators();

        $filtersArray = [];
        foreach ($filters as $filter) {
            foreach ($operators as $k => $v) {
                $params = explode($k, $filter);
                if (count($params) > 1) {
                    if ($params[1] == 'null') {
                        $params[1] = null;
                    }
                    $column = $params[0];
                    $operator = $v;
                    $value = $params[1];

                    $filtersArray[] = [$column, $operator, $value];
                }
            }
        }

        return $filtersArray;
    }

    /**
     * Filters operators dictionary
     *
     * @return array
     */
    private static function getOperators(): array
    {
        return [
            ':' => '=',
            '!' => '!=',
            '>' => '>',
            '<' => '<',
            '~' => 'LIKE',
            '|' => 'NOT LIKE'
        ];
    }
}
