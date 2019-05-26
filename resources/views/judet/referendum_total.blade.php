@include('judet/header')

<?php
$da1 = 0;
$nu1 = 0;
$da2 = 0;
$nu2 = 0;
$da1 = $totalVotesCount->ref1_5_votes;
$nu1 = $totalVotesCount->ref1_6_votes;
$da2 = $totalVotesCount->ref2_5_votes;
$nu2 = $totalVotesCount->ref2_6_votes;
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

$procentaj_total_g1 = number_format((100 * $sections['ref1SectionsCount']) / $sections['count'], 2);
$procentaj_total_g2 = number_format((100 * $sections['ref2SectionsCount']) / $sections['count'], 2);
?>


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