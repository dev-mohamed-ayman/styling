<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('assets/css/web/main.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&family=Cairo:wght@200..1000&display=swap"
        rel="stylesheet">
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">

</head>

<body>

<main>
    {{-- Start Header --}}
    <header class="navbar container">
        <img class="logo" src="{{ asset('assets/logo.svg') }}" alt="Logo">
        <div class="links">
            <nav>
                <a href="#" class="active">الرئيسية</a>
                <a href="#">من نحن</a>
                <a href="#">خدماتنا</a>
                <a href="#">آراء العملاء</a>
                <a href="#"> تحميل التطبيق</a>
            </nav>
            <a href="#" class="btn btn-primary">سجّل الآن</a>
        </div>
        <button class="hamburger-menu" aria-label="Toggle Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </header>
    {{-- End Header --}}
    {{-- Start Hero Section --}}
    <section class="hero" style="background-image: url({{asset('assets/hero-bg.png')}})">
        <div class="container">
            <div class="content">
                <span>مرحبًا بك في نسق</span>
                <p>نساعدك على اختيار الملابس وتنسيقها بسهولة لتبدو دائمًا أنيقًا ومتألقًا.</p>
                <div class="btns">
                    <a class="btn" href="#">
                        حمّل التطبيق
                        <i class="bx bx-arrow-down-circle"></i>
                    </a>
                    <a class="btn" href="#">
                        اكتشف خدماتنا
                        <i class="bx bx-caret-big-left"></i>
                    </a>
                </div>
            </div>
            <div class="social-links">
                <div class="line"></div>
                <div class="links">
                    <a href="">
                        wwwwwwww
                        <i class="bx bx-facebook"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    {{-- End Hero Section --}}
</main>

<script src="{{ asset('assets/js/web/main.js') }}"></script>

</body>

</html>
