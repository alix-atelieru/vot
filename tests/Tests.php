<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
use Illuminate\Contracts\Console\Kernel;


$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

use Illuminate\Database\Eloquent\Model;
use App\Model\User;
use App\Model\SessionTokens;
use App\Model\Volunteer;
use App\Model\Voter;
use App\Model\Message;
use App\Model\Team;
use App\Functions\DT;
use Illuminate\Support\Facades\DB;

if (env('DB_DATABASE') != 'dev4a_usr_test') {
	echo "bad test database\n";
	exit;
} 

function testLogin() {
	$response = Volunteer::loginWithDict(['email' => 'aaa@aaa.ro', 'password' => '1234']);
	if (empty($response['ok'])) {
		echo "testLogin failed 1";
		return;
	} else {
		echo "testLogin success 1\n";
	}

	$response = Volunteer::loginWithDict(['email' => 'aaa@AAA.ro', 'password' => '1234']);
	if (empty($response['ok'])) {
		echo "testLogin failed 2";
		return;
	} else {
		echo "testLogin success 2\n";
	}

	$response = Volunteer::loginWithDict(['email' => 'aaa@aaa2.ro', 'password' => '1234']);
	if (!empty($response['ok'])) {
		echo "testLogin failed 3";
		return;
	} else {
		echo "testLogin success 3\n";
	}

	$response = Volunteer::loginWithDict(['email' => 'aaa@aaa.ro', 'password' => '12345']);
	if (!empty($response['ok'])) {
		echo "testLogin failed 4";
		return;
	} else {
		echo "testLogin success 4\n";
	}

	$response = Volunteer::loginWithDict(['email' => 'bbb@bbb.ro', 'password' => '1234']);
	if (!empty($response['ok'])) {
		echo "testLogin failed 6";
		return;
	} else {
		echo "testLogin success 6\n";
	}
}

//sterge tot din voters si voters_questions;
function clearVoters() {
	DB::delete("delete from voters");
	DB::delete("delete from voters_answers");
}

