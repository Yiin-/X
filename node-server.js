const htmlPdf = require('html-pdf-chrome')
const fs = require('fs')
const app = require('express')()
const bodyParser = require('body-parser')
const mkdirp = require('mkdirp')
const getDirName = require('path').dirname
const slash = require('slash')

app.use(bodyParser.json())

const options = {
  completionTrigger: new htmlPdf.CompletionTrigger.Timer(500),
  port: 9222
}

app.post('/html_to_pdf', (req, res) => {
  htmlPdf.create(req.body.html.toString(), options).then((pdf) => {
    const path = slash(req.body.save_to_path.toString())

    mkdirp(getDirName(path), function (err) {
      if (err) return

      fs.writeFile(path, pdf.toBuffer())
      res.status(200).send()
    })
  }).catch((e) => {
    res.status(500).send(e)
  })
})

app.listen(3000)
