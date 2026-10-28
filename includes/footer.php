<section class="w3l-footer-29-main modern-footer">

  <!-- Animated background -->
  <div class="footer-glow footer-glow-1"></div>
  <div class="footer-glow footer-glow-2"></div>

  <div class="footer-29 py-5">
    <div class="container py-lg-4">

      <div class="row footer-top-29">

        <!-- ================= Contact ================= -->
        <div class="col-lg-4 col-md-6 footer-list-29 footer-contact">

          <div class="footer-brand">
            <div class="footer-logo">
              <i class="fa fa-scissors"></i>
            </div>

            <div>
              <h4>Kuya Nash</h4>
              <span>Premium Barbershop</span>
            </div>
          </div>

          <p class="footer-desc">
            Fresh cuts, premium grooming, and modern style crafted for gentlemen.
          </p>

          <h6 class="footer-title-29">Contact Us</h6>

          <ul class="footer-contact-list">

            <?php
            $ret=mysqli_query($con,"select * from tblpage where PageType='contactus'");
            while ($row=mysqli_fetch_array($ret)) {
            ?>

            <li>
              <div class="footer-icon">
                <span class="fa fa-map-marker"></span>
              </div>

              <p><?php echo $row['PageDescription']; ?></p>
            </li>

  <li>
  <div class="footer-icon">
    <span class="fa fa-phone"></span>
  </div>

  <?php
    $mobile = preg_replace('/[^0-9]/', '', $row['MobileNumber']);

    // Force Philippine 11-digit format
    if(substr($mobile,0,2) != '09'){
        $mobile = '0' . substr($mobile,-10);
    }
  ?>

  <a href="tel:<?php echo $mobile; ?>">
    <?php echo $mobile; ?>
  </a>
</li>
            <li>
              <div class="footer-icon">
                <span class="fa fa-envelope"></span>
              </div>

              <a href="mailto:<?php echo $row['Email']; ?>">
                <?php echo $row['Email']; ?>
              </a>
            </li>

            <?php } ?>

          </ul>

        </div>

        <!-- ================= Links ================= -->
        <div class="col-lg-4 col-md-6 footer-list-29 footer-links">

          <h6 class="footer-title-29">Quick Links</h6>

          <ul>

            <li>
              <a href="index.php">
                <span>Home</span>
              </a>
            </li>

            <li>
              <a href="about.php">
                <span>About</span>
              </a>
            </li>

            <li>
              <a href="services.php">
                <span>Services</span>
              </a>
            </li>

            <li>
              <a href="contact.php">
                <span>Contact Us</span>
              </a>
            </li>

          </ul>

        </div>

        <!-- ================= About ================= -->
        <div class="col-lg-4 col-md-12 footer-list-29 footer-about">

          <?php
          $ret=mysqli_query($con,"select * from tblpage where PageType='aboutus'");
          while ($row=mysqli_fetch_array($ret)) {
          ?>

          <h6 class="footer-title-29">
            <?php echo $row['PageTitle']; ?>
          </h6>

          <p class="footer-about-text">
            <?php echo $row['PageDescription']; ?>
          </p>

          <?php } ?>

          <!-- Newsletter -->
          <div class="footer-newsletter">

            <h5>Stay Updated</h5>

            <form class="newsletter-form">

              <input type="email"
                placeholder="Enter your email"
                required>

              <button type="submit">
                Subscribe
              </button>

            </form>

          </div>

        </div>

      </div>
    </div>
  </div>

  <!-- ================= Copyright ================= -->
  <section class="w3l-copyright">

    <div class="container">

      <div class="bottom-copies">

        <div class="copyright-left">
          <p>
            © 2024 <strong>Kuya Nash</strong>
            <span>• Premium Barbershop</span>
          </p>
        </div>

        <!-- Social -->
        <div class="main-social-footer-29">

          <a href="#" class="social facebook">
            <span class="fa fa-facebook"></span>
          </a>

          <a href="#" class="social twitter">
            <span class="fa fa-twitter"></span>
          </a>

          <a href="#" class="social instagram">
            <span class="fa fa-instagram"></span>
          </a>

          <a href="#" class="social linkedin">
            <span class="fa fa-linkedin"></span>
          </a>

        </div>

      </div>

    </div>

  </section>

</section>

<style>

/* ===============================
   FOOTER
================================= */

.modern-footer{
  position:relative;
  overflow:hidden;

  background:
    linear-gradient(
      180deg,
      #0a0a0a 0%,
      #111111 100%
    );

  color:#fff;
}

/* ===============================
   GLOW EFFECTS
================================= */

.footer-glow{
  position:absolute;
  border-radius:50%;
  filter:blur(90px);
  opacity:.25;
  pointer-events:none;
}

.footer-glow-1{
  width:320px;
  height:320px;
  background:#2563eb;

  top:-100px;
  left:-100px;

  animation:floatGlow 8s ease-in-out infinite;
}

.footer-glow-2{
  width:250px;
  height:250px;
  background:#7c3aed;

  bottom:-120px;
  right:-80px;

  animation:floatGlow2 9s ease-in-out infinite;
}

@keyframes floatGlow{
  0%,100%{
    transform:translateY(0px);
  }
  50%{
    transform:translateY(30px);
  }
}

@keyframes floatGlow2{
  0%,100%{
    transform:translateX(0px);
  }
  50%{
    transform:translateX(-30px);
  }
}

/* ===============================
   FOOTER GRID
================================= */

.footer-top-29{
  position:relative;
  z-index:2;
  row-gap:2rem;
}

/* ===============================
   BRAND
================================= */

.footer-brand{
  display:flex;
  align-items:center;
  gap:14px;
  margin-bottom:1rem;
}

