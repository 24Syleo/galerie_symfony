const changeTemplate = document.getElementById("galChange");
const galCarousel = document.getElementById("galCarousel");
const galList = document.getElementById("galList");

changeTemplate.onclick = () => {
  if (changeTemplate.checked) {
    galList.classList.add("d-block");
    galList.classList.remove("d-none");
    galCarousel.classList.add("d-none");
    galCarousel.classList.remove("d-block");
  } else {
    galList.classList.add("d-none");
    galList.classList.remove("d-block");
    galCarousel.classList.add("d-block");
    galCarousel.classList.remove("d-none");
  }
};
