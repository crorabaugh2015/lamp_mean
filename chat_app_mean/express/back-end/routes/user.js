const express = require('express');
const router = express.Router();
const md5 = require('md5');
const jwt = require('jsonwebtoken');
// const mysql = require('mysql');
const MongoClient = require('mongodb').MongoClient;
const url = "mongodb://localhost:27017/";
// const con = mysql.createConnection({
//   host: "localhost",
//   user: "root",
//   password: "",
//   database: "simpleangular"
// });

router.get('/register', async function (req, res, next) {
  res.send({ status: 1});
});

/* GET users listing. */
router.post('/register', async function (req, res, next) {
  // console.log('got url');
  try {
    let { username, email, password } = req.body; 
   
    const hashed_password = md5(password.toString())
    // const checkUsername = `Select username FROM users WHERE username = ?`;
    // con.query(checkUsername, [username], (err, result, fields) => {
    //   if(!result.length){
    //     const sql = `Insert Into users (username, email, password) VALUES ( ?, ?, ? )`
    //     con.query(
    //       sql, [username, email, hashed_password],
    //     (err, result, fields) =>{
    //       if(err){
    //         res.send({ status: 0, data: err });
    //       }else{
    //         let token = jwt.sign({ data: result }, 'secret')
    //         res.send({ status: 1, data: result, token : token });
    //       }
         
    //     })
    //   }
    // });


    MongoClient.connect(url, function(err, db) {
      if (err) throw err;
      var dbo = db.db("chatapp");
      var myobj = { username: username, password: hashed_password };
      var alreadyExists = dbo.collection('users').countDocuments( { "username": username } );

      //apparently alreadyexists is a promise?
      alreadyExists.then((resolved) => {
        if (resolved > 0) {
          console.log("User already exists!");
          res.send({ status: 0, error: 'exists' });
        } else {
          dbo.collection("users").insertOne(myobj, function(err2, res2) {
            if (err2) throw err2;
            console.log("1 document inserted");
            // console.log(username);
            let token = jwt.sign({ data: username }, 'secret');
            res.send({ status: 1, data: username, token: token });
            db.close();
          });
        }
      });
    });
  } catch (error) {
    res.send({ status: 0, error: error });
  }
});
router.post('/login', async function (req, res, next) {
  // console.log('reached endpoint');
  try {
    let { username, password } = req.body; 
    MongoClient.connect(url, function(err, db) {
      if (err) throw err;
      const hashed_password = md5(password.toString());
      // console.log(username, hashed_password);
      // console.log('yes');
      var dbo = db.db("chatapp");
      var userExists = dbo.collection('users').countDocuments( { "username": username, "password": hashed_password } );
      // console.log(userExists);
      userExists.then((resolved) => {
        // console.log(resolved);
        if (resolved > 0) {
          console.log("User exists!");
          
          let token = jwt.sign({ data: username }, 'secret');
          res.send({ status: 1, data: username, token: token });
        } else {
          console.log("Incorrect username or password");
          res.send({ status: 0, error: 'incorrect username/password' });
          // dbo.collection("users").insertOne(myobj, function(err2, res2) {
          //   if (err2) throw err2;
          //   console.log("1 document inserted");
          //   let token = jwt.sign({ data: res2 }, 'secret');
          //   res.send({ status: 1, data: res2, token: token });
          //   db.close();
          // });
        }
      });
    });

    // const sql = `SELECT * FROM users WHERE username = ? AND password = ?`
    // con.query(
    //   sql, [username, hashed_password],
    // function(err, result, fields){
    //   if(err){
    //     res.send({ status: 0, data: err });
    //   }else{
    //     let token = jwt.sign({ data: result }, 'secret')
    //     res.send({ status: 1, data: result, token: token });
    //   }
     
    // })
  } catch (error) {
    res.send({ status: 0, error: error });
  }
});
module.exports = router;
