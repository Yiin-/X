{{--
    List of variables used in this template
    ----------------------------------------
    -- Invoice info
    $invoiceNumber
    $poNumber
    $date
    $dueDate

    -- Currency
    $currencySymbol
    $currencyCode

    -- Summary
    $subTotal
    $discount
    $tax
    $paidIn
    $grandTotal

    -- Client info
    $clientName
    $clientAddress1
    $clientAddress2
    $clientCountry
    $clientEmail

    -- User info
    $userCompanyName
    $userAddress1
    $userAddress2
    $userCountry
    $userCompanyEmail
    $noteToClient
--}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i,900,900i&amp;subset=latin-ext" rel="stylesheet">
    <style type="text/css" media="all">
        body {
            font: 11pt 'Lato';
            background: #f5f5f5;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .clearfix {
            clear: both;
        }

        .page {
            width: 21cm;
            min-height: 29.7cm;
            padding-bottom: 294px;
            margin: 1cm auto;
            background: white;
            position: relative;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.15);
        }

        .heading {
            height: 226px;
            background: #f4f5f9;
            padding: 1cm 50px;
            display: flex;
            justify-content: space-between;
        }

        .column {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .footer .column {
            justify-content: flex-start;
            color: #9da0a7;
            font-size: 14px;
        }



        .column--left {
            justify-content: flex-end;
        }

        .column--right {
            text-align: right;
        }

        .invoiceMeta {
            display: flex;
            flex-direction: column;
        }

        .invoiceMeta h1 {
            text-transform: uppercase;
            font-size: 1.7em;
            letter-spacing: 1px;
            font-weight: 500;
        }

        .invoiceMeta span {
            color: #999;
            margin-top: 10px;
            font-size: 14px;
        }

        h1 {
            font-weight: 400;
            margin: 0;
        }

        .subTitle {
            color: #929292;
            font-size: 14px;
            margin-top: 12px;
        }

        .totalAmount,
        .totalValue {
            color: #38c174;
            font-size: 19px;
            font-weight: 400;
        }
        .currencyCode {
            font-size: 15px;
        }

        .itemsList {
            font-size: 16px;
        }

        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 97px;
            padding: 0 50px;
            border-bottom: 1px solid #eee;
        }

        .qty {
            font-size: 0.9em;
            color: #888;
        }

        .itemDescription {
            font-size: 0.9em;
            color: #888;
            margin-top: 7px;
        }


        .itemCostAmount {
            color: #38c174;
        }

        .summary {
            padding: 41px 50px;
            text-align: right;
            float: right;
        }

        .total {
            padding: 0 50px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            text-align: right;
        }

        .summaryItemName {
            text-transform: uppercase;
            color: #999;
            letter-spacing: 0.7px;
            vertical-align: middle;
            font-size: 0.9em;
        }

        .summaryItem {
            line-height: 31px;
        }

        .summaryItemValue {
            font-size: 15px;
            vertical-align: middle;
            padding-left: 25px;
        }

        .totalName {
            text-transform: uppercase;
            color: #000;
            letter-spacing: 0.7px;
            vertical-align: middle;
            font-weight: bold;
            font-size: 14px;

        }

        .totalValue {
            margin-left: 25px;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 60px 50px 136px;
            background: #f9fafc;
            display: flex;
        }

        .footer .column:nth-child(1), .footer .column:nth-child(2) {
            width: 30%;
            padding-right: 30px;
        }

        .footer .column:nth-child(3) {
            width: 40%;
        }

        .payNowBtn {
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 76px;
            font-size: 21px;
            font-weight: bold;
            text-shadow: 0 0 2px #2975ee;
            letter-spacing: 0.7px;
            font-family: inherit;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(to bottom, #388bff, #3b75ff);
            color: white;
            text-decoration: none;
            border: 1px solid #2975ee;
        }

        .columnTitle {
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        .name {
            color: black;
            text-shadow: 0px 0px 0px black;
            padding: 16px 0 11px;
        }

        .addressLine {
            line-height: 1.5;
        }

        .contactEmail {
            margin-top: 10px;
            word-wrap: break-word;
        }

        .noteText {
            padding: 16px 0;
            line-height: 1.5;
        }


        @page {
            min-height: 29.7cm;
            margin: 0;
        }

        /*@media print {*/
            body {
                margin: 0;
                padding: 0;
            }
            .page {
                display: table;
                width: 100%;
                margin: 0;
                min-height: initial;
                border: none;
                border-radius: 0;
                box-shadow: none;
                -webkit-print-color-adjust: exact;
            }
            .item {
                page-break-after: auto;
                page-break-inside: avoid;
            }
            .item:nth-child(7n + 7) {
                /*page-break-after: always;*/
            }
            .heading {
                margin: 0;
            }
            .total {
                page-break-after: avoid;
            }
            .footer {
                position: absolute;
                width: 100%;
                padding-bottom: 90px;
            }
            /*.footer::after {
                counter-increment: page;
                content: counter(page);
            }*/
            .payNowBtn {
                display: none;
            }
        /*}*/
    </style>
</head>
<body>
<div class="invoice">
    <div class="page">
        <div class="heading">
            <div class="column column--left">
                <h1>{{ $userCompanyName }}</h1>
                <span class="subTitle">Web design for Alphabet homepage</span>
            </div>
            <div class="column column--right">
                <div class="invoiceMeta">
                    <h1>Invoice</h1>
                    <span class="invoiceNumber">
                        {{ $invoiceNumber }}
                    </span>
                    @if ($poNumber)
                    <span class="invoiceNumber">
                        Order: <span class="highlight">{{ $poNumber }}</span>
                    </span>
                    @endif
                    <span class="invoiceDate">
                        {{ $date }}
                    </span>
                </div>
                <div>
                    <span class="totalAmount">
                        {{ $currencySymbol }} @money($grandTotal)
                    </span>
                </div>
            </div>
        </div>
        <div class="itemsList">
        @foreach ($items as $item)
            @include ('pdfs.invoice.default.item-row', ['item' => $item])
        @endforeach
        </div>
        <table class="summary">
            <tr class="summaryItem">
                <td class="summaryItemName">
                    Sub total
                </td>
                <td class="summaryItemValue">
                    {{ $currencySymbol }} @money($subTotal)
                </td>
            </tr>
            <tr class="summaryItem">
                <td class="summaryItemName">
                    Discount
                </td>
                <td class="summaryItemValue">
                    {{ $currencySymbol }} @money($discount)
                </td>
            </tr>
            <tr class="summaryItem">
                <td class="summaryItemName">
                    Tax
                </td>
                <td class="summaryItemValue">
                    {{ $currencySymbol }} @money($tax)
                </td>
            </tr>
            <tr class="summaryItem">
                <td class="summaryItemName">
                    Partial / Deposit
                </td>
                <td class="summaryItemValue">
                    {{ $currencySymbol }} @money($paidIn)
                </td>
            </tr>
        </table>
        <div class="clearfix"></div>
        <div class="total">
            <div class="totalName">
                Total Due
            </div>
            <div class="totalValue">
                {{ $currencySymbol }} @money($grandTotal)
                <span class="currencyCode">
                    {{ $currencyCode }}
                </span>
            </div>
        </div>
        <div class="footer">
            <div class="column">
                <div class="columnTitle">
                    To
                </div>
                <div class="name">
                    {{ $clientName }}
                </div>
                <div class="address">
                    @if ($clientAddress1)
                    <div class="addressLine">
                        {{ $clientAddress1 }}
                    </div>
                    @endif
                    @if ($clientAddress1)
                    <div class="addressLine">
                        {{ $clientAddress2 }}
                    </div>
                    @endif
                    @if ($clientCountry)
                    <div class="addressLine">
                        {{ $clientCountry }}
                    </div>
                    @endif
                </div>
                <div class="contact">
                    @if ($clientEmail)
                    <div class="contactEmail">
                        {{ $clientEmail }}
                    </div>
                    @endif
                </div>
            </div>
            <div class="column">
                <div class="columnTitle">
                    From
                </div>
                <div class="name">
                    {{ $userCompanyName }}
                </div>
                <div class="address">
                    @if ($userAddress1)
                    <div class="addressLine">
                        {{ $userAddress1 }}
                    </div>
                    @endif
                    @if ($userAddress1)
                    <div class="addressLine">
                        {{ $userAddress2 }}
                    </div>
                    @endif
                    @if ($userCountry)
                    <div class="addressLine">
                        {{ $userCountry }}
                    </div>
                    @endif
                </div>
                <div class="contact">
                    @if ($userCompanyEmail)
                    <div class="contactEmail">
                        {{ $userCompanyEmail }}
                    </div>
                    @endif
                </div>
            </div>
            <div class="column">
                @if ($noteToClient)
                <div class="columnTitle">
                    Note
                </div>
                <div class="noteText">
                    {{ $noteToClient }}
                </div>
                @endif
            </div>
            <a href="/" class="payNowBtn">
                Pay Now
            </a>
        </div>
    </div>
</div>
</div>
</body>
</html>