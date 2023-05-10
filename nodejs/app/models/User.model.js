module.exports = (sequelize, Sequelize) => {
	const UserList = sequelize.define("users", {
		_token: {
			type: Sequelize.STRING,
		},
		name: {
			type: Sequelize.STRING,
		},
		email: {
			type: Sequelize.STRING,
		},
		password: {
			type: Sequelize.STRING,
		},
		role: {
			type: Sequelize.STRING,
		}
	},
	{
		timestamps: false,
	});
	return UserList;
};
