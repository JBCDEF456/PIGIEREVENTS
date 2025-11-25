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
                    <td class="align-middle"><img src="${user.img}" alt="Photo de ${user.nom}" class="img-thumbnail table-thumbnail" style="width: 60px;height: 60px;object-fit: contain;border-radius: 10%; border-color: gray;" /></td>
                    <td class="align-middle ">${user.nom}</td>
                    <td class="align-middle">${user.classe}</td>
                    <td>
                        <button class="btn btn-sm btn-danger delete-user-btn" data-event-id="${user.id}" data-event-name="${user.nom}" data-event-img="${user.img}" data-bs-toggle="modal" data-type="candidat" data-action="delete" data-bs-target="#delete-notify" onclick=actionEvent(this)>Supprimer</button>
                        <button class="btn btn-sm btn-primary update-user-btn" data-event-id="${user.id}" data-event-name="${user.nom}" data-event-classe="${user.classe}" data-event-img="${user.img}" data-bs-target="#PartUpdater" data-bs-toggle="modal" data-type="candidat" data-action="update" onclick=actionEvent(this)>Modifier</button>
                    </td>
                </tr>
                `;
            });
        }
    })
    .catch(error => { console.error("Erreur lors de la récupération des participants:", error); });
}

function notifyMessage(message, type){
    const container = document.getElementById('alertContainer');

    const alertHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    container.innerHTML = alertHTML;

    setTimeout(() => {
        const alertEl = container.querySelector('.alert');
        if (bootstrap && alertEl) { 
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alertEl);
            bsAlert.close();
        } else {
           
            container.innerHTML = ''; 
        }
    }, 5000);
}

function deleteEvents(item,type){
    const conteneur = document.getElementById("text");
    const userID = item.getAttribute('data-event-id');
    conteneur.innerHTML = `Voulez vous vraiment supprimer: ${item.getAttribute('data-event-name')}`;
    const url = `AdminAPI.php?delEvent=${userID}&type=${type}`;
    const supprimer = document.getElementById("confirm-delete-btn");
    supprimer.setAttribute("data-bs-dismiss", "modal");
    supprimer.addEventListener("click", function(){
        fetch(url)
            .then(response => {
                if(response.ok){
                    console.log("Action reussite, pas de contenu JSON");
                    notifyMessage("Element supprime avec success", 'success');
                    if(type === 'candidat'){
                        getParticipants();
                    }else{
                        getEvents();
                    }
                }else{
                    notifyMessage("Erreur lors de la suppression", 'danger');
                    console.log("Erreur dans le processus");
                }
            })
            .catch(error => {
                notifyMessage("Erreur lors de la suppression", 'danger');
                console.error('Erreur fetch: ', error);
            });
    });
}



function ModifyEvent(item,itemType){
    if(itemType === 'categorie'){
       //Manip d'affichages des valeurs par defauts
       //Recuperation des input
       let name = document.getElementById('newName');
       let desc = document.getElementById('newDes');
       let img = document.getElementById('lastImg');
       let state = document.getElementById('updateState');
       //Transfert de donnee au form
       const updater = document.getElementById('CatUpdate');
       updater.setAttribute('data-event-id', item.getAttribute('data-event-id'));
       //Don des valeurs par defaut
       name.setAttribute('value', item.getAttribute('data-event-name'));
       desc.setAttribute('value', item.getAttribute('data-event-des'));
       img.setAttribute('src', item.getAttribute('data-event-img'));
       if(item.getAttribute('data-event-state')==='1'){
        state.setAttribute('checked', 'checked');
        state.setAttribute('value', '1');
        state.checked = true;
       }else{
        state.removeAttribute('checked');
        state.setAttribute('value', '0');
        state.checked = false;
       }
        let ID = document.getElementById('IDcat');
        ID.setAttribute('value', item.getAttribute('data-event-id'));

    }else if(itemType === 'candidat'){
        //Recuperation des input
        let name = document.getElementById('newName2');
        console.log(item.getAttribute('data-event-img'));
        let classe = document.getElementById('newCla');
        let img = document.getElementById('lastImg2');
        //Transfert de donnee au form
        const updater = document.getElementById('PartUpdate');
        updater.setAttribute('data-event-id', item.getAttribute('data-event-id'));
        //Don des valeurs par defaut
        name.setAttribute('value', item.getAttribute('data-event-name'));
        classe.setAttribute('value', item.getAttribute('data-event-classe'));
        img.setAttribute('src', item.getAttribute('data-event-img'));

        let ID = document.getElementById('IDpart');
        ID.setAttribute('value', item.getAttribute('data-event-id'));
    }
}


//Gestion dynamique des actions execute
function actionEvent(item){
    const itemType = item.getAttribute("data-type");
    const itemAction = item.getAttribute("data-action");
    if(itemAction === "delete"){
        deleteEvents(item,itemType);
    }else if(itemAction === "update"){
        ModifyEvent(item,itemType);
    }else  if (itemAction === "participants"){
        addParticipants(eventID=item.getAttribute("data-event-id"));
    }
}

function getCandidature(item){
    console.log("J'affiche les candidatures");
    const conteneur = document.getElementById('list-candidats')
    const catID = item.getAttribute('data-event-id');
    conteneur.innerHTML="";
    fetch(`AdminAPI.php?candEvent=${catID}`)
        .then(response => response.json())
        .then(data => {
            if(data.length === 0){
            conteneur.innerHTML = `<tr><td colspan='5' class='text-center'>Aucun participant trouvé.</td></tr>`;
        }
        })

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
                    <td><img src="${event.img}" alt="Image du ${event.nom_cat}" class="img-thumbnail table-thumbnail" style="width: 60px;height: 60px;object-fit: contain;border-radius: 10%; border-color: gray;" /></td>
                    <td class="align-middle" data-bs-toggle="tooltip" data-bs-placement="top" title="${event.nom_cat}"><div class="text-truncate text-center" style="max-width: 300px;">${event.nom_cat}</div></td>
                    <td class="align-middle text-center" data-bs-toggle="tooltip" data-bs-placement="top" title="${event.des_cat}"><div class="text-truncate text-center" style="max-width: 300px;">${event.des_cat}</div></td>
                    <td><span class="${badgeClass} align-middle mt-4">${statusText}</span></td>
                    <td>
                        <button class="btn btn-sm btn-danger delete-event-btn align-middle" data-event-id="${event.id}" data-event-name="${event.nom_cat}" data-bs-toggle="modal" data-type="categorie" data-action="delete" data-bs-target="#delete-notify" onclick=actionEvent(this)>Supprimer</button>
                        <button class="btn btn-sm btn-primary update-event-btn align-middle" data-event-state="${event.isActive}" data-event-id="${event.id}" data-event-name="${event.nom_cat}" data-event-img="${event.img}" data-event-des="${event.des_cat}" data-type="categorie" data-bs-toggle="modal" data-bs-target="#CatUpdater" data-action="update" onclick=actionEvent(this)>Modifier</button>
                        <button class="btn btn-sm btn-success manage-participants-btn align-middle" data-event-id="${event.id}" data-type="categorie" data-action="participants" data-bs-toggle="modal" data-bs-target="#candidature" onclick=getCandidature(this) >Participants</button>
                    </td>
                </tr>
                `;
                initialiserTooltips();
            });
        }
    })
    .catch(error => { console.error("Erreur lors de la récupération des événements:", error); });
}