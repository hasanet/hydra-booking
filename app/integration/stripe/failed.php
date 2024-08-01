<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Payment - Parker Prins Lebano</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">

  <!-- Facebook Opengraph integration: https://developers.facebook.com/docs/sharing/opengraph -->
  <meta property="og:title" content="">
  <meta property="og:image" content="">
  <meta property="og:url" content="">
  <meta property="og:site_name" content="">
  <meta property="og:description" content="">

  <!-- Twitter Cards integration: https://dev.twitter.com/cards/  -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:site" content="">
  <meta name="twitter:title" content="">
  <meta name="twitter:description" content="">
  <meta name="twitter:image" content="">

  <!-- Place your favicon.ico and apple-touch-icon.png in the template root directory -->
  <link href="favicon.ico" rel="shortcut icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800" rel="stylesheet">

  <!-- Bootstrap CSS File -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- Main Stylesheet File -->
  <link href="css/style.css" rel="stylesheet">
  <style>
    /*

  Theme Name: Imperial

  Theme URL: https://bootstrapmade.com/imperial-free-onepage-bootstrap-template/

  Author: BootstrapMade

  Author URL: https://bootstrapmade.com

*/

/*--------------------------------------------------------------

# General

--------------------------------------------------------------*/

body {

background: #fff;

color: #666666;

font-family: "Open Sans", sans-serif;

}



a {

color: #03C4EB;

transition: 0.5s;

}



a:hover, a:active, a:focus {

color: #03c5ec;

outline: none;

text-decoration: none;

}



p {

padding: 0;

margin: 0 0 30px 0;

}



h1, h2, h3, h4, h5, h6 {

font-family: "Raleway", sans-serif;

font-weight: 400;

margin: 0 0 20px 0;

padding: 0;

}



/* Prelaoder */

#preloader {

position: fixed;

left: 0;

top: 0;

z-index: 999;

width: 100%;

height: 100%;

overflow: visible;

background: #fff url("../img/preloader.svg") no-repeat center center;

}



/* Back to top button */

.back-to-top {

position: fixed;

display: none;

background: rgba(0, 0, 0, 0.2);

color: #fff;

padding: 6px 12px 9px 12px;

font-size: 16px;

border-radius: 2px;

right: 15px;

bottom: 15px;

transition: background 0.5s;

}







.back-to-top:focus {

background: rgba(0, 0, 0, 0.2);

color: #fff;

outline: none;

}



.back-to-top:hover {

background: #03C4EB;

color: #fff;

}



/*--------------------------------------------------------------

# Welcome

--------------------------------------------------------------*/

#hero {

display: table;

width: 100%;

height: 100vh;

background: url(../img/hero-bg.jpg) top center;

background-size: cover;

}



@media (min-width: 1024px) {

#hero {

  background-attachment: fixed;

}

}



#hero .hero-logo {

margin: 20px;

}



#hero .hero-logo img {

max-width: 100%;

}



#hero .hero-container {

background: rgba(0, 0, 0, 0.8);

display: table-cell;

margin: 0;

padding: 0;

text-align: center;

vertical-align: middle;

}



#hero h1 {

margin: 30px 0 10px 0;

font-weight: 700;

line-height: 48px;

text-transform: uppercase;

color: #fff;

}



@media (max-width: 768px) {

#hero h1 {

  font-size: 28px;

  line-height: 36px;

}

}



#hero h2, h3 {

color: #ffffff;

margin-bottom: 50px;

}



@media (max-width: 768px) {

#hero h2 {

  font-size: 24px;

  line-height: 26px;

  margin-bottom: 30px;

}

#hero h2 .rotating {

  display: block;

}

}



#hero .rotating > .animated {

display: inline-block;

}



#hero .actions a {

font-family: "Raleway", sans-serif;

text-transform: uppercase;

font-weight: 500;

font-size: 16px;

letter-spacing: 1px;

display: inline-block;

padding: 8px 20px;

border-radius: 2px;

transition: 0.5s;

margin: 10px;

}



#hero .btn-get-started {

background: #03C4EB;

border: 2px solid #03C4EB;

color: #fff;

}



#hero .btn-get-started:hover {

background: none;

border: 2px solid #fff;

color: #fff;

}



#hero .btn-services {

border: 2px solid #fff;

color: #fff;

}



#hero .btn-services:hover {

background: #03C4EB;

border: 2px solid #03C4EB;

}



/*--------------------------------------------------------------

# Header

--------------------------------------------------------------*/

#header {

background: #111;

padding: 20px 0;

height: 90px;

}



#header #logo {

float: left;

}



#header #logo h1 {

font-size: 36px;

margin: 0;

padding: 6px 0;

line-height: 1;

