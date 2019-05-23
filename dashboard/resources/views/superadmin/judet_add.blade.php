@include('superadmin/header')

@if (session('errorLabel'))
	{{ session('errorLabel') }}
@endif

@if (session('success'))
	{{ session('success') }}
@endif

<script>
	//$(document).ready(function)

	$(document).ready(function() {
		$("#admin_type").change(function() {
			if ($(this).val() == 'national') {
				$('#judet_id').attr('disabled', 'disabled');
			} else if ($(this).val() == 'judet') {
				$('#judet_id').removeAttr('disabled');
			}
		});
	});

</script>

<form method="POST" action="{{ route('superadmin.admins.judet.add') }}">
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
		Tip cont:
		<select name="type" id="admin_type">
			<option value="judet">Admin judetean</option>
			<option value="national">Admin national</option>
		</select>
	</div>

	<div>
		<select name="judet_id" id="judet_id">
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