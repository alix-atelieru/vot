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