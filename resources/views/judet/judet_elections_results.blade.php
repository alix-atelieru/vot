@include('judet/header')
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css'>


    	<?php
        $data = array();
		$procent_completate = '';
		if(1 == 1){
						$toate_info_partide_per_judet =0;
							
						
			//$toate_info_partide_per_judet = $toate_info_partide_per_judet->fetchAll();
			
			$psd_votes = 0;
			$pnl_votes = 0;
			$usr_votes = 0;
			$proromania_votes = 0;
			$udmr_votes = 0;
			$alde_votes = 0;
			$pmp_votes = 0;
			
			$psd_votes_per = 0;
			$pnl_votes_per = 0;
			$usr_votes_per = 0;
			$proromania_votes_per = 0;
			$udmr_votes_per = 0;
			$alde_votes_per = 0;
			$pmp_votes_per = 0;
			$e = 0;
			$f = 0;
			$e_f = 0;
			
			$sectii_completate = 0;
			$total_sectii = 0;
			$psd_votes = $judetElectionTotals['psd_votes'];
			$pnl_votes = $judetElectionTotals['pnl_votes'];
			$usr_votes = $judetElectionTotals['usr_votes'];
			$proromania_votes = $judetElectionTotals['proromania_votes'];
			$udmr_votes = $judetElectionTotals['udmr_votes'];
			$alde_votes = $judetElectionTotals['alde_votes'];
			$pmp_votes = $judetElectionTotals['pmp_votes'];
			
			$e = $judetElectionTotals['e_votes'];
			$f = $judetElectionTotals['f_votes'];
					
					
			$sectii_completate = $judetElectionTotals['sections_counted'];
			$total_sectii = $judetElectionTotals['sections_counted']+ $judetElectionTotals['e_null_count'];
								
	


			$e_f = ($e ) <= 0 ? ($psd_votes + $pnl_votes + $usr_votes + $proromania_votes + $udmr_votes + $alde_votes + $pmp_votes) : $e ;
			
			if($e_f <= 0){
				die('nu exista informatii');
			}
			
			$psd_votes_per = round(((100 * $psd_votes) / $e_f), 2);
			$pnl_votes_per = round(((100 * $pnl_votes) / $e_f), 2);
			$usr_votes_per = round(((100 * $usr_votes) / $e_f), 2);
			$proromania_votes_per = round(((100 * $proromania_votes) / $e_f), 2);
			$udmr_votes_per = round(((100 * $udmr_votes) / $e_f), 2);
			$alde_votes_per = round(((100 * $alde_votes) / $e_f), 2);
			$pmp_votes_per = round(((100 * $pmp_votes) / $e_f), 2);
				
			$procent_completate = ($sectii_completate * 100) / $total_sectii;
			$data['procentaje_partide'] = array($psd_votes_per, $pnl_votes_per, $usr_votes_per, $proromania_votes_per, $udmr_votes_per, $alde_votes_per, $pmp_votes_per);
			$data['rezultate_partide'] = array(
				array('PSD', $psd_votes),
				array('PNL', $pnl_votes),
				array('USR PLUS', $usr_votes),
				array('PRO ROMÂNIA', $proromania_votes),
				array('UDMR', $udmr_votes),
				array('ALDE', $alde_votes),
				array('PMP', $pmp_votes),
			);
			$data['culori_partide'] = array(
				array('PSD', '#DD2C24', $psd_votes),
				array('PNL', '#F9E10B', $pnl_votes),
				array('USR PLUS', '#FD5100', $usr_votes),
				array('PRO ROMÂNIA', '#F3B250', $proromania_votes),
				array('UDMR', '#3A864F', $udmr_votes),
				array('ALDE', '#2190CB', $alde_votes),
				array('PMP', '#A6CE3A', $pmp_votes),
			);
			rsort($data['procentaje_partide']);
			array_multisort(array_column($data['rezultate_partide'], 1), SORT_DESC, $data['rezultate_partide']);
			array_multisort(array_column($data['culori_partide'], 2), SORT_DESC, $data['culori_partide']);
		}

        ?>
    	
    	
    	<div class='charts_wrapper'>
	        <canvas class="chart"></canvas>
        </div>
        <?php 
        if($procent_completate){
        ?>
        <h2>Au fost completate <?php echo round($procent_completate, 2); ?>%</h2>
        <?php }?>
        
        
        <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js'></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0"></script>
        <script>
        var procentaje_partide = <?php echo isset($data['procentaje_partide']) ? json_encode($data['procentaje_partide']) : "[]"; ?>;
        var labels_partide = <?php echo isset($data['rezultate_partide']) ? json_encode($data['rezultate_partide']) : "[]"; ?>;
        var culori_partide = <?php echo isset($data['culori_partide']) ? json_encode($data['culori_partide']) : "[]"; ?>;
        culori_partide = culori_partide.map(function(cul, index){
        	return cul[1]
        })
        document.querySelectorAll('.chart').forEach(function(el, index){
            ctx = el.getContext('2d');
            new Chart(ctx, {
                // The type of chart we want to create
                type : 'bar',

                // The data for our dataset
                data : {
                    labels : labels_partide,
                    datasets : [{
                        label : '',
                        backgroundColor : culori_partide,
                        data : procentaje_partide,
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
        })
        </script>
        <div class='container graph_badge' style='padding-top: 20px; padding-bottom: 20px;'>
        	<div style='font-size: 20px;'>
Total voturi: <span class='badge badge-primary'>{{ $judetElectionTotals['totalVotes'] }}</span>
</div>
<div style='font-size: 20px;'>
	PSD: <span class='badge badge-primary'>{{ $judetElectionTotals['psd_votes'] }}</span>
</div>
<div style='font-size: 20px;'>
	USR PLUS: <span class='badge badge-primary'>{{ $judetElectionTotals['usr_votes'] }}</span>
</div>
<div style='font-size: 20px;'>
	PRO ROMÂNIA: <span class='badge badge-primary'>{{ $judetElectionTotals['proromania_votes'] }}</span>
</div>
<div style='font-size: 20px;'>
	UDMR: <span class='badge badge-primary'>{{ $judetElectionTotals['udmr_votes'] }}</span>
</div>
<div style='font-size: 20px;'>
	PNL: <span class='badge badge-primary'>{{ $judetElectionTotals['pnl_votes'] }}</span>
</div>
<div style='font-size: 20px;'>
	ALDE: <span class='badge badge-primary'>{{ $judetElectionTotals['alde_votes'] }}</span>
</div>
<div style='font-size: 20px;'>
	PMP: <span class='badge badge-primary'>{{ $judetElectionTotals['pmp_votes'] }}</span>
</div>


<div style='font-size: 20px;'>
Sectii care au completat:	<span class='badge badge-primary'>
<?php 
$procent_sectii_care_au_completat = $judetElectionTotals['sections_counted'] * 100 / ($judetElectionTotals['sections_counted'] + $judetElectionTotals['e_null_count']);
echo $judetElectionTotals['sections_counted'] . ' (' . number_format($procent_sectii_care_au_completat, 3) . '%)'; ?></span>
</div>
<div style='font-size: 20px;'>
Sectii care au nu completat:	<span class='badge badge-primary'>
	<?php
$procent_sectii_care_au_NU_completat = $judetElectionTotals['e_null_count'] * 100 / ($judetElectionTotals['sections_counted'] + $judetElectionTotals['e_null_count']);
echo $judetElectionTotals['e_null_count'] . ' (' . number_format($procent_sectii_care_au_NU_completat, 3) . '%)';
?></span>
</div>
<div style='font-size: 20px;'>
e. VOTURI VALABIL EXPRIMATE (=TOTAL VOTURI - VOTURI NULE):	<span class='badge badge-primary'>{{ $judetElectionTotals['e_votes'] }}</span>
</div>
<div style='font-size: 20px;'>
f. VOTURI NULE:	<span class='badge badge-primary'>{{ $judetElectionTotals['f_votes'] }}</span>
</div>

</div>