//todo:reseteaza mereu baza de date;
function testCreateVote() {
	$dict = ['nume' => 'n1', 'prenume' => 'p1', 'volunteer_id' => 55];
	$response = Voter::createFromDict($dict);
	if ($response['ok'] == false) {
		echo "testCreateVote1 failed\n";
	} else {
		echo "testCreateVote1 success\n";
	}

	$dict = ['nume' => 'n1', 'prenume' => 'p1', 'volunteer_id' => 50000];
	$response = Voter::createFromDict($dict);
	if ($response['ok'] == true) {
		echo "testCreateVote2 failed";
	} else {
		if ($response['error'] == 'VOLUNTEER_NOT_FOUND') {
			echo "testCreateVote2 success\n";
		} else {
			echo "testCreateVote2 failed\n";
		}
	}

	$dict = ['nume' => 'n1', 'prenume' => 'p1'];
	$response = Voter::createFromDict($dict);
	if ($response['ok'] == true) {
		echo "testCreateVote3 failed";
	} else {
		if ($response['error'] == 'MISSING_VOLUNTEER') {
			echo "testCreateVote3 success\n";
		} else {
			echo "testCreateVote3 failed\n";
		}
	}

	//tre sa testam team_id null;tre sa testam si cazul in care e inactiv;56 e inactiv;
	$dict = ['nume' => 'n1', 'prenume' => 'p1', 'volunteer_id' => 57];
	$response = Voter::createFromDict($dict);
	if ($response['ok'] == true) {
		echo "testCreateVote4 failed";
	} else {
		if ($response['error'] == 'Nu ai echipa.Datele nu au fost salvate.') {
			echo "testCreateVote4 success\n";
		} else {
			echo "testCreateVote4 failed\n";
		}
	}

	$dict = ['nume' => 'n1', 'prenume' => 'p1', 'volunteer_id' => 56];
	$response = Voter::createFromDict($dict);
	if ($response['ok'] == true) {
		echo "testCreateVote5 failed";
	} else {
		if ($response['error'] == 'VOLUNTEER_NOT_FOUND') {
			echo "testCreateVote5 success\n";
		} else {
			echo "testCreateVote5 failed\n";
		}
	}
	//poate sa verificam si ca s-au bagat datele corect?todo:lat/lng/varsta invalide?
	$dict = ['nume' => 'n1', 
			 'prenume' => 'p1', 
			 'email' => 'aa@aa.ro',
			 'telefon' => '076',
			 'varsta' => 5,
			 'sex' => 'm',
			 'tara' => 'RO',
			 'judet' => 'jud',
			 'lat' => 45,
			 'lng' => 3.66,
			 'localitate' => 'Buc',
			 'strada' => 'str',
			 'numarul' => '56',
			 'bloc' => 'bb',
			 'scara' => 'sc1',
			 'apartament' => '77',
			 'feedback' => 'fbk',
			 'nota_evaluare' => 5,
			 'location_type' => 'acasa',
			 'volunteer_id' => 55];

	$response = Voter::createFromDict($dict);
	if ($response['ok'] == true) {
		echo "testCreateVote6 success\n";
	} else {
		echo "testCreateVote6 failed\n";
	}


	$dict = ['nume' => 'n1', 
			 'prenume' => 'p1', 
			 'email' => 'aa@aa.ro',
			 'telefon' => '076',
			 'varsta' => 5,
			 'sex' => 'm',
			 'tara' => 'RO',
			 'judet' => 'jud',
			 'lat' => 'xxx',
			 'lng' => 3.66,
			 'localitate' => 'Buc',
			 'strada' => 'str',
			 'numarul' => '56',
			 'bloc' => 'bb',
			 'scara' => 'sc1',
			 'apartament' => '77',
			 'feedback' => 'fbk',
			 'nota_evaluare' => 5,
			 'location_type' => 'acasa',
			 'volunteer_id' => 55];

	$response = Voter::createFromDict($dict);
	if ($response['ok'] == true) {
		echo "testCreateVote7 failed";
	} else {
		if ($response['error'] == 'Lat. invalida') {
			echo "testCreateVote7 success\n";
		} else {
			echo "testCreateVote7 failed\n";
		}
	}

	$dict = ['nume' => 'n1', 
			 'prenume' => 'p1', 
			 'email' => 'aa@aa.ro',
			 'telefon' => '076',
			 'varsta' => '4',
			 'sex' => 'm',
			 'tara' => 'RO',
			 'judet' => 'jud',
			 'lat' => '3,4',
			 'lng' => 'xxx',
			 'localitate' => 'Buc',
			 'strada' => 'str',
			 'numarul' => '56',
			 'bloc' => 'bb',
			 'scara' => 'sc1',
			 'apartament' => '77',
			 'feedback' => 'fbk',
			 'nota_evaluare' => 5,
			 'location_type' => 'acasa',
			 'volunteer_id' => 55];

	$response = Voter::createFromDict($dict);
	if ($response['ok'] == true) {
		echo "testCreateVote8 failed";
	} else {
		if ($response['error'] == 'Lng. invalida') {
			echo "testCreateVote8 success\n";
		} else {
			echo "testCreateVote8 failed\n";
		}
	}


	$dict = ['nume' => 'n1', 
			 'prenume' => 'p1', 
			 'email' => 'aa@aa.ro',
			 'telefon' => '076',
			 'varsta' => 'xxx',
			 'sex' => 'm',
			 'tara' => 'RO',
			 'judet' => 'jud',
			 'lat' => '34',
			 'lng' => '56',
			 'localitate' => 'Buc',
			 'strada' => 'str',
			 'numarul' => '56',
			 'bloc' => 'bb',
			 'scara' => 'sc1',
			 'apartament' => '77',
			 'feedback' => 'fbk',
			 'nota_evaluare' => 5,
			 'location_type' => 'acasa',
			 'volunteer_id' => 55];

	$response = Voter::createFromDict($dict);
	if ($response['ok'] == true) {
		echo "testCreateVote9 failed";
	} else {
		if ($response['error'] == 'Varsta invalida') {
			echo "testCreateVote9 success\n";
		} else {
			echo "testCreateVote9 failed\n";
		}
	}


	$dict = ['nume' => 'n1', 
			 'prenume' => 'p1', 
			 'email' => 'aa@aa.ro',
			 'telefon' => '076',
			 'varsta' => '200',
			 'sex' => 'm',
			 'tara' => 'RO',
			 'judet' => 'jud',
			 'lat' => '34',
			 'lng' => '56',
			 'localitate' => 'Buc',
			 'strada' => 'str',
			 'numarul' => '56',
			 'bloc' => 'bb',
			 'scara' => 'sc1',
			 'apartament' => '77',
			 'feedback' => 'fbk',
			 'nota_evaluare' => 5,
			 'location_type' => 'acasa',
			 'volunteer_id' => 55];

	$response = Voter::createFromDict($dict);
	if ($response['ok'] == true) {
		echo "testCreateVote10 failed";
	} else {
		if ($response['error'] == 'Varsta invalida') {
			echo "testCreateVote10 success\n";
		} else {
			echo "testCreateVote10 failed\n";
		}
	}

}

