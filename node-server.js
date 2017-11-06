const htmlPdf = require('html-pdf-chrome')
const fs = require('fs')
const app = require('express')()
const bodyParser = require('body-parser')
const mkdirp = require('mkdirp')
const getDirName = require('path').dirname
const slash = require('slash')

app.use(bodyParser.json())

const options = {
  completionTrigger: new htmlPdf.CompletionTrigger.Timer(1000)
  // port: 9222
}

app.post('/html_to_pdf', (req, res) => {
  console.log('> html_to_pdf')
  console.log('Generating invoice pdf to ' + req.body.save_to_path.toString())
  htmlPdf.create(req.body.html.toString(), options).then((pdf) => {
    const path = slash(req.body.save_to_path.toString())
    console.log('Generated successfully.')

    mkdirp(getDirName(path), function (err) {
      if (err) {
        console.log('Couldnt generate directory', err)
        res.status(500).send(err)
        return
      }

      console.log('Sending response')

      fs.writeFile(path, pdf.toBuffer())
      res.status(200).send()
    })
  }).catch((e) => {
    console.log('Failed', e)
    res.status(500).send(e)
  })
})

app.listen(3000)
