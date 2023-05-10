module.exports = (sequelize, Sequelize) => {
	const ItemList = sequelize.define("items", {
		user_id: {
			type: Sequelize.INTEGER
		},
		category_id: {
			type: Sequelize.INTEGER
		},
		img_url: {
			type: Sequelize.STRING
		},
		name: {
			type: Sequelize.STRING
		},
		asin: {
			type: Sequelize.STRING
		},
		jan: {
			type: Sequelize.STRING
		},
		register_price: {
			type: Sequelize.INTEGER
		},
		target_price: {
			type: Sequelize.INTEGER
		},
		min_price: {
			type: Sequelize.INTEGER
		},
		shop_url: {
			type: Sequelize.STRING
		},
		status: {
			type: Sequelize.INTEGER
		},
		is_notified: {
			type: Sequelize.INTEGER
		},
	},
	{ 
		timestamps: false
	});
	return ItemList;
};