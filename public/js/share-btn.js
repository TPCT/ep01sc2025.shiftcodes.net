$(document).ready(function () {
  const $shareBtn = $("#share");
  const $shareOverlay = $("#share-overlay");
  const $closeBtn = $("#close");

  console.log($shareBtn);
  console.log($closeBtn);

  $shareBtn.on("click", function () {
    $shareOverlay.addClass("active");
    $shareBtn.hide();
  });

  $closeBtn.on("click", function () {
    $shareOverlay.removeClass("active");
    $shareBtn.show();
  });
});
