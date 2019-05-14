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

	<div>
		PSD
		<input type="text" name="psd_votes" value="{{ $section->psd_votes }}" />
	</div>

	<div>
		USRPLUS
		<input type="text" name="usr_votes" value="{{ $section->usr_votes }}" />
	</div>

	<div>
		ALDE
		<input type="text" name="alde_votes" value="{{ $section->alde_votes }}" />
	</div>

	<div>
		PROROMANIA
		<input type="text" name="proromania_votes" value="{{ $section->proromania_votes }}" />
	</div>

	<div>
		PMP
		<input type="text" name="pmp_votes" value="{{ $section->pmp_votes }}" />
	</div>

	<div>
		UDMR
		<input type="text" name="udmr_votes" value="{{ $section->udmr_votes }}" />
	</div>

	<div>
		Altii
		<input type="text" name="other_votes" value="{{ $section->other_votes }}" />
	</div>

	<div>
		Total voturi sectie: {{ $section->total_votes }}
	</div>

	<input type="submit" value="Salveaza" />

</form>