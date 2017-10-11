<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns=3D"http://www.w3.org/1999/xhtml" lang=3D"en" xml:lang=3D"en">
  <head>
    <meta http-equiv=3D"Content-Type" content=3D"text/html; charset=3Dutf-8">
    <meta name=3D"viewport" content=3D"width=3Ddevice-width">
    <style>
      /**
  * IMPORTANT:
  * Please read before changing anything, CSS involved in our HTML emails is
  * extremely specific and written a certain way for a reason. It might not make
  * sense in a normal setting but Outlook loves it this way.
  *
  * !!! [override] prevents Yahoo Mail breaking media queries. It must be used
  * !!! at the beginning of every line of CSS inside a media query.
  * !!! Do not remove.
  *
  * !!! div[style*=3D"margin: 16px 0"] allows us to target a weird margin
  * !!! bug in Android's email client.
  * !!! Do not remove.
  *
  * Also, the img files are hosted on S3. Please don't break these URLs!
  * The images are also versioned by date, so please update the URLs accordingly
  * if you create new versions
  *
***/


/**
  * # Root
  * - CSS resets and general styles go here.
**/

html, body,
a, span, div[style*=3D"margin: 16px 0"] {
  border: 0 !important;
  margin: 0 !important;
  outline: 0 !important;
  padding: 0 !important;
  text-decoration: none !important;
}

a, span,
td, th {
  -webkit-font-smoothing: antialiased !important;
  -moz-osx-font-smoothing: grayscale !important;
}

/**
  * # Delink
  * - Classes for overriding clients which creates links out of things like
  *   emails, addresses, phone numbers, etc.
**/

span.st-Delink a {
  color: #525f7f !important;
  text-decoration: none !important;
}

/** Modifier: preheader */
span.st-Delink.st-Delink--preheader a {
  color: white !important;
  text-decoration: none !important;
}
/** */

/** Modifier: footer */
span.st-Delink.st-Delink--footer a {
  color: #8898aa !important;
  text-decoration: none !important;
}
/** */

/**
  * # Header
**/

table.st-Header td.st-Header-background div.st-Header-area {
  height: 76px !important;
  width: 600px !important;
  background-repeat: no-repeat !important;
  background-size: 600px 76px !important;
}

table.st-Header td.st-Header-logo div.st-Header-area {
  height: 21px !important;
  width: 49px !important;
  background-repeat: no-repeat !important;
  background-size: 49px 21px !important;
}


/**
  * # Retina
  * - Targets high density displays and devices smaller than 768px.
  *
  * ! For mobile specific styling, see `# Mobile`.
**/

@media (-webkit-min-device-pixel-ratio: 1.25), (min-resolution: 120dpi), all and (max-width: 768px) {

  /**
    * # Target
    * - Hides images in these devices to display the larger version as a
    *   background image instead.
  **/

  /** Modifier: mobile */
  body[override] div.st-Target.st-Target--mobile img {
    display: none !important;
    margin: 0 !important;
    max-height: 0 !important;
    min-height: 0 !important;
    mso-hide: all !important;
    padding: 0 !important;
    font-size: 0 !important;
    line-height: 0 !important;
  }
  /** */

  /**
    * # Header
  **/

  body[override] table.st-Header td.st-Header-background div.st-Header-area {
    background-image: url('https://stripe-images.s3.amazonaws.com/html_emails/2017-08-21/header/Header-background.png') !important;
  }

  /** Modifier: white */
  body[override] table.st-Header.st-Header--white td.st-Header-background div.st-Header-area {
    background-image: url('https://stripe-images.s3.amazonaws.com/html_emails/2017-08-21/header/Header-background--white.png') !important;
  }
  /** */

  /** Modifier: simplified */
  body[override] table.st-Header.st-Header--simplified td.st-Header-logo div.st-Header-area {
    background-image: url('https://stripe-images.s3.amazonaws.com/html_emails/2017-08-21/header/Header-logo.png') !important;
  }
  /** */

}

/**
  * # Mobile
  * - This affects emails views in clients less than 600px wide.
**/

