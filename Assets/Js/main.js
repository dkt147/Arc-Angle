  

// wings (only if elements exist)

if (document.querySelector(".left") && document.querySelector(".right")) {

  const wingsTl = gsap.timeline({

    repeat: -1,

    yoyo: true,

    repeatDelay: 0.5

  });



  wingsTl.to(".left", {

    rotationY: 70,

    opacity: 0.8,

    duration: 0.5,

    ease: "power2.inOut"

  }, 0)

  .to(".right", {

    rotationY: -70,

    opacity: 0.8,

    duration: 0.5,

    ease: "power2.inOut"

  }, 0);

}



// progress bar (only if elements exist)

if (document.querySelector(".progress-bar") && document.querySelector(".progress-handle")) {

  const bar = document.querySelector(".progress-bar");

  const handles = document.querySelector(".progress-handle");



  window.addEventListener("load", () => {

    bar.classList.add("active");



    const tl = gsap.timeline();



    tl.to(bar, {

      width: "70%",

      duration: 2,

      ease: "power2.out"

    }, 0);



    tl.to(handles, {

      x: "360px", 

      duration: 2,

      ease: "power2.out"

    }, 0);

  });

}



// card (only if elements exist)

if (document.querySelector(".card-container") && document.querySelector(".card.top")) {

  const container = document.querySelector(".card-container");

  const topCard = document.querySelector(".card.top");



  container.classList.add("active");



  gsap.to(topCard, {

    rotateY: 360,

    duration: 4,

    ease: "none",   

    repeat: -1    

  });

  gsap.to(topCard, {

    rotateX: 10,

    yoyo: true,

    repeat: -1,

    duration: 2,

    ease: "sine.inOut"

  });

}



  // neon bar (only if elements exist)

  if (document.querySelector(".neon-bar-fill") && document.querySelector(".neon-bar-handle")) {

    const fill = document.querySelector(".neon-bar-fill");

    const handle = document.querySelector(".neon-bar-handle");



    window.addEventListener("load", () => {

      const tl = gsap.timeline();



      tl.to(fill, {

        width: "70%",

        duration: 2,

        ease: "power2.out"

      }, 0);



      tl.to(handle, {

        x: "350px",

        duration: 2,

        ease: "power2.out"

      }, 0);

    });

  }





  // statue bg (only if element exists)

  if (document.querySelector(".statue-bg")) {

    gsap.to(".statue-bg", {

      rotation: 360,

      duration: 20,       // slower = higher number (try 30 for even slower)

      repeat: -1,         // infinite loop

      ease: "none",       // constant smooth rotation

      transformOrigin: "center center" // rotate around center

    });

  }





  if (document.querySelector(".call-statue-bg")) {

    gsap.to(".call-statue-bg", {

      rotation: 360,

      duration: 20,       // slower = higher number (try 30 for even slower)

      repeat: -1,         // infinite loop

      ease: "none",       // constant smooth rotation

      transformOrigin: "center center" // rotate around center

    });

  }



// upload functionality (only if elements exist)

if (document.querySelector(".seven-right #uploadBox") && document.querySelector(".seven-right #fileInput") && document.querySelector(".seven-right #previewImage") && document.querySelector(".seven-right #uploadText")) {

  const uploadBox = document.querySelector(".seven-right #uploadBox");

  const fileInput = document.querySelector(".seven-right #fileInput");

  const previewImage = document.querySelector(".seven-right #previewImage");

  const uploadText = document.querySelector(".seven-right #uploadText");



  // Click anywhere on the box to open file dialog

  uploadBox.addEventListener("click", () => fileInput.click());



  // When file is selected, show it inside the box

  fileInput.addEventListener("change", (e) => {

    const file = e.target.files[0];

    if (!file) return;



    const reader = new FileReader();

    reader.onload = () => {

      previewImage.src = reader.result;

      previewImage.style.display = "block";



      // Fade-in image

      setTimeout(() => {

        previewImage.style.opacity = "1";

      }, 50);



      // Hide text smoothly

      uploadText.style.opacity = "0";

      setTimeout(() => (uploadText.style.display = "none"), 400);

    };

    reader.readAsDataURL(file);

  });

}







// drop modal (only if elements exist)

if (document.getElementById('opendropBtn') && document.getElementById('dropModal') && document.getElementById('dropFrame')) {

  // Open modal on click

  document.getElementById('opendropBtn').addEventListener('click', function (e) {

    e.preventDefault(); // <a> ke default scroll ko rokta hai

    document.getElementById('dropModal').style.display = 'flex';

    document.getElementById('dropFrame').src = 'live-drop.html';

  });



  // Close modal function (called from form.html)

  function closedropModal() {

    document.getElementById('dropModal').style.display = 'none';

    document.getElementById('dropFrame').src = 'live-drop.html'; // Reset iframe content

  }



  // Make function global

  window.closedropModal = closedropModal;

}





// form modal (only if elements exist)