font-family: "Raleway", sans-serif;

font-weight: 700;

letter-spacing: 3px;

text-transform: uppercase;

}



#header #logo h1 a, #header #logo h1 a:hover {

color: #fff;

}



#header #logo img {

padding: 0;

margin: 0;

max-height: 50px;

}



@media (max-width: 768px) {

#header {

  height: 80px;

}

#header #logo h1 {

  font-size: 26px;

}

#header #logo img {

  max-height: 40px;

}

}



.is-sticky #header {

background: rgba(0, 0, 0, 0.85);

}



/*--------------------------------------------------------------

# Navigation Menu

--------------------------------------------------------------*/

/* Nav Menu Essentials */

.nav-menu, .nav-menu * {

margin: 0;

padding: 0;

list-style: none;

}



.nav-menu ul {

position: absolute;

display: none;

top: 100%;

left: 0;

z-index: 99;

}



.nav-menu li {

position: relative;

white-space: nowrap;

}



.nav-menu > li {

float: left;

}



.nav-menu li:hover > ul,

.nav-menu li.sfHover > ul {

display: block;

}



.nav-menu ul ul {

top: 0;

left: 100%;

}



.nav-menu ul li {

min-width: 180px;

}



/* Nav Menu Arrows */

.sf-arrows .sf-with-ul {

padding-right: 30px;

}



.sf-arrows .sf-with-ul:after {

content: "\f107";

position: absolute;

right: 15px;

font-family: FontAwesome;

font-style: normal;

font-weight: normal;

}



.sf-arrows ul .sf-with-ul:after {

content: "\f105";

}



/* Nav Meu Container */

#nav-menu-container {

float: right;

margin: 5px 0;

}



@media (max-width: 768px) {

#nav-menu-container {

  display: none;

}

}



/* Nav Meu Styling */

.nav-menu a {

padding: 10px 15px;

text-decoration: none;

display: inline-block;

color: #fff;

font-family: 'Raleway', sans-serif;

font-weight: 300;

font-size: 14px;

outline: none;

}



.nav-menu a:hover, .nav-menu li:hover > a, .nav-menu .menu-active > a {

color: #03C4EB;

}



.nav-menu ul {

margin: 4px 0 0 15px;

box-shadow: 0px 0px 1px 1px rgba(0, 0, 0, 0.08);

}



.nav-menu ul li {

background: #fff;

border-top: 1px solid #f4f4f4;

}



.nav-menu ul li:first-child {

border-top: 0;

}



.nav-menu ul li:hover {

background: #f6f6f6;

}



.nav-menu ul li a {

color: #333;

}



.nav-menu ul ul {

margin: 0;

}



/* Mobile Nav Toggle */

#mobile-nav-toggle {

position: fixed;

right: 0;

top: 0;

z-index: 999;

margin: 20px 20px 0 0;

border: 0;

background: none;

font-size: 24px;

display: none;

transition: all 0.4s;

outline: none;

}



#mobile-nav-toggle i {

color: #fff;

}



@media (max-width: 768px) {

#mobile-nav-toggle {

  display: inline;

}

}



/* Mobile Nav Styling */

#mobile-nav {

position: fixed;

top: 0;

padding-top: 18px;

bottom: 0;

z-index: 998;

background: rgba(0, 0, 0, 0.9);

left: -260px;

width: 260px;

overflow-y: auto;

transition: 0.4s;

}



#mobile-nav ul {

padding: 0;

margin: 0;

list-style: none;

}



#mobile-nav ul li {

position: relative;

}



#mobile-nav ul li a {

color: #fff;

font-size: 16px;

overflow: hidden;

padding: 10px 22px 10px 15px;

position: relative;

text-decoration: none;

width: 100%;

display: block;

outline: none;

}



#mobile-nav ul li a:hover {

color: #fff;

}



#mobile-nav ul li li {

padding-left: 30px;

}



#mobile-nav ul .menu-has-children i {

position: absolute;

right: 0;

z-index: 99;

padding: 15px;

cursor: pointer;

color: #fff;

}



#mobile-nav ul .menu-has-children i.fa-chevron-up {

color: #03C4EB;

}



#mobile-nav ul .menu-item-active {

color: #03C4EB;

}



#mobile-body-overly {

width: 100%;

height: 100%;

z-index: 997;

top: 0;

left: 0;

position: fixed;

background: rgba(0, 0, 0, 0.6);

display: none;

}



/* Mobile Nav body classes */

body.mobile-nav-active {

overflow: hidden;

}



body.mobile-nav-active #mobile-nav {

left: 0;

}



body.mobile-nav-active #mobile-nav-toggle {

color: #fff;

}



/*--------------------------------------------------------------

# Sections

--------------------------------------------------------------*/

