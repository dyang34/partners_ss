/*
$(document).ready(function(){
    var swiper = new Swiper(".main-visual-swiper", {
        spaceBetween: 30,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            renderBullet: function (index, className) {
                return '<span class="' + className + '">' + (index + 1) + "</span>";
              },
        },
    });

    $('.swiper-pagination-bullet').hover(function() {
        $( this ).trigger( "click" );
     });
});
*/
// https://codepen.io/sonaeko/pen/BrRoJE



// main visual image area     https://codepen.io/search/pens?q=swiper+gallery+thumbs
var swiper = new Swiper(".main-visual", {
    slidesPerView: 4,
    freeMode: true,
    watchSlidesProgress: true,
  });
  var swiper2 = new Swiper(".main-visual2", {
    spaceBetween: 4,
    effect: "fade",
    thumbs: {
      swiper: swiper,
    },
  });


// UDIRECT Life Area
  var swiper = new Swiper(".direct-life-swiper", {
    pagination: {
      el: ".swiper-pagination",
      dynamicBullets: true,
    },
  });