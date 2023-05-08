@extends("layouts.main")
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
										<th rowspan="1" colspan="1" style="width: 50px;">操作</th>
										<th rowspan="1" colspan="1" style="width: 100px;">ユーザー名</th>
										<th rowspan="1" colspan="1" style="width: 250px;">メール</th>
										<th rowspan="1" colspan="1" style="width: 150px;">役割</th>
										<th rowspan="1" colspan="1" style="width: 150px;">パーミッション</th>
									</tr>
								</thead>
								<tbody id="item-table-body">
									@foreach($users as $user)
									@if ($user['role'] == 'admin') @continue @endif
									<tr data-id={{$user->id}}>
										<td>
											<span class="delete"><i class="fa fa-trash text-info" aria-hidden="true"></i></span>
										</td>
										<td rowspan="1" colspan="1">{{$user['family_name']}}</td>
										<td rowspan="1" colspan="1">{{$user['email']}}</td>
										<td rowspan="1" colspan="1">{{$user['role']}}</td>
										<td rowspan="1" colspan="1">
											<div class="form-group">
												<div class="custom-control custom-switch">
													<input type="checkbox" class="custom-control-input permission" id={{"customSwitch".$user->id}} @if($user['is_permitted']) checked @endif>
													<label class="custom-control-label" for={{"customSwitch".$user->id}}></label>
												</div>
											</div>
										</td>
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
	

	<script>
		$(document).ready(function() {
			$('.delete').on('click', function(event) {
				let _tr = $(event.target).parents('tr');
				let userId = _tr.data('id');
				console.log(userId);
				$.ajax({
					url: './delete_account',
					type: 'get',
					data: {
						id: userId
					},
					success: function() {
						_tr.remove();
					}
				});
			});

			$('.permission').on('click', function(event) {
				let isPermitted = (event.target.checked == true) ? 1 : 0;
				$.ajax({
					url: './permit_account',
					type:'get',
					data: {
						id: event.target.id.replace("customSwitch", ""),
						isPermitted: isPermitted
					},
					success: function(response) {
						console.log(response);
					}
				});
			});
		});
	</script>
@endsection
