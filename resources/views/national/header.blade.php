<html>
	@include('admin/head')
	<body>
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div>
				<ul class="navbar-nav mr-auto header-menu">
					<li class="nav-item">
						<a class="btn btn-light" href="{{ route('national.observers.show') }}">
							Observatori
						</a>
					</li>

					<li class="nav-item">
						<a class="btn btn-light" href="{{ route('national.sections.show') }}">
							Sectii
						</a>
					</li>

					<!--
					<li class="nav-item">
						<a class="btn btn-light" href="#">
							Ticketing
						</a>
					</li>
					-->

					<li class="nav-item">
						<a class="btn btn-light" href="{{ route('national.observers.stats') }}">
							Raportare Observatori
						</a>
					</li>

					<li class="nav-item">
						<a class="btn btn-light" href="{{ route('national.election.count') }}">
							Rezultate nationale alegeri
						</a>
					</li>

					<li class="nav-item">
						<a class="btn btn-light" href="{{ route('national.election.judet.count') }}">
							Rezultate alegeri judet
						</a>
					</li>

					<li class="nav-item">
						<a class="btn btn-light" href="{{ route('national.observers.quizes') }}">
							Chestionare
						</a>
					</li>

					<div style="clear:both"></div>
				</ul>
			</div>
		</nav>