.footer-logo{
  width:55px;
  height:55px;

  border-radius:16px;

  display:flex;
  align-items:center;
  justify-content:center;

  font-size:1.3rem;

  background:
    linear-gradient(
      135deg,
      #2563eb,
      #3b82f6
    );

  box-shadow:
    0 12px 30px rgba(37,99,235,.35);
}

.footer-brand h4{
  margin:0;
  font-size:1.3rem;
  font-weight:700;
}

.footer-brand span{
  font-size:.82rem;
  color:rgba(255,255,255,.55);
  letter-spacing:1px;
}

.footer-desc{
  color:rgba(255,255,255,.65);
  line-height:1.8;
  margin-bottom:1.7rem;
}

/* ===============================
   TITLES
================================= */

.footer-title-29{
  font-size:1.1rem;
  font-weight:700;
  margin-bottom:1.3rem;
  position:relative;
}

.footer-title-29::after{
  content:'';
  position:absolute;

  left:0;
  bottom:-8px;

  width:45px;
  height:3px;

  border-radius:20px;

  background:
    linear-gradient(
      90deg,
      #3b82f6,
      #60a5fa
    );
}

/* ===============================
   CONTACT LIST
================================= */

.footer-contact-list{
  list-style:none;
  padding:0;
  margin:0;
}

.footer-contact-list li{
  display:flex;
  align-items:flex-start;
  gap:14px;

  margin-bottom:1rem;
}

.footer-icon{
  min-width:42px;
  height:42px;

  border-radius:12px;

  display:flex;
  align-items:center;
  justify-content:center;

  background:rgba(255,255,255,.06);

  color:#60a5fa;

  transition:.3s ease;
}

.footer-contact-list li:hover .footer-icon{
  transform:translateY(-3px);
  background:#2563eb;
  color:#fff;
}

.footer-contact-list p,
.footer-contact-list a{
  color:rgba(255,255,255,.7);
  text-decoration:none;
  line-height:1.7;

  transition:.3s ease;
}

.footer-contact-list a:hover{
  color:#fff;
}

/* ===============================
   LINKS
================================= */

.footer-links ul{
  list-style:none;
  padding:0;
}

.footer-links li{
  margin-bottom:1rem;
}

.footer-links a{
  display:inline-flex;
  align-items:center;
  gap:10px;

  color:rgba(255,255,255,.72);
  text-decoration:none;

  transition:.3s ease;
}

.footer-links a::before{
  content:"→";
  color:#3b82f6;

  transition:.3s ease;
}

.footer-links a:hover{
  color:#fff;
  transform:translateX(6px);
}

/* ===============================
   ABOUT
================================= */

.footer-about-text{
  color:rgba(255,255,255,.68);
  line-height:1.9;
  margin-bottom:2rem;
}

/* ===============================
   NEWSLETTER
================================= */

.footer-newsletter{
  padding:1.5rem;

  border-radius:22px;

  background:
    linear-gradient(
      145deg,
      rgba(255,255,255,.05),
      rgba(255,255,255,.02)
    );

  border:1px solid rgba(255,255,255,.06);

  backdrop-filter:blur(10px);
}

.footer-newsletter h5{
  margin-bottom:1rem;
  font-weight:600;
}

.newsletter-form{
  display:flex;
  gap:10px;
  flex-wrap:wrap;
}

.newsletter-form input{
  flex:1;
  min-width:180px;

  height:50px;
  padding:0 16px;

  border:none;
  outline:none;

  border-radius:14px;

  background:rgba(255,255,255,.06);
  color:#fff;
}

.newsletter-form input::placeholder{
  color:rgba(255,255,255,.4);
}

.newsletter-form button{
  height:50px;
  padding:0 22px;

  border:none;
  border-radius:14px;

  background:
    linear-gradient(
      135deg,
      #2563eb,
      #3b82f6
    );

  color:#fff;
  font-weight:600;

  cursor:pointer;

  transition:.3s ease;
}

.newsletter-form button:hover{
  transform:translateY(-2px);
  box-shadow:
    0 12px 25px rgba(37,99,235,.35);
}

/* ===============================
   COPYRIGHT
================================= */

.w3l-copyright{
  position:relative;
  z-index:2;

  border-top:1px solid rgba(255,255,255,.06);

  margin-top:3rem;
  padding:1.4rem 0;
}

.bottom-copies{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:1rem;
  flex-wrap:wrap;
}

.copyright-left p{
  margin:0;
  color:rgba(255,255,255,.72);
  font-size:.95rem;
}

.copyright-left span{
  color:rgba(255,255,255,.45);
}

/* ===============================
   SOCIALS
================================= */

.main-social-footer-29{
  display:flex;
  gap:12px;
}

.social{
  width:45px;
  height:45px;

  display:flex;
  align-items:center;
  justify-content:center;

  border-radius:14px;

  text-decoration:none;
  color:#fff;

  background:rgba(255,255,255,.06);

  transition:.35s ease;
}

.social:hover{
  transform:translateY(-5px);

  background:
    linear-gradient(
      135deg,
      #2563eb,
      #3b82f6
    );

  box-shadow:
    0 15px 25px rgba(37,99,235,.35);
}

/* ===============================
   RESPONSIVE
================================= */

@media(max-width:991px){

  .footer-top-29{
    text-align:center;
  }

  .footer-brand{
    justify-content:center;
  }

  .footer-title-29::after{
    left:50%;
    transform:translateX(-50%);
  }

  .footer-contact-list li{
    justify-content:center;
  }

  .bottom-copies{
    flex-direction:column;
    text-align:center;
  }

}

@media(max-width:600px){

  .newsletter-form{
    flex-direction:column;
  }

  .newsletter-form button{
    width:100%;
  }

}

</style>