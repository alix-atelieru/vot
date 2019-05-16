<table class="wp-list-table widefat fixed striped pages table table-striped dataTable no-footer">
	<thead class="thead-dark" role="grid">
		<tr>
			<th>
				Judet
			</th>

			<th>
				Numar
			</th>

			@foreach ($counterFieldsLabels as $label)
				<th>
					{{ $label }}
				</th>
			@endforeach
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

				@foreach ($counterFieldsKeys as $field)
					<td>
						@if (!empty($section->{$field}))
							{{ $section->{$field} }} voturi
						@else
							0 voturi
						@endif
					</td>
				@endforeach

				<td>
					{{ $section->total_votes }}
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