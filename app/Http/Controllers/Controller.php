<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getPage($requestDict) {
    	if (empty($requestDict['page'])) {
    		return 1;
    	}

    	$inputPage = intval($requestDict['page']);
    	if ($inputPage <= 0) {
    		return 1;
    	}

    	return $inputPage;
    }
	
	public function getNextPageUrl($urlNoParams, $requestDict, $currentPage, $pagesCount) {
		$params = $requestDict;
		if ($currentPage < $pagesCount) {
			$params['page'] = $currentPage+1;
		} else {
			return null;
		}
		return $urlNoParams .'?' . http_build_query($params);
	}
	
	public function getPrevPageUrl($urlNoParams, $requestDict, $currentPage) {
		$params = $requestDict;
		if ($currentPage > 1) {
			$params['page'] = $currentPage-1;
		} else {
			return null;
		}
		
		return $urlNoParams . '?' . http_build_query($params);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}