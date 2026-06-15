var $ = jQuery.noConflict();

jQuery(document).ready(function ($) {
  // manage popup
  $("a.header-btn").on("click", function (e) {
    e.preventDefault();
    $(".riva-inquire-now-popup-wrapper").addClass("active");
  });

  $(".riva-inquire-now-popup-wrapper .close-icon").on("click", function (e) {
    e.preventDefault();
    $(".riva-inquire-now-popup-wrapper").removeClass("active");
  });
  // manage popup

  // manage popup

  $("#riva_landing_slider").slick({
    dots: false,
    slidesToShow: 3,
    slidesToScroll: 1,
    centerMode: true,
    prevArrow:
      "<button type='button' class='slick-prev slider-arrow '><img src='/wp-content/themes/salient-child/images/slider-arrow.png'></button>",
    nextArrow:
      "<button type='button' class='slick-next slider-arrow'><img src='/wp-content/themes/salient-child/images/slider-arrow.png'></button>",
    responsive: [
      {
        breakpoint: 640,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          infinite: false,
          dots: false,
          centerMode: false,
        },
      },
    ],
  });

  $("#load-more").on("click", function () {
    var button = $(this);
    var page = button.data("page");
    var maxPages = button.data("max");

    $.ajax({
      url: ajax_params.ajax_url, // Uses the dynamic AJAX URL
      type: "POST",
      data: {
        action: "load_more",
        page: page,
      },
      beforeSend: function () {
        button.text("Loading...");
      },
      success: function (data) {
        if (data) {
          $("#my-post-list").append(data);
          button.data("page", page + 1);
          button.text("Load More");

          if (page + 1 > maxPages) {
            button.remove(); // Hide button if no more posts
          }
        } else {
          button.remove();
        }
      },
    });
  });

  $(".filter-erase span").on("click", function () {
    window.location.reload();
  });

  $(".filter-item .riva-select").on("mouseover", function () {
    $(this).toggleClass("active");
    $(".filter-item .riva-select").not(this).removeClass("active");
  });

  $(".filter-options").on("mouseleave", function () {
    $(this).closest(".filter-item").find(".riva-select").removeClass("active");
  });

  // ajax filter
  let floor = "";
  let bedrooms = "";
  let bathrooms = "";
  let category = "";
  let area = "";
  let price = "";
  let orderBy = "";
  let order = "";
  let filterPage = 1;

  $(".filter-option").on("click", function () {
    $(".filter-erase").removeClass("hidden");
    var selectedText = $(this).text();

    $(this).parents(".filter-item").find(".riva-select").toggleClass("active");

    $(this).siblings().removeClass("selected");
    $(this).addClass("selected");

    if (
      $(this).parents(".filter-item").find(".riva-select").hasClass("append")
    ) {
      let text = $(this)
        .parents(".filter-item")
        .find(".riva-select")
        .attr("data-filter");
      $(this)
        .parents(".filter-item")
        .find(".riva-select span")
        .text(text + " " + selectedText);
    } else {
      $(this)
        .parents(".filter-item")
        .find(".riva-select span")
        .text(selectedText);
      category = $(this).attr("data-category");
    }

    if (
      $(this)
        .parents(".filter-item")
        .find(".riva-select")
        .attr("data-filter") == "floor"
    ) {
      floor = selectedText;
    } else if (
      $(this)
        .parents(".filter-item")
        .find(".riva-select")
        .attr("data-filter") == "bedrooms"
    ) {
      bedrooms = selectedText;
    } else if (
      $(this)
        .parents(".filter-item")
        .find(".riva-select")
        .attr("data-filter") == "bathrooms"
    ) {
      bathrooms = selectedText;
    } else if (
      $(this)
        .parents(".filter-item")
        .find(".riva-select")
        .attr("data-filter") == "area"
    ) {
      area = selectedText;
    } else if (
      $(this)
        .parents(".filter-item")
        .find(".riva-select")
        .attr("data-filter") == "price"
    ) {
      price = selectedText;
    } else if (
      $(this)
        .parents(".filter-item")
        .find(".riva-select")
        .attr("data-filter") == "order"
    ) {
      order = selectedText;
    } else if (
      $(this)
        .parents(".filter-item")
        .find(".riva-select")
        .attr("data-filter") == "orderby"
    ) {
      orderBy = selectedText;
    }

    console.log("Floor" + floor);
    console.log("Bedrooms" + bedrooms);
    console.log("Bathrooms" + bathrooms);
    console.log("category" + category);
    console.log("area" + area);
    console.log("price" + price);
    console.log("order By" + orderBy);
    console.log("Order" + order);
    // console.log("Selected Text: " + selectedText);

    $.ajax({
      url: ajax_params.ajax_url, // Uses the dynamic AJAX URL
      type: "POST",
      data: {
        action: "filter_availability_cards",
        floor: floor,
        bedrooms: bedrooms,
        bathrooms: bathrooms,
        category: category,
        area: area,
        price: price,
        order: order,
        orderby: orderBy,
      },

      success: function (response) {
        $("#availability-cards").html(response.data.html);
        $(".result-info .result-count").text(response.data.count);
        currentPage = response.data.page;
        maxPage = response.data.max_page;
        // console.log("response received");
        $("#load-more-availability").remove();

        // if (currentPage == maxPage) {
        //   $(".card-action").remove();
        // } else {
        //   $(".card-action").html(
        //     '<a class="btn-primary" href="#" id="load-more-filter-availability">LOAD MORE</a>'
        //   );
        // }
      },
      error: function (error) {
        console.log(error);
      },
    });
  });

  $(".feature-button").on("click", function () {
    // find parent residence-features-box
    $(this).parents(".residence-features-box").toggleClass("open");
  });

  /**----------------------------*/
  let nextPage = 2;

  $("#load-more-availability").on("click", function (e) {
    e.preventDefault();
    $.ajax({
      url: ajax_params.ajax_url,
      type: "POST",
      data: {
        action: "load_more_cards",
        orderby: orderBy,
        order: order,
        page: nextPage,
      },
      success: function (response) {
        $("#availability-cards").append(response.data.html);
        let queryPage = response.data.page;
        let maxPages = response.data.max_page;

        let currentTotalCount = $(".result-info .result-count").text();
        let newCount =
          parseInt(currentTotalCount) + parseInt(response.data.count);
        $(".result-info .result-count").text(newCount);

        if (queryPage == maxPages) {
          $("#load-more-availability").remove();
        }
        nextPage++;
      },
      error: function (error) {
        console.log(error);
      },
    });
  });

  /**----------------------------*/
  /**----------------------------*/

  $(document).on("click", "#load-more-filter-availability", function (e) {
    e.preventDefault();
  });

  /**----------------------------*/

  // let offset = stickyitem.offset().top;

  //   $(window).scroll(function () {
  //     if ($(window).scrollTop() > 0) {
  //       stickyitem.css({
  //         position: "fixed",
  //         top: "50%",
  //         left: "50%",
  //         zIndex: "999999",
  //         Transform: "translate(-50%, -50%)",
  //         opacity: "0.5",
  //       });
  //       $(".wpb_row.riva-hero .wpb_row.inner_row").css({
  //         opacity: "0",
  //       });
  //     } else {
  //       stickyitem.css({
  //         position: "relative",
  //         opacity: "1",
  //       });
  //       $(".wpb_row.riva-hero .wpb_row.inner_row").css({
  //         opacity: "1",
  //       });
  //     }
  //   });

  // check gsap is installed or not

  //   if (typeof gsap == "undefined") {
  //     console.log("GSAP is loaded");
  //   } else {
  //     console.log("GSAP is not loaded");
  //   }

  // register ScrollTrigger plugin
  gsap.registerPlugin(ScrollTrigger);

  // sticky logo animation on body scroll
  gsap.to(".sticky-logo", {
    scrollTrigger: {
      trigger: "body",
      start: "top top",
      end: "bottom top",
      scrub: true,
    },
    scale: 0.5,
    opacity: 0.3,
    ease: "power1.out",
    zIndex: 1,
  });

  let mm = gsap.matchMedia();

  //   // animate amenities cards on scroll
  //   let amenitiesCards = gsap.utils.toArray(".amenities-card .nectar-fancy-box");

  //   amenitiesCards.forEach((element, index) => {
  //     gsap.to(element, {
  //       scrollTrigger: {
  //         trigger: element,
  //         start: "top bottom",
  //         end: "top top+=100",
  //         scrub: true,
  //       },
  //       //   y: -250,
  //       y: -100 * (index + 1),
  //       //   scale: 1,
  //       //   opacity: 0.5,
  //       ease: "none",
  //     });
  //   });

  // Define the media query for screens larger than 980px
  mm.add("(min-width: 981px)", () => {
    // animate amenities cards on scroll
    let amenitiesCards = gsap.utils.toArray(
      ".amenities-card .nectar-fancy-box",
    );

    amenitiesCards.forEach((element, index) => {
      gsap.to(element, {
        scrollTrigger: {
          trigger: element,
          start: "top bottom",
          end: "top top+=100",
          scrub: true,
        },
        y: -100 * (index + 1),
        ease: "none",
      });
    });
  });

  console.log(amenitiesCards);
  //   gsap.to(".nectar-button", {
  //     x: 100,
  //     y: 500,
  //     yoyo: true,
  //     repeat: -1,
  //     duration: 1,
  //     ease: "power2.out",
  //   });

  /**---------------------------- */
});
