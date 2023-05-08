@extends("layouts.main")

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
<link rel="stylesheet" href="{{asset('assets/css/datatables.css')}}">
<style>
	td {
		text-align: center !important;
		vertical-align: middle !important;
	}
	th {
		text-align: center !important;
		vertical-align: middle !important;
	}
</style>
@endsection

@section('content')
<div class="content-wrapper">	
	<div class="container-xxl flex-grow-1 container-p-y">
		<div class="card">
			<div class="card-body" style="overflow: auto;">
				<table class="table table-bordered table-hover datatable">
					<thead>
						<tr>
							<!-- <th rowspan="1" colspan="1" style="width: 30px;">
								<div class="icheck-info">
									<input type="checkbox" id="select-all" name="select-all" />
									<label for="select-all"></label>
								</div>
							</th> -->
							<!-- <th rowspan="1" colspan="1" style="width: 20px;">No</th> -->
							<th>商品画像</th>
							<th style="width: 250px;">商品名</th>
							<th>ASIN</th>
							<th>JAN</th>
							<th>登録時<br/>価格</th>
							<th>目標価格</th>
							<th>現在価格</th>
							<th>更新時間</th>
							<th><span class="text-danger" onclick="deleteItem('all')"><i class='bx bxs-trash'></i></span></th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</div>
			<!-- /.card-body -->
		</div>
	</div>
</div>
@endsection

@section("script")
	<script src="{{asset('assets/js/datatables.min.js')}}"></script>

	<script>
	const deleteItem = (deleteId) => {
		if (window.confirm('本当にデータを削除しますか?')) {
			$.ajax({
				url: '{{ route("delete_item") }}',
				type: 'post',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: {
					id: deleteId
				},
				success: function(response) {
					location.href = '{{ route("item_list") }}';
					toastr.success('データは正常に削除されました。');
				}
			});
		}
	};

    var datatable = $('.datatable').DataTable({
		processing: true,
		serverSide: true,
		autoConfig: true,
		pageLength: 10,
		ajax: "{{ route('item_datatable') }}",
		columns: [{
				data: null,
				name: 'y_img_url',
				render:function(data, type ,row) {
					return (
						`<a href="${row.y_shop_url}" target="_blank"><img src=${row.y_img_url} style="width: 64px; height: 64px;" /></a>`
					)
				}
			},
			{
				data: 'item_name',
				name: 'item_name'
			},
			{
				data: 'asin',
				name: 'asin',
			},
			{
				data: 'jan',
				name: 'jan',
			},
			{
				data: null,
				name: 'y_register_price',
				render:function(data, type ,row) {
					return (
						`<span>￥${row.y_register_price}</span>`
					)
				}
			},
			{
				data: null,
				name: 'y_target_price',
				render:function(data, type ,row) {
					return (
						`<span>￥${row.y_target_price}</span>`
					)
				}
			},
			{
				data: null,
				name: 'y_min_price',
				render:function(data, type ,row) {
					return (
						`<span>￥${row.y_min_price}</span>`
					)
				}
			},
			{
				data: 'updated_time',
				name: 'updated_time',
			},
			{
				data: null,
				name: 'id',
				render:function(data, type ,row) {
					return (
						`<span class="text-danger" onclick="deleteItem(${row.id})"><i class='bx bxs-trash'></i></span>`
					)
				}
			},
		]
    });
	</script>
@endsection
