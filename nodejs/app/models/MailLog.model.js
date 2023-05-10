module.exports = (sequelize, Sequelize) => {
	const MailLogList = sequelize.define("mail_logs", {
		user_id: {
			type: Sequelize.INTEGER
		},
		category_id: {
			type: Sequelize.INTEGER
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