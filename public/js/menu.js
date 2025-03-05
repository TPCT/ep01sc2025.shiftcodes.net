(function ($) {
  $.fn.menumaker = function (options) {
    var cssmenu = $(this),
      settings = $.extend(
        {
          format: "dropdown",
          breakpoint: 1200,
          sticky: false,
        },
        options
      );

    return this.each(function () {
      cssmenu.find("li ul").parent().addClass("has-sub");
      if (settings.format != "select") {
        // Check if the body has the 'arabic' class
        var menuText = document.body.classList.contains("arabic-version")
          ? "القائمة"
          : "menu";

        cssmenu.prepend(
          '<div id="menu-button">' +
            '<i class="fa-solid fa-bars"></i> ' +
            '<span class="menu-btn-text">' +
            menuText +
            "</span>" +
            settings.title +
            "</div>"
        );
        $(this)
          .find("#menu-button")
          .on("click", function () {
            $(this).toggleClass("menu-opened");

            var mainmenu = $(this).next("ul");
            if (mainmenu.hasClass("open")) {
              mainmenu.hide().removeClass("open");
            } else {
              mainmenu.show().addClass("open");
              if (settings.format === "dropdown") {
                mainmenu.find("ul").show();
              }
            }
            // Toggle icon
            var icon = $(this).find("i");
            if ($(this).hasClass("menu-opened")) {
              icon.removeClass("fa-bars").addClass("fa-xmark");
            } else {
              icon.removeClass("fa-xmark").addClass("fa-bars");
            }
          });

        multiTg = function () {
          function updateMenu() {
            var screenWidth = $(window).width();

            if (screenWidth <= 1200) {
              // Add icons if they don't already exist
              if (cssmenu.find(".submenu-button").length === 0) {
                cssmenu
                  .find(".has-sub")
                  .prepend(
                    '<span class="submenu-button"><i class="fa-solid fa-plus"></i></span>'
                  );
              }
            } else {
              // Remove icons if they exist
              cssmenu.find(".submenu-button").remove();
            }
          }

          // Initialize menu
          updateMenu();

          // Set up event handler for submenu button
          cssmenu.find(".submenu-button").on("click", function () {
            $(this).toggleClass("submenu-opened");
            var submenu = $(this).siblings("ul");
            if (submenu.hasClass("open")) {
              submenu.removeClass("open").hide();
            } else {
              submenu.addClass("open").show();
            }
            // Toggle the icon
            var icon = $(this).find("i");
            if ($(this).hasClass("submenu-opened")) {
              icon.removeClass("fa-plus").addClass("fa-minus");
            } else {
              icon.removeClass("fa-minus").addClass("fa-plus");
            }
          });

          // Handle window resize
          $(window).resize(function () {
            updateMenu();
          });
        };

        if (settings.format === "multitoggle") multiTg();
        else cssmenu.addClass("dropdown");
      } else if (settings.format === "select") {
        cssmenu.append('<select style="width: 100%"/>').addClass("select-list");
        var selectList = cssmenu.find("select");
        selectList.append("<option>" + settings.title + "</option>", {
          selected: "selected",
          value: "",
        });
        cssmenu.find("a").each(function () {
          var element = $(this),
            indentation = "";
          for (i = 1; i < element.parents("ul").length; i++) {
            indentation += "-";
          }
          selectList.append(
            '<option value="' +
              $(this).attr("href") +
              '">' +
              indentation +
              element.text() +
              "</option"
          );
        });
        selectList.on("change", function () {
          window.location = $(this).find("option:selected").val();
        });
      }

      if (settings.sticky === true) cssmenu.css("position", "fixed");

      resizeFix = function () {
        if ($(window).width() > settings.breakpoint) {
          cssmenu.find("ul").show();
          cssmenu.removeClass("small-screen");
          if (settings.format === "select") {
            cssmenu.find("select").hide();
          } else {
            cssmenu.find("#menu-button").removeClass("menu-opened");
          }
        }

        if (
          $(window).width() <= settings.breakpoint &&
          !cssmenu.hasClass("small-screen")
        ) {
          cssmenu.find("ul").hide().removeClass("open");
          cssmenu.addClass("small-screen");
          if (settings.format === "select") {
            cssmenu.find("select").show();
          }
        }
      };
      resizeFix();
      return $(window).on("resize", resizeFix);
    });
  };
})(jQuery);
