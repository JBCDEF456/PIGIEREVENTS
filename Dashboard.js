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

function initialiserTooltips() {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

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
                    <td class="align-middle"><img src="${user.img_part}" alt="Photo de ${user.nom}" class="img-thumbnail table-thumbnail" style="width: 60px;height: 60px;object-fit: contain;border-radius: 10%; border-color: gray;" /></td>
                    <td class="align-middle ">${user.nom}</td>
                    <td class="align-middle">${user.classe}</td>
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

function deleteEvents(item){
    const conteneur = document.getElementById("text");
    const userID = item.getAttribute('data-event-id');
    conteneur.innerHTML = `Voulez vous vraiment supprimer: ${userID}`;
    const url = `AdminAPI.php?delEvents=${userID}`;
    const supprimer = document.getElementById("confirm-delete-btn");
    supprimer.setAttribute("data-bs-dismiss", "modal");
    supprimer.addEventListener("click", function(){
        fetch(url)
            .then(response => {
                if(response.ok){
                    console.log("Action reussite, pas de contenu JSON");
                    getEvents();
                }else{
                    console.log("Erreur dans le processus");
                }
            })
            .catch(error => console.error('Erreur fetch: ', error));
    });
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
                let badgeClass = event.isActive === 1 ? "badge rounded-pill text-bg-success" : "badge rounded-pill text-bg-danger";
                let statusText = event.isActive === 1 ? "Active" : "Inactive";
                conteneur.innerHTML += `
                <tr>
                    <td><img src="${event.img_cat}" alt="Image du ${event.nom_cat}" class="img-thumbnail table-thumbnail" style="width: 60px;height: 60px;object-fit: contain;border-radius: 10%; border-color: gray;" /></td>
                    <td class="align-middle">${event.nom_cat}</td>
                    <td class="align-middle text-center" data-bs-toggle="tooltip" data-bs-placement="top" title="${event.des_cat}"><div class="text-truncate text-center" style="max-width: 300px;">${event.des_cat}</div></td>
                    <td><span class="${badgeClass} align-middle">${statusText}</span></td>
                    <td>
                        <button class="btn btn-sm btn-danger delete-event-btn align-middle" data-event-id="${event.id_cat}" data-bs-toggle="modal" data-bs-target="#delete-notify" onclick=deleteEvents(this)>Supprimer</button>
                        <button class="btn btn-sm btn-primary update-event-btn align-middle" data-event-id="${event.id_cat}">Modifier</button>
                        <button class="btn btn-sm btn-secondary manage-participants-btn align-middle" data-event-id="${event.id_cat}">Participants</button>
                    </td>
                </tr>
                `;
                initialiserTooltips();
            });
        }
    })
    .catch(error => { console.error("Erreur lors de la récupération des événements:", error); });
}