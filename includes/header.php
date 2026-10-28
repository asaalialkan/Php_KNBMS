<?php 
$isLoggedIn = !empty($_SESSION['bpmsuid']);
?>

<!-- =========================
     HEADER ONLY
========================= -->

<section class="w3l-header-4 header-sticky">

  <header class="absolute-top" id="mainHeader">

    <div class="hdr-glow"></div>

    <div class="hdr-wrap">

      <!-- BRAND -->
      <a class="hdr-brand" href="index.php">

        <div class="hdr-logo">
          <svg width="20" height="20" viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2.2"
            stroke-linecap="round"
            stroke-linejoin="round">

            <path d="M6 3l12 9-12 9"/>
            <line x1="6" y1="12" x2="18" y2="12"/>

          </svg>
        </div>

        <div class="hdr-brand-text">
          <span>Barbershop</span>
          <small>Premium Grooming</small>
        </div>

      </a>

      <!-- MOBILE TOGGLE -->
      <button class="hdr-toggle" id="hdrToggle">

        <span class="hdr-bar"></span>
        <span class="hdr-bar"></span>
        <span class="hdr-bar"></span>

      </button>

      <!-- NAVIGATION -->
      <nav class="hdr-nav" id="hdrNav">

        <!-- LEFT LINKS -->
        <ul class="hdr-links">

          <li>
            <a class="hdr-link active" href="index.php">Home</a>
          </li>

          <li>
            <a class="hdr-link" href="about.php">About</a>
          </li>

          <li>
            <a class="hdr-link" href="services.php">Services</a>
          </li>

          <li>
            <a class="hdr-link" href="contact.php">Contact</a>
          </li>
         <li>
              <a class="hdr-link" href="signup.php">
                Signup
              </a>
            </li>

            <li>
              <a class="hdr-btn" href="login.php">
                Login
              </a>
            </li>
   

        </ul>

        <!-- RIGHT SIDE -->
        <ul class="hdr-actions">

          <?php if(!$isLoggedIn): ?>

   
       <li>
            <a class="hdr-link hdr-admin" href="admin/index.php">
              Admin
            </a>
          </li>
          <?php else: ?>

            <li>
              <a class="hdr-btn hdr-book-btn"
                href="book-appointment.php">

                Book Appointment

              </a>
            </li>

            <!-- ACCOUNT DROPDOWN -->
            <li class="hdr-dropdown-wrap">

              <button class="hdr-account" id="acctBtn">

                <svg width="16"
                  height="16"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2">

                  <circle cx="12" cy="8" r="4"/>
                  <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>

                </svg>

                My Account

                <svg class="hdr-chevron"
                  width="12"
                  height="12"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2.5">

                  <polyline points="6 9 12 15 18 9"/>

                </svg>

              </button>

              <!-- DROPDOWN -->
              <ul class="hdr-dropdown" id="acctDropdown">

                <li>
                  <a class="hdr-drop-item"
                    href="booking-history.php">

                    Booking History

                  </a>
                </li>

                <li>
                  <a class="hdr-drop-item"
                    href="invoice-history.php">

                    Invoice History

                  </a>
                </li>

                <li>
                  <a class="hdr-drop-item"
                    href="profile.php">

                    Profile

                  </a>
                </li>

                <li>
                  <a class="hdr-drop-item"
                    href="change-password.php">

                    Settings

                  </a>
                </li>

                <li class="hdr-divider"></li>

                <li>
                  <a class="hdr-drop-item logout"
                    href="logout.php">

                    Logout

                  </a>
                </li>

              </ul>

            </li>

          <?php endif; ?>

        </ul>

      </nav>

    </div>

  </header>

</section>

<style>

/* =========================
   HEADER
========================= */

.w3l-header-4 .absolute-top{
  position:sticky;
  top:0;
  width:100%;
  z-index:1000;

  background:rgba(10,10,10,.82);

  backdrop-filter:blur(18px);
  -webkit-backdrop-filter:blur(18px);

  border-bottom:1px solid rgba(255,255,255,.06);

  transition:.35s ease;
  overflow:visible;
}

