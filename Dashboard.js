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
                if(activeSection === "participants-section"){
                    getParticipants();
                }
                if(activeSection === "votes-section"){
                    getEvents();
                }
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
                if(targetId === "participants-section"){
                    getParticipants();
                }
                if(targetId === "votes-section"){
                    getEvents();
                }
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


function getParticipants(){
    console.log("Récupération des participants...");
    const conteneur = document.getElementById("liste-user-body");
    conteneur.innerHTML = "";
    fetch("AdminAPI.php?action=participants")
    .then(response => response.json())
    .then(data => {
        if(data.length === 0){
            conteneur.innerHTML = `<tr><td colspan='5' class='text-center'>Aucun participant trouvé.</td></tr>`;
        }else{
            conteneur.innerHTML = "";
            data.forEach(user => {
                conteneur.innerHTML += `<tr>
                    <td>${user.photo}</td>
                    <td>${user.nom}</td>
                    <td>${user.classe}</td>
                    <td>${user.actions}</td>
                    <td>
                        <button class="btn btn-sm btn-danger delete-user-btn" data-user-id="${user.id}">Supprimer</button>
                        <button class="btn btn-sm btn-primary update-user-btn" data-user-id="${user.id}">Modifier</button>
                    </td>
                </tr>
                `;
            });
        }
    })
    .catch(error => { console.error("Erreur lors de la récupération des participants:", error); });
}

function getEvents(){
    console.log("Récupération des événements...");
    const conteneur = document.getElementById("liste-events-body");
    conteneur.innerHTML = "";
    fetch("AdminAPI.php?action=categories")
    .then(response => response.json())
    .then(data => {
        if(data.length === 0){
            conteneur.innerHTML = `<tr><td colspan='5' class='text-center'>Aucun événement trouvé.</td></tr>`;
        }else{
            conteneur.innerHTML = "";
            data.forEach(event => {
                let badgeClass = event.isActive === "Actif" ? "badge rounded-pill text-bg-success" : "badge rounded-pill text-bg-danger";
                conteneur.innerHTML += `<tr>
                    <td>${event.photo}</td>
                    <td>${event.nom_cat}</td>
                    <td><span class="${badgeClass}">${event.isActive}</span></td>
                    <td>${event.description}</td>
                    <td>
                        <button class="btn btn-sm btn-danger delete-event-btn" data-event-id="${event.id}">Supprimer</button>
                        <button class="btn btn-sm btn-primary update-event-btn" data-event-id="${event.id}">Modifier</button>
                    </td>
                </tr>
                `;
            });
        }
    })
    .catch(error => { console.error("Erreur lors de la récupération des événements:", error); });
}