const axios = require("axios");
const { itemList, categoryList } = require("../models");
const amazonPaapi = require("amazon-paapi");

class GetItemInfo {
	constructor(category, code) {
		this.category = category;
		this.code = code;
		this.jan = "";
		this.result = {};
		this.query = {};
		this.yahooData = [];
	}

	async asinToJan() {
		// convert asin into jan
		this.query.asin = this.code;
		this.query.category_id = this.category.id;

		let commonParameters = {
			AccessKey: this.category.access_key,
			SecretKey: this.category.secret_key,
			PartnerTag: this.category.partner_tag,
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
		
		await amazonPaapi
			.GetItems(commonParameters, requestParameters)
			.then((amazonData) => {
				try {
					if (amazonData.ItemsResult.Items[0].ItemInfo === undefined) {
						this.query.name = `ASIN ${this.code}に一致する商品は見つかりませんでした。`;
						this.query.status = 0;
						this.jan = null;
						this.result = undefined;
					} else {
						this.jan = amazonData.ItemsResult.Items[0].ItemInfo.ExternalIds.EANs.DisplayValues[0];
						console.log('JAN code>>>>>>>>>>>>>>>>>>>', this.jan);
					}
				} catch (err) {
					console.log(
						"---------- amazon data THEN error ----------",
						err.message
					);
				}
			})
			.catch((err) => {
				console.log("---------- amazon data CATCH error ----------", err.message);
			});

		return;
	}

	async getYahooInfo() {
		// gets the information of items from YAHOO
		this.query.jan = this.jan;
		if (this.jan != null) {
			let url = `https://shopping.yahooapis.jp/ShoppingWebService/V3/itemSearch?appid=${this.category.yahoo_id}&affiliate_type=vc&affiliate_id=https%3A%2F%2Fck.jp.ap.valuecommerce.com%2Fservlet%2Freferral%3Fsid%3D3691564%26pid%3D889248890%26vc_url%3D&jan_code=${this.jan}&image_size=76&results=1&price_from=${this.category.target_price}&in_stock=true&sort=%2Bprice&condition=new`;

			await axios
				.get(url, {})
				.then(async (res) => {
					if (res !== undefined && res.data.hits.length > 0) {
						this.yahooData = res.data.hits;
						this.result = this.yahooData[0];
					} else {
						this.query.name = "JANに一致する商品は見つかりませんでした。";
						this.query.status = 0;
					}
				})
				.catch((err) => {
					console.log(
						"---------- yahoo data CATCH error ----------",
						err.message
					);
				});
		}
		return;
	}

	async processResult() {
		try {
			this.query.img_url = this.result.image.small;
			this.query.name = this.result.name;
			this.query.register_price = Number(this.result.price);
			this.query.min_price = this.query.register_price;
			this.query.shop_url = this.result.url;

			this.query.target_price = Math.floor(
				(this.query.register_price * (100 - this.category.fall_pro)) / 100
			);

			if (this.result.price > 0) {
				this.query.status = 1;
			}

			this.query.is_notified = 0;
		} catch (err) {
			console.log(
				"---------- data process error ----------",
				err.message
			);
		}
		return;
	}

	operDB() {
		this.query.user_id = this.category.user_id;

		let condition = {
			user_id: this.category.user_id,
			asin: this.code
		};

		itemList
			.findAll({ where: condition })
			.then((data) => {
				if (data.length) {
					itemList.update(this.query, { where: condition });
				} else {
					itemList.create(this.query);
				}
			})
			.catch((err) => {
				console.log(
					"----------database error----------",
					err.message
				);
			});
	}

	async main() {
		await this.asinToJan();
		await this.getYahooInfo();
		await this.processResult();
		await this.operDB();
	}
}

const yahooInput = async (category, codeList) => {
	try {
		// await itemList.destroy({where: {'category_id': category.id}});

		var index = 0;
		var len = codeList.length;

		var inputInterval = setInterval(() => {
			if (index < len) {
				let getItemInfo = new GetItemInfo(category, codeList[index]);
				getItemInfo.main();
				index++;

				let query = {};
				query.is_reg = 1;
				query.len = len;
				query.reg_num = index;
				
				categoryList.update(query, {where: {id: category.id}});
			} else {
				let query = {};
				query.is_reg = 0;
				query.round = 0;
				query.stop = 0;			
				setTimeout(() => {
					categoryList.update(query, {where: {id: category.id}});
				}, 5000);
				clearInterval(inputInterval);
			}
		}, 2100);
	} catch (err) {
		console.log(err);
	}
};

exports.getInfo = (req, res) => {
	let reqData = JSON.parse(req.body.asin);

	categoryList
		.findByPk(reqData.category_id)
		.then((category) => {
			yahooInput(category, reqData.codes);
		})
		.catch((err) => {
			console.log("Cannot get user information.", err);
		});
};