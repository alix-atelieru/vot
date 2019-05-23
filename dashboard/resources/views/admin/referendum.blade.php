<div class='container'>
@if (session('error'))
	<h2 style="color:red">
		{{ session('error') }}
	</h2>
@endif

@if (session('success'))
	<h2 style="color:green">
		{{ session('success') }}
	</h2>
@endif

<form method="POST">
	{{ csrf_field() }}

	<h3>
		Intrebari referendum 1:
	</h3>
	<div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>1. Numărul persoanelor înscrise în lista pentru referendum: </strong></label>
            <div class="col-sm-2">
                <input class="form-control bold_text center_field_text" type="text" name="ref1_1" value="{{ $section->ref1_1 }}" />
            </div>
    </div>
    
    <div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>2. Numărul participanţilor:</strong></label>
            <div class="col-sm-2">
                <input class="form-control bold_text center_field_text" type="text" name="ref1_2" value="{{ $section->ref1_2 }}" />
            </div>
    </div>
    
    
 	<div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>
            	3. Numărul de buletine de vot primite pentru a fi întrebuinţate: 
            </strong></label>
            <div class="col-sm-2">
               <input class="form-control bold_text center_field_text" type="text" name="ref1_3" value="{{ $section->ref1_3 }}" />
            </div>
    </div>
    
    
    <div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>
            	4. Numărul de buletine de vot rămase neîntrebuinţate: 
            </strong></label>
            <div class="col-sm-2">
               <input class="form-control bold_text center_field_text" type="text" name="ref1_4" value="{{ $section->ref1_4 }}" />
            </div>
    </div>
    
    
    <div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>
            	5. Numărul voturilor valabil exprimate la răspunsul "DA": 
            </strong></label>
            <div class="col-sm-2">
               <input class="form-control bold_text center_field_text" type="text" name="ref1_5" value="{{ $section->ref1_5 }}" />
            </div>
    </div>
    
    
    <div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>
            	6. Numărul voturilor valabil exprimate la răspunsul "NU": 
            </strong></label>
            <div class="col-sm-2">
               <input class="form-control bold_text center_field_text" type="text" name="ref1_6" value="{{ $section->ref1_6 }}" />
            </div>
    </div>
    
    
    <div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>
            	7. Numărul voturilor nule: 
            </strong></label>
            <div class="col-sm-2">
               <input class="form-control bold_text center_field_text" type="text" name="ref1_7" value="{{ $section->ref1_7 }}" />
            </div>
    </div>
    
    
    <div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>
            	8. Numărul voturilor contestate:
            </strong></label>
            <div class="col-sm-2">
                <input class="form-control bold_text center_field_text" type="text" name="ref1_8" value="{{ $section->ref1_8 }}" />
            </div>
    </div>
    
    
    <div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>
            	9. Numărul întâmpinărilor şi contestaţiilor primite: 
            </strong></label>
            <div class="col-sm-2">
               <input class="form-control bold_text center_field_text" type="text" name="ref1_9" value="{{ $section->ref1_9 }}" />
            </div>
    </div>
    
    
    <div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>
            	10. Numărul întâmpinărilor şi contestaţiilor soluţionate: 
            </strong></label>
            <div class="col-sm-2">
               <input class="form-control bold_text center_field_text" type="text" name="ref1_10" value="{{ $section->ref1_10 }}" />
            </div>
    </div>
    
    <div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>
            	11. Numărul întâmpinărilor şi contestaţiilor înaintate biroului electoral de circumscripţie: 
            </strong></label>
            <div class="col-sm-2">
               <input class="form-control bold_text center_field_text" type="text" name="ref1_11" value="{{ $section->ref1_11 }}" />
            </div>
    </div>
    
 	<input type="hidden" name="nr" value="1" />

	<input class='btn btn-primary' type="submit" value="Salveaza" />
</form>


<form method="POST">
	{{ csrf_field() }}

	<h3>
		Intrebari referendum 2:
	</h3>


	<div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>
            	1. Numărul persoanelor înscrise în lista pentru referendum: 
            </strong></label>
            <div class="col-sm-2">
               <input class="form-control bold_text center_field_text" type="text" name="ref2_1" value="{{ $section->ref2_1 }}" />
            </div>
    </div>


	<div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>
            	2. Numărul participanţilor: 
            </strong></label>
            <div class="col-sm-2">
            	<input class="form-control bold_text center_field_text" type="text" name="ref2_2" value="{{ $section->ref2_2 }}" />
            </div>
    </div>
    
    
    <div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>
            	3. Numărul de buletine de vot primite pentru a fi întrebuinţate: 
            </strong></label>
            <div class="col-sm-2">
            	<input class="form-control bold_text center_field_text" type="text" name="ref2_3" value="{{ $section->ref2_3 }}" />
            </div>
    </div>
    
    
    <div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>
            	4. Numărul de buletine de vot rămase neîntrebuinţate: 
            </strong></label>
            <div class="col-sm-2">
            	<input class="form-control bold_text center_field_text" type="text" name="ref2_4" value="{{ $section->ref2_4 }}" />
            </div>
    </div>
    
    
    <div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>
            	5. Numărul voturilor valabil exprimate la răspunsul "DA": 
            </strong></label>
            <div class="col-sm-2">
            	<input class="form-control bold_text center_field_text" type="text" name="ref2_5" value="{{ $section->ref2_5 }}" />
            </div>
    </div>
    
    
    <div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>
            	6. Numărul voturilor valabil exprimate la răspunsul "NU": 
            </strong></label>
            <div class="col-sm-2">
            	<input class="form-control bold_text center_field_text" type="text" name="ref2_6" value="{{ $section->ref2_6 }}" />
            </div>
    </div>
    
    
    <div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>
            	7. Numărul voturilor nule: 
            </strong></label>
            <div class="col-sm-2">
            	<input class="form-control bold_text center_field_text" type="text" name="ref2_7" value="{{ $section->ref2_7 }}" />
            </div>
    </div>
    
    
    <div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>
            	8. Numărul voturilor contestate: 
            </strong></label>
            <div class="col-sm-2">
            	<input class="form-control bold_text center_field_text" type="text" name="ref2_8" value="{{ $section->ref2_8 }}" />
            </div>
    </div>
    
    
    <div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>
            	9. Numărul întâmpinărilor şi contestaţiilor primite: 
            </strong></label>
            <div class="col-sm-2">
            	<input class="form-control bold_text center_field_text" type="text" name="ref2_9" value="{{ $section->ref2_9 }}" />
            </div>
    </div>
    
    
    <div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>
            	10. Numărul întâmpinărilor şi contestaţiilor soluţionate: 
            </strong></label>
            <div class="col-sm-2">
            	<input class="form-control bold_text center_field_text" type="text" name="ref2_10" value="{{ $section->ref2_10 }}" />
            </div>
    </div>
    
    
    <div class="form-group row">
            <label class="col-sm-10 col-form-label dashed_row"><strong>
            	11. Numărul întâmpinărilor şi contestaţiilor înaintate biroului electoral de circumscripţie: 
            </strong></label>
            <div class="col-sm-2">
            	<input class="form-control bold_text center_field_text" type="text" name="ref2_11" value="{{ $section->ref2_11 }}" />
            </div>
    </div>
    
 	<input type="hidden" name="nr" value="2" />

	<input class='btn btn-primary' type="submit" value="Salveaza" />
</form>

</div>