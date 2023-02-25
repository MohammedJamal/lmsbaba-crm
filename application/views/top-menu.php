<?php
$ip = $this->input->ip_address();
$ip_wise_country_code=ip_info("$ip", "Country Code");
?>
<ul class="navbar-nav ml-auto">
    <li class="nav-item">
        <a href="https://lmsbaba.com/#Features" class="nav-link dropdown-toggle">Features
        </a>
    </li>
    <li class="nav-item">
        <a href="about-us" class="nav-link dropdown-toggle">
            About us
        </a>
    </li>
    <!-- <li class="nav-item">
        <a href="#AboutUs" class="nav-link dropdown-toggle">
            About us
        </a>
    </li> -->
    <?php if($ip_wise_country_code=='IN'){?>
    <li class="nav-item">
        <a href="https://lmsbaba.com/#Pricing" class="nav-link">Pricing</a>
    </li>
    <?php } ?>
    <li class="nav-item">
        <a href="https://lmsbaba.com/#Clients" class="nav-link">Clients</a>
    </li>
    <!-- <li class="nav-item">
        <a href="#Clients" class="nav-link">We are Hiring</a>
    </li> -->
    <li class="nav-item">
        <a href="contact-us" class="nav-link contact_us--">Contact</a>
    </li>
    <li class="nav-item border-btn">
        <a href="schedule-demo" class="nav-link btn btn-primary book_demo--">Book Demo</a>
    </li>
</ul>