@media all and (max-width: 600px) {

  /**
    * # Wrapper
  **/

  body[override] table.st-Wrapper,
  body[override] table.st-Width.st-Width--mobile {
    min-width: 100% !important;
    width: 100% !important;
  }

  /**
    * # Spacer
  **/

  /** Modifier: gutter */
  body[override] td.st-Spacer.st-Spacer--gutter {
    width: 32px !important;
  }
  /** */

  /** Modifier: kill */
  body[override] td.st-Spacer.st-Spacer--kill {
    width: 0 !important;
  }
  /** */

  /** Modifier: emailEnd */
  body[override] td.st-Spacer.st-Spacer--emailEnd {
    height: 32px !important;
  }
  /** */

  /**
    * # Font
  **/

  /** Modifier: title */
  body[override] td.st-Font.st-Font--title,
  body[override] td.st-Font.st-Font--title span,
  body[override] td.st-Font.st-Font--title a {
    font-size: 28px !important;
    line-height: 36px !important;
  }
  /** */

  /** Modifier: header */
  body[override] td.st-Font.st-Font--header,
  body[override] td.st-Font.st-Font--header span,
  body[override] td.st-Font.st-Font--header a {
    font-size: 24px !important;
    line-height: 32px !important;
  }
  /** */

  /** Modifier: body */
  body[override] td.st-Font.st-Font--body,
  body[override] td.st-Font.st-Font--body span,
  body[override] td.st-Font.st-Font--body a {
    font-size: 18px !important;
    line-height: 28px !important;
  }
  /** */

  /** Modifier: caption */
  body[override] td.st-Font.st-Font--caption,
  body[override] td.st-Font.st-Font--caption span,
  body[override] td.st-Font.st-Font--caption a {
    font-size: 14px !important;
    line-height: 20px !important;
  }
  /** */

  /**
    * # Header
  **/
  body[override] table.st-Header td.st-Header-background div.st-Header-area {
    margin: 0 auto !important;
    width: auto !important;
    background-position: 0 0 !important;
  }

  body[override] table.st-Header td.st-Header-background div.st-Header-area {
    background-image: url('https://stripe-images.s3.amazonaws.com/html_emails/2017-08-21/header/Header-background--mobile.png') !important;
  }

  /** Modifier: white */
  body[override] table.st-Header.st-Header--white td.st-Header-background div.st-Header-area {
    background-image: url('https://stripe-images.s3.amazonaws.com/html_emails/2017-08-21/header/Header-background--white--mobile.png') !important;
  }
  /** */

  /** Modifier: simplified */
  body[override] table.st-Header.st-Header--simplified td.st-Header-logo {
    width: auto !important;
  }

  body[override] table.st-Header.st-Header--simplified td.st-Header-spacing {
    width: 0 !important;
  }

  body[override] table.st-Header.st-Header--simplified td.st-Header-logo div.st-Header-area {
    margin: 0 auto !important;
    background-image: url('https://stripe-images.s3.amazonaws.com/html_emails/2017-08-21/header/Header-logo.png') !important;
  }
  /** */

  /**
    * # Divider
  **/

  body[override] table.st-Divider td.st-Spacer.st-Spacer--gutter,
  body[override] tr.st-Divider td.st-Spacer.st-Spacer--gutter {
    background-color: #e6ebf1;
  }

  /**
    * # Blocks
  **/

  body[override] table.st-Blocks table.st-Blocks-inner {
      border-radius: 0 !important;
  }

  body[override] table.st-Blocks table.st-Blocks-inner table.st-Blocks-item td.st-Blocks-item-cell {
      display: block !important;
  }

  /**
    * # Button
  **/

  body[override] table.st-Button {
      margin: 0 auto !important;
      width: 100% !important;
  }

  body[override] table.st-Button td.st-Button-area,
  body[override] table.st-Button td.st-Button-area a.st-Button-link,
  body[override] table.st-Button td.st-Button-area span.st-Button-internal {
    height: 44px !important;
    line-height: 44px !important;
    font-size: 18px !important;
    vertical-align: middle !important;
  }
}

