const express = require('express');
const router = express.Router();
const user = require('./user');
const chats = require('./chats');
router.get('/', async function (req, res, next) {
    // console.log('got');
    res.send({ status: 1});
});
router.use('/user', user);
router.use('/chats', chats);
module.exports = router;
