const express = require('express');
const app = express();
const mysql = require('mysql');

const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'obituary_platform'
});

db.connect((err) => {
  if (err) {
    console.error('Error connecting to database:', err);
    return;
  }
  console.log('Connected to database');
});

app.use(express.json());

app.post('/submitObituary', (req, res) => {
  const { name, D_O_B, D_O_D, author, content } = req.body;

  const sql = `INSERT INTO obituary (name, D_O_B, D_O_D, author, content) VALUES (?,?,?,?,?)`;
  const values = [name, D_O_B, D_O_D, author, content];

  db.query(sql, values, (err, results) => {
    if (err) {
      console.error('Error inserting into database:', err);
      res.status(500).send({ message: 'Error inserting into database' });
    } else {
      res.send({ message: 'New record created successfully' });
    }
  });
});

app.listen(3000, () => {
  console.log('Server listening on port 3000');
});