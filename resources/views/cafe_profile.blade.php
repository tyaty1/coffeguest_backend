@extends('layouts.app')

@section('content')
	<div class="container cafe-data">
		<div class="row">
			<div class="col-md-2 logo-wrapper">
				<img class="img-responsive" src={{ $main['logo'] }}>
			</div>
			<div class="col-md-6 data-wrapper">
				<div class="cafe-name">
					{{$main['name']}}
					<div class="avg">
						{{ $main['avg'] }}
					</div>
				</div>
				<div class="opening-hours out">
					<img src="" ><div class="in"></div>
				</div>
				<div class="address out">
					<img src="" class="img-responsive">
					<div class="address in">
						 {{ $main['address']}}
					</div>
					
				</div>
				<div class></div>
				
			</div>
			<div class= "col-md-4 map-wrapper">
				<div class="map-l ">
					{!! Mapper::render() !!}
				</div>
			</div>
		</div>
	</div>
		
@endsection