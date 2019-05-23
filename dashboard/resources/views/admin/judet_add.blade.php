@if (session('errorLabel'))
	{{ session('errorLabel') }}
@endif

@if (session('success'))
	{{ session('success') }}
@endif

<form method="POST" action="{{ route('national.admins.judet.add') }}">
	{{ csrf_field() }}
	<div>
		Username: <input type="text" name="username" />
	</div>

	<div>
		Password: <input type="password" name="password" />
	</div>

	<div>
		Nume complet: <input type="text" name="full_name" />
	</div>

	<div>
		<select name="judet_id">
			@foreach ($judete as $judet)
				<option value="{{ $judet->id }}">
					{{ $judet->name }}
				</option>
			@endforeach
		</select>
	</div>

	<div>
		<input type="submit" value="Creeaza" />
	</div>
</form>