@include('national/header')

<table>
@foreach ($rows as $row)
	<tr>
		<td>
			{{ $row->judet_name }}
		</td>

		<td>
			{{ $row->nr }}
		</td>

		<td>
			Votanti bec  {{ $row->voturi }}
		</td>

		<td>
			Voturi curente {{ $row->total_votes }}
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
