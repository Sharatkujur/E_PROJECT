<?php
$active = "About";
include("functions.php");
include("header.php");
?>

<section class="about-section">
<style>
        .about-header {
            position: relative;
            width: 100%;
            height: 200px;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/path-to-your-header-image.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .about-header h1 {
            color: white;
            font-size: 3.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .content-section {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
        }

        .content-section.reverse {
            direction: rtl;
        }

        .content-section.reverse .text-content {
            direction: ltr;
        }

        .text-content {
            padding: 20px;
        }

        .text-content h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #333;
        }

        .text-content p {
            line-height: 1.6;
            color: #666;
            margin-bottom: 15px;
        }

        .image-container {
            width: 100%;
            height: 400px;
            overflow: hidden;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .quote-section {
            background-color: #f9f9f9;
            padding: 40px 20px;
            text-align: center;
            margin: 40px 0;
        }

        .quote-section blockquote {
            max-width: 800px;
            margin: 0 auto;
            font-style: italic;
            color: #555;
        }

        .quote-section cite {
            display: block;
            margin-top: 20px;
            color: #888;
        }

        .contact-info {
            text-align: center;
            padding: 20px;
            background-color: #f5f5f5;
            margin-top: 40px;
        }

        @media (max-width: 768px) {
            .content-section {
                grid-template-columns: 1fr;
            }
            
            .content-section.reverse {
                direction: ltr;
            }
        }
    </style>
</head>
<body>
    <header class="about-header">
        <h1>About</h1>
    </header>

    <section class="content-section">
        <div class="text-content">
            <h2>Our Story</h2>
            <p>Welcome to Ecoscape, your trusted partner in nurturing a healthy, balanced, and vibrant lifestyle.</p>
            <p>At the heart of everything we do lies a deep commitment to enhancing your well-being, making your home a sanctuary, and helping you shine with confidence. Our carefully curated offerings in Nutrition, Home & Living, and Beauty & Personal Care are designed to enrich every facet of your life.</p>
        </div>
        <div class="image-container">
            <img src="img/nut.jpeg" alt="Modern wardrobe setup">
        </div>
    </section>

    <section class="content-section reverse">
        <div class="text-content">
            <h2>Our Mission</h2>
            <p>We aim to inspire and empower you to embrace a lifestyle that’s not only healthy but also fulfilling and sustainable. From nourishing your body to creating a cozy living space and embracing self-care rituals, we’re here to guide you every step of the way.

</p>
            <p>We are committed to creating a seamless and enjoyable shopping journey, focusing on intuitive design, and high performance. By staying attuned to emerging trends and continuously improving our platform, we strive to offer a modern and reliable space where users can explore, choose, and shop with confidence.</p>
        </div>
        <div class="image-container">
            <img src="img/jogging.jpg" alt="Fashion model">
        </div>
    </section>

    <div class="quote-section">
        <blockquote>
            "Creativity is just connecting things. When you ask creative people how they did something, they feel a little guilty because they didn't really do it, they just saw something. It seemed obvious to them after a while."
            <cite>- Steve Jobs</cite>
        </blockquote>
    </div>
</section>

<?php
include("footer.php");
?>
