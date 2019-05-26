<div class='container'>
	@if (session('success'))
		<h2 style="color:green">
			{{ session('success') }}
		</h2>
	@endif
	
	@if (session('error'))
		<h2 style="color:red">
			{{ session('error') }}
		</h2>
	@endif
	<!--
	<form method="POST">
		{{ csrf_field() }}
		<div>
			<h4>Judet: <span class="badge badge-primary">{{ $section->judet->name }}</span></h4>
		</div>
	
		<div>
			<h4>Numar sectie: <span class="badge badge-primary">{{ $section->nr }}</span></h4>
		</div>
	
		<div>
			
			<h4>Nume sectie: 
				@if (!empty($section->name))
					<span class="badge badge-primary">{{ $section->name }}</span>
				@else
					<span class="badge badge-primary">-</span>
				@endif
			</h4>
		</div>
	
		@foreach ($counterFields as $field)
			<div class="form-group row">
				<label class="col-sm-10 col-form-label dashed_row"><strong>{{ $field['label'] }}</strong></label>
				<div class="col-sm-2">
					<input class="form-control bold_text center_field_text" type="text" name="{{ $field['field'] }}" value="{{ $section->{$field['field']} }}" />
				</div>
			</div>	
		@endforeach
	
		<div>
			<h4>Total voturi sectie: <span class="badge badge-primary">{{ $section->total_votes }}</span></h4>
		</div>
	
		<div class="form-group row">
		    <label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul total al alegătorilor înscriși în lista electorală permanentă și în copia de pe lista electorală specială (pct. a = pct. a1 + pct. a2), din care:</label>
		    <div class="col-sm-2">
		    	<input class="form-control bold_text center_field_text" type="text" name="a" value="{{ $section->a }}" />
		    </div>
		</div>
	
		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul total al alegătorilor înscriși în lista electorală permanentă (pct. a1 ≥ pct. b1)</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="a1" value="{{ $section->a1 }}" />
			</div>
		</div>
	
		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul total al alegătorilor înscriși în copia de pe lista electorală specială (pct. a2 ≥ pct. b2)</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="a2" value="{{ $section->a2 }}" />
			</div>
		</div>
	
		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul total al alegătorilor care s-au prezentat la urne, înscriși în listele electorale existente la secția de votare (pct. b = pct. b1 + pct. b2 + pct. b3), din care:</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="b" value="{{ $section->b }}" />
			</div>
		</div>
	
		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul total al alegătorilor înscriși în lista electorală permanentă, care s-au prezentat la urne:</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="b1" value="{{ $section->b1 }}" />
			</div>
		</div>
	
		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul total al alegătorilor înscriși în copia de pe lista electorală specială, care s-au prezentat la urne</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="b2" value="{{ $section->b2 }}" />
			</div>
		</div>
	
		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul total al alegătorilor înscriși în lista electorală suplimentară, care s-au prezentat la urne</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="b3" value="{{ $section->b3 }}" />
			</div>
		</div>
	
		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul buletinelor de vot primite (pct. c ≥ pct. d + pct. e + pct. f)</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="c" value="{{ $section->c }}" />
			</div>	
		</div>
	
		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul buletinelor de vot neîntrebuințate și anulate</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="d" value="{{ $section->d }}" />
			</div>
		</div>
	
		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul voturilor valabil exprimate (pct. e ≤ pct. b - pct. f) (pct. e = suma voturilor valabil exprimate la pct. g)</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="e" value="{{ $section->e }}" />
			</div>
		</div>
	
		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul voturilor nule</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="f" value="{{ $section->f }}" />
			</div>
		</div>
	
		<input type="submit" class='btn btn-primary' value="Salveaza" />
	
	</form>
</div>
-->

@if (empty($requestDict['form_type']))
	Tip form nespecificat
	{{ die() }}
@endif

@if ($requestDict['form_type'] != '1' && $requestDict['form_type'] != '2')
	Tip form invalid
	{{ die() }}
@endif

