var currentImage = 0;
var sliderImages = document.querySelectorAll(".slider img");
var sliderPrev = document.querySelector(".slider button.prev");
var sliderNext = document.querySelector(".slider button.next");

function showImage(index) {
  sliderImages.forEach(function (img) {
    img.classList.remove("active");
  });
  sliderImages[index].classList.add("active");
}

function nextImage() {
  currentImage++;
  if (currentImage >= sliderImages.length) {
    currentImage = 0;
  }
  showImage(currentImage);
}

function prevImage() {
  currentImage--;
  if (currentImage < 0) {
    currentImage = sliderImages.length - 1;
  }
  showImage(currentImage);
}

var autoSlide = setInterval(function () {
  nextImage();
}, 5000);

sliderPrev.addEventListener("click", function () {
  clearInterval(autoSlide);
  prevImage();
  autoSlide = setInterval(function () {
    nextImage();
  }, 5000);
});

sliderNext.addEventListener("click", function () {
  clearInterval(autoSlide);
  nextImage();
  autoSlide = setInterval(function () {
    nextImage();
  }, 5000);
});
$(document).ready(function () {
  $(".vid-item").each(function (index) {
    $(this).on("click", function () {
      var current_index = index + 1;
      $(".vid-item .thumb").removeClass("active");
      $(".vid-item:nth-child(" + current_index + ") .thumb").addClass("active");
    });
  });
});
