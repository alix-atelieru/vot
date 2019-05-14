<table class="wp-list-table widefat fixed striped pages table table-striped dataTable no-footer">
	<thead class="thead-dark" role="grid">
		<tr>
			<th>
				Nume
			</th>

			<th>
				Prenume
			</th>

			<th>
				Telefon
			</th>

			<th>
				Pin
			</th>

			<th>
				Judet
			</th>

			<th>
				Sectie <!--atentie: judet/sectie pot fi nule; -->
			</th>

			<th>
				SMS
			</th>

			<th>
				Edit
			</th>
		</tr>
	</thead>

	<tbody>
		@foreach ($observers as $observer)
			<tr>
				<td>
					{{ $observer->family_name }}
				</td>

				<td>
					{{ $observer->given_name }}
				</td>

				<td>
					{{ $observer->phone }}
				</td>

				<td>
					{{ $observer->pin }}
				</td>

				<td>
					@if (!empty($observer->judet_name))
						{{ $observer->judet_name }}
					@else
						-
					@endif
				</td>

				<td>
					@if (!empty($observer->section_nr))
						{{ $observer->section_nr }}
					@else
						-
					@endif
				</td>

				<td>
					<input type="text" placeholder="Trimite SMS observatorului" />
					<input type="button" value="Trimite SMS" />
				</td>

				<td>
					<a href="{{ route('observer.update.show', ['id' => $observer->id]) }}" target="_blank">
						Update
					</a>
				</td>
			</tr>
		@endforeach
	</tbody>

</table>