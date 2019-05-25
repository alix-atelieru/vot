@include('national/header')

<form method="POST">
	{{ csrf_field() }}
	Judet:
	<select name="judet_id">
		@foreach ($judete as $judet)
			<option value="{{ $judet->id }}">
				{{ $judet->name }}
			</option>
		@endforeach
	</select>
	Numar:<input type="text" name="nr" />

	Observator: <input type="text" name="full_name" placeholder="Nume observator" />

	<input type="submit" />
</form>