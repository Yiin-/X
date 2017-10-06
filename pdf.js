const htmlPdf = require('html-pdf-chrome')
const fs = require('fs')
const app = require('express')

const options = {
  port: 9222
}

app.post('/pdf/raw', (req, res) => {
  htmlPdf.create(res.body.html, options).then((pdf) => {
    res.send()
  })
})

app.listen(3000)

fs.readFile('test.html', (err, data) => {
  if (err) {
    throw err
  }

  htmlPdf.create(data, options).then((pdf) => {
    fs.writeFile('test.pdf', pdf.toBuffer())
  })
})
