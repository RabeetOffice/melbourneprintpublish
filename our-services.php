<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
      <?php require_once __DIR__ . '/includes/seo-meta.php'; mpp_seo_head('our-services'); ?>      <?php include("includes/style.php"); ?>
      
    <link rel="canonical" href="<?php echo e(brand_canonical()); ?>" />
           </head>
   <body class="<?php echo basename($_SERVER['PHP_SELF'], '.php'); ?>">
      <?php include("includes/disclaimer.php"); ?>
      <header id="masthead" class="site-header">
         <?php include("includes/header.php"); ?>
      </header>
      
   <style>
   .top-cus {
    margin-top: 50px;}
    
    .list-cus {
    padding-bottom: 50px;
}
</style>
      <!-- INNER BANNER -->
      <section class="inner-banner">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="head45">
                     <h1>Our Services</h1>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- SERVICES -->
      <section class="services-sec1 pt80 pb80">
         <div class="container">
            <div class="row">
               <div class="col-md-12 text-center top-cus">
                  <div class="head38 mb20">
                     <h2>Complete Publishing Solutions</h2>
                  </div>
                  <div class="para16 mb40">
                     <p>
                        Explore our expert services designed to help authors write, publish,
                        print and market their books successfully.
                     </p>
                  </div>
               </div>
            </div>
            <div class="service-wrapper-list list-cus">
               <div class="row">
                  <!-- Publishing -->
                  <div class="col-md-4 service_card-wrap">
                     <div class="service_card_bx">
                        <div class="service_card_data">
                           <div class="service_card_content">
                              <h3>Publishing</h3>
                              <div class="service_card_excerpt">
                                 <p>Complete publishing services from ISBN registration to worldwide distribution. Your book goes live on Amazon, Apple Books, Google Play, and major global retailers while you retain full ownership, rights, and maximum royalties from every sale.</p>
                              </div>
                              <div>
                                 <a class="bttn1" href="/publishing/">Read More</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Editing -->
                  <div class="col-md-4 service_card-wrap">
                     <div class="service_card_bx">
                        <div class="service_card_data">
                           <div class="service_card_content">
                              <h3>Editing</h3>
                              <div class="service_card_excerpt">
                                 <p>Professional editing across all levels including developmental editing, copy editing, line editing, and proofreading. Our editors polish structure, grammar, style, clarity, and flow to ensure your manuscript is publication-ready.</p>
                              </div>
                              <div>
                                 <a class="bttn1" href="/professional-editing/">Read More</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Ghostwriting -->
                  <div class="col-md-4 service_card-wrap">
                     <div class="service_card_bx">
                        <div class="service_card_data">
                           <div class="service_card_content">
                              <h3>Ghostwriting</h3>
                              <div class="service_card_excerpt">
                                 <p>Professional ghostwriters bring your ideas to life across fiction, non-fiction, memoirs, business books, and children's stories. You keep complete ownership and confidentiality while receiving a polished manuscript ready to publish.</p>
                              </div>
                              <div>
                                 <a class="bttn1" href="/ghost-writing/">Read More</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Design -->
                  <div class="col-md-4 service_card-wrap">
                     <div class="service_card_bx">
                        <div class="service_card_data">
                           <div class="service_card_content">
                              <h3>Design</h3>
                              <div class="service_card_excerpt">
                                 <p>Custom book cover designs that attract attention and increase sales. We create eBook covers, print wraparounds, children's illustrations, branding assets, and marketing graphics with revisions included.</p>
                              </div>
                              <div>
                                 <a class="bttn1" href="/book-design/">Read More</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Marketing -->
                  <div class="col-md-4 service_card-wrap">
                     <div class="service_card_bx">
                        <div class="service_card_data">
                           <div class="service_card_content">
                              <h3>Marketing</h3>
                              <div class="service_card_excerpt">
                                 <p>Strategic marketing campaigns designed to generate book sales. We manage Amazon optimisation, social media marketing, paid advertising, launches, reviews, email campaigns, and author brand growth.</p>
                              </div>
                              <div>
                                 <a class="bttn1" href="/marketing/">Read More</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Formatting -->
                  <div class="col-md-4 service_card-wrap">
                     <div class="service_card_bx">
                        <div class="service_card_data">
                           <div class="service_card_content">
                              <h3>Book Formatting</h3>
                              <div class="service_card_excerpt">
                                 <p>Professional formatting for paperback, hardcover, Kindle, EPUB and digital platforms. We ensure clean layouts, readable typography, proper margins, clickable contents pages, and upload-ready files.</p>
                              </div>
                              <div>
                                 <a class="bttn1" href="/book-formatting-services-in-melbourne/">Read More</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Ebook Writing -->
                  <div class="col-md-4 service_card-wrap">
                     <div class="service_card_bx">
                        <div class="service_card_data">
                           <div class="service_card_content">
                              <h3>E-Book Writing</h3>
                              <div class="service_card_excerpt">
                                 <p>Transform your concept into a professionally written eBook tailored for online audiences. Ideal for experts, coaches, businesses, and authors seeking valuable digital content that sells.</p>
                              </div>
                              <div>
                                 <a class="bttn1" href="/e-book-writing-services-in-melbourne/">Read More</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Fiction -->
                  <div class="col-md-4 service_card-wrap">
                     <div class="service_card_bx">
                        <div class="service_card_data">
                           <div class="service_card_content">
                              <h3>Fiction Ghostwriting</h3>
                              <div class="service_card_excerpt">
                                 <p>Our fiction ghostwriters craft engaging novels, thrillers, romance, fantasy, and literary stories with strong characters, compelling plots, and a voice aligned with your vision.</p>
                              </div>
                              <div>
                                 <a class="bttn1" href="/fiction-ghostwriting-services-in-melbourne/">Read More</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Proofreading -->
                  <div class="col-md-4 service_card-wrap">
                     <div class="service_card_bx">
                        <div class="service_card_data">
                           <div class="service_card_content">
                              <h3>Book Proofreading</h3>
                              <div class="service_card_excerpt">
                                 <p>Final-stage proofreading for spelling, punctuation, grammar, formatting consistency, and accuracy. Ideal before publishing to ensure a polished and professional final manuscript.</p>
                              </div>
                              <div>
                                 <a class="bttn1" href="/book-proofreading-services-in-melbourne/">Read More</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Non Fiction -->
                  <div class="col-md-4 service_card-wrap">
                     <div class="service_card_bx">
                        <div class="service_card_data">
                           <div class="service_card_content">
                              <h3>Non-Fiction Ghostwriting</h3>
                              <div class="service_card_excerpt">
                                 <p>Professional non-fiction ghostwriting for memoirs, biographies, self-help, business, leadership, and educational books. We turn your expertise into a compelling published work.</p>
                              </div>
                              <div>
                                 <a class="bttn1" href="/non-fiction-ghostwriting-service-in-melbourne/">Read More</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Printing -->
                  <div class="col-md-4 service_card-wrap">
                     <div class="service_card_bx">
                        <div class="service_card_data">
                           <div class="service_card_content">
                              <h3>Book Printing</h3>
                              <div class="service_card_excerpt">
                                 <p>Premium book printing for paperback and hardcover editions with multiple size options, finishes, paper stocks, and durable production quality for authors and businesses.</p>
                              </div>
                              <div>
                                 <a class="bttn1" href="/book-printing-services-in-melbourne/">Read More</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Trailer -->
                  <div class="col-md-4 service_card-wrap">
                     <div class="service_card_bx">
                        <div class="service_card_data">
                           <div class="service_card_content">
                              <h3>Book Trailer Videos</h3>
                              <div class="service_card_excerpt">
                                 <p>Professional promotional book trailer videos built for websites, social media, and ads. Capture attention, create intrigue, and drive stronger reader engagement.</p>
                              </div>
                              <div>
                                 <a class="bttn1" href="/book-trailer-video-services-in-melbourne/">Read More</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Author Website Development - NEW SERVICE -->
                  <div class="col-md-4 service_card-wrap">
                     <div class="service_card_bx">
                        <div class="service_card_data">
                           <div class="service_card_content">
                              <h3>Author Website Development</h3>
                              <div class="service_card_excerpt">
                                 <p>Custom, mobile-responsive author websites designed to sell books and grow your audience. Includes book listings, blog integration, email newsletter setup, SEO, media kits, and full training on WordPress.</p>
                              </div>
                              <div>
                                 <a class="bttn1" href="/author-website-development-services-in-melbourne/">Read More</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Book Illustration - NEW SERVICE -->
                  <div class="col-md-4 service_card-wrap">
                     <div class="service_card_bx">
                        <div class="service_card_data">
                           <div class="service_card_content">
                              <h3>Book Illustration</h3>
                              <div class="service_card_excerpt">
                                 <p>Original custom illustration for children's books, comics, graphic novels, and educational titles. From character design to full colour spreads, matched with illustrators who fit your genre and vision.</p>
                              </div>
                              <div>
                                 <a class="bttn1" href="/book-illustration-services-in-melbourne/">Read More</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Academic Proofreading - NEW SERVICE -->
                  <div class="col-md-4 service_card-wrap">
                     <div class="service_card_bx">
                        <div class="service_card_data">
                           <div class="service_card_content">
                              <h3>Academic Proofreading</h3>
                              <div class="service_card_excerpt">
                                 <p>Expert proofreading for theses, dissertations, research papers, essays, journal articles, and academic books. Grammar, punctuation, citation formatting (APA, MLA, Chicago), and consistency checks.</p>
                              </div>
                              <div>
                                 <a class="bttn1" href="/academic-proofreading-services-in-melbourne/">Read More</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- CTA -->
      <!--<section class="cta-sec">-->
      <!--   <div class="container">-->
      <!--      <div class="row">-->
      <!--         <div class="col-md-12 text-center">-->
      <!--            <div class="head38 mb20">-->
      <!--               <h2>Ready To Publish Your Book?</h2>-->
      <!--            </div>-->
      <!--            <div class="para16 mb20">-->
      <!--               <p>Speak with our Melbourne publishing experts today.</p>-->
      <!--            </div>-->
      <!--            <div class="bttn-grp">-->
      <!--               <a class="bttn2" href="javascript:void(0);" data-toggle="modal"-->
      <!--                  data-target="#exampleModalCenter">Get Started</a>-->
      <!--               <a class="bttn3 openChatBtn" href="javascript:void(0);">Live Chat</a>-->
      <!--            </div>-->
      <!--         </div>-->
      <!--      </div>-->
      <!--   </div>-->
      <!--</section>-->
      <?php include("includes/footer.php"); ?>
      <?php include("includes/script.php"); ?>
   </body>
</html>