function clearMessages() {
	DB::delete("delete from messages");
}

function testCreateMessages() {
	$messageDict = ['content' => 'aa', 'type' => 'TEAM', 'user_id' => 1];
	$messageDict['created_at'] = DT::now();
	$response = Message::createFromDict($messageDict);
	if ($response['ok']) {
		echo "testCreateMessages1 success\n";
	} else {
		echo "testCreateMessages1 failed\n";
	}

	$messageDict = ['type' => 'TEAM', 'user_id' => 1];
	$messageDict['created_at'] = DT::now();
	$response = Message::createFromDict($messageDict);
	if ($response['ok'] === false) {
		echo "testCreateMessages2 success\n";
	} else {
		echo "testCreateMessages2 failed\n";
	}

	$messageDict = ['content' => 'aa', 'user_id' => 1];
	$messageDict['created_at'] = DT::now();
	$response = Message::createFromDict($messageDict);
	if ($response['ok'] === false) {
		echo "testCreateMessages3 success\n";
	} else {
		echo "testCreateMessages3 failed\n";
	}

	$messageDict = ['content' => 'aa', 'type' => 'TEAM'];
	$messageDict['created_at'] = DT::now();
	$response = Message::createFromDict($messageDict);
	if ($response['ok'] === false) {
		echo "testCreateMessages4 success\n";
	} else {
		echo "testCreateMessages4 failed\n";
	}

	$messageDict = ['content' => 'aa', 'type' => 'TEAM', 'user_id' => 100];
	$messageDict['created_at'] = DT::now();
	$response = Message::createFromDict($messageDict);
	if ($response['ok'] === false) {
		echo "testCreateMessages5 success\n";
	} else {
		echo "testCreateMessages5 failed\n";
	}

	$messageDict = ['content' => 'aa', 'type' => 'TEAMxx', 'user_id' => 1];
	$messageDict['created_at'] = DT::now();
	$response = Message::createFromDict($messageDict);
	if ($response['ok'] === false) {
		echo "testCreateMessages6 success\n";
	} else {
		echo "testCreateMessages6 failed\n";
	}

	$messageDict = ['content' => 'aa', 'type' => 'TEAM', 'user_id' => 56];
	$messageDict['created_at'] = DT::now();
	$response = Message::createFromDict($messageDict);
	if ($response['ok'] === false) {
		echo "testCreateMessages7 success\n";
	} else {
		echo "testCreateMessages7 failed\n";
	}
}

/*
avem echipele team1,team2,team3,team4;
echipele team1 si team2 sunt la egalitate;
team3 si team4 au puncte diferite;
avem voluntarii 1,2,3,4. 1 si 2 au egalitate, 3 si 4 au nr diferit de votanti
tre facut clearVoters;
avem voluntarii 55,56,57;ordinea e desc dupa user_id
tre sa vedem ce echipe au ce membrii;
poate ar trebui sa punem 3 intr-o echipa;

13: 55,67
59: 58
60: 62,63
61: 64,65,66
pt fiecare voluntar->tre bagate activitati;
tre sa echilibram echipele
55:3 votanti;56:4 votanti
58: 4 votanti
62: 3 votanti, 63: 4 votanti
64:5, 65:5, 66:3

echipe:
	13:7, 59:0, 60:7, 61:13;

cine sunt cu 3: 55,66,62;deci 66,62,55
cu 4:67,63,58;
cu 5:65,64;

clasament global voluntari:65,64,67,63,58,66,62,55
clasament echipe: 61,60,13,59
*/

