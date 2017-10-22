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
    <link rel="stylesheet" type="text/css" href="/css/landing-page.css">
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
                        An evolutionary successor to Sage and Salesforce,
                        designed with efficiency and comfortable user experience in mind,
                        Overseer is the cloud accounting and project management
                        platform of the future.


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
                <div class="mainImage"></div>
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
        "You."
   ],
    typeSpeed: 50,
    backDelay: 1000,
    backSpeed: 30,
    showCursor: true,
    loop: false,

    onComplete() {
        document.getElementsByClassName('typed-cursor')[0].style.display = 'none';
    }
});
</script>
</body>
</html>