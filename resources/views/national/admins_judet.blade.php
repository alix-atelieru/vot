@include('national/header')


@if (session('errorLabel'))
	<div style="color:red">
		{{ session('errorLabel') }}
	</div>
@endif

@if (session('success'))
	<div style="color:green">
		{{ session('success') }}
	</div>
@endif

<a href="{{ route('national.account.create.judet') }}" class="btn btn-primary">
	Creeaza admin judetean
</a>
<table class="wp-list-table widefat fixed striped pages table table-striped dataTable no-footer">
	<thead class="thead-dark" role="grid">
		<tr>
			<th>
				Username
			</th>
			<th>
				Nume complet
			</th>

			<th>
				Judet
			</th>

			<th>
				Update
			</th>

			<th>
				Sterge
			</th>
		</tr>
	</thead>

	<tbody>

		@foreach ($adminsJudet as $admin)
			<tr>
				<td>
					{{$admin->username}}
				</td>

				<td>
					{{$admin->full_name}}
				</td>

				<td>
					{{$admin->judet->name}}
				</td>

				<td>
					<a href="{{ route('national.account.update.judet.show', ['id' => $admin->id]) }}">
						Update
					</a>
				</td>
				
				<td>
					<a href="{{ route('national.admins.judet.delete') }}?id={{ $admin->id }}">
						Sterge
					</a>
				</td>
			</tr>
		@endforeach

	</tbody>
</table>