//hmm, team_id lipseste?neah, se ia din voluntar.
function addVotersForRankingTests() {
	$dict = ['nume' => 'n1', 'prenume' => 'p1', 'volunteer_id' => 55];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n2', 'prenume' => 'p2', 'volunteer_id' => 55];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n3', 'prenume' => 'p3', 'volunteer_id' => 55];
	Voter::createFromDict($dict);

	$dict = ['nume' => 'n4', 'prenume' => 'p4', 'volunteer_id' => 67];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n5', 'prenume' => 'p5', 'volunteer_id' => 67];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n6', 'prenume' => 'p6', 'volunteer_id' => 67];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n7', 'prenume' => 'p7', 'volunteer_id' => 67];
	Voter::createFromDict($dict);

	$dict = ['nume' => 'n7', 'prenume' => 'p7', 'volunteer_id' => 58];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n8', 'prenume' => 'p8', 'volunteer_id' => 58];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n9', 'prenume' => 'p9', 'volunteer_id' => 58];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n10', 'prenume' => 'p10', 'volunteer_id' => 58];
	Voter::createFromDict($dict);


	$dict = ['nume' => 'n11', 'prenume' => 'p11', 'volunteer_id' => 62];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n12', 'prenume' => 'p12', 'volunteer_id' => 62];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n13', 'prenume' => 'p13', 'volunteer_id' => 62];
	Voter::createFromDict($dict);
	
	$dict = ['nume' => 'n14', 'prenume' => 'p14', 'volunteer_id' => 63];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n15', 'prenume' => 'p15', 'volunteer_id' => 63];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n16', 'prenume' => 'p16', 'volunteer_id' => 63];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n17', 'prenume' => 'p17', 'volunteer_id' => 63];
	Voter::createFromDict($dict);

	$dict = ['nume' => 'n18', 'prenume' => 'p18', 'volunteer_id' => 64];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n19', 'prenume' => 'p19', 'volunteer_id' => 64];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n20', 'prenume' => 'p20', 'volunteer_id' => 64];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n21', 'prenume' => 'p21', 'volunteer_id' => 64];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n22', 'prenume' => 'p22', 'volunteer_id' => 64];
	Voter::createFromDict($dict);

	$dict = ['nume' => 'n23', 'prenume' => 'p23', 'volunteer_id' => 65];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n24', 'prenume' => 'p24', 'volunteer_id' => 65];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n25', 'prenume' => 'p25', 'volunteer_id' => 65];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n26', 'prenume' => 'p26', 'volunteer_id' => 65];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n27', 'prenume' => 'p27', 'volunteer_id' => 65];
	Voter::createFromDict($dict);


	$dict = ['nume' => 'n28', 'prenume' => 'p28', 'volunteer_id' => 66];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n29', 'prenume' => 'p29', 'volunteer_id' => 66];
	Voter::createFromDict($dict);
	$dict = ['nume' => 'n30', 'prenume' => 'p30', 'volunteer_id' => 66];
	Voter::createFromDict($dict);

}

