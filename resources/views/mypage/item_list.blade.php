@extends("layouts.main")
@section('content')
<style>
	table th, table td {
		text-align: center !important;
		vertical-align: middle !important;
	}
</style>

<div class="content-wrapper">	
	<div class="container-xxl flex-grow-1 container-p-y">
		<div class="card">
				<div class="card-header">
					<button class="btn btn-info float-left" onclick="deleteItem()">削 除</button>
					<button class="btn btn-info float-right" data-toggle="modal" data-target="#updateModal">トラッキング更新</button>
				</div>

				<div class="card-body">
					<div class="text-nowrap">
						<table class="table table-bordered" style="width: 100%;" id="item-table">
							<!-- <table class="table table-bordered table-head-fixed" style="width: 100%;" id="item-table"> -->
								<thead>
									<tr>
										<th rowspan="1" colspan="1" style="width: 30px;">
											<div class="icheck-info">
												<input type="checkbox" id="select-all" name="select-all" />
												<label for="select-all"></label>
											</div>
										</th>
										<th rowspan="1" colspan="1" style="width: 20px;">No</th>
										<th rowspan="1" colspan="1" style="width: 100px;">商品画像</th>
										<th rowspan="1" colspan="1" style="width: 250px;">商品名</th>
										<th rowspan="1" colspan="1" style="width: 150px;">ASIN</th>
										<th rowspan="1" colspan="1" style="width: 150px;">JAN</th>
										<th rowspan="1" colspan="1" style="width: 100px;">登録時<br/>価格</th>
										<th rowspan="1" colspan="1" style="width: 100px;">目標価格</th>
										<th rowspan="1" colspan="1" style="width: 100px;">現在価格</th>
										<th rowspan="1" colspan="1" style="width: 100px;">更新時間</th>
									</tr>
								</thead>
								<tbody id="item-table-body">
									@foreach($items as $item)
									@if ($item['y_register_price'] == null)
										@continue;
									@endif
									<tr data-productId="{{$item['id']}}">
										<td rowspan="1" colspan="1">
											<div class="icheck-info d-inline">
												<input type="checkbox" id="{{ 'select-'.$item['id'] }}" name="{{ 'select-'.$item['id'] }}" />
												<label for="{{ 'select-'.$item['id'] }}"></label>
											</div>
										</td>
										<td>{{ $loop->iteration + ($items->currentPage() - 1) * 50 }}</td>
										<td rowspan="1" colspan="1">
											<img src="{{$item['y_img_url']}}" style="width: 64px; height: 64px;" />
										</td>
										<td rowspan="1" colspan="1">{{$item['item_name']}}</td>
										<td rowspan="1" colspan="1">{{$item['asin']}}</td>
										<td rowspan="1" colspan="1">{{$item['jan']}}</td>
										<td rowspan="1" colspan="1">
										￥{{$item['y_register_price']}}
										</td>
										<td rowspan="1" colspan="1" onclick="edit(event);">
										￥{{$item['y_target_price']}}
										</td>
										<td rowspan="1" colspan="1">
											<!-- <a href="{{ $item->y_shop_list }}" target="_blank"> -->
											<a href="./shop_list/{{$item['id']}}" target="_blank" onclick="refresh(event, {{$item['id']}})">
											￥{{$item['y_min_price']}}
											</a>
										</td>
										<td rowspan="1" colspan="1">{{$item['updated_time']}}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>								
				
				<!-- /.card-body -->
				@if (count($items)) {{ $items->onEachSide(1)->links('mypage.pagination') }} @endif
			</div>	
		</div>
	</div>
</div>
<div id="toast-container" class="toast-top-right"></div>

<div class="modal fade" id="updateModal" aria-modal="true" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-group row">
					<label for="udpatePrice" class="col-sm-4 col-form-label">現在価格の</label>
					<div class="col-sm-4">
						<select class="custom-select rounded-0" id="udpatePrice" name="update">
							<option @if ($user->y_register_percent == 5) selected @endif><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">5</font></font></option>
							<option @if ($user->y_register_percent == 10) selected @endif><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">10</font></font></option>
							<option @if ($user->y_register_percent == 15) selected @endif><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">15</font></font></option>
							<option @if ($user->y_register_percent == 20) selected @endif><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">20</font></font></option>
							<option @if ($user->y_register_percent == 25) selected @endif><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">25</font></font></option>
							<option @if ($user->y_register_percent == 30) selected @endif><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">30</font></font></option>
							<option @if ($user->y_register_percent == 35) selected @endif><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">35</font></font></option>
							<option @if ($user->y_register_percent == 40) selected @endif><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">40</font></font></option>
							<option @if ($user->y_register_percent == 45) selected @endif><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">45</font></font></option>
							<option @if ($user->y_register_percent == 50) selected @endif><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">50</font></font></option>
							<option @if ($user->y_register_percent == 55) selected @endif><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">55</font></font></option>
							<option @if ($user->y_register_percent == 60) selected @endif><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">60</font></font></option>
							<option @if ($user->y_register_percent == 65) selected @endif><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">65</font></font></option>
							<option @if ($user->y_register_percent == 70) selected @endif><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">70</font></font></option>
							<option @if ($user->y_register_percent == 75) selected @endif><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">75</font></font></option>
							<option @if ($user->y_register_percent == 80) selected @endif><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">80</font></font></option>
							<option @if ($user->y_register_percent == 85) selected @endif><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">85</font></font></option>
							<option @if ($user->y_register_percent == 90) selected @endif><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">90</font></font></option>
							<option @if ($user->y_register_percent == 95) selected @endif><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">95</font></font></option>
							<option @if ($user->y_register_percent == 100) selected @endif><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">100</font></font></option>
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-info float-right" data-dismiss="modal" onclick="updateTracking(event);">更新</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
	<div id="toast-container" class="toast-top-right"></div>

