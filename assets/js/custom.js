// Responsive Menu

const hamburger = document.querySelector(".hamburger");
const navLinks = document.querySelector(".nav-links");
const links = document.querySelectorAll(".nav-links li");

// hamburger.addEventListener('click', ()=>{
//    //Animate Links
//     navLinks.classList.toggle("open");
//     links.forEach(link => {
//         link.classList.toggle("fade");
//     });

//     //Hamburger Animation
//     hamburger.classList.toggle("toggle");
// });

// Toggle Menu
hamburger.addEventListener("click", () => {
  navLinks.classList.toggle("open");
  hamburger.classList.toggle("toggle");

  links.forEach((link) => {
    link.classList.toggle("fade");
  });
});

// Submenu toggle (mobile)
document.querySelectorAll(".menu-item-has-children > a").forEach((link) => {
  link.addEventListener("click", function (e) {
    if (window.innerWidth <= 1199) {
      let parent = this.parentElement;
      let submenu = parent.querySelector(".sub-menu");

      if (submenu) {
        e.preventDefault();

        // toggle active class
        parent.classList.toggle("active");
      }
    }
  });
});

// Menu Active Class

// jQuery(document).ready(function ($) {
//   $("ul").on("click", "li a", function () {
//     $(this).closest("ul").find("a").removeClass("active");
//     $(this).addClass("active");
//   });
// });

jQuery(document).ready(function ($) {

  var currentPath = window.location.pathname.replace(/\/$/, "");

  // reset active
  $("ul li a").removeClass("active");
  $("ul li").removeClass("active");

  $("ul li a").each(function () {

    var linkPath = $(this).attr("href");
    if (!linkPath || linkPath === "#") return;

    linkPath = linkPath.replace(/\/$/, "");

    // ✅ HOME
    if (linkPath === "/") {
      if (currentPath === "" || currentPath === "/") {
        activateMenu($(this));
      }
    }

    // ✅ BLOG SPECIAL CASE (blog listing + blog detail)
    else if (linkPath === "/blog.php") {
      if (currentPath.includes("/blog")) {
        activateMenu($(this));
      }
    }

    // ✅ NORMAL PAGES (exact match)
    else if (currentPath === linkPath) {
      activateMenu($(this));
    }

  });

  function activateMenu(el) {

    var href = el.attr("href");

    // avoid double adding issues
    $("ul li a[href='" + href + "']").addClass("active");

    // parent li active
    el.parents("li").addClass("active");

    // parent menu (Services dropdown fix)
    el.closest("ul").closest("li").children("a").addClass("active");
  }

});

// Sticky JS Start
// Sticky on all pages, all widths. Class is added once the user scrolls
// past 50px so the navbar pins to the viewport top.
jQuery(window).scroll(function () {
  if (jQuery(window).scrollTop() >= 50) {
    jQuery(".site-header").addClass("sticky");
  } else {
    jQuery(".site-header").removeClass("sticky");
  }
});

// Sticky JS End

// Counter Start

function inVisible(element) {
  //Checking if the element is
  //visible in the viewport
  var WindowTop = $(window).scrollTop();
  var WindowBottom = WindowTop + $(window).height();
  var ElementTop = element.offset().top;
  var ElementBottom = ElementTop + element.height();
  //animating the element if it is
  //visible in the viewport
  if (ElementBottom <= WindowBottom && ElementTop >= WindowTop)
    animate(element);
}

function animate(element) {
  //Animating the element if not animated before
  if (!element.hasClass("ms-animated")) {
    var maxval = element.data("max");
    var html = element.html();
    element.addClass("ms-animated");
    $({
      countNum: element.html(),
    }).animate(
      {
        countNum: maxval,
      },
      {
        //duration 5 seconds
        duration: 5000,
        easing: "linear",
        step: function () {
          element.html(Math.floor(this.countNum) + html);
        },
        complete: function () {
          element.html(this.countNum + html);
        },
      },
    );
  }
}

//When the document is ready
$(function () {
  //This is triggered when the
  //user scrolls the page
  $(window).scroll(function () {
    //Checking if each items to animate are
    //visible in the viewport
    $("span[data-max]").each(function () {
      inVisible($(this));
    });
  });
});

// Counter End

// industry tabs

