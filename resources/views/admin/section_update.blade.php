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
		Judet: {{ $section->judet->name }}
	</div>

	<div>
		Numar sectie: {{ $section->nr }}
	</div>

	<div>
		Nume sectie: 
		@if (!empty($section->name))
			{{ $section->name }}
		@else
			-
		@endif
	</div>

	@foreach ($counterFields as $field)
		<div>
			{{ $field['label'] }}
			<input type="text" name="{{ $field['field'] }}" value="{{ $section->{$field['field']} }}" />
		</div>	
	@endforeach

	<div>
		Total voturi sectie: {{ $section->total_votes }}
	</div>

	<div>
		Numărul total al alegătorilor înscriși în lista electorală permanentă și în copia de pe lista electorală specială (pct. a = pct. a1 + pct. a2), din care:
		<input type="text" name="a" value="{{ $section->a }}" />
	</div>

	<div>
		Numărul total al alegătorilor înscriși în lista electorală permanentă (pct. a1 ≥ pct. b1)
		<input type="text" name="a1" value="{{ $section->a1 }}" />
	</div>

	<div>
		Numărul total al alegătorilor înscriși în copia de pe lista electorală specială (pct. a2 ≥ pct. b2)
		<input type="text" name="a2" value="{{ $section->a2 }}" />
	</div>

	<div>
		Numărul total al alegătorilor care s-au prezentat la urne, înscriși în listele electorale existente la secția de votare (pct. b = pct. b1 + pct. b2 + pct. b3), din care:
		<input type="text" name="b" value="{{ $section->b }}" />
	</div>

	<div>
		Numărul total al alegătorilor înscriși în lista electorală permanentă, care s-au prezentat la urne:
		<input type="text" name="b1" value="{{ $section->b1 }}" />
	</div>

	<div>
		Numărul total al alegătorilor înscriși în copia de pe lista electorală specială, care s-au prezentat la urne
		<input type="text" name="b2" value="{{ $section->b2 }}" />
	</div>

	<div>
		Numărul total al alegătorilor înscriși în lista electorală suplimentară, care s-au prezentat la urne
		<input type="text" name="b3" value="{{ $section->b3 }}" />
	</div>

	<div>
		Numărul buletinelor de vot primite (pct. c ≥ pct. d + pct. e + pct. f)
		<input type="text" name="c" value="{{ $section->c }}" />
	</div>

	<div>
		Numărul buletinelor de vot neîntrebuințate și anulate
		<input type="text" name="d" value="{{ $section->d }}" />
	</div>

	<div>
		Numărul voturilor valabil exprimate (pct. e ≤ pct. b - pct. f) (pct. e = suma voturilor valabil exprimate la pct. g)
		<input type="text" name="e" value="{{ $section->e }}" />
	</div>

	<div>
		Numărul voturilor nule
		<input type="text" name="f" value="{{ $section->f }}" />
	</div>

	<input type="submit" value="Salveaza" />

</form>