@media (-webkit-min-device-pixel-ratio: 1.25), (min-resolution: 120dpi), all and (max-width: 768px) {

  /**
    * # mobile image
   **/
  body[override] div.st-Target.st-Target--mobile img {
    display: none !important;
    margin: 0 !important;
    max-height: 0 !important;
    min-height: 0 !important;
    mso-hide: all !important;
    padding: 0 !important;

    font-size: 0 !important;
    line-height: 0 !important;
  }

  /**
    * # document-list-item image
   **/
  body[override] div.st-Icon.st-Icon--document {
    background-image: url('https://stripe-images.s3.amazonaws.com/notifications/icons/document--16--regular.png') !important;
  }
}

    </style>
  </head>
  <body class=3D"st-Email" bgcolor=3D"f6f9fc" style=3D"border: 0; margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; min-width: 100%; width: 100%;" override=3D"fix">

    <!-- Background -->
    <table class=3D"st-Background" bgcolor=3D"f6f9fc" border=3D"0" cellpadding=3D"0" cellspacing=3D"0" width=3D"100%" style=3D"border: 0; margin: 0; padding: 0;">
      <tbody>
        <tr>
          <td style=3D"border: 0; margin: 0; padding: 0;">

            <!-- Wrapper -->
            <table class=3D"st-Wrapper" align=3D"center" bgcolor=3D"ffffff" border=3D"0" cellpadding=3D"0" cellspacing=3D"0" width=3D"600" style=3D"border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; margin: 0 auto; min-width: 600px;">
              <tbody>
                <tr>
                  <td style=3D"border: 0; margin: 0; padding: 0;">
                    <table class=3D"st-Preheader st-Width st-Width--mobile" border=3D"0" cellpadding=3D"0" cellspacing=3D"0" width=3D"600" style=3D"min-width: 600px;">
  <tbody>
    <tr>
      <td align=3D"center" height=3D"0" style=3D"border: 0; margin: 0; padding: 0; color: #ffffff; display: none !important; font-size: 1px; line-height: 1px; max-height: 0; max-width: 0; mso-hide: all !important; opacity: 0; overflow: hidden; visibility: hidden;">
        <span class=3D"st-Delink st-Delink--preheader" style=3D"color: #ffffff; text-decoration: none;">

          Before you can start accepting live payments, you need to confirm your email address.

          <!-- Prevents elements showing up in email client preheader text -->
          =E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;=E2=80=8C&nbsp;
          <!-- /Prevents elements showing up in email client preheader text -->

        </span>
      </td>
    </tr>
  </tbody>
</table>

<table class=3D"st-Header st-Header--simplified st-Width st-Width--mobile" border=3D"0" cellpadding=3D"0" cellspacing=3D"0" width=3D"600" style=3D"min-width: 600px;">
  <tbody>
    <tr>
      <td class=3D"st-Spacer st-Spacer--divider" colspan=3D"4" height=3D"19" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
    <tr>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
      <td class=3D"st-Header-logo" align=3D"left" height=3D"21" width=3D"49" style=3D"border: 0; margin: 0; padding: 0;">
        <div class=3D"st-Header-area st-Target st-Target--mobile" style=3D"background-color: #6772e5;">
          <a style=3D"border: 0; margin: 0; padding: 0; text-decoration: none;" href=3D"https://stripe.com">
            <img alt=3D"Stripe" border=3D"0" class=3D"st-Header-source" height=3D"21" width=3D"49" style=3D"border: 0; margin: 0; padding: 0; color: #6772e5; display: block; font-family: Helvetica, Arial, sans-serif; font-size: 12px; font-weight: normal;" src=3D"https://stripe-images.s3.amazonaws.com/html_emails/2017-08-21/header/Header-logo.png">
          </a>
        </div>
      </td>
      <td class=3D"st-Header-spacing" width=3D"423" style=3D"border: 0; margin: 0; padding: 0;">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
    <tr>
      <td class=3D"st-Spacer st-Spacer--divider" colspan=3D"4" height=3D"19" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
    <tr class=3D"st-Divider">
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
      <td bgcolor=3D"#e6ebf1" colspan=3D"2" height=3D"1" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
    <tr>
      <td class=3D"st-Spacer st-Spacer--divider" colspan=3D"4" height=3D"32" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
  </tbody>
</table>

<table class=3D"st-Copy st-Width st-Width--mobile" border=3D"0" cellpadding=3D"0" cellspacing=3D"0" width=3D"600" style=3D"min-width: 600px;">
  <tbody>
    <tr>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
      <td class=3D"st-Font st-Font--body" style=3D"border: 0; margin: 0; padding: 0; color: #525F7f; font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">

      Before you can start accepting live payments, you need to confirm your email address.

      </td>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
    <tr>
      <td class=3D"st-Spacer st-Spacer--stacked" colspan=3D"3" height=3D"12" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
  </tbody>
