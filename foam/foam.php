<?php
$siteRoot = realpath(__DIR__ . '/..');
$docRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
$basePath = '';
if ($siteRoot && $docRoot && strpos($siteRoot, $docRoot) === 0) {
    $basePath = '/' . trim(str_replace('\\', '/', substr($siteRoot, strlen($docRoot))), '/');
    if ($basePath === '/') {
        $basePath = '';
    }
}
$formAction = $basePath . '/form-submission.php';
$currentUrl = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://')
    . ($_SERVER['HTTP_HOST'] ?? 'melbourneprintpublish.com.au')
    . ($_SERVER['REQUEST_URI'] ?? '/');
?>
<div class="contact-form">
    
     <form action="<?php echo htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8'); ?>" method="POST">

    <!-- First & Last Name -->
    <div class="form-group">
      <div class="form-group-col">
        <input name="firstName" type="text" placeholder="First Name" required>
      </div>
      <div class="form-group-col">
        <input name="lastName" type="text" placeholder="Last Name" required>
      </div>
    </div>

    <!-- Email & Phone -->
    <div class="form-group">
      <div class="form-group-col">
        <input name="email" type="email" placeholder="Enter your email address" required>
      </div>
      <div class="form-group-col">
        <input name="phone" type="tel" placeholder="Enter your phone number" required>
      </div>
    </div>

    <!-- Services -->
    <div class="form-group-row">
      <select name="services" required>
        <option value="">Please select services</option>
        <option value="Book Publishing">Book Publishing</option>
        <option value="3D Book Cover Design">3D Book Cover Design</option>
        <option value="Book Marketing">Book Marketing</option>
        <option value="Proof-Reading">Proof-Reading</option>
        <option value="Author Website Development">Author's Website Development</option>
        <option value="Book Writing">Book Writing</option>
        <option value="Other">Other</option>
      </select>
    </div>
    
    <!-- Message -->
    <div class="form-group-row">
      <textarea name="message" placeholder="Please let us know what's on your mind..." rows="6" required></textarea>
    </div>

    <input type="hidden" name="form_type" value="contact">
    <input type="hidden" name="fullpageurl" value="<?php echo htmlspecialchars($currentUrl, ENT_QUOTES, 'UTF-8'); ?>">

    <?php if (function_exists('mpp_recaptcha_form_field')) { mpp_recaptcha_form_field(); } ?><button type="submit">Submit</button>

  </form>

    <!--<form>-->
    <!--    <div class="form-group">-->
    <!--        <div class="form-group-col">-->
                <!-- <label>First Name</label> -->
    <!--            <input name="firstName" id="firstName" type="text" placeholder="First Name" required>-->
    <!--        </div>-->
    <!--        <div class="form-group-col">-->
                <!-- <label>Last Name</label> -->
    <!--            <input name="lastName" id="lastName" type="text" placeholder="Last Name" required>-->
    <!--        </div>-->
    <!--    </div>-->


    <!--    <div class="form-group">-->
    <!--        <div class="form-group-col">-->
                <!-- <label>Email Address</label> -->
    <!--            <input name="email" id="email" type="email" placeholder="Enter your email address" required>-->
    <!--        </div>-->
    <!--        <div class="form-group-col">-->
                <!-- <label>Phone Number</label> -->
    <!--            <input name="phone" id="phone" type="tel" placeholder="Enter your phone number">-->
    <!--        </div>-->
    <!--    </div>-->


    <!--    <div class="form-group-row">-->
            <!-- <label>Please Select Service</label> -->
    <!--        <select id="services">-->
    <!--            <option value="" selected="selected">Please select services</option>-->
    <!--            <option value="Book Publishing">Book Publishing</option>-->
    <!--            <option value="3D Book Cover Design">3D Book Cover Design</option>-->
    <!--            <option value="Book Marketing">Book Marketing</option>-->
    <!--            <option value="Proof-Reading">Proof-Reading</option>-->
    <!--            <option value="Author's Website Development">Author's Website Development</option>-->
    <!--            <option value="Book Writing">Book Writing</option>-->
    <!--        </select>-->
    <!--    </div>-->
        
    <!--    <div class="form-group-row">-->
            <!-- <label>Your Message</label> -->
    <!--        <textarea id="textarea" class="textarea" placeholder="Please let us know what's on your mind. Have a question for us? Ask away." rows="10" cols="50"></textarea>-->
    <!--    </div>-->

    <!--    <button type="submit">Submit</button>-->
    <!--</form>-->
</div>