/* GLOW */

.hdr-glow{
  position:absolute;
  width:450px;
  height:450px;

  background:radial-gradient(circle,
    rgba(59,130,246,.28) 0%,
    rgba(251, 251, 252, 0) 70%);

  top:-220px;
  left:-120px;

  pointer-events:none;
}

/* WRAP */

.hdr-wrap{
  max-width:1250px;
  margin:auto;
  padding:0 1.5rem;
  height:78px;

  display:flex;
  align-items:center;
  justify-content:space-between;

  position:relative;
}

/* BRAND */

.hdr-brand{
  display:flex;
  align-items:center;
  gap:14px;

  text-decoration:none;
  color:#fff;
}

.hdr-logo{
  width:64px;
  height:44px;

  border-radius:14px;

  display:flex;
  align-items:center;
  justify-content:center;

  background:linear-gradient(135deg,#2563eb,#60a5fa);

  box-shadow:
    0 10px 25px rgba(37,99,235,.35);

  transition:.35s ease;
}

.hdr-brand:hover .hdr-logo{
  transform:rotate(-8deg) scale(1.06);
}

.hdr-brand-text{
  display:flex;
  flex-direction:column;
}

.hdr-brand-text span{
  font-size:1.1rem;
  font-weight:700;
  letter-spacing:.2px;
}

.hdr-brand-text small{
  color:rgba(255,255,255,.55);
  font-size:.7rem;
  letter-spacing:1.5px;
  text-transform:uppercase;
}

/* NAVIGATION */

.hdr-nav{
  display:flex;
  align-items:center;
  justify-content:space-between;

  flex:1;
  margin-left:3rem;
}

.hdr-links,
.hdr-actions{
  display:flex;
  align-items:center;
  gap:.45rem;

  list-style:none;
}

.hdr-actions{
  margin-left:auto;
}

/* LINKS */

.hdr-link{
  position:relative;

  color:rgba(255,255,255,.72);
  text-decoration:none;

  font-size:14px;
  font-weight:500;

  padding:11px 14px;
  border-radius:12px;

  transition:.3s ease;
}

.hdr-link:hover{
  color:#fff;
  background:rgba(255,255,255,.06);
}

.hdr-link::after{
  content:'';

  position:absolute;
  left:12px;
  right:12px;
  bottom:7px;

  height:2px;
  background:#3b82f6;

  transform:scaleX(0);
  transform-origin:center;

  transition:.3s ease;
}

.hdr-link:hover::after,
.hdr-link.active::after{
  transform:scaleX(1);
}

.hdr-admin{
  color:#60a5fa !important;
  font-weight:600;
}

/* BUTTON */

.hdr-btn{
  display:inline-flex;
  align-items:center;
  justify-content:center;

  padding:11px 18px;
  border-radius:14px;

  text-decoration:none;
  color:#fff;

  font-size:14px;
  font-weight:600;

  background:linear-gradient(135deg,#2563eb,#3b82f6);

  box-shadow:
    0 12px 25px rgba(37,99,235,.25);

  transition:.35s ease;
}

.hdr-btn:hover{
  transform:translateY(-2px);

  box-shadow:
    0 18px 35px rgba(37,99,235,.35);
}

/* ACCOUNT */

.hdr-dropdown-wrap{
  position:relative;
}

.hdr-account{
  display:flex;
  align-items:center;
  gap:8px;

  background:rgba(255,255,255,.05);
  border:1px solid rgba(255,255,255,.08);

  color:#fff;

  padding:10px 14px;
  border-radius:14px;

  cursor:pointer;

  transition:.3s ease;
}

.hdr-account:hover{
  background:rgba(255,255,255,.09);
}

.hdr-chevron{
  transition:.3s ease;
}

.hdr-account.active .hdr-chevron{
  transform:rotate(180deg);
}

/* DROPDOWN */

.hdr-dropdown{
  position:absolute;
  top:calc(100% + 14px);
  right:0;

  width:240px;

  background:rgba(15,15,15,.98);

  backdrop-filter:blur(18px);
  -webkit-backdrop-filter:blur(18px);

  border:1px solid rgba(255,255,255,.08);
  border-radius:18px;

  padding:12px;

  list-style:none;

  opacity:0;
  visibility:hidden;
  transform:translateY(12px);

  transition:all .28s ease;

  box-shadow:
    0 20px 40px rgba(0,0,0,.45),
    0 0 0 1px rgba(255,255,255,.03);

  z-index:999;
}

.hdr-dropdown.open{
  opacity:1;
  visibility:visible;
  transform:translateY(0);
}

/* ITEMS */

.hdr-drop-item{
  display:block;

  color:rgba(255,255,255,.82);
  text-decoration:none;

  padding:13px 15px;
  border-radius:14px;

  font-size:14px;
  font-weight:500;

  transition:.25s ease;
}

.hdr-drop-item:hover{
  background:rgba(59,130,246,.14);
  color:#fff;
  transform:translateX(4px);
}

.hdr-divider{
  height:1px;
  background:rgba(255,255,255,.08);
  margin:10px 0;
}

.logout{
  color:#f87171;
}

/* MOBILE */

.hdr-toggle{
  display:none;
  flex-direction:column;
  gap:5px;

  width:44px;
  height:44px;

  border:none;
  background:rgba(255,255,255,.05);

  border-radius:12px;
  cursor:pointer;
}

.hdr-bar{
  width:22px;
  height:2px;
  background:#fff;
  margin:auto;
}

@media(max-width:950px){

  .hdr-toggle{
    display:flex;
  }

  .hdr-nav{
    position:absolute;
    top:78px;
    left:0;
    right:0;

    display:flex;
    flex-direction:column;
    align-items:stretch;

    background:#0f0f0f;

    padding:1rem 1.3rem 1.5rem;

    border-bottom:1px solid rgba(255,255,255,.07);

    opacity:0;
    visibility:hidden;
    transform:translateY(-15px);

    transition:.35s ease;
  }

  .hdr-nav.open{
    opacity:1;
    visibility:visible;
    transform:translateY(0);
  }

  .hdr-links,
  .hdr-actions{
    flex-direction:column;
    width:100%;
  }

  .hdr-link,
  .hdr-btn,
  .hdr-account{
    width:100%;
    justify-content:center;
  }

  .hdr-actions{
    margin-top:1rem;
    padding-top:1rem;

    border-top:1px solid rgba(255,255,255,.08);
  }

  .hdr-dropdown{
    position:static;
    width:100%;
    margin-top:10px;
  }

}

</style>

<script>

(function(){

  const toggle = document.getElementById('hdrToggle');
  const nav = document.getElementById('hdrNav');

  const acctBtn = document.getElementById('acctBtn');
  const acctDropdown = document.getElementById('acctDropdown');

  /* MOBILE MENU */

  if(toggle){

    toggle.addEventListener('click',()=>{

      toggle.classList.toggle('open');
      nav.classList.toggle('open');

    });

  }

  /* ACCOUNT DROPDOWN */

  if(acctBtn){

    acctBtn.addEventListener('click',(e)=>{

      e.stopPropagation();

      acctBtn.classList.toggle('active');
      acctDropdown.classList.toggle('open');

    });

    document.addEventListener('click',()=>{

      acctBtn.classList.remove('active');
      acctDropdown.classList.remove('open');

    });

  }

  /* HEADER SHADOW */

  const header = document.getElementById('mainHeader');

  window.addEventListener('scroll',()=>{

    if(window.scrollY > 15){

      header.style.background = "rgba(8,8,8,.92)";
      header.style.boxShadow =
        "0 10px 30px rgba(0,0,0,.35)";

    }else{

      header.style.background =
        "rgba(10,10,10,.82)";

      header.style.boxShadow = "none";

    }

  });

})();

</script>