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

	<p>
 		1. Numărul persoanelor înscrise în lista pentru referendum: <input type="text" name="ref1_1" value="{{ $section->ref1_1 }}" />
 	</p>

 	<p>
 		2. Numărul participanţilor: <input type="text" name="ref1_2" value="{{ $section->ref1_2 }}" />
 	</p>

 	<p>
 		3. Numărul de buletine de vot primite pentru a fi întrebuinţate: <input type="text" name="ref1_3" value="{{ $section->ref1_3 }}" />
 	</p>

 	<p>
 		4. Numărul de buletine de vot rămase neîntrebuinţate: <input type="text" name="ref1_4" value="{{ $section->ref1_4 }}" />
 	</p>

 	<p>
 		5. Numărul voturilor valabil exprimate la răspunsul "DA": <input type="text" name="ref1_5" value="{{ $section->ref1_5 }}" />
 	</p>

 	<p>
 		6. Numărul voturilor valabil exprimate la răspunsul "NU": <input type="text" name="ref1_6" value="{{ $section->ref1_6 }}" />
 	</p>

 	<p>
 		7. Numărul voturilor nule: <input type="text" name="ref1_7" value="{{ $section->ref1_7 }}" />
 	</p>

 	<p>
 		8. Numărul voturilor contestate: <input type="text" name="ref1_8" value="{{ $section->ref1_8 }}" />
 	</p>

 	<p>
 		9. Numărul întâmpinărilor şi contestaţiilor primite: <input type="text" name="ref1_9" value="{{ $section->ref1_9 }}" />
 	</p>

 	<p>
 		10. Numărul întâmpinărilor şi contestaţiilor soluţionate: <input type="text" name="ref1_10" value="{{ $section->ref1_10 }}" />
 	</p>

 	<p>
 		11. Numărul întâmpinărilor şi contestaţiilor înaintate biroului electoral de circumscripţie: 
 		<input type="text" name="ref1_11" value="{{ $section->ref1_11 }}" />
 	</p>

 	<input type="hidden" name="nr" value="1" />

	<input type="submit" value="Salveaza" />
</form>


<form method="POST">
	{{ csrf_field() }}

	<h3>
		Intrebari referendum 2:
	</h3>

	<p>
 		1. Numărul persoanelor înscrise în lista pentru referendum: <input type="text" name="ref2_1" value="{{ $section->ref2_1 }}" />
 	</p>

 	<p>
 		2. Numărul participanţilor: <input type="text" name="ref2_2" value="{{ $section->ref2_2 }}" />
 	</p>

 	<p>
 		3. Numărul de buletine de vot primite pentru a fi întrebuinţate: <input type="text" name="ref2_3" value="{{ $section->ref2_3 }}" />
 	</p>

 	<p>
 		4. Numărul de buletine de vot rămase neîntrebuinţate: <input type="text" name="ref2_4" value="{{ $section->ref2_4 }}" />
 	</p>

 	<p>
 		5. Numărul voturilor valabil exprimate la răspunsul "DA": <input type="text" name="ref2_5" value="{{ $section->ref2_5 }}" />
 	</p>

 	<p>
 		6. Numărul voturilor valabil exprimate la răspunsul "NU": <input type="text" name="ref2_6" value="{{ $section->ref2_6 }}" />
 	</p>

 	<p>
 		7. Numărul voturilor nule: <input type="text" name="ref2_7" value="{{ $section->ref2_7 }}" />
 	</p>

 	<p>
 		8. Numărul voturilor contestate: <input type="text" name="ref2_8" value="{{ $section->ref2_8 }}" />
 	</p>

 	<p>
 		9. Numărul întâmpinărilor şi contestaţiilor primite: <input type="text" name="ref2_9" value="{{ $section->ref2_9 }}" />
 	</p>

 	<p>
 		10. Numărul întâmpinărilor şi contestaţiilor soluţionate: <input type="text" name="ref2_10" value="{{ $section->ref2_10 }}" />
 	</p>

 	<p>
 		11. Numărul întâmpinărilor şi contestaţiilor înaintate biroului electoral de circumscripţie: 
 		<input type="text" name="ref2_11" value="{{ $section->ref2_11 }}" />
 	</p>

 	<input type="hidden" name="nr" value="2" />

	<input type="submit" value="Salveaza" />
</form>