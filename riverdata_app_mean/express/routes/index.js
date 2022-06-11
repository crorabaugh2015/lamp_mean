const express = require('express');
const router = express.Router();
const data = require('./data');
router.get('/', async function (req, res, next) {
    // console.log('got');
    res.send({ status: 1});
});
router.use('/data', data);
module.exports = router;
