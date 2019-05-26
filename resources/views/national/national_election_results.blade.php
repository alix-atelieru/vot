@include('national/header')
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css'>
<?php $db = DB::connection()->getPdo(); 
$query_judete = "SELECT * FROM judete"; //persons WHERE id = :userid
$judete = $db->prepare($query_judete);
$judete->execute(); //array(':userid' => $userID )
$judete = $judete->fetchAll(PDO::FETCH_ASSOC);
$judet_get = (!isset($_GET['judet'])) ? 'toate' : $_GET['judet'];
?>

<form action='' method='GET'>
    		<p>
    			<select name='judet'>
    				<option disabled selected>Selecteaza judet</option>
    				<option value='toate'>Toate</option>
    				<?php foreach($judete as $judet){
    					?>
    					<option value='<?php echo $judet['id']; ?>' <?php if (isset($judet_get)) if ($judet_get==$judet['id']) echo " selected='selected' ";?>><?php echo $judet['name']; ?></option>
    					<?php
    				} ?>
    			</select>
    			<input type='submit' value='Trimite' name='submit'>
    		</p>
    	</form>
    	<?php
        $data = array();
		$procent_completate = '';
		if(1 == 1){
			if(!isset($judet_get)){
				return false;
			}
			if(empty($judet_get)){
				return false;
			}
			$toate_info_partide_per_judet =0;
			if($judet_get == 'toate'){
				
			}else{
				$query_toate_info = "SELECT * FROM sections WHERE judet_id = :judet_id";
				$toate_info_partide_per_judet = $db->prepare($query_toate_info);
				$toate_info_partide_per_judet->execute(array(':judet_id' => $judet_get));
					$toate_info_partide_per_judet = $toate_info_partide_per_judet->fetchAll();
			}
			
			//$toate_info_partide_per_judet = $toate_info_partide_per_judet->fetchAll();
			
			$psd_votes = 0;
			$pnl_votes = 0;
			$usr_votes = 0;
			$proromania_votes = 0;
			$udmr_votes = 0;
			$alde_votes = 0;
			$pmp_votes = 0;
			
			
			$psdi_votes = 0;
			$unpr_votes = 0;
			$pru_votes = 0;
			$bun_votes = 0;
			$prodemo_votes = 0;
			$tudoran_votes = 0;
			$costea_votes = 0;
			$simion_votes = 0;
			
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
			if ($judet_get== 'toate'){
				$psd_votes = $nationalElectionTotals['psd_votes'];
				$pnl_votes = $nationalElectionTotals['pnl_votes'];
				$usr_votes = $nationalElectionTotals['usr_votes'];
				$proromania_votes = $nationalElectionTotals['proromania_votes'];
				$udmr_votes = $nationalElectionTotals['udmr_votes'];
				$alde_votes = $nationalElectionTotals['alde_votes'];
				$pmp_votes = $nationalElectionTotals['pmp_votes'];
				
				$e = $nationalElectionTotals['e_votes'];
				$f = $nationalElectionTotals['f_votes'];
				
					
					$sectii_completate = $nationalElectionTotals['sections_counted'];
					$total_sectii = $nationalElectionTotals['sections_counted']+ $nationalElectionTotals['e_null_count'];
								
			}else{
				$total_sectii = count($toate_info_partide_per_judet);
				foreach ($toate_info_partide_per_judet as $partid) {
					$psd_votes += intval($partid['psd_votes']);
					$pnl_votes += intval($partid['pnl_votes']);
					$usr_votes += intval($partid['usr_votes']);
					$proromania_votes += intval($partid['proromania_votes']);
					$udmr_votes += intval($partid['udmr_votes']);
					$alde_votes += intval($partid['alde_votes']);
					$pmp_votes += intval($partid['pmp_votes']);
					
					
					$psdi_votes += intval($partid['psdi_votes']);
					$unpr_votes += intval($partid['unpr_votes']);
					$pru_votes += intval($partid['pru_votes']);
					$bun_votes += intval($partid['bun_votes']);
					$prodemo_votes += intval($partid['prodemo_votes']);
					$tudoran_votes += intval($partid['tudoran_votes']);
					$costea_votes += intval($partid['costea_votes']);
					$simion_votes += intval($partid['simion_votes']);
					
					
					$e += intval($partid['e']);
					$f += intval($partid['f']);
					
					if($partid['count_last_updated_at'] != NULL){
						$sectii_completate++;
					}
				}
				$nationalElectionTotals['psd_votes'] = $psd_votes;
				$nationalElectionTotals['pnl_votes'] = $pnl_votes;
				$nationalElectionTotals['usr_votes'] = $usr_votes;
				$nationalElectionTotals['proromania_votes'] = $proromania_votes;
				$nationalElectionTotals['udmr_votes'] = $udmr_votes;
				$nationalElectionTotals['alde_votes'] = $alde_votes;
				$nationalElectionTotals['pmp_votes'] = $pmp_votes;
				
				$nationalElectionTotals['psdi_votes'] = $psdi_votes;
				$nationalElectionTotals['unpr_votes'] = $unpr_votes;
				$nationalElectionTotals['pru_votes'] = $pru_votes;
				$nationalElectionTotals['bun_votes'] = $bun_votes;
				$nationalElectionTotals['prodemo_votes'] = $prodemo_votes;
				$nationalElectionTotals['tudoran_votes'] = $tudoran_votes;
				$nationalElectionTotals['costea_votes'] = $costea_votes;
				$nationalElectionTotals['simion_votes'] = $simion_votes;
				
				$nationalElectionTotals['e_votes'] = $e;
				$nationalElectionTotals['f_votes'] = $f;
			}
			$e_f = ($e) <= 0 ? ($psd_votes + $pnl_votes + $usr_votes + $proromania_votes + $udmr_votes + $alde_votes + $pmp_votes) : $e ;
			
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
	Total voturi: <span class='badge badge-primary'><?php echo $nationalElectionTotals['totalVotes']; ?></span>
</div>
<div style='font-size: 20px;'>
	PSD: <span class='badge badge-primary'><?php echo $nationalElectionTotals['psd_votes']; ?></span>
</div>
<div style='font-size: 20px;'>
	USR PLUS: <span class='badge badge-primary'><?php echo $nationalElectionTotals['usr_votes']; ?></span>
</div>
<div style='font-size: 20px;'>
	PRO ROMÂNIA: <span class='badge badge-primary'><?php echo $nationalElectionTotals['proromania_votes']; ?></span>
</div>
<div style='font-size: 20px;'>
	UDMR: <span class='badge badge-primary'><?php echo $nationalElectionTotals['udmr_votes']; ?></span>
</div>
<div style='font-size: 20px;'>
	PNL: <span class='badge badge-primary'><?php echo $nationalElectionTotals['pnl_votes']; ?></span>
</div>

<div style='font-size: 20px;'>
	ALDE: <span class='badge badge-primary'><?php echo $nationalElectionTotals['alde_votes']; ?></span>
</div>
<div style='font-size: 20px;'>
	PRODEMO: <span class='badge badge-primary'><?php echo $nationalElectionTotals['prodemo_votes']; ?></span>
</div>
<div style='font-size: 20px;'>
	PMP: <span class='badge badge-primary'><?php echo $nationalElectionTotals['pmp_votes']; ?></span>
</div>

<div style='font-size: 20px;'>
	PSR: <span class='badge badge-primary'><?php echo $nationalElectionTotals['psr_votes']; ?></span>
</div>

<div style='font-size: 20px;'>
	PSDI: <span class='badge badge-primary'><?php echo $nationalElectionTotals['psdi_votes']; ?></span>
</div>

<div style='font-size: 20px;'>
	PRU: <span class='badge badge-primary'><?php echo $nationalElectionTotals['pru_votes']; ?></span>
</div>

<div style='font-size: 20px;'>
	UNPR: <span class='badge badge-primary'><?php echo $nationalElectionTotals['unpr_votes']; ?></span>
</div>

<div style='font-size: 20px;'>
	BUN: <span class='badge badge-primary'><?php echo $nationalElectionTotals['bun_votes']; ?></span>
</div>

<div style='font-size: 20px;'>
	GREGORIANA CARMEN TUDORAN: <span class='badge badge-primary'><?php echo $nationalElectionTotals['tudoran_votes']; ?></span>
</div>

<div style='font-size: 20px;'>
	GEORGE-NICOLAE SIMION: <span class='badge badge-primary'><?php echo $nationalElectionTotals['simion_votes']; ?></span>
</div>

<div style='font-size: 20px;'>
	PETER COSTEA: <span class='badge badge-primary'><?php echo $nationalElectionTotals['costea_votes']; ?></span>
</div>
<div style='font-size: 20px;'>
Sectii care au completat:	<span class='badge badge-primary'><?php 
$procent_sectii_care_au_completat = $nationalElectionTotals['sections_counted'] * 100 / ($nationalElectionTotals['sections_counted'] + $nationalElectionTotals['e_null_count']);
echo $nationalElectionTotals['sections_counted'] . ' (' . number_format($procent_sectii_care_au_completat, 3) . '%)'; ?></span>
</div>
<div style='font-size: 20px;'>
Sectii care au nu completat:	<span class='badge badge-primary'><?php
$procent_sectii_care_au_NU_completat = $nationalElectionTotals['e_null_count'] * 100 / ($nationalElectionTotals['sections_counted'] + $nationalElectionTotals['e_null_count']);
echo $nationalElectionTotals['e_null_count'] . ' (' . number_format($procent_sectii_care_au_NU_completat, 3) . '%)';
?></span>
</div>
<div style='font-size: 20px;'>
e. VOTURI VALABIL EXPRIMATE (=TOTAL VOTURI - VOTURI NULE):	<span class='badge badge-primary'><?php echo $nationalElectionTotals['e_votes']; ?></span>
</div>
<div style='font-size: 20px;'>
f. VOTURI NULE:	<span class='badge badge-primary'><?php echo $nationalElectionTotals['f_votes']; ?></span>
</div>
</div>






<h2>4:</h2>
<div class='container graph_badge' style='padding-top: 20px; padding-bottom: 20px;'>
<div style='font-size: 20px;'>
	Total voturi: <span class='badge badge-primary'><?php echo $nationalElectionTotals4['totalVotes']; ?></span>
</div>
<div style='font-size: 20px;'>
	PSD: <span class='badge badge-primary'><?php echo $nationalElectionTotals4['psd_votes']; ?></span>
</div>
<div style='font-size: 20px;'>
	USR PLUS: <span class='badge badge-primary'><?php echo $nationalElectionTotals4['usr_votes']; ?></span>
</div>
<div style='font-size: 20px;'>
	PRO ROMÂNIA: <span class='badge badge-primary'><?php echo $nationalElectionTotals4['proromania_votes']; ?></span>
</div>
<div style='font-size: 20px;'>
	UDMR: <span class='badge badge-primary'><?php echo $nationalElectionTotals4['udmr_votes']; ?></span>
</div>
<div style='font-size: 20px;'>
	PNL: <span class='badge badge-primary'><?php echo $nationalElectionTotals4['pnl_votes']; ?></span>
</div>

<div style='font-size: 20px;'>
	ALDE: <span class='badge badge-primary'><?php echo $nationalElectionTotals4['alde_votes']; ?></span>
</div>
<div style='font-size: 20px;'>
	PRODEMO: <span class='badge badge-primary'><?php echo $nationalElectionTotals4['prodemo_votes']; ?></span>
</div>
<div style='font-size: 20px;'>
	PMP: <span class='badge badge-primary'><?php echo $nationalElectionTotals4['pmp_votes']; ?></span>
</div>

<div style='font-size: 20px;'>
	PSR: <span class='badge badge-primary'><?php echo $nationalElectionTotals4['psr_votes']; ?></span>
</div>

<div style='font-size: 20px;'>
	PSDI: <span class='badge badge-primary'><?php echo $nationalElectionTotals4['psdi_votes']; ?></span>
</div>

<div style='font-size: 20px;'>
	PRU: <span class='badge badge-primary'><?php echo $nationalElectionTotals4['pru_votes']; ?></span>
</div>

<div style='font-size: 20px;'>
	UNPR: <span class='badge badge-primary'><?php echo $nationalElectionTotals4['unpr_votes']; ?></span>
</div>

<div style='font-size: 20px;'>
	BUN: <span class='badge badge-primary'><?php echo $nationalElectionTotals4['bun_votes']; ?></span>
</div>

<div style='font-size: 20px;'>
	GREGORIANA CARMEN TUDORAN: <span class='badge badge-primary'><?php echo $nationalElectionTotals4['tudoran_votes']; ?></span>
</div>

<div style='font-size: 20px;'>
	GEORGE-NICOLAE SIMION: <span class='badge badge-primary'><?php echo $nationalElectionTotals4['simion_votes']; ?></span>
</div>

<div style='font-size: 20px;'>
	PETER COSTEA: <span class='badge badge-primary'><?php echo $nationalElectionTotals4['costea_votes']; ?></span>
</div>
<div style='font-size: 20px;'>
Sectii care au completat:	<span class='badge badge-primary'><?php 
$procent_sectii_care_au_completat4 = $nationalElectionTotals4['sections_counted'] * 100 / ($nationalElectionTotals4['sections_counted'] + $nationalElectionTotals4['e_null_count']);
echo $nationalElectionTotals4['sections_counted'] . ' (' . number_format($procent_sectii_care_au_completat4, 3) . '%)'; ?></span>
</div>
<div style='font-size: 20px;'>
Sectii care au nu completat:	<span class='badge badge-primary'><?php
$procent_sectii_care_au_NU_completat4 = $nationalElectionTotals4['e_null_count'] * 100 / ($nationalElectionTotals4['sections_counted'] + $nationalElectionTotals4['e_null_count']);
echo $nationalElectionTotals4['e_null_count'] . ' (' . number_format($procent_sectii_care_au_NU_completat4, 3) . '%)';
?></span>
</div>
<div style='font-size: 20px;'>
e. VOTURI VALABIL EXPRIMATE (=TOTAL VOTURI - VOTURI NULE):	<span class='badge badge-primary'><?php echo $nationalElectionTotals4['e_votes']; ?></span>
</div>
<div style='font-size: 20px;'>
f. VOTURI NULE:	<span class='badge badge-primary'><?php echo $nationalElectionTotals4['f_votes']; ?></span>
</div>
</div>