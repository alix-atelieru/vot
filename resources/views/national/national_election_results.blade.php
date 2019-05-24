@include('national/header')
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css'>
<?php $db = DB::connection()->getPdo(); 
$query_judete = "SELECT * FROM judete"; //persons WHERE id = :userid
$judete = $db->prepare($query_judete);
$judete->execute(); //array(':userid' => $userID )
$judete = $judete->fetchAll(PDO::FETCH_ASSOC);
?>
<form action='' method='GET'>
    			<div class='row'>
    				<div class='col-sm-3'>
		    			<select class='form-control' name='judet'>
		    				<option disabled selected>Selecteaza judet</option>
		    				<option value='toate'>Toate</option>
		    				<?php foreach($judete as $judet){
		    					?>
		    					<option value='<?php echo $judet['id']; ?>'><?php echo $judet['name']; ?></option>
		    					<?php
		    				} ?>
		    			</select>
	    			</div>
	    			<div class='col-sm-9'>
	    				<input type='submit' value='Trimite' name='submit' class='btn btn-primary'>
	    			</div>
    			</div>
    	</form>
    	<?php
        $data = array();
		$procent_completate = '';
		if(isset($_GET['submit'])){
			if(!isset($_GET['judet'])){
				return false;
			}
			if(empty($_GET['judet'])){
				return false;
			}
			if($_GET['judet'] == 'toate'){
				$query_toate_info = "SELECT * FROM sections";
				$toate_info_partide_per_judet = $db->prepare($query_toate_info);
				$toate_info_partide_per_judet->execute();
			}else{
				$query_toate_info = "SELECT * FROM sections WHERE judet_id = :judet_id";
				$toate_info_partide_per_judet = $db->prepare($query_toate_info);
				$toate_info_partide_per_judet->execute(array(':judet_id' => $_GET['judet']));
			}
			
			$toate_info_partide_per_judet = $toate_info_partide_per_judet->fetchAll();
			
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
			$total_sectii = count($toate_info_partide_per_judet);
			
			foreach ($toate_info_partide_per_judet as $partid) {
				$psd_votes += intval($partid['psd_votes']);
				$pnl_votes += intval($partid['pnl_votes']);
				$usr_votes += intval($partid['usr_votes']);
				$proromania_votes += intval($partid['proromania_votes']);
				$udmr_votes += intval($partid['udmr_votes']);
				$alde_votes += intval($partid['alde_votes']);
				$pmp_votes += intval($partid['pmp_votes']);
				
				$e += intval($partid['e']);
				$f += intval($partid['f']);
				
				if($partid['count_last_updated_at'] != NULL){
					$sectii_completate++;
				}
			}
			
			$e_f = ($e - $f) <= 0 ? ($psd_votes + $pnl_votes + $usr_votes + $proromania_votes + $udmr_votes + $alde_votes + $pmp_votes) : $e - $f;
			
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
				array('PRO Romania', $proromania_votes),
				array('UDMR', $udmr_votes),
				array('ALDE', $alde_votes),
				array('PMP', $pmp_votes),
			);
			$data['culori_partide'] = array(
				array('PSD', '#DD2C24', $psd_votes),
				array('PNL', '#F9E10B', $pnl_votes),
				array('USR PLUS', '#FD5100', $usr_votes),
				array('PRO Romania', '#F3B250', $proromania_votes),
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
Total voturi: {{ $nationalElectionTotals['totalVotes'] }}
<div>
	Voturi psd: {{ $nationalElectionTotals['psd_votes'] }}
</div>

<div>
	Voturi pnl: {{ $nationalElectionTotals['pnl_votes'] }}
</div>

<div>
	Voturi usr: {{ $nationalElectionTotals['usr_votes'] }}
</div>

<div>
	Voturi alde: {{ $nationalElectionTotals['alde_votes'] }}
</div>

<div>
	Proromania: {{ $nationalElectionTotals['proromania_votes'] }}
</div>

<div>
	PMP: {{ $nationalElectionTotals['pmp_votes'] }}
</div>

<div>
	UDMR: {{ $nationalElectionTotals['udmr_votes'] }}
</div>

<div>
	Prodemo: {{ $nationalElectionTotals['prodemo_votes'] }}
</div>

<div>
	PSR: {{ $nationalElectionTotals['psr_votes'] }}
</div>

<div>
	PSDI: {{ $nationalElectionTotals['psdi_votes'] }}
</div>

<div>
	PRU: {{ $nationalElectionTotals['pru_votes'] }}
</div>

<div>
	UNPR: {{ $nationalElectionTotals['unpr_votes'] }}
</div>

<div>
	BUN: {{ $nationalElectionTotals['bun_votes'] }}
</div>

<div>
	Tudoran: {{ $nationalElectionTotals['tudoran_votes'] }}
</div>

<div>
	Simion: {{ $nationalElectionTotals['simion_votes'] }}
</div>

<div>
	Costea: {{ $nationalElectionTotals['costea_votes'] }}
</div>