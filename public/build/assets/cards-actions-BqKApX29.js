document.addEventListener("DOMContentLoaded",function(){const n=Array.from(document.querySelectorAll(".card-collapsible")),i=Array.from(document.querySelectorAll(".card-expand")),u=Array.from(document.querySelectorAll(".card-close")),s=document.getElementById("sortable-4");n.forEach(function(e){e.addEventListener("click",function(t){t.preventDefault(),new bootstrap.Collapse(e.closest(".card").querySelector(".collapse")),e.closest(".card-header").classList.toggle("collapsed"),Helpers._toggleClass(e.firstElementChild,"bx-chevron-down","bx-chevron-up")})}),i.forEach(function(e){e.addEventListener("click",function(t){t.preventDefault(),Helpers._toggleClass(e.firstElementChild,"bx-fullscreen","bx-exit-fullscreen"),e.closest(".card").classList.toggle("card-fullscreen")})}),document.addEventListener("keyup",function(e){if(e.preventDefault(),e.key==="Escape"){const t=document.querySelector(".card-fullscreen");t&&(Helpers._toggleClass(t.querySelector(".card-expand").firstElementChild,"bx-fullscreen","bx-exit-fullscreen"),t.classList.toggle("card-fullscreen"))}}),u.forEach(function(e){e.addEventListener("click",function(t){t.preventDefault(),e.closest(".card").classList.add("d-none")})}),s&&Sortable.create(s,{animation:500,handle:".card"});const l=document.querySelectorAll(".card-reload");l&&(document.querySelectorAll(".card-action").forEach((t,c)=>{t.dataset.cardId=`card-${c+1}`}),l.forEach(t=>{t.addEventListener("click",function(c){c.preventDefault();const r=t.closest(".card-action");if(!r){console.error("Closest card with .card-action class not found!");return}const a=r.dataset.cardId;Block.standard(`[data-card-id="${a}"]`,{backgroundColor:document.documentElement.getAttribute("data-bs-theme")==="dark"?"rgba("+window.Helpers.getCssVar("black-rgb")+", 0.5)":"rgba("+window.Helpers.getCssVar("white-rgb")+", 0.5)",svgSize:"0px"});const f=`
          <div class="sk-fold sk-primary">
            <div class="sk-fold-cube"></div>
            <div class="sk-fold-cube"></div>
            <div class="sk-fold-cube"></div>
            <div class="sk-fold-cube"></div>
          </div>
          <h5>LOADING...</h5>
        `,o=r.querySelector(".notiflix-block");o&&(o.innerHTML=f),setTimeout(function(){Block.remove(`[data-card-id="${a}"]`);const d=r.querySelector(".card-alert");d&&(d.innerHTML=`
              <div class="alert alert-solid-danger alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <span class="fw-medium">Holy grail!</span> Your success/error message here.
              </div>
            `)},2500)})}))});
