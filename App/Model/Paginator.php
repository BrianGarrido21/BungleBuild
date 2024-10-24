<?php

interface Paginator {

    public function getPaginatedResults($itemsPerPage, $currentPage);
    
    public function getTotalItems();
    
    public function getTotalPages($itemsPerPage);
    
    public function getCurrentPage();
    
    public function getItemsPerPage();
    
    public function hasPreviousPage($currentPage);
    
    public function hasNextPage($currentPage, $itemsPerPage);
}