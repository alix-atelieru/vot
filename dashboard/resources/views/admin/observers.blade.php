<script>
	jQuery(document).ready(function() {
		//console.log('done');
		$('.js-send-sms').click(function() {
			var phone = $(this).parent().parent().find('.js-message').attr('data-phone');
			var message = $(this).parent().parent().find('.js-message').val();
			$(this).hide();
			$.ajax({
				url: APP_URL+'/observer/send_sms',
				data: {'message': message, 'phone': phone},
				function(response) {
					//console.log(response);
				}
			});
			
		});
	});
</script>

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
				S-a logat?
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
					@if (!empty($observer->login_at))
						DA
					@else
						NU
					@endif
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
					<div class='row'>
						<div class='col-sm-6'>
							<input class='form-control js-message' type="text" placeholder="Trimite SMS observatorului" data-phone="{{ $observer->phone }}" />
						</div>
						<div class='col-sm-6'>
							<input class='btn btn-primary js-send-sms' type="button" value="Trimite SMS" />
						</div>
					</div>
				</td>

				<td>
					<a class='btn btn-primary' href="{{ route('observer.update.show', ['id' => $observer->id]) }}" target="_blank">
						Update
					</a>
				</td>
			</tr>
		@endforeach
	</tbody>
</table>