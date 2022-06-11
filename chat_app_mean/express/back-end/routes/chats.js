const express = require('express');
const router = express.Router();
const md5 = require('md5');
const jwt = require('jsonwebtoken');
const ObjectId = require('mongodb').ObjectId;
// const mysql = require('mysql');
const MongoClient = require('mongodb').MongoClient;
const url = "mongodb://localhost:27017/";
// const con = mysql.createConnection({
//   host: "localhost",
//   user: "root",
//   password: "",
//   database: "simpleangular"
// });

// router.get('/register', async function (req, res, next) {
//   res.send({ status: 1});
// });

// /* GET users listing. */
// router.post('/register', async function (req, res, next) {
//   // console.log('got url');
//   try {
//     let { username, email, password } = req.body; 
   
//     const hashed_password = md5(password.toString())
//     // const checkUsername = `Select username FROM users WHERE username = ?`;
//     // con.query(checkUsername, [username], (err, result, fields) => {
//     //   if(!result.length){
//     //     const sql = `Insert Into users (username, email, password) VALUES ( ?, ?, ? )`
//     //     con.query(
//     //       sql, [username, email, hashed_password],
//     //     (err, result, fields) =>{
//     //       if(err){
//     //         res.send({ status: 0, data: err });
//     //       }else{
//     //         let token = jwt.sign({ data: result }, 'secret')
//     //         res.send({ status: 1, data: result, token : token });
//     //       }
         
//     //     })
//     //   }
//     // });


//     MongoClient.connect(url, function(err, db) {
//       if (err) throw err;
//       var dbo = db.db("chatapp");
//       var myobj = { username: username, password: hashed_password };
//       var alreadyExists = dbo.collection('users').countDocuments( { "username": username } );

//       //apparently alreadyexists is a promise?
//       alreadyExists.then((resolved) => {
//         if (resolved > 0) {
//           console.log("User already exists!");
//           res.send({ status: 0, error: 'exists' });
//         } else {
//           dbo.collection("users").insertOne(myobj, function(err2, res2) {
//             if (err2) throw err2;
//             console.log("1 document inserted");
//             // console.log(username);
//             let token = jwt.sign({ data: username }, 'secret');
//             res.send({ status: 1, data: username, token: token });
//             db.close();
//           });
//         }
//       });
//     });
//   } catch (error) {
//     res.send({ status: 0, error: error });
//   }
// });
router.post('/create', async function (req, res, next) {
  // console.log('reached endpoint');
  try {
    let { username_from, username_to, message } = req.body; 
    console.log(username_from, username_to, message)

    MongoClient.connect(url, function(err, db) {
      if (err) throw err;
      var dbo = db.db("chatapp");
      var alreadyExists = dbo.collection('users').countDocuments( { "username": username_to } );

      //apparently alreadyexists is a promise?
      alreadyExists.then((resolved) => {
        if (resolved > 0) {

          
            // console.log(username, hashed_password);
            // console.log('yes');
            var myobj = { username_from: username_from, username_to: username_to, message: message, seen: 0, datetime_created: new Date() };
            
              dbo.collection("chats").insertOne(myobj, function(err2, res2) {
              if (err2) throw err2;
              console.log("1 document inserted");
              // console.log(username);
              res.send({ status: 1 });
              db.close();
              });
          
        } else {
          res.send({ status: 0, error: 'no user found!' });
        }
      });
    })
  } catch (error) {
    res.send({ status: 0, error: error });
  }
});


router.get('/list/:username', async function (req, res, next) {
  // console.log('reached endpoint');
  try {
    let username_to = req.params.username; 
    MongoClient.connect(url, function(err, db) {
      if (err) throw err;
      var dbo = db.db("chatapp");
      var unseen_entries = dbo.collection('chats').find( { "username_to": username_to, "seen": 0 } ).toArray();

      // apparently alreadyexists is a promise?
      unseen_entries.then((resolved) => {
        var seen_entries = dbo.collection('chats').find( { "username_to": username_to, "seen": 1 } ).toArray();
        seen_entries.then((resolved2) => {
          res.send({ status: 1, unseen: resolved, seen: resolved2 });
        });
      });
    })
  } catch (error) {
    res.send({ status: 0, error: error });
  }
});

router.get('/chat/:id', async function (req, res, next) {
  // console.log('reached endpoint');
  try {
    let object_id = req.params.id;
    // console.log(object_id)
    MongoClient.connect(url, function(err, db) {
      if (err) throw err;
      var dbo = db.db("chatapp");
      var entry = dbo.collection('chats').find( { "_id": new ObjectId(object_id) } ).toArray();

      // apparently alreadyexists is a promise?
      entry.then((resolved) => {
        dbo.collection("chats").updateOne({"_id": new ObjectId(object_id)}, {$set:{seen: 1}}, function(err2, res2) {
          if (err2) throw err2;
          console.log(res2);
        });

        res.send({ status: 1, entry: resolved });
      });
    })
  } catch (error) {
    res.send({ status: 0, error: error });
  }
});
module.exports = router;
