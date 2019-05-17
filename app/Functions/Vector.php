<?php
namespace App\Functions;

class Vector {
	/*
	avem o lista de dictionare v=[['a'=> 1, 'b'=> 2], ['a'=>3, 'b'=>5]];fieldsToCollapse = ['a', 'b'], $collapseUnderKey = 'k'
	avem v[0]['k']['a'] si v[0]['k']['b']
	*/
	public static function collapseFields($objectsVector, $fieldsToCollapse, $collapseUnderKey, $removeFieldFromObject=true) {
		foreach ($objectsVector as &$object) {
			$object->{$collapseUnderKey} = new \stdClass();
			foreach ($fieldsToCollapse as $fieldToCollapse) {
				if (isset($object->{$fieldToCollapse})) {
					$object->{$collapseUnderKey}->{$fieldToCollapse} = $object->{$fieldToCollapse};
				} else {
					$object->{$collapseUnderKey}->{$fieldToCollapse} = null;
				}

				if ($removeFieldFromObject) {
					unset($object->{$fieldToCollapse});
				}
			}
		}
		return $objectsVector;
	}


	public static function collapseFields2($dictsVector, $fieldsToCollapse, $collapseUnderKey, $removeFieldFromObject=true) {
		foreach ($dictsVector as &$dict) {
			foreach ($fieldsToCollapse as $fieldToCollapse) {
				if (array_key_exists($fieldToCollapse, $dict)) {
					$dict[$collapseUnderKey][$fieldToCollapse] = $dict[$fieldToCollapse];
				}
			}
		}

		return $dictsVector;
	}

	public static function orderBy($array, $orderByField, $order) {
		if ($order == 'asc') {
			usort($array, function($o1, $o2) use ($orderByField) {
				if ($o1->{$orderByField} == $o2->{$orderByField}) {
					return 0;
				}

				return $o1->{$orderByField} > $o2->{$orderByField};
			});
		}

		if ($order == 'desc') {
			usort($array, function($o1, $o2) use ($orderByField) {
				if ($o1->{$orderByField} == $o2->{$orderByField}) {
					return 0;
				}

				return $o1->{$orderByField} < $o2->{$orderByField};
			});
		}

		return $array;
	}


	public static function mapArrayOfDictsByKey($array, $key) {
		$dict = array();
		foreach ($array as $element) {
			$dict[$element[$key]] = $element;
		}
		
		return $dict;
	}

	public static function mapArrayOfObjectsByKey($array, $key) {
		$dict = array();
		foreach ($array as $element) {
			//$dict[$element[$key]] = $element;
			$dict[$element->{$key}] = $element;
		}
		
		return $dict;
	}

}
?>