if (document.getElementById('openFormBtn') && document.getElementById('formModal') && document.getElementById('formFrame')) {

  // Open modal on click

  document.getElementById('openFormBtn').addEventListener('click', function (e) {

    e.preventDefault(); // <a> ke default scroll ko rokta hai

    document.getElementById('formModal').style.display = 'flex';

    document.getElementById('formFrame').src = 'form.html';

  });



  // Close modal function (called from form.html)

  function closeFormModal() {

    document.getElementById('formModal').style.display = 'none';

    document.getElementById('formFrame').src = ''; // Reset iframe content

  }



  // Open form modal function (for iframe calls)

  function openFormModal() {

    document.getElementById('formModal').style.display = 'flex';

    document.getElementById('formFrame').src = 'form.html';

  }



// Make functions global (only if defined)

if (typeof closeFormModal !== 'undefined') {

  window.closeFormModal = closeFormModal;

}

if (typeof openFormModal !== 'undefined') {

  window.openFormModal = openFormModal;

}

}


    // ================= Asharib Code ===================
// Cinematic mode toggle (only if element exists)
// if (document.getElementById('cinematicToggle')) {
//   const cinematicToggle = document.getElementById('cinematicToggle');
//   let cinematicMode = false;
//   let hue = 0;
//   let interval = null;

//   cinematicToggle.addEventListener('click', () => {
//     cinematicMode = !cinematicMode;
//     const root = document.documentElement;

//     if (cinematicMode) {
//       root.style.transition = 'filter 0.8s ease-in-out';
//       interval = setInterval(() => {
//         hue = (hue + 20) % 360; 
//         root.style.filter = `hue-rotate(${hue}deg)`;
//       }, 1200); 

//       cinematicToggle.textContent = 'Cinematic Mode: On';
//     } else {
//       clearInterval(interval);
//       root.style.filter = 'hue-rotate(0deg)';
//       cinematicToggle.textContent = 'Cinematic Mode: Off';
//     }
//   });
// }
  // ================= Asharib code end ================
  // Cinematic mode toggle (only if element exists)
  
if (document.getElementById('cinematicToggle')) {
  const cinematicToggle = document.getElementById('cinematicToggle');
  let cinematicMode = false;
  let hue = 0;
  let animationId = null; // Changed from interval to animationId
  const targetHue = 186;

  cinematicToggle.addEventListener('click', () => {
    const backgroundVideo = document.getElementsByClassName("background-video")[0];
    cinematicMode = !cinematicMode;
    const root = document.documentElement;

    // Smooth transition for video change
    backgroundVideo.style.transition = 'opacity 0.8s ease-in-out';
    backgroundVideo.style.opacity = '0';
    
    if (cinematicMode) {
      setTimeout(() => {
        backgroundVideo.src = "Assets/Media/Videos/Hero1.gif";
        backgroundVideo.style.opacity = '1';
      }, 400);

      root.style.transition = 'filter 0.8s ease-in-out';

      function animateHue() {
        // Smooth oscillation between 0 and targetHue
        const time = Date.now() / 2000; // 2 second cycle
        hue = (targetHue / 2) + (targetHue / 2) * Math.sin(time);
        
        root.style.filter = `hue-rotate(${hue}deg)`;
        animationId = requestAnimationFrame(animateHue); // Store the animation frame ID
      }
      
      animateHue();
      cinematicToggle.textContent = 'Cinematic Mode: On';
      
    } else {
      // Cancel the animation frame properly
      if (animationId) {
        cancelAnimationFrame(animationId);
        animationId = null;
      }
      
      root.style.filter = 'hue-rotate(0deg)';
      hue = 0;
      cinematicToggle.textContent = 'Cinematic Mode: Off';

      setTimeout(() => {
        backgroundVideo.src = "Assets/Media/Videos/Hero.gif";
        backgroundVideo.style.opacity = '1';
      }, 400);
    }
  });
}




const box = document.getElementById("box");
const paint = document.getElementById("paint");
const logo = document.getElementById("logo");

box.addEventListener("mousemove", (e) => {
  const rect = box.getBoundingClientRect();
  const x = e.clientX - rect.left - rect.width / 2;
  const y = e.clientY - rect.top - rect.height / 2;


  paint.style.transform = `translateZ(5px) rotateY(${x / 8}deg) rotateX(${ -y / 8 }deg)`;

  logo.style.transform = `translateZ(120px) rotateY(${x / 9}deg) rotateX(${ -y / 9 }deg) scale(1.12)`;
});

box.addEventListener("mouseleave", () => {
  paint.style.transform = `translateZ(0px) rotateY(0deg) rotateX(0deg)`;
  logo.style.transform = `translateZ(0px) rotateY(0deg) rotateX(0deg) scale(1)`;
});

// const video = document.getElementById('bgVideo');

//     video.addEventListener('ended', () => {
//         video.currentTime = 0;
//         video.play();
//     });

//     window.addEventListener('load', () => {
//         video.play().catch(() => {});
//     });