/* Sections Common

--------------------------------*/

.section-title {

font-size: 32px;

color: #111;

text-transform: uppercase;

text-align: center;

font-weight: 700;

}



.section-description {

text-align: center;

margin-bottom: 40px;

}



.section-title-divider {

width: 50px;

height: 3px;

background: #03C4EB;

margin: 0 auto;

margin-bottom: 20px;

}



/* Get Started Section

--------------------------------*/

#about {

background: #fff;

padding: 80px 0;

}



#about .about-img {

overflow: hidden;

}



#about .about-img img {

max-width: 100%;

}



@media (max-width: 768px) {

#about .about-img {

  height: auto;

}

#about .about-img img {

  margin-left: 0;

  padding-bottom: 30px;

}

}



#about .about-content {

background: #fff;

}



#about .about-title {

color: #333;

font-weight: 700;

font-size: 28px;

}



#about .about-text {

line-height: 26px;

margin-bottom: 15px;

}



#about .about-text:last-child {

margin-bottom: 0;

}



/* Services Section

--------------------------------*/

#services {

background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), url("../img/services-bg.jpg") fixed center center;

background-size: cover;

padding: 80px 0 60px 0;

}



#services .service-item {

margin-bottom: 20px;

}



#services .service-icon {

float: left;

background: #03C4EB;

padding: 16px;

border-radius: 50%;

transition: 0.5s;

border: 1px solid #03C4EB;

}



#services .service-icon i {

color: #fff;

font-size: 24px;

}



#services .service-item:hover .service-icon {

background: #fff;

}



#services .service-item:hover .service-icon i {

color: #03C4EB;

}



#services .service-title {

margin-left: 80px;

font-weight: 700;

margin-bottom: 15px;

text-transform: uppercase;

}



#services .service-title a {

color: #111;

}



#services .service-description {

margin-left: 80px;

line-height: 24px;

}



/* Subscribe Section

--------------------------------*/

#subscribe {

background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url(../img/subscribe-bg.jpg) fixed center center;

background-size: cover;

padding: 80px 0;

}



#subscribe .subscribe-title {

color: #fff;

font-size: 28px;

font-weight: 700;

}



#subscribe .subscribe-text {

color: #fff;

}



#subscribe .subscribe-btn-container {

text-align: center;

padding-top: 20px;

}



#subscribe .subscribe-btn {

font-family: "Raleway", sans-serif;

text-transform: uppercase;

font-weight: 500;

font-size: 16px;

letter-spacing: 1px;

display: inline-block;

padding: 8px 20px;

border-radius: 2px;

transition: 0.5s;

margin: 10px;

border: 2px solid #fff;

color: #fff;

}



#subscribe .subscribe-btn:hover {

background: #03C4EB;

border: 2px solid #03C4EB;

}



/* Portfolio Section

--------------------------------*/

#portfolio {

background: #fff;

padding: 80px 0;

}



#portfolio .portfolio-item {

background-position: center center;

background-size: cover;

background-repeat: no-repeat;

position: relative;

height: 260px;

width: 100%;

display: table;

overflow: hidden;

margin-bottom: 30px;

}



#portfolio .portfolio-item .details {

height: 260px;

background: #fff;

display: table-cell;

vertical-align: middle;

opacity: 0;

transition: 0.3s;

text-align: center;

}



#portfolio .portfolio-item .details h4 {

font-size: 16px;

transition: transform 0.3s, opacity 0.3s;

transform: translate3d(0, -15px, 0);

font-weight: 700;

color: #333333;

}



#portfolio .portfolio-item .details span {

display: block;

color: #666666;

font-size: 13px;

transition: transform 0.3s, opacity 0.3s;

transform: translate3d(0, 15px, 0);

}



#portfolio .portfolio-item:hover .details {

opacity: 0.8;

}



#portfolio .portfolio-item:hover .details h4 {

transform: translate3d(0, 0, 0);

}



#portfolio .portfolio-item:hover .details span {

transform: translate3d(0, 0, 0);

}



/* Testimonials Section

--------------------------------*/

#testimonials {

background: #f6f6f6;

padding: 80px 0;

}



#testimonials .profile {

text-align: center;

}



#testimonials .profile .pic {

border-radius: 50%;

border: 6px solid #fff;

margin-bottom: 15px;

overflow: hidden;

height: 260px;

width: 260px;

}



#testimonials .profile .pic img {

max-width: 100%;

}



#testimonials .profile h4 {

font-weight: 700;

color: #03C4EB;

margin-bottom: 5px;

}



#testimonials .profile span {

color: #333333;

}



#testimonials .quote {

position: relative;

background: #fff;

padding: 60px;

margin-top: 40px;

