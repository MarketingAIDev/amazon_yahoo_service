module.exports = (sequelize, Sequelize) => {
	const MailLogList = sequelize.define("mail_logs", {
		user_id: {
			type: Sequelize.INTEGER
		},
		category_id: {
			type: Sequelize.STRING
		},
		asin: {
			type: Sequelize.STRING
		},
		msg: {
			type: Sequelize.STRING
		}
	},
	{ 
		timestamps: false
	});
	return MailLogList;
};