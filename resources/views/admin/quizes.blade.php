<table class="wp-list-table widefat fixed striped pages table table-striped dataTable no-footer">
	<thead class="thead-dark" role="grid">
		<th>
			Telefon
		</th>
		<th>
			Nume
		</th>

		<th>
			Data/ora
		</th>
		@foreach ($questions as $question)
			<th>
				{{ $question->content }}
			</th>
		@endforeach
	</thead>

	<tbody>
		@foreach($observers as $observer)
			<tr>
				<td>
					{{ $observer->phone }}
				</td>
				<td>
					{{ $observer->family_name }} {{ $observer->given_name }}
				</td>
				<td>
					{{ $observer->quiz_last_updated_datetime }}
				</td>

				@foreach ($observer->answers as $answer)
					<td>
						{{ $answer->answer }}
					</td>
				@endforeach
			</tr>
		@endforeach
	</tbody>
</table>