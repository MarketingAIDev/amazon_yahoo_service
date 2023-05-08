const axios = require("axios");
const { yahoo_list, user_list } = require("../models");
const amazonPaapi = require("amazon-paapi");

class GetItemInfo {
	constructor(user, code, token) {
		this.user = user;
		this.code = code;
		this.token = token;
		this.jan = "";
		this.result = {};
		this.query = {};
		this.yahooData = [];
	}

	async asinToJan() {
		// convert asin into jan
		this.query.asin = this.code;

		let commonParameters = {
			AccessKey: this.user.access_key,
			SecretKey: this.user.secret_key,
			PartnerTag: this.user.partner_tag,
			PartnerType: "Associates",
			Marketplace: "www.amazon.co.jp",
		};

		console.log(commonParameters);

		let requestParameters = {
			// this is the parameter to get information with asin from amazon
			ItemIds: [this.code],
			ItemIdType: "ASIN",
			Condition: "New",
			Resources: [
				"ItemInfo.ExternalIds", // this is neccesary to get EANs from asin
			],
		};

		console.log(requestParameters);

		await amazonPaapi
			.GetItems(commonParameters, requestParameters)
			.then((amazonData) => {
				try {
					if (amazonData.ItemsResult.Items[0].ItemInfo === undefined) {
						this.query.item_name =
							"ASIN " + this.code + "に一致する商品は見つかりませんでした。";
						this.query.status = 0;
						this.jan = null;
						this.result = undefined;
					} else {
						this.jan =
							amazonData.ItemsResult.Items[0].ItemInfo.ExternalIds.EANs.DisplayValues[0];
					}
				} catch (err) {
					console.log(
						"----------amazon data THEN error----------",
						err.message
					);
				}
			})
			.catch((err) => {
				console.log("----------amazon data CATCH error----------", err.message);
			});

		return;
	}

	async getYahooInfo() {
		// gets the information of items from YAHOO
		this.query.jan = this.jan;
		if (this.jan != null) {
			let url = `https://shopping.yahooapis.jp/ShoppingWebService/V3/itemSearch?appid=${this.token}&jan_code=${this.jan}&image_size=76&results=1&price_from=${this.user.y_lower_bound}&price_to=${this.user.y_upper_bound}&in_stock=true&sort=%2Bprice&condition=new`;

			await axios
				.get(url, {})
				.then(async (res) => {
					if (res !== undefined && res.data.hits.length > 0) {
						this.yahooData = res.data.hits;
						this.result = this.yahooData[0];
					} else {
						this.query.item_name = "JANに一致する商品は見つかりませんでした。";
						this.query.status = 0;
					}
				})
				.catch((err) => {
					console.log(
						"----------yahoo data CATCH error----------",
						err.message
					);
				});
		}
		return;
	}

	async processResult() {
		if (this.result === undefined) return;
		try {
			this.query.y_img_url = this.result.image.small;
			this.query.item_name = this.result.name;
			this.query.y_register_price = Number(this.result.price);
			this.query.y_min_price = this.query.y_register_price;
			this.query.y_shop_url = this.result.url;

			this.query.y_target_price = Math.floor(
				(this.query.y_register_price * this.user.y_register_percent) / 100
			);

			if (this.result.price > 0) {
				this.query.status = 1;
			}

			this.query.is_mailed = 0;
		} catch (err) {
			console.log(err);
		}
		return;
	}

	operDB() {
		this.query.user_id = this.user.id;
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

		let condition = {
			user_id: this.user.id,
			asin: this.code
		};

		yahoo_list
			.findAll({ where: condition })
			.then((data) => {
				if (data.length) {
					yahoo_list.update(this.query, { where: condition });
				} else {
					yahoo_list.create(this.query);
				}
			})
			.catch((error) => {
				console.log("----------database error----------", error.message);
			});
	}

	async main() {
		await this.asinToJan();
		await this.getYahooInfo();
		await this.processResult();
		await this.operDB();
	}
}

const yahooInput = (user, list) => {
	try {
		var index = 0;
		var len = list.length;
		var token = [user.yahoo_token, user.yahoo_token1, user.yahoo_token2];

		var inputInterval = setInterval(() => {
			var finishQuery = {};
			if (index <= len) {
				let getItemInfo = new GetItemInfo(user, list[index], token[index % 3]);
				getItemInfo.main();

				finishQuery.is_registering = 1;
				finishQuery.register_number = index;
				user_list.update(finishQuery, { where: { id: user.id } });

				index++;
			} else {
				clearInterval(inputInterval);
				if (user.is_tracking == 0) finishQuery.is_tracking = 1;

				setTimeout(() => {
					finishQuery.is_registering = 0;
					finishQuery.register_number = 0;
					user_list.update(finishQuery, { where: { id: user.id } });
				}, 6000);
			}
		}, 2100);
	} catch (err) {
		console.log(err);
	}
};

exports.getInfo = (req, res) => {
	let user_id = JSON.parse(req.body.index);
	let item_list = JSON.parse(req.body.code);

	user_list
		.findByPk(user_id)
		.then((user) => {
			yahooInput(user, item_list);
		})
		.catch((err) => {
			console.log("Cannot get user information.", err);
		});
};
