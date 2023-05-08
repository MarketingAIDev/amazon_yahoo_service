const express = require("express");
const router = express.Router();
const yahoo = require("../controllers/yahoo.controller.js");

router.post("/get_info", yahoo.getInfo);

module.exports = router;
