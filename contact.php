<?php
$active = "Contact";
include('db.php');
include("functions.php");
include("header.php");

?>


<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="index.php"><i class="fa fa-home"></i> Home</a>
                    <span>Contact</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section Begin -->

<!-- Contact Section Begin -->
<section class="contact-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="contact-title">
                    <h4>Contacts Us</h4>
                    <p>Your Passion is our Satisfaction</p>
                </div>
                <div class="contact-widget">
                    <div class="cw-item">
                        <div class="ci-icon">
                            <i class="ti-location-pin"></i>
                        </div>
                        <div class="ci-text">
                            <span>Address:</span>
                            <p>Garden City University</p>
                        </div>
                    </div>
                    <div class="cw-item">
                        <div class="ci-icon">
                            <i class="ti-mobile"></i>
                        </div>
                        <div class="ci-text">
                            <span>Phone:</span>
                            <p>+91 8849656188</p>
                        </div>
                    </div>
                    <div class="cw-item">
                        <div class="ci-icon">
                            <i class="ti-email"></i>
                        </div>
                        <div class="ci-text">
                            <span>Email:</span>
                            <p>ecoscape815@gmail.com</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 offset-lg-1">

                <div class="contact-form">
                    <div class="leave-comment">
                        <h4>Leave A Message</h4>
                        <p>Our staff will answer your questions.</p>
                        <form action="contact.php" class="comment-form">
                                <div class="col-lg-12">
                                    <button class="site-btn" name="submit" onclick="window.open('https://mail.google.com/mail/?view=cm&fs=1&to=ecoscape815@gmail.com', '_blank')">Click Here to ask</button>
                                </div>
                            </div>
                        </form>

                        <?php

                        if (isset($_POST['submit'])) {
                            $user_name = $_POST['name'];
                            $user_email = $_POST['email'];
                            $user_subject = $_POST['subject'];
                            $user_msg = $_POST['message'];

                            $receiver_mail = 'ecoscape815@gmail.com';

                            mail($receiver_mail, $user_name, $user_subject, $user_msg, $user_email);
                        }

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Section End -->

<?php
include('footer.php');
?>


</body>

</html>