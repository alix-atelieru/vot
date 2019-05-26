@include('national/header')
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css'>
<?php
$db = DB::connection()->getPdo(); 
$query_judete = "SELECT * FROM judete"; //persons WHERE id = :userid
$judete = $db->prepare($query_judete);
$judete->execute(); //array(':userid' => $userID )
$judete = $judete->fetchAll(PDO::FETCH_ASSOC);
$judet_get = (!isset($_GET['judet'])) ? 'toate' : $_GET['judet'];

?>
<form action='' method='GET'>
    		<div class='row'>
    			<div class='col-sm-4'>
    			<select name='judet' class='form-control' >
    				<option disabled selected>Selecteaza judet</option>
    				<option value='toate'>Toate</option>
    				<?php foreach($judete as $judet){
    					?>
    					<option value='<?php echo $judet['id']; ?>' <?php if (isset($judet_get)) if ($judet_get==$judet['id']) echo " selected='selected' ";?>><?php echo $judet['name']; ?></option>
    					<?php
    				} ?>
    			</select>
    			</div>
    			<div class='col-sm-8'>
    				<input type='submit' class='btn btn-primary' value='Trimite' name='submit'>
    			</div>
    		</div>
    	</form>
<?php
//print_r($totalVotesCount);
//print_r($sections);
$da1 = 0;
$nu1 = 0;
$da2 = 0;
$nu2 = 0;

if($judet_get === 'toate'){
	$da1 = $totalVotesCount->ref1_5_votes;
	$nu1 = $totalVotesCount->ref1_6_votes;
	$da2 = $totalVotesCount->ref2_5_votes;
	$nu2 = $totalVotesCount->ref2_6_votes;
}else{
	$query_ref_sections = "SELECT SUM(ref1_5) as ref1_5, SUM(ref1_6) as ref1_6, SUM(ref2_5) as ref2_5, SUM(ref2_6) as ref2_6 FROM `sections` WHERE judet_id=:judet_id"; //persons WHERE id = :userid
	$query_ref_sections = $db->prepare($query_ref_sections);
	$query_ref_sections->execute(array(':judet_id' => $judet_get)); //array(':userid' => $userID )
	$query_ref_sections = $query_ref_sections->fetchAll(PDO::FETCH_ASSOC);
	
	$da1 = $query_ref_sections[0]['ref1_5'];
	$nu1 = $query_ref_sections[0]['ref1_6'];
	$da2 = $query_ref_sections[0]['ref2_5'];
	$nu2 = $query_ref_sections[0]['ref2_6'];
}
if(empty($da1)){
	die('nu exista informatii');
}
if(empty($nu1)){
	die('nu exista informatii');
}
if(empty($da2)){
	die('nu exista informatii');
}
if(empty($nu2)){
	die('nu exista informatii');
}
$da1_procent = number_format((100 * $da1) / ($da1 + $nu1), 2);
$nu1_procent = number_format((100 * $nu1) / ($da1 + $nu1), 2);
$da2_procent = number_format((100 * $da2) / ($da2 + $nu2), 2);
$nu2_procent = number_format((100 * $nu2) / ($da2 + $nu2), 2);

$rezultate_ref1 = array(array('NU', intval($nu1)), array('DA', intval($da1)));
$rezultate_ref2 = array(array('NU', intval($nu2)), array('DA', intval($da2)));

$rezultate_ref_all = array($rezultate_ref1, $rezultate_ref2);
$procentaje_referendum = array(array(floatval($nu1_procent), floatval($da1_procent)), array( floatval($nu2_procent), floatval($da2_procent)));


//procentaj grafic 1 da => (100 * $totalVotesCount->ref1_5_votes) / ($totalVotesCount->ref1_5_votes + $totalVotesCount->ref1_6_votes)
//procentaj grafic 1 nu => (100 * $totalVotesCount->ref1_6_votes) / ($totalVotesCount->ref1_5_votes + $totalVotesCount->ref1_6_votes)
//procentaj grafic 1 => (100 * $sections['ref1SectionsCount']) / $sections['count']
//procentaj grafic 2 => (100 * $sections['ref2SectionsCount']) / $sections['count']

$procentaj_total_g1 = number_format((100 * $sections['ref1SectionsCount']) / $sections['count'], 2);
$procentaj_total_g2 = number_format((100 * $sections['ref2SectionsCount']) / $sections['count'], 2);
?>

{{--{{$totalVotesCount->ref1_5_votes}} =nr de voturi da la intrebarea 1;
{{$totalVotesCount->ref1_6_votes}}=nr de voturi nu la intrebarea 1;
{{$totalVotesCount->ref2_5_votes}}=nr de voturi da la intrebarea 2;
{{$totalVotesCount->ref2_6_votes}}=nr de voturi nu la intrebarea 2;

{{$sections['ref1SectionsCount']}} = numarul de sectii care au completat date pt intrebarea 1
{{$sections['ref2SectionsCount']}} = numarul de sectii care au completat date pt intrebarea 2
{{$sections['count']}} =cate sectii sunt;--}}
	

    	<div class='charts_wrapper'>
	        <canvas class="chart" id='chart_0'></canvas>
	        <h2>Procentaj date completate intrebarea 1: <span class='badge badge-primary'><?php echo $procentaj_total_g1; ?>%</span></h2>
	        <canvas class="chart" id='chart_1'></canvas>
	        <h2>Procentaj date completate intrebarea 2: <span class='badge badge-primary'><?php echo $procentaj_total_g2; ?>%</span></h2>
        </div>
        
        
        <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js'></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0"></script>
        <script>
        var procentaje_referendum = <?php echo isset($procentaje_referendum) ? json_encode($procentaje_referendum) : "[]"; ?>;
        //console.log(procentaje_referendum)
        var labels_referendum = <?php echo isset($rezultate_ref_all) ? json_encode($rezultate_ref_all) : "[]"; ?>;
        for(var i = 0; i < procentaje_referendum.length; i++){
	            ctx = document.getElementById('chart_' + i).getContext('2d');
	            new Chart(ctx, {
	                // The type of chart we want to create
	                type : 'bar',
	
	                // The data for our dataset
	                data : {
	                    labels : labels_referendum[i],
	                    datasets : [{
	                        label : '',
	                        backgroundColor : ['#ff5151', '#32C787'],
	                        data : procentaje_referendum[i],
	                        borderWidth: 1
	                    }]
	                },
	                // Configuration options go here
	                options : {
	                	plugins: {
					      datalabels: {
					         display: true,
					         align: 'center',
					         anchor: 'center',
					         color: '#000',
					         formatter: function (value) {
					           return value + '%';
					         },
					         font: {
					           weight: 'bold',
					           size: 18,
					         }
					      }
					   },
	                	legend: {
					        display: false,
					    },
	                    scales : {
							xAxes: [{
								ticks: {
					                fontSize: 20
					            }
							}],
	                        yAxes : [{
	                            ticks : {
	                            	fontSize: 18,
	                                min : 0,
	                                max : 100,
	                                callback : function(value) {
	                                    return value + "%"
	                                }
	                            },
	                            scaleLabel : {
	                                display : true,
	                                fontSize: 18,
	                                labelString : "Procentaj"
	                            }
	                        }]
	                    }
	                }
	            });
        }
        </script>