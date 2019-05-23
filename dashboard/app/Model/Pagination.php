<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/*

*/
class Pagination {
	public static function pagesCount($itemsCount, $itemsPerPage) {
		if ($itemsPerPage == 0) {
			return 0;
		}

		return ceil($itemsCount/$itemsPerPage);
	}

	/*
	offset(1, 1) -> 0
	offset(2, 1) -> 1
	offset(3, 1) -> 2
	*/
	public static function offset($currentPage, $itemsPerPage) {
		return $itemsPerPage*($currentPage-1);
	}

	public static function getPage($requestDict) {
		$defaultPage = 1;
		if (empty($requestDict['page'])) {
			return $defaultPage;
		}

		$invalidPage = (intval($requestDict['page']) <= 0);
		if ($invalidPage) {
			return $defaultPage;
		}

		return intval($requestDict['page']);
	}

	public static function getPageFromRequest($request) {
        $currentPage = 1;
        if (!empty($request['page'])) {
            $currentPageAttempt = intval($request['page']);
            if ($currentPageAttempt <= 0) {
                $currentPage = 1;
            } else {
                $currentPage = intval($currentPageAttempt);
            }
        }
        return $currentPage;
	}
}
?>