var getslide = jQuery(".industy-tabs .main-box li").length - 1;

var slidecal = "10%";

jQuery(".box").css({ width: slidecal });

jQuery(".box").click(function () {
  jQuery(".box").removeClass("active");
  jQuery(this).addClass("active");
});

// CountUp

// $(".counter-value").each(function () {
//   var $this = $(this),
//     countTo = $this.attr("data-count");

//   $({ countNum: $this.text() }).animate(
//     {
//       countNum: countTo,
//     },

//     {
//       duration: 2000,
//       easing: "linear",
//       step: function () {
//         $this.text(Math.floor(this.countNum));
//       },
//       complete: function () {
//         $this.text(this.countNum);
//         //alert('finished');
//       },
//     },
//   );
// });

// tabs

// $("[data-targetit]").on("click", function (e) {
//   ($(this).addClass("active"), $(this).siblings().removeClass("active"));
//   var t = $(this).data("targetit");
//   ($("." + t)
//     .siblings('[class^="box-"]')
//     .hide(),
//     $("." + t).fadeIn());
// });

jQuery(document).ready(function ($) {
  jQuery(".logo-slider").slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    infinite: true,
    arrows: false,
    dots: false,
    swipe: true,
    swipeToSlide: true,
    autoplay: true,
    cssEase: "ease-in-out",
    autoplaySpeed: 3000,
    centerMode: false,
    centerPadding: "0px",
    responsive: [
      {
        breakpoint: 1199,
        settings: {
          slidesToShow: 5,
          infinite: true,
          arrows: false,
          dots: false,
        },
      },
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 4,
          infinite: true,
          arrows: false,
          dots: false,
        },
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 3,
          autoplay: true,
          centerPadding: "0px",
          centerMode: false,
          arrows: false,
          dots: false,
        },
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 2,
          autoplay: true,
          centerPadding: "0px",
          centerMode: false,
          arrows: false,
          dots: false,
        },
      },
    ],
  });

  jQuery(".blogs-slider").slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    infinite: true,
    arrows: false,
    dots: false,
    swipe: true,
    swipeToSlide: true,
    autoplay: false,
    cssEase: "ease-in-out",
    autoplaySpeed: 3000,
    centerMode: false,
    centerPadding: "0px",
    responsive: [
      {
        breakpoint: 1199,
        settings: {
          slidesToShow: 3,
          infinite: true,
          arrows: false,
          dots: false,
        },
      },
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 2,
          infinite: true,
          arrows: false,
          dots: false,
        },
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
          autoplay: true,
          centerPadding: "0px",
          centerMode: false,
          arrows: false,
          dots: false,
        },
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          autoplay: true,
          centerPadding: "0px",
          centerMode: false,
          arrows: false,
          dots: false,
        },
      },
    ],
  });

  jQuery(".testimonial-slider").slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    infinite: true,
    arrows: false,
    dots: true,
    swipe: true,
    swipeToSlide: true,
    autoplay: true,
    cssEase: "ease-in-out",
    autoplaySpeed: 3000,
    centerMode: true,
    centerPadding: "0px",
    responsive: [
      {
        breakpoint: 1199,
        settings: {
          slidesToShow: 3,
          infinite: true,
          arrows: false,
          dots: true,
        },
      },
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 2,
          autoplay: true,
          centerPadding: "0px",
          centerMode: false,
          arrows: false,
          dots: false,
        },
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
          autoplay: true,
          centerPadding: "0px",
          centerMode: false,
          arrows: false,
          dots: false,
        },
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          autoplay: true,
          centerPadding: "0px",
          centerMode: false,
          arrows: false,
          dots: false,
        },
      },
    ],
  });

  jQuery(".portfolio-sliders").slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    infinite: true,
    arrows: false,
    dots: false,
    swipe: true,
    swipeToSlide: true,
    autoplay: true,
    cssEase: "ease-in-out",
    autoplaySpeed: 3000,
    centerMode: true,
    centerPadding: "0px",
    responsive: [
      {
        breakpoint: 1199,
        settings: {
          slidesToShow: 3,
          infinite: true,
          arrows: false,
          dots: false,
        },
      },
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 3,
          autoplay: true,
          centerPadding: "0px",
          centerMode: false,
          arrows: false,
          dots: false,
        },
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 2,
          autoplay: true,
          centerPadding: "0px",
          centerMode: false,
          arrows: false,
          dots: false,
        },
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          autoplay: true,
          centerPadding: "0px",
          centerMode: false,
          arrows: false,
          dots: false,
        },
      },
    ],
  });

  // Portfolio Start

  jQuery(".portfolio-slider").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    infinite: true,
    arrows: false,
    dots: false,
    swipe: true,
    swipeToSlide: true,
    autoplay: true,
    cssEase: "ease-in-out",
    autoplaySpeed: 3000,
    centerMode: true,
    centerPadding: "50px",
    responsive: [
      {
        breakpoint: 1199,
        settings: {
          slidesToShow: 1,
          infinite: true,
          autoplay: true,
          arrows: false,
          dots: false,
        },
      },

      {
        breakpoint: 991,
        settings: {
          slidesToShow: 1,
          infinite: true,
          autoplay: true,
          arrows: false,
          dots: false,
          centerMode: false,
          centerPadding: "0px",
        },
      },

      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
          autoplay: true,
          centerPadding: "0px",
          centerMode: false,
          arrows: false,
          dots: false,
        },
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          autoplay: true,
          centerPadding: "0px",
          centerMode: false,
          arrows: false,
          dots: false,
        },
      },
    ],
  });

  jQuery(document).ready(function ($) {
    // Initial setup to show the first tab
    $(".portfolio-tabs .portfolio-cat-list li:nth-child(1)").addClass("active");
    $(".portfolio-tabs .portfolio-content li:nth-child(1)").addClass(
      "showfirst",
    );

    // ✅ Tab click event + Slick Refresh Fix
    $(".portfolio-tabs .portfolio-cat-list li").click(function (e) {
      e.preventDefault();

      var cur_attr = $(this).attr("data-targetit");

      $(".portfolio-tabs .portfolio-cat-list li").removeClass("active");
      $(this).addClass("active");

      $(".portfolio-content li").removeClass("showfirst");
      var $activeTab = $(".portfolio-content ." + cur_attr).addClass(
        "showfirst",
      );

      // ✅ Slick refresh (jerk fix)
      setTimeout(function () {
        $activeTab.find(".portfolio-slider").slick("setPosition");
        $activeTab.find(".portfolio-slider").slick("refresh");
      }, 50);
    });
  });

  // Portfolio End

  // Start Accordions

  jQuery(document).ready(function ($) {
    $("#heading1").attr("aria-expanded", "true").removeClass("collapsed");
    $("#collapse1").addClass("show");

    $(".card-header").on("click", function () {
      let _this = $(this);
      _this
        .parent()
        .parent()
        .find(".card-header>div")
        .attr("aria-expanded", "false")
        .addClass("collapsed");
      _this
        .parent()
        .parent()
        .find(".card-header+.collapse")
        .removeClass("show");

      _this
        .children("div")
        .first()
        .attr("aria-expanded", "true")
        .removeClass("collapsed");
      _this.next().addClass("show");
    });
  });

  // End Accordions
});