</table>
<table class=3D"st-Copy st-Width st-Width--mobile" border=3D"0" cellpadding=3D"0" cellspacing=3D"0" width=3D"600" style=3D"min-width: 600px;">
  <tbody>
    <tr>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
      <td class=3D"st-Font st-Font--body" style=3D"border: 0; margin: 0; padding: 0; color: #525F7f; font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">

        <!-- Button & Modifier: fullWidth -->
        <table class=3D"st-Button st-Button--fullWidth" border=3D"0" cellpadding=3D"0" cellspacing=3D"0" width=3D"100%">
          <tbody>
            <tr>
              <td align=3D"center" class=3D"st-Button-area" height=3D"38" valign=3D"middle" style=3D"border: 0; margin: 0; padding: 0; background-color: #666ee8; border-radius: 5px; text-align: center;">
                <a class=3D"st-Button-link" style=3D"border: 0; margin: 0; padding: 0; color: #ffffff; display: block; height: 38px; text-align: center; text-decoration: none;" href=3D"https://dashboard.stripe.com/confirm_email?t=3DzHeqGR9wzsVFre5NxHmUaP2Jd6nGiYiE">
                  <span class=3D"st-Button-internal" style=3D"border: 0; margin: 0; padding: 0; color: #ffffff; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: bold; height: 38px; line-height: 38px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: middle; white-space: nowrap; width: 100%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Confirm<span style=3D"border: 0; margin: 0; padding: 0; color: #666ee8; font-size: 12px; text-decoration: none;">=E2=80=91</span>email<span style=3D"border: 0; margin: 0; padding: 0; color: #666ee8; font-size: 12px; text-decoration: none;">=E2=80=91</span>address&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
        <!-- /Button & Modifier: fullWidth -->

      </td>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
    <tr>
      <td class=3D"st-Spacer st-Spacer--stacked" colspan=3D"3" height=3D"12" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
  </tbody>
</table>

<table class=3D"st-Divider st-Width st-Width--mobile" border=3D"0" cellpadding=3D"0" cellspacing=3D"0" width=3D"600" style=3D"min-width: 600px;">
  <tbody>
    <tr>
      <td class=3D"st-Spacer st-Spacer--divider" colspan=3D"3" height=3D"20" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
    <tr>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
      <td bgcolor=3D"#e6ebf1" height=3D"1" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
    <tr>
      <td class=3D"st-Spacer st-Spacer--divider" colspan=3D"3" height=3D"31" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
  </tbody>
</table>

<table class=3D"st-Copy st-Width st-Width--mobile" border=3D"0" cellpadding=3D"0" cellspacing=3D"0" width=3D"600" style=3D"min-width: 600px;">
  <tbody>
    <tr>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
      <td class=3D"st-Font st-Font--body" style=3D"border: 0; margin: 0; padding: 0; color: #525F7f; font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">

      Once you're ready to start integrating Stripe, we recommend taking a look at our <a style=3D"border: 0; margin: 0; padding: 0; color: #3297d3; text-decoration: none;" href=3D"https://stripe.com/docs">docs</a>.

      </td>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
    <tr>
      <td class=3D"st-Spacer st-Spacer--stacked" colspan=3D"3" height=3D"12" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
  </tbody>
</table>

<table class=3D"st-Copy st-Width st-Width--mobile" border=3D"0" cellpadding=3D"0" cellspacing=3D"0" width=3D"600" style=3D"min-width: 600px;">
  <tbody>
    <tr>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
      <td class=3D"st-Font st-Font--body" style=3D"border: 0; margin: 0; padding: 0; color: #525F7f; font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">

      If you are not a programmer or just want to check out existing integrations that require little to no development, you may find the <a style=3D"border: 0; margin: 0; padding: 0; color: #3297d3; text-decoration: none;" href=3D"https://support.stripe.com/questions/using-stripe-without-programming">resources</a> we've put together helpful

      </td>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
    <tr>
      <td class=3D"st-Spacer st-Spacer--stacked" colspan=3D"3" height=3D"12" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
  </tbody>
</table>

