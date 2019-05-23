<script>

$(document).ready(function() {
	$('#judet_id').change(function() {
		$('#save').attr('disabled', 'disabled');
		var selectedJudetId = parseInt($(this).val());
		$.ajax({
			url: APP_URL + '/admin/judet/sections?judet_id='+selectedJudetId,
			success: function(response) {
				//console.log(response);
				$('#save').removeAttr('disabled');
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
<div class='container'>
@if (!empty($observer))
	@if (session('error'))
		{{ session('error') }}
	@endif

	@if (session('success'))
		{{ session('success') }}
	@endif

	<form method="POST">
		{{ csrf_field() }}
<div class="form-group row">
            <label  class="col-sm-10 col-form-label dashed_row bold_text">Nume: </label>
            <div class="col-sm-2">
                <input class="form-control bold_text center_field_text" type="text" name="family_name" placeholder="Nume" value="{{ $observer->family_name }}" />
            </div>
        </div>
        <div class="form-group row">
            <label  class="col-sm-10 col-form-label dashed_row bold_text">Prenume: </label>
            <div class="col-sm-2">
                <input class="form-control bold_text center_field_text" class="form-control bold_text center_field_text" type="text" name="given_name" placeholder="Prenume" value="{{ $observer->given_name }}" />
            </div>
        </div>
        <div class="form-group row">
            <label  class="col-sm-10 col-form-label dashed_row bold_text">Telefon: </label>
            <div class="col-sm-2">
                <input class="form-control bold_text center_field_text" type="text" name="phone" placeholder="Telefon" value="{{ $observer->phone }}" />
            </div>
        </div>
        <div class="form-group row">
            <label  class="col-sm-10 col-form-label dashed_row bold_text">Pin: </label>
            <div class="col-sm-2">
                <input class="form-control bold_text center_field_text" type="text" name="pin" placeholder="Pin" value="{{ $observer->pin }}" />
            </div>
        </div>
		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Judet: </label>
			<div class="col-sm-2">
				<!-- pentru conturile de judet->nu il lasa sa schimbe judetul -->
				<select class="form-control bold_text center_field_text" name="judet_id" id="judet_id"<?php if ($isJudet) echo ' disabled'; ?>>
					@if (empty($observer->judet))
						<option value="" selected>
							-
						</option>			
					@endif
					@foreach ($judete as $judet)
						<?php
						$selected = "";
						if ($observer->judet_id == $judet->id) {
							$selected = 'selected="selected"';
						}
						?>
						<option value="{{ $judet->id }}" <?php echo $selected ?>>
							{{ $judet->name }}
						</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group row">
			<label  class="col-sm-10 col-form-label dashed_row bold_text">Sectia: </label>
			<div class="col-sm-2">
				<select class="form-control bold_text center_field_text" name="section_id" id="section_id">
					@if (empty($observer->section_id))
						<option value="" selected>
							Fara sectie
						</option>
					@else
						<option value="">
							Fara sectie
						</option>			
					@endif
		
					@foreach($judetSections as $section)
						<?php
						$selected = "";
						if ($observer->section_id == $section->id) {
							$selected = 'selected="selected"';
						}
						?>
		
						<option value="{{ $section->id }}" <?php echo $selected ?>>
							{{ $section->nr }}
						</option>
					@endforeach
				</select>
			</div>
		</div>
		
<div class="form-group row">
		<input class='btn btn-primary' type="submit" value="Salveaza" id="save" />
	</div>
	</form>
@else
	Acest observator nu exista
@endif
</div>