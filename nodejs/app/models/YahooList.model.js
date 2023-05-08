module.exports = (sequelize, Sequelize) => {
  const YahooList = sequelize.define("items", {
    user_id: {
      type: Sequelize.INTEGER
    },
    y_img_url: {
      type: Sequelize.STRING
    },
    item_name: {
      type: Sequelize.STRING
    },
    code_kind: {
      type: Sequelize.INTEGER
    },
    asin: {
      type: Sequelize.STRING
    },
    jan: {
      type: Sequelize.STRING
    },

    y_register_price: {
      type: Sequelize.INTEGER
    },
    y_target_price: {
      type: Sequelize.INTEGER
    },
    y_min_price: {
      type: Sequelize.INTEGER
    },
    postage: {
      type: Sequelize.INTEGER
    },
    y_shop_url: {
      type: Sequelize.STRING
    },
    
    status: {
      type: Sequelize.INTEGER
    },
    is_mailed: {
      type: Sequelize.INTEGER
    },
    updated_time: {
      type: Sequelize.STRING
    },
  },
  { 
    timestamps: false
  });
  return YahooList;
};