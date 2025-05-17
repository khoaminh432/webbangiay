<?php
class Pagination {
    public $currentPage;
    public $perPage;
    public $totalItems;
    public $totalPages;

    public function __construct($totalItems, $currentPage = 1, $perPage = 8) {
        $this->totalItems = (int)$totalItems;
        $this->perPage = (int)$perPage;
        $this->totalPages = $this->perPage > 0 ? (int)ceil($this->totalItems / $this->perPage) : 1;
        $this->currentPage = max(1, min((int)$currentPage, $this->totalPages));
    }

    public function getOffset() {
        return ($this->currentPage - 1) * $this->perPage;
    }

    public function getLimit() {
        return $this->perPage;
    }

    public function hasPrevious() {
        return $this->currentPage > 1;
    }

    public function hasNext() {
        return $this->currentPage < $this->totalPages;
    }
}
?>