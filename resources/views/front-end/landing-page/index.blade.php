<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Overseer</title>
    <meta name="description" content="Overseer is suite of managements tools for businesses of all sizes.">

    <link rel=apple-touch-icon sizes=180x180 href=/static/apple-touch-icon.png>
    <link rel=icon type=image/png href=/static/favicon-32x32.png sizes=32x32>
    <link rel=icon type=image/png href=/static/favicon-16x16.png sizes=16x16>
    <link rel="shortcut icon" href=/static/favicon.ico>
    <meta name=apple-mobile-web-app-title content=Overseer>
    <meta name=application-name content=Overseer>
    <meta name=theme-color content=#ffffff>
</head>
<body>
    <header class="nav">
        <div class="container">
            <div class="navSection navSection--logo">
                <a href="/">
                    overseer
                </a>
            </div>
            <div class="navSection navSection--primary">
                <a href="">
                    Products
                </a>
                <a href="">
                    Developers
                </a>
                <a href="">
                    Company
                </a>
                <a href="">
                    Pricing
                </a>
            </div>
            <div class="navSection navSection--secondary">
                <a href="">
                    Support
                </a>
                <a href="/login" class="loginLink">
                    Login
                </a>
            </div>
        </div>
    </header>
    <div class="content">
        <main>
            <header>
                <div class="gradient"></div>
                <section id="intro">
                    <div class="container">
                        <h1>
                          Built for <span class="typedElement"></span>
                        </h1>
                        <p class="commonText commonText--body">
                          Stripe is the best software platform for running an internet business.
                          We handle billions of dollars every year for forward-thinking businesses around the world.
                        </p>
                        <ul>
                            <li>
                                <a href="/register" class="commonButton">
                                    Create Account
                                </a>
                            </li>
                            <li>
                                <a href="/demo" class="commonButton">
                                    Try Demo
                                </a>
                            </li>
                        </ul>
                    </div>
                </section>
            </header>
        </main>
    </div>

<script src="/js/typed.min.js" type="text/javascript" charset="utf-8"></script>
<script>
var typed = new Typed('.typedElement', {
    strings: [
        "Accountants",
        "Shopkeepers",
        "Freelancers",
        "Entrepreneurs",
        "Office Administrators",
        "Project Managers",
        "Business",
        "You"
   ],
    typeSpeed: 50,
    backDelay: 2000,
    backSpeed: 30,
    showCursor: true,
    loop: true
});
</script>

<style type="text/css" media="screen">
@font-face {
    font-family: 'Overseer';

    src: url('/fonts/Lintel ExtraBold.ttf') format('truetype');
}

body {
    margin: 0;
    padding: 0;
    background: #fff;
    min-height: 100%;
    display: flex;
    flex-direction: column;
    font-size: 62.5%;
    font-family: Camphor,Open Sans,Segoe UI,sans-serif;
    font-weight: 400;
    font-style: normal;
    text-rendering: optimizeLegibility;
    font-feature-settings: "pnum";
    font-variant-numeric: proportional-nums;
}

.nav {
    font-family: Camphor,Open Sans,Segoe UI,sans-serif;
    position: absolute;
    left: 0;
    top: 10px;
    right: 0;
    z-index: 500;
    height: 50px;
    perspective: 2000px;
}

.nav a {
    text-decoration: none;
    color: #6772e5;
    transition: color .1s
}

.nav .navSection {
    padding: 0;
    margin: 0
}

.nav .navSection {
    list-style: none
}

.navSection a {
    font-size: 17px;
    font-weight: 400;
    height: 50px;
    line-height: 50px;
    white-space: nowrap;
    padding: 0 25px;
    color: #fff;
}

.navSection--logo {
    position: absolute;
    top: 0;
    left: 0;
}

.navSection--logo a {
    padding-left: 20px;
    font-family: Overseer, sans-serif;
    font-size: 28px;
    line-height: 50px;
    color: #ffffff !important;
}

.navSection--primary {
    display: flex;
    justify-content: center;
}

.navSection--secondary {
    position: absolute;
    top: 0;
    right: 0;
    display: flex;
}

.navSection .loginLink {
    font-weight: 500;
}

@media (min-width: 670px) {
    #intro {
        display: flex;
        align-items: center;
        height: 600px;
    }
}

@media (min-width: 880px) {
    #intro {
        height: 760px;
    }
}

#intro {
    position: relative;
}

.gradient {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    transform: skewY(-12deg);
    transform-origin: 0;
    background: linear-gradient(135deg,#5866c2,#723be1);
}

.content {
    flex-grow: 1;
}

main {
    position: relative;
    overflow: hidden;
    display: block;
}

main header {
    position: relative;
}

.gradient::after {
    background: white;
    width: 100%;
    height: 600px;
    bottom: -493px;
    transform: skewY(-12deg);
}


#intro h1 {
    font-size: 32px;
    font-weight: 400;
    color: #fff
}

@media (min-width: 880px) {
    #intro h1 {
        font-size:40px
    }
}

#intro p {
    max-width: 500px;
    margin-top: 20px;
    color: #d9fcff;
}

@media (min-width: 670px) {
    #intro p {
        max-width:60%
    }
}

@media (min-width: 880px) {
    #intro p {
        max-width:50%
    }
}

#intro ul {
    display: flex;
    margin-top: 40px
}

@media (min-width: 670px) {
    #intro ul {
        margin-top:65px
    }
}

#intro li:first-child a {
    margin-right: 23px;
    color: #fff;
    background: #3ecf8e;
    text-shadow: 0 1px 3px rgba(36,180,126,.4);
}

#intro li:last-child a {
    padding: 0px 30px;
}

#intro p {
    color: #e6e4f6;
}

.commonButton {
    white-space: nowrap;
    display: inline-block;
    height: 40px;
    line-height: 40px;
    padding: 0 14px;
    box-shadow: 0 4px 6px rgba(50,50,93,.11), 0 1px 3px rgba(0,0,0,.08);
    background: #fff;
    border-radius: 4px;
    font-size: 15px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .025em;
    color: #6772e5;
    text-decoration: none;
    transition: all .15s ease;
}

.commonButton:hover {
    transform: translateY(-1px);
    box-shadow: 0 7px 14px rgba(50,50,93,.1),0 3px 6px rgba(0,0,0,.08)
}

.commonButton:first-child:hover {
    color: #7795f8;
}
.commonButton:first-child:active {
    color: #555abf;
}

.commonButton:active {
    background-color: #f6f9fc;
    transform: translateY(1px);
    box-shadow: 0 4px 6px rgba(50,50,93,.11),0 1px 3px rgba(0,0,0,.08)
}

ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

#intro ul {
    display: flex;
    margin-top: 65px;
}

.container {
    position: relative;
    max-width: 1040px;
    margin: 0 auto;
    padding: 0 20px;
    width: 100%;
}

#intro .container {
    padding-top: 70px;
    padding-bottom: 100px;
}

@media (min-width: 670px) {
    #intro .container {
        margin-top: -7%;
        padding-top: 0;
        padding-bottom: 0;
    }
}

.commonText {
    font-size: 17px;
}
</style>
</body>
</html>