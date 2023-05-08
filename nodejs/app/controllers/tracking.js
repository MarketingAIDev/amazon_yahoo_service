const axios = require("axios");
const { yahoo_list, user_list } = require("../models");

exports.updateInfo = async () => {
	await user_list
		.findAll({ where: { is_permitted: 1 } })
		.then((res) => {
			for (let user of res) {
				yahooTracking(user);
			}
		})
		.catch((err) => {
			console.log("Cannot access user data>>>>>>>>>>", err.message);
		});
};

yahooTracking = async (user) => {
	await yahoo_list
		.findAll({ where: { user_id: user.id, status: 1 } })
		.then((items) => {
			var index = 0;
			var token = [user.yahoo_token, user.yahoo_token1, user.yahoo_token2];
			var len = items.length;
			let checkInterval = setInterval(() => {
				if (index < len) {
					let checkItemInfo = new CheckItemInfo(
						user,
						items[index],
						token[index % 3]
					);
					checkItemInfo.main();
					index++;
				} else {
					clearInterval(checkInterval);
					yahooTracking(user);
					index = 0;
				}
			}, 2100);
		})
		.catch((err) => {
			console.log("yahoo tracking function error>>>>>>>>>>", err.message);
		});
};

class CheckItemInfo {
	constructor(user, item, token) {
		this.user = user;
		this.item = item;
		this.token = token;
		this.query = {};
		this.result = {};
	}

	async main() {
		if (this.item === undefined) return;
		this.query.user_id = this.user.id;
		let url = `https://shopping.yahooapis.jp/ShoppingWebService/V3/itemSearch?appid=${this.token}&jan_code=${this.item.jan}&image_size=76&results=1&price_from=${this.user.y_lower_bound}&price_to=${this.user.y_upper_bound}&in_stock=true&sort=%2Bprice&condition=new`;

		console.log(url);
		console.log(this.token);
		console.log(this.item.jan);

		console.log('axios start>>>>>>>>>>>>>>>>>>>>>>>>');
		await axios
			.get(url, {})
			.then(async (res) => {
				if (res !== undefined && res.data.hits.length > 0) {
					this.result = res.data.hits[0];

					this.query.y_img_url = this.result.image.small;
					this.query.item_name = this.result.name;
					this.query.y_min_price = Number(this.result.price);
					this.query.y_shop_url = this.result.url;
					this.query.is_mailed = 0;

					const d = new Date();
					this.query.updated_time =
						d.getFullYear() +
						"." +
						(d.getMonth() + 1) +
						"." +
						d.getDate() +
						" " +
						(d.getHours() < 10 ? "0" + d.getHours() : d.getHours()) +
						":" +
						(d.getMinutes() < 10 ? "0" + d.getMinutes() : d.getMinutes()) +
						":" +
						(d.getSeconds() < 10 ? "0" + d.getSeconds() : d.getSeconds());
				} else {
					this.query.item_name = "JANに一致する商品は見つかりませんでした。";
					this.query.status = 0;
				}

				var searchQuery = { jan: this.item.jan, user_id: this.user.id };

				yahoo_list.update(this.query, {
					where: searchQuery,
				});
				
				yahoo_list.findAll({ where: searchQuery }).then(async (data) => {
					if (this.query.y_min_price == 0) return;
					if (this.query.y_min_price < this.item.y_target_price && this.item.is_mailed == 0) {
						var tar_price = "前回の価格:" + this.item.y_register_price;
						var cur_price = "今回の価格:" + this.query.y_min_price;
						var productUrl = "URL:" + this.item.y_img_url;
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
								productImgUrl,
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
								query.is_mailed = 1;
								products.update(query, { where: condition });

								// var note = {
								// 	code: data,
								// 	user_id: this.user.id,
								// };
								// errors.create(note);
							})
							.catch(function (err) {
								console.log("cant notify to discord>>>>>>>>>>", err.message);
							});
					}
				});
				console.log('axios success>>>>>>>>>>>>>>>>>>>>>>>>>');
			})
			.catch((err) => {
				console.log("update error>>>>>>>>>>", err.message);
			});
	}
}
