@include('national/header')

<h2>
	e=0
</h2>
<table class="wp-list-table widefat fixed striped pages table table-striped dataTable no-footer">
<thead class="thead-dark">
	<tr>
		<th>
			Judet
		</th>

		<th>
			Numar sectie
		</th>

		<th>
			Suma voturi partide raportate
		</th>

		<th>
			Voturi valabil exprimate raportate
		</th>

		<th>
			Voturi nule raportate
		</th>

		<th>
			Link
		</th>
	</tr>
</thead>
@foreach ($rows3 as $row)
	<tr>
		<td>
			{{ $row->judet_name }}
		</td>

		<td>
			{{ $row->nr }}
		</td>

		<td>
			{{ $row->total_votes }}
		</td>

		<td>
			 {{ $row->e }}
		</td>

		<td>
			{{ $row->f }}
		</td>

		<td>
			<a 
			href="{{ route('national.section') }}?<?php echo http_build_query(['judet_id' => $row->judet_id, 'section_id' => $row->id, 'filter_type' => 'by_judet_section']); ?>">
				Link
			</a>
		</td>
	</tr>
@endforeach

</table>