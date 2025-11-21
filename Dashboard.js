//Manipulation du contenu du DOM une fois la page chargée
document.addEventListener("DOMContentLoaded", function () {

    //(Amelioration du systeme dynamique)Restauration de la section active depuis le localStorage
    const activeSection = localStorage.getItem("activeSection");
    if(activeSection){
        const section = document.querySelectorAll(".section-content");
        section.forEach(n => {
            n.classList.add("d-none");
        })
        const targetSection = document.getElementById(activeSection);
        if(targetSection){
            targetSection.classList.remove("d-none");
        }
        const btn = document.querySelectorAll(".sidebar-link");
        btn.forEach((n) => {
            n.classList.remove("active");
            if(n.getAttribute("data-bs-target") ===  activeSection){
                n.classList.add("active");
            }
        });
    }else{
        const section = document.querySelectorAll(".section-content");
        section.forEach(n => {
            n.classList.add("d-none");
        })
        const targetSection = document.getElementById("dashboard-section");
        if(targetSection){
            targetSection.classList.remove("d-none");
        }
        const btn = document.querySelectorAll(".sidebar-link");
        btn.forEach((n) => {
            n.classList.remove("active");
            if(n.getAttribute("data-bs-target") ===  "dashboard-section"){
                n.classList.add("active");
            }
        });
    }
    
    //Management des boutons de la sidebar
    const btnmodif = document.querySelectorAll(".sidebar-link");
    btnmodif.forEach((n) =>
        n.addEventListener("click", function (e) {
            //Reset de la console a chaque click
            console.clear();
            e.preventDefault();
            btnmodif.forEach((btn) => btn.classList.remove("active"));
            this.classList.add("active");
        
    

            //Mangagement du contenu dynamique de la page
            const section = document.querySelectorAll(".section-content");
            section.forEach(n =>{
                n.classList.add("d-none")
            });
            const targetId = this.getAttribute("data-bs-target");
            localStorage.setItem("activeSection", targetId); 
            console.log("j'affiche les elements du Target ID:", targetId); // Debug: log the target ID
            const targetSection = document.getElementById(targetId);
            console.log("j'affiche les elements de Target Section:", targetSection); // Debug: log the target section element
            if (targetSection) {
                targetSection.classList.remove("d-none");
            }

            //Management de la sidebar sur mobile
            const sidebarMenu = document.getElementById("sidebarMenu");
            if(sidebarMenu.classList.contains("show")){
                const bsOffcanvas = bootstrap.Offcanvas.getInstance(sidebarMenu);
                if(bsOffcanvas){
                    bsOffcanvas.hide();
                }
            }
        })
    );

});