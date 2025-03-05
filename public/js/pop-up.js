// Ticket 116

$(document).ready(function () {
  const popUpModal = $("#popup-modal");

  // Function to check if modal is visible and set page overflow accordingly
  function togglePageScroll() {
    if (popUpModal.is(":visible")) {
      $("html").css("overflow", "hidden"); // Disable scrolling
    } else {
      $("html").css("overflow", "auto"); // Enable scrolling
    }
  }

  // Call the function immediately to check the initial state
  togglePageScroll();

  // Use a MutationObserver to detect style changes on the modal
  const observer = new MutationObserver(togglePageScroll);
  observer.observe(popUpModal[0], {
    attributes: true,
    attributeFilter: ["style"],
  });
});
