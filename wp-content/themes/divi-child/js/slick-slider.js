document.addEventListener('DOMContentLoaded', function(){
  jQuery('.single-item:not(.slick-initialized)').slick({
    dots: true,
    arrows: true,
    infinite: false,
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: false,
    autoplaySpeed: 7000,
    responsive: [
      {
        breakpoint: 1400,
        settings: {
          slidesToShow: 2,
          arrows: true
        }
      },
      {
        breakpoint: 980,
        settings: {
          slidesToShow: 2,
          arrows: true
        }
      },
      {
        breakpoint: 780,
        settings: {
          slidesToShow: 1,
          arrows: true
         }
      }
    ]
  });
});

document.addEventListener('DOMContentLoaded', function(){
  jQuery('.blue-slider:not(.slick-initialized)').slick({
    dots: true,
    arrows: true,
    infinite: false,
    slidesToShow: 3,
    slidesToScroll: 3,
    autoplay: false,
    autoplaySpeed: 7000,
    responsive: [
      {
        breakpoint: 968,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: true
        }
      },
      {
        breakpoint: 680,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: true
         }
      }
    ]
  });
});

document.addEventListener('DOMContentLoaded', function(){
  jQuery('.single-item-arrows:not(.slick-initialized)').slick({
    dots: true,
    infinite: false,
    slidesToShow: 3,
    slidesToScroll: 3,
    autoplay: false,
    autoplaySpeed: 5000,
    responsive: [
      {
        breakpoint: 1200,
        settings: { slidesToShow: 2,
        slidesToScroll: 2 }
      },
      {
        breakpoint: 680,
        settings: { slidesToShow: 1,
        slidesToScroll: 1 }
      }
    ]
  });
});
