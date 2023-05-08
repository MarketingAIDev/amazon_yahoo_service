module.exports = {
	HOST: "localhost",
	USER: "xs786968_admin",
	PASSWORD: "admin123",
	DB: "xs786968_db",
	dialect: "mysql",
	pool: {
		max: 5,
		min: 0,
		acquire: 30000,
		idle: 10000
	}
};