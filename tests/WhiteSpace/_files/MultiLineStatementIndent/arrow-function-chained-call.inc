<?php

declare(strict_types = 1);

class IntContPortal_DataGrid_QueryParams
{
    public function saveGridPreferences()
    {
        $operation = new \IntContPortal\RetryableOperation\RetryOnMysqlDeadlockOrTimeout(
            fn () => $pref->setViewName($viewName)
                ->setFilterValues($params['filterValues'])
                ->setNumRowsPerPage($blah->test($params, 'rows'))
                ->setSortByColumn($orderCol)
                ->setSortByDirection($orderDirection[0])
                ->save(),
            $this->applicationLog
        );
    }
    
}