// Load More

jQuery(document).ready(function ($) {
  let increment = 6;

  // Initially hide hidden posts
  $(".hidden-post").hide();

  // ✅ Agar hidden posts hi nahi hain to button hide
  if ($(".hidden-post").length === 0) {
    $(".load-more").hide();
  }

  $(".load-more").click(function (e) {
    e.preventDefault();

    let hiddenItems = $(".hidden-post:hidden").slice(0, increment);

    hiddenItems.slideDown().removeClass("hidden-post");

    // ✅ Agar ab koi hidden post nahi bachi to button hide
    if ($(".hidden-post:hidden").length === 0) {
      $(".load-more").hide();
    }
  });
});

// Blog Detail TOC Stick Js

// jQuery(window).scroll(function () {
//   if (jQuery(window).width() > 1024) {
//     var scrollTop = jQuery(window).scrollTop();

//     var sectionTop = jQuery(".s-blog-sec1").offset().top;
//     var sectionHeight = jQuery(".s-blog-sec1").outerHeight();

//     var sectionBottom = sectionTop + sectionHeight;

//     var stickyHeight = jQuery(".sidebar-sticky").outerHeight();

//     // Sticky start + end limit
//     if (
//       scrollTop > sectionTop + 300 &&
//       scrollTop < sectionBottom - stickyHeight - 180
//     ) {
//       jQuery(".sidebar-sticky").addClass("sticky");
//     } else {
//       jQuery(".sidebar-sticky").removeClass("sticky");
//     }
//   } else {
//     jQuery(".sidebar-sticky").removeClass("sticky");
//   }
// });

