module.exports = (sequelize, Sequelize) => {
	const UserList = sequelize.define(
		"users",
		{
			email: {
				type: Sequelize.STRING,
			},
			password: {
				type: Sequelize.STRING,
			},
			y_register_percent: {
				type: Sequelize.INTEGER,
			},
			y_lower_bound: {
				type: Sequelize.INTEGER,
			},
			y_upper_bound: {
				type: Sequelize.INTEGER,
			},
			fee_include: {
				type: Sequelize.INTEGER,
			},
			ex_key: {
				type: Sequelize.INTEGER,
			},
			is_tracking: {
				type: Sequelize.INTEGER,
			},
			is_permitted: {
				type: Sequelize.INTEGER,
			},
			register_number: {
				type: Sequelize.INTEGER,
			},
			is_registering: {
				type: Sequelize.INTEGER,
			},
			len: {
				type: Sequelize.INTEGER,
			},
			name: {
				type: Sequelize.STRING,
			},
			_token: {
				type: Sequelize.STRING,
			},
			yahoo_token: {
				type: Sequelize.STRING,
			},
			yahoo_token1: {
				type: Sequelize.STRING,
			},
			yahoo_token2: {
				type: Sequelize.STRING,
			},
			access_key: {
				type: Sequelize.STRING,
			},
			secret_key: {
				type: Sequelize.STRING,
			},
			partner_tag: {
				type: Sequelize.STRING,
			},
			web_hook: {
				type: Sequelize.STRING,
			},
			fall_pro: {
				type: Sequelize.INTEGER,
			},
			total: {
				type: Sequelize.INTEGER,
			},
		},
		{
			timestamps: false,
		}
	);
	return UserList;
};