@endsection

@section("script")

	<script>
		// $("#table-wrapper")[0].style.height = window.innerHeight - 200 + "px";
		@if ($user->is_permitted == 0 && $user->id != 0)
			toastr.error('管理者の承認をお待ちください。');
		@endif

		let selectedItem = [];
		let allItem = [];

		$(document).ready(function() {
			$('input[name="key"]').val(localStorage.getItem('keyword'));
			
			@if (isset($warningItem))
				toastr.warning("「{{$warningItem['item_name'].'」<br/>価格が変更されました。'}}");
				setTimeout(() => {
					window.close();
				}, 10000);
			@endif

			@if (isset($errorItem))
				toastr.error("「{{$errorItem['item_name'].'」<br/>価格が目標価格より低くなりました。'}}");
			@endif

			$.ajax({
				url: './get_allitems',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				type: 'post',
				success: function(response) {
					console.log(response);
					for (let item of response) {
						allItem.push(item.id);
					}
				}
			});

			if (localStorage.getItem('isAll') == 1) {
				let items = $('input[id^=select-]');
				for (let i = 1, len = items.length; i < len; i++) {
					items[i].checked = true;
				}
				$('input[id="select-all"]').prop('checked', true);
				selectedItem = allItem;
			}

			$('#select-all').on('click', function(event) {
				if (event.target.checked) {
					selectedItem = allItem;
					let items = $('input[id^=select-]');
					for (let i = 1, len = items.length; i < len; i++) {
						items[i].checked = true;
					}
					localStorage.setItem('isAll', 1);
				} else {
					selectedItem = [];
					$('input[id ^= select-]').prop('checked', false);
					localStorage.setItem('isAll', 0);
				}
			});

			$('input[id ^= select-]').on('click', event => {
				if (event.target.id != 'select-all') {
					let _id = parseInt(event.target.id.replace('select-', ''));
					let pos = selectedItem.indexOf(_id);
					if (pos == -1) {
						selectedItem.push(_id);
					} else {
						selectedItem.splice(pos, 1);
					}
				}
				console.log(selectedItem);
			});

			setInterval(() => {
				location.href = '/'
			}, 300000);
		});

		const deleteItem = () => {
			if (selectedItem.length == 0) {
				toastr.error('削除するデータを選択してください！');
				return;
			}

			if (window.confirm('本当にデータを削除しますか?')) {
				toastr.info('削除操作が進行中です。少々お待ちください。');
				$.ajax({
					url: './delete_item',
					type: 'post',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					data: {
						ids: JSON.stringify(selectedItem)
					},
					success: function(response) {
						// console.log(response);
						localStorage.setItem('isAll', 0);
						$('input[id="select-all"]').prop('checked', false);
						location.href = './item_list';
						toastr.success('データは正常に削除されました。');
					}
				});
			}
		};

		// for update tracking price individually
		const edit = (e) => {
			if (e.target.nodeName != "TD") {
				return;
			}
			let oldInput = $('input[name="edit_price"]');
			if (oldInput.length) {
				oldInput[0].parentElement.innerHTML = oldInput[0].value;
			}
			let _td = e.target;
			_td.innerHTML = '<input name="edit_price" type="text" style="width: ' + (_td.offsetWidth - 10) + 'px;" value="' + _td.innerText + '" onchange="editTracking(event);" />';
		};

		const editTracking = (e) => {
			let newPrice = e.target.value;
			$.ajax({
				url: './edit_track',
				type: 'get',
				data: {
					id: e.target.parentElement.parentElement.dataset.productid,
					price: newPrice.replace('￥', '')
				},
				success: function(response) {
					console.log(response)
				}
			})
			e.target.parentElement.innerHTML = newPrice;
		};

		// for batch operation of updating tracking price
    const updateTracking = async (e) => {
      e.preventDefault();
			toastr.info('更新操作が進行中です。少々お待ちください。');
      let percent = $('select[name="update"]').val();
      $.ajax({
        url: './update_tracking',
        type: 'get',
        data: {
          percent: percent
        },
      });

			setTimeout(function() {
				location.href = './item_list';
				localStorage.setItem('isAll', 0);
				$('input[id="select-all"]').prop('checked', false);
			}, 10000);
    };

		const itemSearch = (e) => {
			e.preventDefault();
			let key = e.target.value;
			localStorage.setItem('keyword', key);
			$('#searchForm').submit();
		};

		const refresh = (e, id) => {
			setTimeout(() => {
				$.ajax({
					url: './item/' + id,
					type: 'get',
					success: function(res) {
						$(e.target).html("￥" + res.y_min_price);
						$(e.target).parent().prev().html("￥" + res.y_target_price);
						$(e.target).parent().prev().prev().html("￥" + res.y_register_price);
					}
				});
			}, 60000);
		};
	</script>
@endsection