jQuery(function ($) {

  let isTicking = false;

  $(window).on("scroll", function () {

    if (!isTicking) {

      window.requestAnimationFrame(function () {

        if ($(window).width() > 1024) {

          let scrollTop = $(window).scrollTop();

          let section = $(".s-blog-sec1");
          let sidebar = $(".sidebar-sticky");

          if (!section.length || !sidebar.length) return;

          let sectionTop = section.offset().top;
          let sectionBottom = sectionTop + section.outerHeight();
          let stickyHeight = sidebar.outerHeight();

          if (
            scrollTop > sectionTop + 300 &&
            scrollTop < sectionBottom - stickyHeight - 170
          ) {
            sidebar.addClass("sticky");
          } else {
            sidebar.removeClass("sticky");
          }

        } else {
          $(".sidebar-sticky").removeClass("sticky");
        }

        isTicking = false;

      });

      isTicking = true;
    }

  });

});

// Blog Detail TOC JS

// document.addEventListener("DOMContentLoaded", function () {
//   const tocList = document.querySelector(".toc-list");
//   const headings = document.querySelectorAll(".s-content h2, .s-content h3");

//   headings.forEach((heading, index) => {
//     let id = "toc-" + index;
//     heading.id = id;

//     let li = document.createElement("li");
//     let a = document.createElement("a");

//     a.href = "#" + id;
//     a.textContent = heading.textContent;

//     // indent for h3
//     if (heading.tagName === "H3") {
//       li.style.marginLeft = "0px";
//     }

//     li.appendChild(a);
//     tocList.appendChild(li);
//   });

//   // Active scroll highlight
//   window.addEventListener("scroll", () => {
//     let scrollPos = window.scrollY;

//     headings.forEach((heading) => {
//       let top = heading.offsetTop - 150;
//       let id = heading.id;

//       if (scrollPos >= top) {
//         document
//           .querySelectorAll(".toc-list a")
//           .forEach((a) => a.classList.remove("active"));
//         let activeLink = document.querySelector(`.toc-list a[href="#${id}"]`);
//         if (activeLink) activeLink.classList.add("active");
//       }
//     });
//   });
// });

document.addEventListener("DOMContentLoaded", function () {

  const tocList = document.querySelector(".toc-list");
  const headings = document.querySelectorAll(".s-content h2, .s-content h3");

  headings.forEach((heading, index) => {
    let id = "toc-" + index;
    heading.id = id;

    let li = document.createElement("li");
    let a = document.createElement("a");

    a.href = "#"; // ⚠️ change
    a.setAttribute("data-id", id); // store id
    a.textContent = heading.textContent;

    if (heading.tagName === "H3") {
      li.style.marginLeft = "0px";
    }

    li.appendChild(a);
    tocList.appendChild(li);
  });

  // ✅ CLICK SCROLL (NO HASH IN URL)
  document.querySelectorAll(".toc-list a").forEach(link => {
    link.addEventListener("click", function (e) {
      e.preventDefault();

      let id = this.getAttribute("data-id");
      let target = document.getElementById(id);

      if (target) {
        window.scrollTo({
          top: target.offsetTop - 120,
          behavior: "smooth"
        });
      }
    });
  });

  // ✅ Active scroll highlight
  window.addEventListener("scroll", () => {
    let scrollPos = window.scrollY;

    headings.forEach((heading) => {
      let top = heading.offsetTop - 150;
      let id = heading.id;

      if (scrollPos >= top) {
        document.querySelectorAll(".toc-list a")
          .forEach(a => a.classList.remove("active"));

        let activeLink = document.querySelector(`.toc-list a[data-id="${id}"]`);
        if (activeLink) activeLink.classList.add("active");
      }
    });
  });

});

// Blog Detail Share Button

