@include('national/header')

<script>
	jQuery(document).ready(function() {
		//console.log('done');
		$('.js-send-sms').click(function() {
			$(this).hide();
			var phone = $(this).parent().parent().find('.js-message').attr('data-phone');
			var message = $(this).parent().parent().find('.js-message').val();
			
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

<script>

$(document).ready(function() {
	$('#judet_id').change(function() {
		var selectedJudetId = parseInt($(this).val());
		$.ajax({
			url: APP_URL + '/admin/judet/sections?judet_id='+selectedJudetId,
			success: function(response) {
				if (!response.ok) {
					alert(response.errorLabel);
					return;
				}

				var sectionsHtml = `
				<option value="" selected>
					Fara sectie
				</option>
				`;
				for(var i = 0;i < response.sections.length;i++) {
					var section = response.sections[i];
					sectionsHtml += `
						<option value="${section.id}">
							${section.nr}
						</option>
					`;
				}
				$('#section_id').html(sectionsHtml);
			}
		});
	})
});

</script>

<form method="GET">
	Judet:
	<select name="judet_id" id="judet_id">
		<option></option>
		@foreach ($judete as $judet)
			<?php
			$selected = '';
			if (!empty($requestDict['judet_id']) && $judet->id == $requestDict['judet_id']) {
				$selected = ' selected="selected" ';
			}
			?>
			<option value="{{ $judet->id }}"<?php echo $selected; ?>>
				{{ $judet->name }}
			</option>
		@endforeach
	</select>

	Numar sectie:
	<select id="section_id" name="section_id">
		@if (!empty($judetSections))
			@foreach($judetSections as $judetSection)
				<option 
				value="{{ $judetSection->id }}"
				<?php if (!empty($requestDict['section_id']) && $requestDict['section_id'] == $judetSection->id) echo 'selected'; ?>
				>
					{{ $judetSection->nr }}
				</option>
			@endforeach
		@else
			<option>...</option>
		@endif
	</select>
	<input type="submit" value="Aplica selectie" />
</form>

@if (!empty($section))
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

			<th>
				Actualizare referendum
			</th>

		</tr>
	</thead>

	<tbody>
		<tr>
			<td>
				@if ($userType != 'judet')
					<a href="<?php echo route("$userType.observers.show") ?>?judet_id={{ $section->judet_id }}">
						{{ $judet_name }}
					</a>
					@else
						{{ $judet_name }}
				@endif
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
				<a class='btn btn-primary' href="{{ route('section.update.show', ['id' => $section->id]) }}">
					Actualizare formular alegeri
				</a>
			</td>
		
			<td>
				<a class='btn btn-primary' 
				href="<?php echo route($userType . '.referendum.update.show', ['sectionId' => $section->id]); ?>">
					Actualizare formular referendum
					{{ $userType }}
				</a>
			</td>
		</tr>
	</tbody>
</table>
@endif

@if (!empty($observer))
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
					S-a logat
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
					{{ $judet_name }}
				</td>

				<td>
					{{ $section->nr }}
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
		</tbody>
	</table>
@endif