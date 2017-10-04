const htmlPdf = require('html-pdf-chrome');
const fs = require('fs');
const microtime = require('microtime')

const options = {
  port: 9222
};

fs.readFile('test.html', function (err, data) {
  if (err) throw err;

  const ms = microtime.now()

  htmlPdf.create(data, options).then((pdf) => {
    console.log((microtime.now() - ms) / 1000000)
    fs.writeFile("test.pdf", pdf.toBuffer())
  }).catch((err) => console.log(err));
});