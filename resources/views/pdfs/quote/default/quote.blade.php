<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style type="text/css" media="all">
        @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,700);

        html, body {
          padding: 0;
          margin: 0;
          background: #fff;
          font-family: 'Open Sans', sans-serif;
        }

        .page {
          width: 600px;
          min-height: 848px;
          margin: 0 auto;
          background: white;
        }

        .content {
          width: 480px;
          margin: 0 auto;
          padding: 40px;
        }

        .header {
          display: flex;
          justify-content: space-between;
        }

        .title {
          color: #ff0000;
          font-size: 21px;
        }

        .heading {
          width: 100%;
          margin-top: 50px;
          display: flex;
          justify-content: space-between;
        }

        .heading__text, .heading__meta {
          font-size: 12px;
          color: #5b5b5b;
          font-family: 'Open Sans', sans-serif;
          line-height: 18px;
          width: 50%;
        }

        .heading__meta {
          font-size: 10px;
          text-align: right;
          text-transform: uppercase;
        }

        .highlight {
          font-size: 12px;
        }

        table {
          width: 100%;
          margin-top: 60px;
          color: #5b5b5b;
          font-weight: normal;
          line-height: 1;
          border-collapse: collapse;
        }

        th {
          font-size: 12px;
          font-family: 'Open Sans', sans-serif;
          font-weight: normal;
          vertical-align: top;
          padding: 0 0 7px 0;
          text-align: left;
          border-bottom: 1px solid black;
        }

        tbody {
          font-size: 12px;
          font-family: 'Open Sans', sans-serif;
          line-height: 18px;
          vertical-align: top;
          padding: 10px 0;
        }

        tr:first-child > td {
          padding-top: 20px;
        }

        tr:not(.summary) td {
          color: #646a6e;
          padding: 10px 0;
          border-bottom: 1px solid #e4e4e4;
        }
        tr:not(.summary) td:nth-child(1) {
          color: #ff0000;
        }

        th:nth-child(3), td:nth-child(3) {
          text-align: center;
        }
        th:last-child, td:last-child {
          color: #1e2b33;
          text-align: right;
        }

        .small {
          font-size: 10px;
        }

        .summary td {
          color: #646a6e;
          line-height: 22px;
          text-align: right;
        }
        .summary td:last-child {
          width: 80px;
        }

        .summary--first td {
          padding-top: 20px;
        }

        .summary--important {
          font-weight: bold;
        }
        .summary--important td {
          color: #1e2b33;
        }

        .summary--light td {
          font-size: 10px;
          text-transform: uppercase;
          color: #b0b0b0;
        }

        .footer {
          margin-top: 20px;
          font-size: 12px;
          color: #5b5b5b;
        }
    </style>
</head>
<body>
<div class="page">
  <div class="content">

    <div class="header">
      <div class="logo"></div>
      <div class="title">
        Quote
      </div>
    </div>

    <div class="heading">
      <div class="heading__text">
        {{ $noteToClient }}
      </div>
      <div class="heading__meta">
        Order <span class="highlight">{{ $poNumber }}</span><br>
        {{ $date }}
      </div>
    </div>

    <table>
      <thead>
        <tr>
          <th>Item</th>
          <th>SKU</th>
          <th>Quantity</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($items as $item)
            @include ('pdfs.quote.default.item-row', ['item' => $item])
        @endforeach

        <!-- Summary -->
        <tr class="summary summary--first">
          <td colspan="3" class="description">
            Subtotal
          </td>
          <td>
            {{ $currencySymbol }} {{ $subTotal }}
          </td>
        </tr>
        <tr class="summary summary--important">
          <td colspan="3" class="description">
            Grand Total (Incl.Tax)
          </td>
          <td>
            {{ $currencySymbol }} {{ $grandTotal }}
          </td>
        </tr>
        <tr class="summary summary--light">
          <td colspan="3" class="description">
            Tax
          </td>
          <td>
            {{ $currencySymbol }} {{ $tax }}
          </td>
        </tr>
      </tbody>
    </table>

    <div class="footer">
      <div class="footer__text">
        {{ $footerText }}
      </div>
    </div>
  </div>
</div>
</body>
</html>