const express = require('express');
const router = express.Router();
const md5 = require('md5');
const ObjectId = require('mongodb').ObjectId;
// const mysql = require('mysql');
const MongoClient = require('mongodb').MongoClient;
const url = "mongodb://localhost:27017/";

router.get('/range/:date1/:date2', async function (req, res, next) {
  // console.log('reached endpoint');
  try {
    let date1 = req.params.date1;
    let date2 = req.params.date2;
    // console.log(object_id)
    MongoClient.connect(url, function(err, db) {
      if (err) throw err;
      var dbo = db.db("dataapp");
      var entry = dbo.collection('riverdata').find({SampleDate: {$gte: new Date(date1), $lte: new Date(date2)}}).toArray();

      // apparently alreadyexists is a promise?
      entry.then((resolved) => {
        res.send({ status: 1, entries: resolved });
      });
    })
  } catch (error) {
    res.send({ status: 0, error: error });
  }
});
module.exports = router;