<form method="POST">
	{{ csrf_field() }}

	<input type="hidden" name="form_type" value="{{ $requestDict['form_type'] }}" />

	<?php
	$toggledFormType = $requestDict['form_type'] == '1' ? '2' : '1';
	?>
	<div style="margin-top:20px;margin-bottom:20px;display:flex;align-items:center;justify-content:center">
		<a
		   href="<?php echo route('section.update.show', ['id' => $id, 'form_type' => $toggledFormType]) ?>"
		>
			<span class="badge badge-primary" style="font-size:20px;background-color:#e20858">
				<?php 
					if ($toggledFormType == '1') {
						echo 'Click pt INTRODUCERE REZULTATE RAPIDE';
					} else {
						echo 'Click pt INTRODUCERE PV COMPLET';
					}
				?>
			</span>
		</a>
	</div>

	<div>
		<h4>Judet: <span class="badge badge-primary">{{ $section->judet->name }}</span></h4>
	</div>

	<div>
		<h4>Numar sectie: <span class="badge badge-primary">{{ $section->nr }}</span></h4>
	</div>

	<div>
		
		<h4>Nume sectie: 
			@if (!empty($section->name))
				<span class="badge badge-primary">{{ $section->name }}</span>
			@else
				<span class="badge badge-primary">-</span>
			@endif
		</h4>
	</div>

	@if ($requestDict['form_type'] == '1')
		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>PSD</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="psd_votes" value="{{ $section->psd_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>USR PLUS</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="usr_votes" value="{{ $section->usr_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>PROROMANIA</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="proromania_votes" value="{{ $section->proromania_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>UDMR</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="udmr_votes" value="{{ $section->udmr_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>PNL</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="pnl_votes" value="{{ $section->pnl_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>ALDE</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="alde_votes" value="{{ $section->alde_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>PMP</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="pmp_votes" value="{{ $section->pmp_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">VOTURI VALABIL EXPRIMATE (=TOTAL VOTURI - VOTURI NULE)</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="e" value="{{ $section->e }}" />
			</div>
		</div>

		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul voturilor nule</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="f" value="{{ $section->f }}" />
			</div>
		</div>
	@else
		<div class="form-group row">
		    <label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul total al alegătorilor înscriși în lista electorală permanentă și în copia de pe lista electorală specială (pct. a = pct. a1 + pct. a2), din care:</label>
		    <div class="col-sm-2">
		    	<input class="form-control bold_text center_field_text" type="text" name="a" value="{{ $section->a }}" />
		    </div>
		</div>

		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul total al alegătorilor înscriși în lista electorală permanentă (pct. a1 ≥ pct. b1)</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="a1" value="{{ $section->a1 }}" />
			</div>
		</div>

		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul total al alegătorilor înscriși în copia de pe lista electorală specială (pct. a2 ≥ pct. b2)</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="a2" value="{{ $section->a2 }}" />
			</div>
		</div>

		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul total al alegătorilor care s-au prezentat la urne, înscriși în listele electorale existente la secția de votare (pct. b = pct. b1 + pct. b2 + pct. b3), din care:</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="b" value="{{ $section->b }}" />
			</div>
		</div>

		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul total al alegătorilor înscriși în lista electorală permanentă, care s-au prezentat la urne:</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="b1" value="{{ $section->b1 }}" />
			</div>
		</div>

		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul total al alegătorilor înscriși în copia de pe lista electorală specială, care s-au prezentat la urne</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="b2" value="{{ $section->b2 }}" />
			</div>
		</div>

		<!--
		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul total al alegătorilor înscriși în copia de pe lista electorală specială (pct. a2 ≥ pct. b2)</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="a2" value="{{ $section->a2 }}" />
			</div>
		</div>
		-->

		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul total al alegătorilor înscriși în lista electorală suplimentară, care s-au prezentat la urne</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="b3" value="{{ $section->b3 }}" />
			</div>
		</div>

		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul buletinelor de vot primite (pct. c ≥ pct. d + pct. e + pct. f)</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="c" value="{{ $section->c }}" />
			</div>	
		</div>

		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul buletinelor de vot neîntrebuințate și anulate</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="d" value="{{ $section->d }}" />
			</div>
		</div>

		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul voturilor valabil exprimate (pct. e ≤ pct. b - pct. f) (pct. e = suma voturilor valabil exprimate la pct. g)</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="e" value="{{ $section->e }}" />
			</div>
		</div>

		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Numărul voturilor nule</label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="f" value="{{ $section->f }}" />
			</div>
		</div>

		
		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>PARTIDUL SOCIAL DEMOCRAT</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="psd_votes" value="{{ $section->psd_votes }}" />
			</div>
		</div>	
		
		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>ALIANȚA 2020 USR PLUS</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="usr_votes" value="{{ $section->usr_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>PARTIDUL PRO ROMÂNIA</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="proromania_votes" value="{{ $section->proromania_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>UNIUNEA DEMOCRATĂ MAGHIARĂ DIN ROMÂNIA</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="udmr_votes" value="{{ $section->udmr_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>PARTIDUL NAȚIONAL LIBERAL</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="pnl_votes" value="{{ $section->pnl_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>PARTIDUL ALIANȚA LIBERALILOR ȘI DEMOCRAȚILOR - ALDE</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="alde_votes" value="{{ $section->alde_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>PARTIDUL PRODEMO</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="prodemo_votes" value="{{ $section->prodemo_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>PARTIDUL MIȘCAREA POPULARĂ</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="pmp_votes" value="{{ $section->pmp_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>PARTIDUL SOCIALIST ROMÂN</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="psr_votes" value="{{ $section->psr_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>PARTIDUL SOCIAL DEMOCRAT INDEPENDENT</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="psdi_votes" value="{{ $section->psdi_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>PARTIDUL ROMÂNIA UNITĂ</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="pru_votes" value="{{ $section->pru_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>UNIUNEA NAȚIONALĂ PENTRU PROGRESUL ROMÂNIEI</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="unpr_votes" value="{{ $section->unpr_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>BLOCUL UNITĂȚII NAȚIONALE - BUN</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="bun_votes" value="{{ $section->bun_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>GREGORIANA CARMEN TUDORAN</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="tudoran_votes" value="{{ $section->tudoran_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>GEORGE-NICOLAE SIMION</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="simion_votes" value="{{ $section->simion_votes }}" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row"><strong>PETER COSTEA</strong></label>
			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="text" name="costea_votes" value="{{ $section->costea_votes }}" />
			</div>
		</div>

	@endif
	<input type="submit" class='btn btn-primary' value="Salveaza" />
</form>