font-size: 16px;

font-style: italic;

border-radius: 5px;

}



#testimonials .quote b {

display: inline-block;

font-size: 22px;

left: -9px;

position: relative;

top: -8px;

}



#testimonials .quote small {

display: inline-block;

right: -9px;

position: relative;

top: 4px;

}



/* Team Section

--------------------------------*/

#team {

background: #fff;

padding: 80px 0 60px 0;

}



#team .member {

text-align: center;

margin-bottom: 20px;

}



#team .member .pic {

margin-bottom: 15px;

overflow: hidden;

height: 260px;

}



#team .member .pic img {

max-width: 100%;

}



#team .member h4 {

font-weight: 700;

margin-bottom: 2px;

}



#team .member span {

font-style: italic;

display: block;

font-size: 13px;

}



#team .member .social {

margin-top: 15px;

}



#team .member .social a {

color: #b3b3b3;

}



#team .member .social a:hover {

color: #03C4EB;

}



#team .member .social i {

font-size: 18px;

margin: 0 2px;

}



/* Contact Section

--------------------------------*/

#contact {

background: #fff;

padding: 20px 0;

}



#contact .info {

color: #333333;

}



#contact .info i {

font-size: 32px;

color: #03C4EB;

float: left;

}



#contact .info p {

padding: 0 0 10px 50px;

line-height: 24px;

}



#contact .form #sendmessage {

color: #03C4EB;

border: 1px solid #03C4EB;

display: none;

text-align: center;

padding: 15px;

font-weight: 600;

margin-bottom: 15px;

}



#contact .form #errormessage {

color: red;

display: none;

border: 1px solid red;

text-align: center;

padding: 15px;

font-weight: 600;

margin-bottom: 15px;

}



#contact .form #sendmessage.show, #contact .form #errormessage.show, #contact .form .show {

display: block;

}



#contact .form .validation {

color: red;

display: none;

margin: 0 0 20px;

font-weight: 400;

font-size: 13px;

}



#contact .form input, #contact .form textarea, #contact .form select {

border-radius: 0;

box-shadow: none;

}



#contact h3{

color: #000;

line-height: 50px;

}

.form-control{

display: inline-block;

/*width: 80%;*/

}

#contact .form select{

display: inline-block;

/*width: 200px;*/

}

.special-3{

width: 30%;

}

.special-2{

width: 12%;

}

#contact .form .cvc{

display: inline-block;

width: 19%;

}

#contact .form .amount{

display: inline-block;

/*width: 40%;*/

}



#contact .form button[type="submit"] {

background: #a19e69;

border: 0;

padding: 10px 24px;

color: #fff;

transition: 0.4s;

}

.btn-theme{
      width: 100%;
  margin-top: 25px;
  border-radius: 3px;
  background-color: #a19e69 !important;
  font-family: 'DM Sans', sans-serif;
  color: #fff;
  font-size: 14px;
  line-height: 20px;
  font-weight: 400;
  text-align: center;
} 
.w-button {
  display: inline-block;
  padding: 9px 15px;
  /* background-color: #3898EC; */
  /* color: white; */
  border: 0;
  /* line-height: inherit; */
  text-decoration: none;
  cursor: pointer;
  /* border-radius: 0; */
}

#contact .form input[type="submit"] {

background: #a19e69;

border: 0;

padding: 10px 24px;

color: #fff;

transition: 0.4s;

}

.btn-success{

background: #13294b;

border: 0;

padding: 10px 24px;

color: #fff;

transition: 0.4s;

}

.btn-success:hover{

background: #13293a;

}

#contact .form button[type="submit"]:hover {

background: #13293a;

}



/*--------------------------------------------------------------

# Footer

--------------------------------------------------------------*/

#footer {

background: #111;

padding: 30px 0;

color: #fff;

}



#footer .copyright {

text-align: center;

}



#footer .credits {

padding-top: 10px;

text-align: center;

font-size: 13px;

color: #ccc;

}

@media screen and (max-width: 768px) {

.back-to-top {

  bottom: 15px;

}

.special-3{

  width: 100%;

}

}


  </style>
</head>

<body>
  <!--==========================
  Contact Section
  ============================-->
  <section id="contact">
    <div class="container wow fadeInUp">
      <div class="row">
        <div class="col-md-12">
          <h3 class="section-title">Transaction Failed</h3>
          <div class="section-title-divider"></div>
          <p class="section-description">
            <?php
                if($_GET['msg']) {
                    echo $_GET['msg'];
                } else {
                    echo "Your Transaction has been failed!!!";
                }
            ?>  
          </p>
        </div>
      </div>
        <a href="index.php" class="btn btn-success">Back</a>
    </div>
  </section>

  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
