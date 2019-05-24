@include('judet/header')

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

		$('#section_id').change(function() {
			if ($(this).val().length == 0) {
				return;
			}

			$('#filter_form').submit();
		});

	});
</script>

<form method="GET" id="filter_form">
	Cauta dupa numar sectie:
	<select name="section_id" id="section_id">
		<option></option>
		@foreach($judetSections as $sectionx)
			<option 
			value="{{ $sectionx->id }}"
			<?php if (!empty($requestDict['section_id']) && $requestDict['section_id'] == $sectionx->id) echo 'selected'; ?>
			>
				{{ $sectionx->nr }}
			</option>
		@endforeach
	</select>
	<input type="hidden" name="filter_type" value="by_judet_section"/>
	<input type="submit" />
</form>

<form method="GET">
	Numar de telefon:<input type="text" name="phone" value="<?php if (!empty($requestDict['phone'])) echo $requestDict['phone']; ?>" />
	<input type="hidden" name="filter_type" value="by_phone" />
	<input type="submit" value="Cauta dupa telefon" />
</form>

@if (!empty($filtered))
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
					{{ $judet_name }}
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


	@if (!empty($qa))
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

				<th>
					Quiz
				</th>
			</thead>

			<tbody>
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

					<td>
						<button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal_{{ $observer->id }}">
		                    Quiz
		                </button>
		                
						<!-- Modal QUIZ-->
		                <div 
		                class="modal fade" 
		                id="modal_{{ $observer->id }}" 
		                tabindex="-1" 
		                role="dialog" 
		                aria-labelledby="exampleModalLabel" 
		                aria-hidden="true">
		                    <div class="modal-dialog" role="document">
		                        <div class="modal-content">
		                            <div class="modal-header">
		                                <h5 class="modal-title" id="exampleModalLabel">Quiz</h5>
		                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                                    <span aria-hidden="true">&times;</span>
		                                </button>
		                            </div>
		                            <div class="modal-body">
		                                <div class="list-group">
		                        	     	<?php
											foreach ($qa as $answer) {
								            ?>
			                                    <a href="#" 
			                                    class="list-group-item list-group-item-action flex-column align-items-start">
				                                    <div class="d-flex w-100 justify-content-between">
				                                        <h5 class="mb-1"><strong>Intrebare:</strong> <?php echo $answer->content; ?></h5>
				                                    </div>
				                                    <p class="mb-1">
				                                         <small><strong>Raspuns:</strong> <?php echo $answer->answer; ?></small>
				                                    </p>
			                                    </a>
		                                    <?php
											}
								            ?>
		                                </div>
		                            </div>
		                            <div class="modal-footer">
		                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
		                                    Inchide
		                                </button>
		                            </div>
		                        </div>
		                    </div>
		                </div><!--END MODAL QUIZ-->
		                
		                
					</td>
				</tr>
			</tbody>
		</table>
	@endif


@endif