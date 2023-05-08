@extends("layouts.main")

@section('css')
<style>
	table th, table td {
		text-align: center !important;
		vertical-align: middle !important;
	}
</style>
@endsection

@section('content')
<div class="content-wrapper">
	<div class="container-xxl flex-grow-1 container-p-y">
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
												<span class="delete"><i class='bx bxs-trash'></i></span>
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
</div>
@endsection
	
@section("script")
	<script>
		$(document).ready(function() {
			$('.delete').on('click', function(event) {
				if (!window.confirm("本当にデータを削除しますか？")) {
					return;
				}
				let _tr = $(event.target).parents('tr');
				let userId = _tr.data('id');
				console.log(userId);
				$.ajax({
					url: '{{ route("delete_account") }}',
					type: 'get',
					data: {
						id: userId
					},
					success: function() {
						toastr.success('データが正常に削除されました。');
						_tr.remove();
					}
				});
			});

			$('.permission').on('click', function(event) {
				let isPermitted = (event.target.checked == true) ? 1 : 0;
				$.ajax({
					url: '{{ route("permit_account") }}',
					type:'get',
					data: {
						id: event.target.id.replace("customSwitch", ""),
						isPermitted: isPermitted
					},
					success: function(response) {
						toastr.success('操作は成功しました。');
					}
				});
			});
		});
	</script>
@endsection
