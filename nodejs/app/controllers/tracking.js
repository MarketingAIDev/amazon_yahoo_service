const axios = require("axios");
const { itemList, categoryList } = require("../models");

exports.updateInfo = async () => {
	await categoryList
		.findAll()
		.then((res) => {
			for (let category of res) {
				yahooTracking(category);
			}
		})
		.catch((err) => {
			console.log("Cannot access user data>>>>>>>>>>", err.message);
		});
};

yahooTracking = async (category) => {
	await itemList
		.findAll({ where: { category_id: category.id } })
		.then((items) => {
			var index = 0;
			
			var len = items.length;
			let checkInterval = setInterval(() => {
				categoryList.findByPk(category.id)
				.then((data) => {
					let query = {};
					if (len == 0) {
						data.stop = 1;
					}
					if (data.stop == 0) {
						query.len = len;
						if (index < len) {
							let checkItemInfo = new CheckItemInfo(
								category,
								items[index]
							);
							checkItemInfo.main();
							index++;
							
							query.trk_num = index;
							query.round = data.round;
						} else {
							clearInterval(checkInterval);
							yahooTracking(category);
							index = 0;

							query.round = data.round + 1;
						}
					} else if (data.stop == 1) {
						index = 0;
						query.round = 0;
						query.trk_num = 0;
						clearInterval(checkInterval);
						yahooTracking(category);
					}
					categoryList.update(query, {where: {id: category.id}});
				});
			}, 2100);
		})
		.catch((err) => {
			console.log("yahoo tracking function error>>>>>>>>>>", err.message);
		});
};

class CheckItemInfo {
	constructor(category, item) {
		this.item = item;
		this.query = {};
		this.result = {};
		this.category = category;
	}

	async main() {

		this.query.user_id = this.category.user_id;
		this.query.category_id = this.category.id;

		let url =
			`https://shopping.yahooapis.jp/ShoppingWebService/V3/itemSearch?appid=${this.category.yahoo_id}&affiliate_type=vc&affiliate_id=https%3A%2F%2Fck.jp.ap.valuecommerce.com%2Fservlet%2Freferral%3Fsid%3D3691564%26pid%3D889248890%26vc_url%3D&jan_code=${this.item.jan}&image_size=76&results=1&price_from=${this.category.target_price}&in_stock=true&sort=%2Bprice&condition=new`;

		await axios
			.get(url, {})
			.then(async (res) => {
				if (res !== undefined && res.data.hits.length > 0) {
					this.result = res.data.hits[0];

					this.query.img_url = this.result.image.small;
					this.query.name = this.result.name;
					this.query.min_price = Number(this.result.price);
					this.query.shop_url = this.result.url;
					this.query.is_notified = 0;
				} else {
					this.query.name = "JANに一致する商品は見つかりませんでした。";
					this.query.status = 0;
				}

				var searchQuery = { jan: this.item.jan, user_id: this.category.user_id };

				itemList.update(this.query, {
					where: searchQuery,
				});
				
				itemList.findAll({ where: searchQuery }).then(async (data) => {
					if (this.query.min_price < this.category.target_price) return;

					if (this.query.min_price < this.item.target_price && this.item.is_notified == 0) {
						var tar_price = "前回の価格:" + this.item.register_price;
						var cur_price = "今回の価格:" + this.query.min_price;
						var productUrl = "URL:" + this.query.img_url;
						// var category = "大カテゴリー名:" + this.machine.category;
						// var ranking =
							// "大カテゴリーのランキング:" +
							// item.BrowseNodeInfo.WebsiteSalesRank.SalesRank;
						// var number = "出品者数:" + 191;
						var shop = "出品者:" + "サードパーティー";
						var asin = "ASIN:" + this.item.asin;
						var jan = "JAN:" + this.item.jan;
						var keepaUrl = "https://keepa.com/#!product/5-" + this.item.asin;
						var productImgUrl =
							`https://graph.keepa.com/pricehistory.png?key=6trubr9p3mrqrvecb6jihjq33mgiitmckbf3lj44e32equehfodic3kkf2atpf02&asin=${this.item.asin}&domain=co.jp&salesrank=1`;

						var shopUrl = this.item.shop_url;

						var axios = require("axios");
						var data = JSON.stringify({
							content:
								tar_price +
								"\n" +
								cur_price +
								"\n" +
								productUrl +
								"\n" +
								// category +
								// "\n" +
								// ranking +
								// "\n" +
								shop +
								"\n" +
								asin +
								"\n" +
								jan +
								"\n" +
								keepaUrl +
								"\n" +
								productImgUrl +
								"\n" +
								shopUrl,
						});

						var config = {
							method: "post",
							maxBodyLength: Infinity,
							url: this.user.web_hook,
							headers: {
								"Content-Type": "application/json",
							},
							data: data,
						};

						axios(config)
							.then(function (response) {
								console.log(JSON.stringify(response.data));
								this.query.is_notified = 1;
								products.update(query, { where: condition });

								// var note = {
								// 	code: data,
								// 	user_id: this.category.user_id,
								// };
								// errors.create(note);
							})
							.catch(function (err) {
								console.log("cant notify to discord>>>>>>>>>>", err.message);
							});
					}
				});
			})
			.catch((err) => {
				console.log("update error>>>>>>>>>>", err.message);
			});
	}
}