jQuery(document).ready(function ($) {
  let url = encodeURIComponent(window.location.href);
  let title = encodeURIComponent(document.title);

  let shareLinks = {
    facebook: "https://www.facebook.com/sharer/sharer.php?u=" + url,
    linkedin: "https://www.linkedin.com/sharing/share-offsite/?url=" + url,
    twitter: "https://twitter.com/intent/tweet?url=" + url + "&text=" + title,
  };

  $(".share-btn").on("click", function (e) {
    e.preventDefault();

    let platform = $(this).data("platform");
    let shareUrl = shareLinks[platform];

    let popupWidth = 600;
    let popupHeight = 500;

    let left = window.screen.width / 2 - popupWidth / 2;
    let top = window.screen.height / 2 - popupHeight / 2;

    window.open(
      shareUrl,
      "shareWindow",
      `width=${popupWidth},height=${popupHeight},top=${top},left=${left},scrollbars=yes,resizable=yes`,
    );
  });
});

// jQuery(function($) {

//     let currentUrl = window.location.pathname;

//     if (currentUrl.includes('/blog')) {
//         $('ul.menu li').removeClass('active');

//         $('ul.menu li a').each(function() {
//             if ($(this).attr('href').includes('/blog.php')) {
//                 $(this).parent().addClass('active');
//             }
//         });
//     }

// });

//  Gsap Slider

// gsap.registerPlugin(ScrollTrigger);

// const Scroll = new (function () {
//   let sections;
//   let page;
//   let main;
//   let scrollTrigger;
//   let tl;
//   let win;

//   // Init
//   this.init = () => {
//     sections = document.querySelectorAll("section.section");
//     page = document.querySelector("#page");
//     main = document.querySelector("main");
//     win = {
//       w: window.innerWidth,
//       h: window.innerHeight,
//     };

//     this.setupTimeline();
//     this.setupScrollTrigger();
//     window.addEventListener("resize", this.onResize);
//   };

//   // Setup ScrollTrigger
//   this.setupScrollTrigger = () => {
//     page.style.height = this.getTotalScroll() + win.h + "px";

//     scrollTrigger = ScrollTrigger.create({
//       id: "mainScroll",
//       trigger: "main",
//       animation: tl,
//       pin: true,
//       scrub: true,
//       snap: {
//         snapTo: (value) => {
//           let labels = Object.values(tl.labels);

//           const snapPoints = labels.map((x) => x / tl.totalDuration());
//           const proximity = 0.1;

//           console.log(tl.labels, tl.totalDuration(), labels, snapPoints);

//           for (let i = 0; i < snapPoints.length; i++) {
//             if (
//               value > snapPoints[i] - proximity &&
//               value < snapPoints[i] + proximity
//             ) {
//               return snapPoints[i];
//             }
//           }
//         },
//         duration: { min: 0.2, max: 0.6 },
//       },
//       start: "top top",
//       end: "+=" + this.getTotalScroll(),
//     });
//   };

//   // Setup timeline
//   this.setupTimeline = () => {
//     tl = gsap.timeline();
//     tl.addLabel("label-initial");

//     sections.forEach((section, index) => {
//       const nextSection = sections[index + 1];
//       if (!nextSection) return;

//       tl.to(nextSection, {
//         y: -1 * nextSection.offsetHeight,
//         duration: nextSection.offsetHeight,
//         ease: "linear",
//       }).addLabel(`label${index}`);
//     });
//   };

//   // On resize
//   this.onResize = () => {
//     win = {
//       w: window.innerWidth,
//       h: window.innerHeight,
//     };

//     this.reset();
//   };

//   // Reset
//   this.reset = () => {
//     if (typeof ScrollTrigger.getById("mainScroll") === "object") {
//       ScrollTrigger.getById("mainScroll").kill();
//     }

//     if (typeof tl === "object") {
//       tl.kill();
//       tl.seek(0);
//     }

//     document.body.scrollTop = document.documentElement.scrollTop = 0;
//     this.init();
//   };

//   // Get total scroll
//   this.getTotalScroll = () => {
//     let totalScroll = 0;
//     sections.forEach((section) => {
//       totalScroll += section.offsetHeight;
//     });
//     totalScroll -= win.h;
//     return totalScroll;
//   };
// })();

// Scroll.init();
