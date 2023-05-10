module.exports = (sequelize, Sequelize) => {
	const CategoryList = sequelize.define("categories", {
		user_id: {
			type: Sequelize.INTEGER
		},
		name: {
			type: Sequelize.STRING
		},
		access_key: {
			type: Sequelize.STRING
		},
		secret_key: {
			type: Sequelize.STRING
		},
		partner_tag: {
			type: Sequelize.STRING
		},
		yahoo_id: {
			type: Sequelize.STRING
		},
		target_price: {
			type: Sequelize.STRING
		},
		fall_pro: {
			type: Sequelize.INTEGER
		},
		web_hook: {
			type: Sequelize.STRING
		},
		len: {
			type: Sequelize.INTEGER
		},
		file_name: {
			type: Sequelize.STRING
		},
		reg_num: {
			type: Sequelize.INTEGER
		},
		trk_num: {
			type: Sequelize.INTEGER
		},
		is_reg: {
			type: Sequelize.INTEGER
		},
		stop: {
			type: Sequelize.INTEGER
		},
		round: {
			type: Sequelize.INTEGER
		},
	},
	{ 
		timestamps: false
	});
	return CategoryList;
};