@include('national/header')

<!-- poate punem si fara judet?-->

<form method="GET">
	Judet selectat la login:
	<select name="judet_id">
		<option value="">
			TOATE
		</option>

		@foreach ($judete as $judet)
			<option value="{{ $judet->id }}">
				{{ $judet->name }}
			</option>
		@endforeach
	</select>
	<input type="submit" />
</form>

@include('admin/observers')