<table class=3D"st-Copy st-Width st-Width--mobile" border=3D"0" cellpadding=3D"0" cellspacing=3D"0" width=3D"600" style=3D"min-width: 600px;">
  <tbody>
    <tr>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
      <td class=3D"st-Font st-Font--body" style=3D"border: 0; margin: 0; padding: 0; color: #525F7f; font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">

      In either case, you can view your payments, API request logs, and a variety of other information about your account right from your <a style=3D"border: 0; margin: 0; padding: 0; color: #3297d3; text-decoration: none;" href=3D"https://dashboard.stripe.com">dashboard</a>.

      </td>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
    <tr>
      <td class=3D"st-Spacer st-Spacer--stacked" colspan=3D"3" height=3D"12" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
  </tbody>
</table>

<table class=3D"st-Copy st-Width st-Width--mobile" border=3D"0" cellpadding=3D"0" cellspacing=3D"0" width=3D"600" style=3D"min-width: 600px;">
  <tbody>
    <tr>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
      <td class=3D"st-Font st-Font--body" style=3D"border: 0; margin: 0; padding: 0; color: #525F7f; font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">

      We'll be here to help you with any step along the way. You can find answers to most questions and get in touch with us at https://support.stripe.com/.

      </td>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
    <tr>
      <td class=3D"st-Spacer st-Spacer--stacked" colspan=3D"3" height=3D"12" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
  </tbody>
</table>

<table class=3D"st-Copy st-Width st-Width--mobile" border=3D"0" cellpadding=3D"0" cellspacing=3D"0" width=3D"600" style=3D"min-width: 600px;">
  <tbody>
    <tr>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
      <td class=3D"st-Font st-Font--body" style=3D"border: 0; margin: 0; padding: 0; color: #525F7f; font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">

      Hope you enjoy getting up and running. We're excited to see what comes next!

      </td>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
    <tr>
      <td class=3D"st-Spacer st-Spacer--stacked" colspan=3D"3" height=3D"12" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
  </tbody>
</table>

<table class=3D"st-Footer st-Width st-Width--mobile" border=3D"0" cellpadding=3D"0" cellspacing=3D"0" width=3D"600" style=3D"min-width: 600px;">
  <tbody>
    <tr>
      <td class=3D"st-Spacer st-Spacer--divider" colspan=3D"3" height=3D"20" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
    <tr class=3D"st-Divider">
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
      <td bgcolor=3D"#e6ebf1" colspan=3D"2" height=3D"1" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; max-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
    <tr>
      <td class=3D"st-Spacer st-Spacer--divider" colspan=3D"3" height=3D"31" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
    <tr>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
      <td class=3D"st-Font st-Font--caption" style=3D"border: 0; margin: 0; padding: 0; color: #8898aa; font-family: Helvetica, Arial, sans-serif; font-size: 12px; line-height: 16px;">
        <span class=3D"st-Delink st-Delink--footer" style=3D"border: 0; margin: 0; padding: 0; color: #8898aa; text-decoration: none;">
          Stripe, 1=E2=80=8C8=E2=80=8C5 Berry Street, Suite 5=E2=80=8C5=E2=80=8C0, San Francisco CA 9=E2=80=8C4=E2=80=8C1=E2=80=8C0=E2=80=8C7
        </span>
      </td>
      <td class=3D"st-Spacer st-Spacer--gutter" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" width=3D"64">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
    <tr>
      <td class=3D"st-Spacer st-Spacer--emailEnd" colspan=3D"3" height=3D"64" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
        <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
      </td>
    </tr>
  </tbody>
</table>

                  </td>
                </tr>
              </tbody>
            </table>
            <!-- /Wrapper -->

          </td>
        </tr>
        <tr>
          <td class=3D"st-Spacer st-Spacer--emailEnd" height=3D"64" style=3D"border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;">
            <div class=3D"st-Spacer st-Spacer--filler">&nbsp;</div>
          </td>
        </tr>
      </tbody>
    </table>
    <!-- /Background -->

<img width=3D"1px" height=3D"1px" alt=3D"" src=3D"https://email-57.stripe.com/o/eJwljduuwiAQAL_m9E0CbZfLA9_S0F1QPJY1LFE_X42Pk0lmKKI2U40QyHo_U9B2oa3otXjwxaEHXcLfqg9uMnJ_cv_PXXZ-nU7azobAB1zR7TB7pxr3cXlmGUpGr_esKqsj1dt0icu8JwLtYMHVGRvIIAYwpQQLJu_L1KOM1Krc-JFEXVPjD3zG529AIR_TiL_qhtxK7ceWv2pLRD2LvAG5LEKM"></body></html>