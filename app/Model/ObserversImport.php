<?php
namespace App\Model;
use App\Model\Judet;
use App\Model\Section;

class ObserversImport {
	public function __construct($csvPath) {
		if (!is_readable($csvPath)) {
			throw Exception("$csvPath nu exista");
		}
		$this->csvPath = $csvPath;
	}

	/*
	mai intai scoatem doar rowurile cu tipDelegat=0
	apoi importam judetele, apoi sectiile din judet, apoi observatorii;
	*/
	public function getArray() {
		$f = fopen($this->csvPath, 'r');
		if ($f === false) {
			throw new Exception('cant fopen');
		}
		
		$rows = [];
		$columns = ['judet', 'section', 'nume', 'prenume', 'cnp', 'email', 'telefon', 'sectionName', 'sectionAddress', 'observerType'];
		fgetcsv($f, 10000, ",");//sari capul de tabel
		while (($row = fgetcsv($f, 100000, ",")) !== false) {
			$rowDict = [];
			for($i = 0;$i < count($columns);$i++) {
				$rowDict[$columns[$i]] = $row[$i];
			}
			$rows[] = $rowDict;
		}
		return $rows;
	}

	//cei main sunt cu 0 la observerType;poate facem un csv de test mai intai cu aceeasi structura?
	public function getMainObservers($observers) {
		$mainObservers = [];
		foreach ($observers as $observer) {
			if ($observer['observerType'] == '0') {
				$mainObservers[] = $observer;
			}
		}

		return $mainObservers;
	}

	//ignora fieldurile goale
	/*
	a=[1,2,1,3,4,2];
	poti sa iei coloana
	*/
	public function checkDuplicatedField($observers, $field) {
		if (count($observers) <= 1) {
			return [];
		}
		$dups = [];
		$values = array_column($observers, $field);
		sort($values);
		for($i = 1;$i < count($values);$i++) {
			if ($values[$i] == $values[$i-1]) {
				if (!in_array($values[$i], $dups)) {
					$dups[] = $values[$i];
				}
			}
		}
		return $dups;
	}

	/*

	*/
	public function getUniqItemsBySingleField($list) {

	}

	/*
	aplicam cb pt fiecare elem?maybe hash+compare by hash?
	facem hash pt fiecare row si il adaugam ca cheie
	apoi tre sa comparam dupa un singur field;tot mai tre sa luam rowurile unice;

		[['a'=>1, 'b'=>2], ['a'=>10, 'b'=>5], ['a'=>1, 'b'=>7]] si a e cheia ce returnam?
		tre sa returnam doar campul [['a'=>1], ['a'=>10]];restul de campuri sunt irelevante;
		
	*/
	public function getUniqItems($observers, $fields) {

	}

	//fa judetele
	public function importJudete($observers) {
		$judete = array_unique(array_column($observers, 'judet'));
		$judete = array_values($judete);
		foreach ($judete as $judet) {
			$judetM = new Judet();
			$judetM->name = $judet;
			$judetM->save();
		}
	}

	/*
	todo: tre sa le adaugam in judetul curect
	daca nu gasim o un judet pt o sectie->da eroare;
	o combinatie de nume sectie/nume judet se poate repeta;
	vezi sa nu o bagi de mai multe ori;
	noi de fapt vrem valorile unice;daca facem select where?20k query-uri?
	*/
	public function importSections($observers) {
		$judete = Judet::all();

	}
}
?>