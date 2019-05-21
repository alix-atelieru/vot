@include('national/header')

<form method="POST" action="{{ route('national.election.judet.count.export') }}">
	{{ csrf_field() }}
	<input type="submit" value="EXPORT" />
</form>

<table class="wp-list-table widefat fixed striped pages table table-striped dataTable no-footer">
	<thead class="thead-dark" role="grid">
		<tr>
			<th>
				Nume judet
			</th>
			<th>
				Total voturi
			</th>

			<th>
				PSD
			</th>

			<th>
				USR
			</th>

			<th>
				PROROMANIA
			</th>

			<th>
				UDMR
			</th>

			<th>
				PNL
			</th>

			<th>
				ALDE
			</th>

			<th>
				PRODEMO
			</th>

			<th>
				PMP
			</th>

			<th>
				PSR
			</th>

			<th>
				PSDI
			</th>

			<th>
				PRU
			</th>

			<th>
				UNPR
			</th>

			<th>
				BUN
			</th>

			<th>
				GREGORIANA TUDORAN
			</th>

			<th>
				GEORGE SIMION
			</th>

			<th>
				PETER COSTEA
			</th>

		</tr>
	</thead>
	<tbody>
		
		@foreach ($judete as $judet)
			<tr>
				<td>
					{{ $judet->name }}
				</td>

				<td>
					{{ $judet->votesCount['totalVotes'] }}
				</td>

				<td>
					{{ $judet->votesCount['psd_votes'] }}
				</td>

				<td>
					{{ $judet->votesCount['usr_votes'] }}
				</td>

				<td>
					{{ $judet->votesCount['proromania_votes'] }}
				</td>

				<td>
					{{ $judet->votesCount['udmr_votes'] }}
				</td>

				<td>
					{{ $judet->votesCount['pnl_votes'] }}
				</td>

				<td>
					{{ $judet->votesCount['alde_votes'] }}
				</td>

				<td>
					{{ $judet->votesCount['prodemo_votes'] }}
				</td>

				<td>
					{{ $judet->votesCount['pmp_votes'] }}
				</td>

				<td>
					{{ $judet->votesCount['psr_votes'] }}
				</td>

				<td>
					{{ $judet->votesCount['psdi_votes'] }}
				</td>

				<td>
					{{ $judet->votesCount['pru_votes'] }}
				</td>

				<td>
					{{ $judet->votesCount['unpr_votes'] }}
				</td>

				<td>
					{{ $judet->votesCount['bun_votes'] }}
				</td>

				<td>
					{{ $judet->votesCount['tudoran_votes'] }}
				</td>

				<td>
					{{ $judet->votesCount['simion_votes'] }}
				</td>

				<td>
					{{ $judet->votesCount['costea_votes'] }}
				</td>
			</tr>
		@endforeach
		
	</tbody>
</table>