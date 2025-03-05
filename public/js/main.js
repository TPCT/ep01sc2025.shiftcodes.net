$(document).ready(function () {
  // Footer
  $("#cssmenu").menumaker({
    title: "",
    format: "multitoggle",
  });
  const breakpointValue = 991;

  // to ensure that the plus symbol is showing as initially.
  $(this).find(".main-footer-container .fa-plus").show();
  $(this).find(".main-footer-container .fa-minus").hide();

  $(".toggleButton").click(function () {
    // Check if the window width is less than or equal to 991px our defined break point
    if ($(window).width() <= breakpointValue) {
      // Toggle the visibility of the target element using classes
      $(this).closest("div").next("ul").toggleClass("show");
      // Toggle the icon based on the presence of the 'show' class
      if ($(this).closest(".footer-item").find("ul").hasClass("show")) {
        $(this).find(".fa-plus").hide();
        $(this).find(".fa-minus").show();
      } else {
        $(this).find(".fa-plus").show();
        $(this).find(".fa-minus").hide();
      }
    }
  });

  // Navbar
  $(".navigation-search").click(() => {
    $(".searchBarOpen").addClass("search-active");
    $("body").addClass("no-scroll"); // Disable scroll
  });

  $(".searchBarOpen--closeBtn").click(() => {
    $(".searchBarOpen").removeClass("search-active");
    $("body").removeClass("no-scroll"); // Enable scroll
  });

  // Back to top
  $(".backtotop-box").click(function () {
    $("html, body").animate({ scrollTop: 0 }, "fast");
  });
});

$(document).ready(function () {
  const $shareBtn = $("#share");
  const $shareOverlay = $("#share-overlay");
  const $closeBtn = $("#close");


  $shareBtn.on("click", function () {
    $shareOverlay.addClass("active");
    $shareBtn.hide();
  });

  $closeBtn.on("click", function () {
    $shareOverlay.removeClass("active");
    $shareBtn.show();
  });
});



