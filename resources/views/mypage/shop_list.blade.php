@extends("layouts.mypage")
@section('content')
<style>
	table th, table td {
		text-align: center !important;
		vertical-align: middle !important;
	}
</style>
<?php
$applicationId = "dj00aiZpPVJpRFdoRUdpY3pUaSZzPWNvbnN1bWVyc2VjcmV0Jng9MWE-";
$secret = "jOr0rr7kmvXR9r5T6ERkXfN7KpGTu4vmd8TC8Ahv";
// echo base64_encode($applicationId.":".$secret);
?>

<div class="content-wrapper">	
	<div class="content" style="padding-top: 0.5rem;">
		<div class="col-12">
			<div class="card card-info card-outline">
				<div class="card-body">
					<div class="row">
						<div id="table-wrapper" style="overflow: auto; width: 100%;">
							<table class="table table-bordered" style="width: 100%;" id="item-table">
							<!-- <table class="table table-bordered table-head-fixed" style="width: 100%;" id="item-table"> -->
								<thead>
									<tr>
										<th rowspan="1" colspan="1" style="width: 20px;">No</th>
										<th rowspan="1" colspan="1" style="width: 250px;">商店名</th>
										<th rowspan="1" colspan="1" style="width: 50px;">価格</th>
									</tr>
								</thead>
								<tbody id="item-table-body">
									@foreach ($total as $k => $v)
									<tr>
										<td rowspan="1" colspan="1">{{$loop->iteration}}</td>
										<td rowspan="1" colspan="1"><a href="{{$k}}" target="_blank">{{$k}}</a></td>
										<td rowspan="1" colspan="1">{{$v}}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>								
					</div>
				</div>
				<!-- /.card-body -->
				
				<!-- /.card-footer -->
			</div>
		</div>
	</div>
</div>
@endsection
	
@section("script")
	
@endsection