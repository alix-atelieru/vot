@include('national/header')

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
			Total voturi BEC
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
@foreach ($rows as $row)
	<tr>
		<td>
			{{ $row->judet_name }}
		</td>

		<td>
			{{ $row->nr }}
		</td>

		<td>
			{{ $row->voturi }}
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


<!--
(Voturi totale+nule) < 0.9*bec:
<table>

@foreach ($rows2 as $row)
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
-->


<h2>
	(Voturi totale+nule) < 0.9*bec:
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
			Total voturi BEC
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
@foreach ($rows2 as $row)
	<tr>
		<td>
			{{ $row->judet_name }}
		</td>

		<td>
			{{ $row->nr }}
		</td>

		<td>
			{{ $row->voturi }}
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



<h2>
	Suma voturilor partidelor > voturi valabil exprimate
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
@foreach ($rows2 as $row)
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