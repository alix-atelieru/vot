<table class="wp-list-table widefat fixed striped pages table table-striped dataTable no-footer">
	<thead class="thead-dark" role="grid">
		<tr>
			<th>
				Judet
			</th>

			<th>
				Numar
			</th>

			<th>
				Voturi psd
			</th>

			<th>
				Voturi usr
			</th>

			<th>
				ALDE
			</th>

			<th>
				Voturi Proromania
			</th>

			<th>
				Voturi PMP
			</th>

			<th>
				Voturi UDMR
			</th>

			<th>
				Altii
			</th>

			<th>
				Total voturi
			</th>

			<th>
				Actualizare voturi
			</th>

		</tr>
	</thead>


	<tbody>
		@foreach($sections as $section)
			<tr>
				<td>
					{{ $section->judet_name }}
				</td>

				<td>
					{{ $section->nr }}
				</td>

				<td>
					@if (!empty($section->psd_votes))
						{{ $section->psd_votes }}
					@else
						0 voturi
					@endif
				</td>

				<td>
					@if (!empty($section->usr_votes))
						{{ $section->usr_votes }}
					@else
						0 voturi
					@endif
				</td>

				<td>
					@if (!empty($section->alde_votes))
						{{ $section->alde_votes }}
					@else
						0 voturi
					@endif
				</td>

				<td>
					@if (!empty($section->proromania_votes))
						{{ $section->proromania_votes }}
					@else
						0 voturi
					@endif
				</td>

				<td>
					@if (!empty($section->pmp_votes))
						{{ $section->pmp_votes }}
					@else
						0 voturi
					@endif
				</td>

				<td>
					@if (!empty($section->udmr_votes))
						{{ $section->udmr_votes }}
					@else
						0 voturi
					@endif
				</td>

				<td>
					@if (!empty($section->other_votes))
					{{ $section->other_votes }}
					@else
						0 voturi
					@endif
				</td>

				<td>
					@if (!empty($section->total_votes))
						{{ $section->total_votes }}
					@else
						0 voturi
					@endif
				</td>

				<td>
					<a href="{{ route('section.update.show', ['id' => $section->id]) }}">
						Actualizare
					</a>
				</td>
			</tr>
		@endforeach
	</tbody>
</table>