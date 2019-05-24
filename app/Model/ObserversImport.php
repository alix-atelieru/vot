<?php
namespace App\Model;
use App\Model\Judet;
use App\Model\Section;
use App\Functions\Vector;
use App\Functions\DT;
use Illuminate\Support\Facades\DB;

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


	public function getSectionsKeyed($observers) {
		$sections = [];
		foreach ($observers as $observer) {
			$judet = $observer['judet'];
			$sectionName = $observer['section'];
			if (empty($sections[$judet])) {
				$sections[$judet] = [];
			}

			if (empty($sections[$judet][$sectionName])) {
				$sections[$judet][$sectionName] = ['sectionName' => $observer['sectionName'], 
												   'sectionAddress' => $observer['sectionAddress'],
												   'section' => $observer['section']
												   ];
			}
		}
		return $sections;
	}

	/*
	au judet_id, noi vrem $sectionsKeyed[$judetName][$sectionNr] = $sectionId;
	*/
	public function getSectionsKeyed2($sections, $judeteMapedById) {
		$sectionsKeyed = [];
		foreach ($sections as $section) {
			$judet = $judeteMapedById[$section->judet_id];
			if (empty($judet)) {
				continue;
			}

			$sectionsKeyed[$judet->name][$section->nr] = $section->id;
		}
		return $sectionsKeyed;
	}

	/*
	intoarce un array cu (judet=>'xx', nr_sectie=>'aa', nume=>'vv', adresa=>'ccc')
	mai avem si institutie sectie
	*/
	public function getSections($observers) {
		$keyedSections = $this->getSectionsKeyed($observers);
		$sections = [];
		foreach ($keyedSections as $judetName => $judetSections) {
			foreach ($judetSections as $judetSection) {
				$sections[] = ['judet' => $judetName, 
							   'nr' => $judetSection['section'], 
							   'name' => $judetSection['sectionName'],
							   'address' => $judetSection['sectionAddress']
							   ];
			}
		}
		return $sections;
	}

	public static function undoImport() {
		DB::table('judete')->where('id', '>', 2)->delete();
		DB::table('sections')->where('id', '>', 4)->delete();
		DB::table('observers')->where('id', '>', 5)->delete();
	}

	/*
	$sections e obtinut cu getSections()
	daca un judet nu exista?dam eroare?
	judetele tre sa fi fost importate in prealabil;
	daca un judet are doar observatori inactivi?
	poate facem si update?
	*/
	public function importSections($sections, $onlyCreate) {
		DB::beginTransaction();
		$sectionsErr = [];
		try {
			$judete = Judet::all();
			$judeteMapedByName = Vector::mapArrayOfObjectsByKey($judete, 'name');
			foreach ($sections as $sectionDict) {
				if (empty($judeteMapedByName[$sectionDict['judet']]) || 
					empty($sectionDict['nr']) ||
					empty($sectionDict['address'])) {
					$sectionsErr[] = $sectionDict;
					continue;
				}

				$section = new Section();
				$section->judet_id = $judeteMapedByName[$sectionDict['judet']]->id;
				$section->nr = $sectionDict['nr'];
				$section->adress = $sectionDict['address'];
				$section->name = $sectionDict['name'];
				$section->save();
			}
			DB::commit();

			return ['ok' => true, 'sectionsErr' => $sectionsErr];
		} catch(\Exception $e) {
			DB::rollback();
			return ['ok' => false, 'error' => 'EXCEPTION'];
		}
	}

	/*
	se presupune ca sectiile si judetele au fost importate mai intai
	tre sa luam id-ul sectiei dupa (judet,nr)
	o sa ne trebuiasca judet_id si section_id, so preload sections and judete;
	tre sa verificam mai intai daca importSections a avut ok=true
	updates maybe?
	campuri observator:
		section_id, judet_id, phone, given_name, family_name, cnp, email;
		pinul nu il importam;
	tre sa gasim o sectie dupa numar si dupa judet;
	avem nevoie de $s[$judet][$nr]=$section_id;
	public function getSectionsKeyed2($sections, $judeteMapedById
	*/
	public function importObservers($observers) {
		DB::beginTransaction();
		try {
			$judete = Judet::all();
			$judeteMapedById = Vector::mapArrayOfObjectsByKey($judete, 'id');
			$judeteMapedByName = Vector::mapArrayOfObjectsByKey($judete, 'name');
			$sections = Section::all();
			$sectionsMaped = $this->getSectionsKeyed2($sections, $judeteMapedById);
			foreach ($observers as $observer) {
				//todo:exista??
				$observerCreated = new Observer();
				$observerCreated->section_id = $sectionsMaped[$observer['judet']][$observer['section']];
				$observerCreated->judet_id = $judeteMapedByName[$observer['judet']]->id;
				$observerCreated->phone = $observer['telefon'];
				$observerCreated->family_name = $observer['nume'];
				$observerCreated->given_name = $observer['prenume'];
				$observerCreated->cnp = $observer['cnp'];
				$observerCreated->email = $observer['email'];
				$observerCreated->created_at = DT::now();
				$observerCreated->save();
			}

			DB::commit();

			return ['ok' => true];
		} catch (\Exception $e) {
			DB::rollback();
			echo '</pre>';
			print_r($e);
			echo '</pre>';
			return ['ok' => false, 'error' => 'EXCEPTION'];
		}
	}

	public static function importCreate($csvFilePath) {
		$oi = new ObserversImport($csvFilePath);
		$observers = $oi->getArray();
		$observers = $oi->getMainObservers($observers);
		$oi->importJudete($observers);
		$sections = $oi->getSections($observers);
		$sectionsImportResult = $oi->importSections($sections, true);//true=create
		if ($sectionsImportResult['ok'] == false) {
			return $sectionsImportResult;
		}

		//echo '</pre>';
		//print_r($sectionsImportResult);
		return $oi->importObservers($observers);
	}

	//am putea sa verificam ca sunt toti observatorii cu 0 bagati;
	//tre verificat sa fie un singur observator principal per sectie
	public static function verifyImport($csvFilePath) {

	}

}
?>