function testRanks() {
	/*
	todo:testeaza datele fiecarui voluntar
	public function stats($now, $volunteerTeam)
	*/
	clearVoters();
	
	addVotersForRankingTests();
	$now = DT::now();
	$v55 = Volunteer::findActiveVolunteerByUserId(55);
	$v55Team = Team::where('user_id', $v55->team_id)->first();
	$v55StatsGot = $v55->stats($now, $v55Team);
	if ($v55StatsGot['votersCount'] != 3 || 
		$v55StatsGot['rankInTeam'] != 2 || 
		$v55StatsGot['teamRanking'] != 3 || 
		$v55StatsGot['teamVotersCount'] != 7 ||
		$v55StatsGot['globalRank'] != 8 ||
		$v55StatsGot['teamActiveVolunteersCount'] != 2 ||
		$v55StatsGot['activeVolunteersCount'] != 9) {
		echo "stats1 fail\n";
	} else {
		echo "stats1 success\n";
	}
	

	$v67 = Volunteer::findActiveVolunteerByUserId(67);
	$v67Team = Team::where('user_id', $v67->team_id)->first();
	$v67StatsGot = $v67->stats($now, $v67Team);
	//print_r($v67StatsGot);
	if ($v67StatsGot['votersCount'] != 4 || 
		$v67StatsGot['rankInTeam'] != 1 || 
		$v67StatsGot['teamRanking'] != 3 || 
		$v67StatsGot['teamVotersCount'] != 7 ||
		$v67StatsGot['globalRank'] != 3 ||
		$v67StatsGot['teamActiveVolunteersCount'] != 2 ||
		$v67StatsGot['activeVolunteersCount'] != 9) {
		echo "stats2 fail\n";
	} else {
		echo "stats2 success\n";
	}


	$v58 = Volunteer::findActiveVolunteerByUserId(58);
	$v58Team = Team::where('user_id', $v58->team_id)->first();
	$v58StatsGot = $v58->stats($now, $v58Team);
	//print_r($v67StatsGot);
	if ($v58StatsGot['votersCount'] != 4 || 
		$v58StatsGot['rankInTeam'] != 1 || 
		$v58StatsGot['teamRanking'] != 4 || 
		$v58StatsGot['teamVotersCount'] != 4 ||
		$v58StatsGot['globalRank'] != 5 ||
		$v58StatsGot['teamActiveVolunteersCount'] != 1) {
		echo "stats3 fail\n";
	} else {
		echo "stats3 success\n";
	}

	$v62 = Volunteer::findActiveVolunteerByUserId(62);
	$v62Team = Team::where('user_id', $v62->team_id)->first();
	$v62StatsGot = $v62->stats($now, $v62Team);
	if ($v62StatsGot['votersCount'] != 3 || 
		$v62StatsGot['rankInTeam'] != 2 || 
		$v62StatsGot['teamRanking'] != 2 || 
		$v62StatsGot['teamVotersCount'] != 7 ||
		$v62StatsGot['globalRank'] != 7 ||
		$v62StatsGot['teamActiveVolunteersCount'] != 2) {
		echo "stats4 fail\n";
	} else {
		echo "stats4 success\n";
	}


	$v63 = Volunteer::findActiveVolunteerByUserId(63);
	$v63Team = Team::where('user_id', $v63->team_id)->first();
	$v63StatsGot = $v63->stats($now, $v63Team);
	if ($v63StatsGot['votersCount'] != 4 || 
		$v63StatsGot['rankInTeam'] != 1 || 
		$v63StatsGot['teamRanking'] != 2 || 
		$v63StatsGot['teamVotersCount'] != 7 ||
		$v63StatsGot['globalRank'] != 4 ||
		$v63StatsGot['teamActiveVolunteersCount'] != 2) {
		echo "stats5 fail\n";
	} else {
		echo "stats5 success\n";
	}

	$v64 = Volunteer::findActiveVolunteerByUserId(64);
	$v64Team = Team::where('user_id', $v64->team_id)->first();
	$v64StatsGot = $v64->stats($now, $v64Team);
	if ($v64StatsGot['votersCount'] != 5 || 
		$v64StatsGot['rankInTeam'] != 2 || 
		$v64StatsGot['teamRanking'] != 1 || 
		$v64StatsGot['teamVotersCount'] != 13 ||
		$v64StatsGot['globalRank'] != 2 ||
		$v64StatsGot['teamActiveVolunteersCount'] != 3) {
		echo "stats6 fail\n";
	} else {
		echo "stats6 success\n";
	}


	$v65 = Volunteer::findActiveVolunteerByUserId(65);
	$v65Team = Team::where('user_id', $v65->team_id)->first();
	$v65StatsGot = $v65->stats($now, $v65Team);
	if ($v65StatsGot['votersCount'] != 5 || 
		$v65StatsGot['rankInTeam'] != 1 || 
		$v65StatsGot['teamRanking'] != 1 || 
		$v65StatsGot['teamVotersCount'] != 13 ||
		$v65StatsGot['globalRank'] != 1 ||
		$v65StatsGot['teamActiveVolunteersCount'] != 3) {
		echo "stats7 fail\n";
	} else {
		echo "stats7 success\n";
	}

	$v66 = Volunteer::findActiveVolunteerByUserId(66);
	$v66Team = Team::where('user_id', $v66->team_id)->first();
	$v66StatsGot = $v66->stats($now, $v66Team);
	if ($v66StatsGot['votersCount'] != 3 || 
		$v66StatsGot['rankInTeam'] != 3 || 
		$v66StatsGot['teamRanking'] != 1 || 
		$v66StatsGot['teamVotersCount'] != 13 ||
		$v66StatsGot['globalRank'] != 6 ||
		$v66StatsGot['teamActiveVolunteersCount'] != 3) {
		echo "stats8 fail\n";
	} else {
		echo "stats8 success\n";
	}
}

clearVoters();
testLogin();
echo "-------------------------------------------------\n";

testCreateVote();

echo "-------------------------------------------------\n";
clearMessages();
testCreateMessages();

echo "-------------------------------------------------\n";
testRanks();
?>