/* Variables */

const BURGERBTN = document.querySelector('#burger_btn');
const SEARCHBTN = document.querySelector('#search_btn');
let openBtn = document.querySelector('#btn-open');
let closeBtn = document.querySelector('#btn-close');
let menuToggle = document.querySelector('#menu_toggle');
let formToggle = document.querySelector('#form_toggle');
let actionList = document.querySelectorAll('.file-action__list');
let actionListBtn = document.querySelectorAll('.file-action__btn');
let loginEmail = document.querySelector('.login__form > fieldset > label > #email');
let loginPassword = document.querySelector('.login__form > fieldset > label > span > #password');
let profilEmail = document.querySelector('.profil__form > .profil-container > #email');
let connectBtn = document.querySelector('#connect_btn');
let showHidePassword = document.querySelector('.label-icon-container');
let showIconPassword = document.querySelector('#icon-show');
let hideIconPassword = document.querySelector('#icon-hide');
let AccountTableBody = document.querySelector('#table-body');
let uploadSection = document.querySelector('.upload');
let uploadWrapper = document.querySelector('.upload-wrapper');
let uploadInput = document.querySelector('.upload__input');
let uploadForm = document.querySelector('.upload__form');
let textUploadList = document.querySelector('.upload__list');
let uploadSubmit = document.querySelector('.upload__submit');
let inputPswProfile = document.querySelector('#password_new');
let checkboxPswProfile = document.querySelector('#password_checkbox');
let checkboxPswAccount = document.querySelectorAll('.account-checkbox');
let inputPswAccount = document.querySelectorAll('.account__input--password');
let accountEmail = document.querySelectorAll('[id^="userMail_"]');
let accountPassword = document.querySelectorAll('[id^="userMdp_"]');
let accountFirstName = document.querySelectorAll('[id^="userFirstName_"]');
let accountLastName = document.querySelectorAll('[id^="userLastName_"]');
let accountSubmit = document.querySelector('#comptes--submit');
let folderAddBtn = document.querySelector('#add-folder-btn');
let folderAddForm = document.querySelector('.add-folder__form');
let folderAddInput = document.querySelector('#folderAddInput');

let fileSection = document.querySelector('.files');
let fileBtnRename = document.querySelectorAll('.link-rename');
let filesBtnRename = document.querySelectorAll('.link-rename-file');
let fileTableBtnMove = document.querySelectorAll('.file-move-js');
let folderBtnRename = document.querySelectorAll('.link-rename-folder');
let folderListItem = document.querySelectorAll('.folder-action__list');
let filesListItem = document.querySelectorAll('.files-action__list');

let folderPath = document.querySelectorAll('[data-path-folder]');
let filePath = document.querySelectorAll('[data-path-file]');


let titleUploadList = document.createElement('p');
titleUploadList.textContent = "Liste des fichiers en attente :";

/* Fonctions */

/**
 * Change l'icone du menu burger + Affiche / cache le menu
 */
function toggleMenu() {
    openBtn.classList.toggle('toggle-display');
    closeBtn.classList.toggle('toggle-display');
    menuToggle.classList.toggle('display-none-menu');
};

/**
 * Affiche / cache l'input pour la recherche
 */
function toggleSearch() {
    formToggle.classList.toggle('display-none-search');
};

/**
 * Affiche / cache le menu d'un fichier/dossier (tableau)
 * @param {HTMLElement} target
 */
function toggleActionMenu(target) {
    target.classList.toggle('display-none-action-list');
};

/**
 * Change la couleur de fond de l'élement en attribut
 * @param {HTMLElement} target
 */
function toggleSelectedBgc(target) {
    target.classList.toggle('selected-row');
}

/**
 * Crée des IDs pour chaque ".file-action__btn"
 * Crée les eventListeners pour chaque ".btn-action_$"
 * Le processus est automatisé car les boutons seront générés par PHP (un bouton pour chaque fichier/dossier),
 * on ne connait donc pas le nombre de boutons à l'avance.
 * PHP génère HTML puis JS ajoute les IDs et les EventListeners
 */
function handleActionMenu() {
    //Cette boucle permet d'ajouter la classe "display-none-action-list"
    // a chaque menu attaché a un ul dans le tableau des fichiers
    //(version desktop)
    for (i = 0; i < actionListBtn.length; i++) {
        actionList[i].classList.add('display-none-action-list');
        actionListBtn[i].id = 'btn-action_' + i;
        actionListBtn[i].parentNode.parentNode.id = 'files__row_' + i;
    }

    // Dans les querySelectors qui suivent, on utilise [id^='file-action-btn_']
    // afin sélectionner toutes les ID qui commencent par 'file-action-btn_' ...
    let actionListBtnId = document.querySelectorAll('[id^="btn-action_"]');
    let filesRow = document.querySelectorAll('[id^="files__row_"]');


    actionListBtnId.forEach(element => {
        element.addEventListener('click', e => {
            // vérifie si le menu est caché.
            if (element.nextElementSibling.classList.contains('display-none-action-list')) {
                // si il est caché alors : cache tout les menus
                for (let j = 0; j < actionListBtnId.length; j++) {
                    if (actionList[j] !== element) {
                        actionList[j].classList.add('display-none-action-list');
                        filesRow[j].classList.remove('selected-row');
                    }
                }
                // affiche le menu cliqué
                toggleActionMenu(element.nextElementSibling);
                toggleSelectedBgc(element.parentNode.parentNode);
            } else {
                // si le menu n'est pas caché alors :
                // cache le menu
                toggleActionMenu(element.nextElementSibling);
                toggleSelectedBgc(element.parentNode.parentNode);
            }
        });
    })
    window.addEventListener('click', (e) => {
        if (!e.target.classList.contains('file-action__btn-js')) {
            for (let i = 0; i < actionListBtn.length; i++) {
                actionList[i].classList.add('display-none-action-list');
                filesRow[i].classList.remove('selected-row');
            };
        };
    });

    // Modal déplacer un fichier

};


/**
 * Ajoute un <tr> avec les élements correspondant a ceux nécessaires
 * pour l'ajout d'un compte (page comptes v- admin)
 * (Utilisation de cloneNode possible a voir)
 */
function addAccountInputs() {
    let accoundId = document.querySelectorAll('.compte__id');
    let accountRow = document.querySelectorAll('.comptes__row');
    let lastRow = accountRow[accountRow.length - 1];
    let lastId = accoundId[accoundId.length - 1];
    lastId = lastId.textContent;
    lastId = parseInt(lastId);

    // 1) CREATION DES ELEMENTS
    let tableRow = document.createElement('tr');
    // 1.1) <td> id
    let tableCellId = document.createElement('td');
    let spanLabel = document.createElement('span');
    let spanId = document.createElement('span');
    // 1.2) <td> input nom
    let tableCellName = document.createElement('td');
    let labelName = document.createElement('label');
    let inputName = document.createElement('input');
    // 1.3) <td> input prenom
    let tableCellFirstName = document.createElement('td');
    let labelFirstName = document.createElement('label');
    let inputFirstName = document.createElement('input');
    // 1.4) <td> input mail
    let tableCellMail = document.createElement('td');
    let labelMail = document.createElement('label');
    let inputMail = document.createElement('input');
    // 1.5) <td> input mot de passe + checkbox
    let tableCellPsw = document.createElement('td');
    let wrapperPsw = document.createElement('div');
    let containerPsw = document.createElement('div');
    let labelPsw = document.createElement('label');
    let inputPsw = document.createElement('input');
    let containerPswCheckbox = document.createElement('div');
    let labelPswCheckbox = document.createElement('label');
    let inputPswCheckbox = document.createElement('input');
    // 1.6) <td> select role + lien supprimer
    let tableCellRole = document.createElement('td');
    let labelRole = document.createElement('label');
    let selectRole = document.createElement('select');
    let optionRoleUser = document.createElement('option');
    let optionRoleAdmin = document.createElement('option');
    let linkDeleteRole = document.createElement('button');


    // AFFECTATION DES CLASSES AUX ELEMENTS CREES
    // // 1)
    tableRow.classList.add('comptes__row');
    tableRow.classList.add('compte');
    tableRow.classList.add('compte-new');

    // // 1.1)
    tableCellId.classList.add('comptes__cell');
    spanLabel.classList.add('compte__label');
    spanId.classList.add('compte__id');

    // // 1.2)
    tableCellName.classList.add('comptes__cell');
    let labelForLastName = "userLastName_" + (lastId + 1);
    let inputAtrLastName = "user_new[" + (lastId + 1) + "][user_surname]";
    labelName.htmlFor = labelForLastName;
    labelName.classList.add('compte__label');
    inputName.setAttribute('type', 'text');
    inputName.classList.add('compte__input');
    inputName.classList.add('input-error');
    inputName.addEventListener('input', e => {
        if (inputName.value.length == 0) {
            inputName.classList.add('input-error');
        } else {
            inputName.classList.remove('input-error');
        }
    })
    inputName.setAttribute('name', inputAtrLastName);
    inputName.id = labelForLastName;
    inputName.setAttribute('required', "");
    inputName.setAttribute('maxlength', "100");

    // // 1.3)
    tableCellFirstName.classList.add('comptes__cell');
    let labelForFirstName = "userFirstName_" + (lastId + 1);
    let inputAtrFirstName = "user_new[" + (lastId + 1) + "][user_name]";
    labelFirstName.htmlFor = labelForFirstName;
    labelFirstName.classList.add('compte__label');
    inputFirstName.setAttribute('type', 'text');
    inputFirstName.classList.add('compte__input');
    inputFirstName.classList.add('input-error');
    inputFirstName.addEventListener('input', e => {
        if (inputFirstName.value.length == 0) {
            inputFirstName.classList.add('input-error');
        } else {
            inputFirstName.classList.remove('input-error');
        }
    })
    inputFirstName.setAttribute('name', inputAtrFirstName);
    inputFirstName.id = labelForFirstName;
    inputFirstName.setAttribute('required', "");
    inputFirstName.setAttribute('maxlength', "100");

    // // 1.4)
    tableCellMail.classList.add('comptes__cell');
    tableCellMail.classList.add('comptes__cell--mail');
    let labelForMail = "userMail_" + (lastId + 1);
    let inputAtrMail = "user_new[" + (lastId + 1) + "][email]";
    labelMail.htmlFor = labelForMail;
    labelMail.classList.add('compte__label');
    inputMail.setAttribute('type', 'email');
    inputMail.classList.add('compte__input');
    inputMail.classList.add('input-error');
    inputMail.setAttribute('name', inputAtrMail);
    inputMail.setAttribute('pattern', "^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,5})$");
    inputMail.setAttribute('title', 'exemple: adresse@mail.com');
    inputMail.id = labelForMail;
    inputMail.setAttribute('required', "");
    inputMail.setAttribute('maxlength', "145");


    // // 1.5)
    tableCellPsw.classList.add('comptes__cell');
    tableCellPsw.classList.add('comptes__cell--mdp');
    wrapperPsw.classList.add('wrapper');
    containerPsw.classList.add('container');
    containerPswCheckbox.classList.add('container');
    let labelForPsw = "userMdp_" + (lastId + 1);
    let labelCheckboxForPsw = "userMdpCheckbox_" + (lastId + 1);
    let inputAtrPsw = "user_new[" + (lastId + 1) + "][password]";
    let checkboxAtrPsw = "user_new[" + (lastId + 1) + "][userMdpCheckbox]";
    labelPsw.htmlFor = labelForPsw;
    labelPsw.classList.add('compte__label');
    labelPswCheckbox.htmlFor = labelCheckboxForPsw;
    labelPswCheckbox.classList.add('compte__label');
    inputPsw.setAttribute('type', 'text');
    inputPsw.classList.add('compte__input');
    inputPsw.classList.add('account__input--password');
    inputPsw.setAttribute('name', inputAtrPsw);
    inputPsw.setAttribute('disabled', '');
    inputPsw.setAttribute('pattern', "(?=^.{8,16}$)((?=.*\\d)(?=.*\\w+))(?![.\\n])(?=.*[A-Z])(?=.*[a-z])(?=.*[$&+,:;=?@#_*-]).*$");
    inputPsw.setAttribute('title', '1 maj, 1 min, 1 chiffre, 1 caractère spécial');
    inputPsw.id = labelForPsw;
    inputPswCheckbox.setAttribute('type', 'checkbox');
    inputPswCheckbox.classList.add('profil__input--checkbox');
    inputPswCheckbox.classList.add('account-checkbox');
    inputPswCheckbox.setAttribute('name', checkboxAtrPsw);
    inputPswCheckbox.id = labelCheckboxForPsw;
    inputPsw.setAttribute('maxlength', "16");


    // // 1.6)
    tableCellRole.classList.add('comptes__cell');
    tableCellRole.classList.add('compte__cell--role');
    let labelForRole = "userRole_" + (lastId + 1);
    let selectAtrRole = "user_new[" + (lastId + 1) + "][role]";
    labelRole.htmlFor = labelForRole;
    labelRole.classList.add('compte__label');
    selectRole.setAttribute('type', 'checkbox');
    selectRole.classList.add('compte__input');
    selectRole.classList.add('compte__input--role');
    selectRole.setAttribute('name', selectAtrRole);
    selectRole.id = labelForRole;
    optionRoleUser.setAttribute('value', 'user');
    optionRoleAdmin.setAttribute('value', 'admin');
    linkDeleteRole.setAttribute('type', 'button');
    linkDeleteRole.classList.add('btn--delete');
    linkDeleteRole.classList.add('compte__link');
    linkDeleteRole.classList.add('compte-new-delete');
    linkDeleteRole.addEventListener('click', e => {
        tableRow.remove();
    })


    // INSERTION DES ELEMENTS CREES
    lastRow.parentNode.insertBefore(tableRow, lastRow.nextSibling);
    // (mise a jour de la NodeList accountRow)
    accountRow = document.querySelectorAll('.comptes__row');
    lastRow = accountRow[accountRow.length - 1];

    // ajout 1.1)
    lastRow.appendChild(tableCellId);
    tableCellId.append(spanLabel, spanId);
    spanLabel.textContent = "id";
    spanId.textContent = lastId + 1;

    lastRow.parentNode.insertBefore(tableRow, lastRow.nextSibling);
    accountRow = document.querySelectorAll('.comptes__row');
    lastRow = accountRow[accountRow.length - 1];

    // ajout 1.2)
    lastRow.appendChild(tableCellName);
    tableCellName.append(labelName, inputName);
    labelName.textContent = "Nom";
    inputName.placeholder = "Nom";

    // ajout 1.3)
    lastRow.appendChild(tableCellFirstName);
    tableCellFirstName.append(labelFirstName, inputFirstName);
    labelFirstName.textContent = "Prenom";
    inputFirstName.placeholder = "Prenom";

    // ajout 1.4)
    lastRow.appendChild(tableCellMail);
    tableCellMail.append(labelMail, inputMail);
    labelMail.textContent = "Mail";
    inputMail.placeholder = "mail@exemple.com";

    // ajout 1.5)
    lastRow.appendChild(tableCellPsw);
    tableCellPsw.appendChild(wrapperPsw);
    wrapperPsw.append(containerPsw, containerPswCheckbox);
    containerPsw.append(labelPsw, inputPsw);
    containerPswCheckbox.append(labelPswCheckbox, inputPswCheckbox);
    labelPswCheckbox.textContent = "Changer mdp";
    inputPswCheckbox.addEventListener('change', e => {
        inputPsw.toggleAttribute('disabled');
        inputPsw.value = '';
    })

    // ajout 1.6)
    lastRow.appendChild(tableCellRole);
    tableCellRole.append(labelRole, selectRole, linkDeleteRole);
    selectRole.append(optionRoleUser, optionRoleAdmin)
    labelRole.textContent = "Role";
    optionRoleUser.textContent = "User";
    optionRoleAdmin.textContent = "Admin";
    linkDeleteRole.textContent = "Supprimer";
}


/* Manipulation du DOM */
if (actionList) {
    handleActionMenu();
}

if (AccountTableBody) {

    // Ajout du style error si input vide
    //debut
    accountFirstName.forEach(element => {
        element.addEventListener('input', e => {
            if (element.value.length == 0) {
                element.classList.add('input-error');
            } else {
                element.classList.remove('input-error');
            }
        })
    });
    accountLastName.forEach(element => {
        element.addEventListener('input', e => {
            if (element.value.length == 0) {
                element.classList.add('input-error');
            } else {
                element.classList.remove('input-error');
            }
        })
    });
    accountEmail.forEach(element => {
        element.addEventListener('input', e => {
            if (element.value.length == 0) {
                element.classList.add('input-error');
            } else {
                element.classList.remove('input-error');
            }
        })
    });
    //fin



    /* Vérification REGEX pour les inputs DEJA EXISTANT (générés par PHP) de la page Comptes */

    // On crée deux tableaux. Ces tableaux servent a stocker le résultat de chaque input (erreur ou ok)
    let errorEmailExist = [];
    let errorEmailNew = [];
    let errorFirstNameExist = [];
    let errorFirstNameNew = [];
    let errorLastNameExist = [];
    let errorLastNameNew = [];
    let errorPasswordExist = [];
    let errorPasswordNew = [];

    let errors = [errorEmailExist, errorEmailNew, errorFirstNameExist, errorFirstNameNew, errorLastNameExist, errorLastNameNew, errorPasswordExist, errorPasswordNew];

    /* Boucle qui ajoute un eventListener pour chaque input mail (verif regex), et qui stock le résultat (erreur ou ok) dans différents arrays */
    for (let k = 0; k < accountEmail.length; k++) {
        // Protège index [0] (car index 0 = admin), sinon index[0] = empty
        if (k == 0) {
            errorEmailExist[k] = 'ok';
            errorFirstNameExist[k] = 'ok';
            errorLastNameExist[k] = 'ok';
        }

        accountEmail[k].addEventListener('input', e => {
            if (accountEmail[k].value.match(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,5})$/) && accountEmail[k].value.length <= 50) {
                // Si email valide, on stock 'ok' dans le tableau et on enlève la classe 'input-error'
                errorEmailExist[k] = 'ok';
                accountEmail[k].classList.remove('input-error');
            } else {
                // Si email invalide, on stock 'error' dans le tableau et on ajoute la classe 'input-error'
                errorEmailExist[k] = 'error';
                accountEmail[k].classList.add('input-error');
            }

            // Vérification des tableaux d'erreur, si il y a un 'error', désactive le bouton submit
            if (errorPasswordExist.includes('error') || errorPasswordNew.includes('error') || errorEmailExist.includes('error') || errorEmailNew.includes('error') || errorFirstNameExist.includes('error') || errorFirstNameNew.includes('error') || errorLastNameExist.includes('error') || errorLastNameNew.includes('error')) {
                accountSubmit.disabled = true;
            } else {
                accountSubmit.disabled = false;
            }
        });


        // Même boucle que celle précedente, ici pour input password
        checkboxPswAccount[k].addEventListener('change', e => {
            accountPassword[k].value = '';
            if (accountPassword[k].value.length == 0) {
                errorPasswordExist[k] = 'ok';
            }

            if (errorPasswordExist.includes('error') || errorPasswordNew.includes('error') || errorEmailExist.includes('error') || errorEmailNew.includes('error') || errorFirstNameExist.includes('error') || errorFirstNameNew.includes('error') || errorLastNameExist.includes('error') || errorLastNameNew.includes('error')) {
                accountSubmit.disabled = true;
            } else {
                accountPassword[k].classList.remove('input-error');
                accountSubmit.disabled = false;
            }
        })

        accountPassword[k].addEventListener('input', e => {
            if (accountPassword[k].value.match(/(?=^.{8,16}$)((?=.*\d)(?=.*\w+))(?![.\n])(?=.*[A-Z])(?=.*[a-z])(?=.*[$&+,:;=?@#_*-]).*$/) && accountPassword[k].value.length <= 16) {
                errorPasswordExist[k] = 'ok';
                accountPassword[k].classList.remove('input-error');
            } else {
                errorPasswordExist[k] = 'error';
                accountPassword[k].classList.add('input-error');
            }
            if (accountPassword[k].value.length == 0) {
                errorPasswordExist[k] = 'ok';
            }

            if (errorPasswordExist.includes('error') || errorPasswordNew.includes('error') || errorEmailExist.includes('error') || errorEmailNew.includes('error') || errorFirstNameExist.includes('error') || errorFirstNameNew.includes('error') || errorLastNameExist.includes('error') || errorLastNameNew.includes('error')) {
                accountSubmit.disabled = true;
            } else {
                accountSubmit.disabled = false;
            }
        });


        // Vérification inputs accountFirstName
        if (accountFirstName[k].value.length == 0) {
            errorFirstNameExist[k] = 'error';
        } else {
            errorFirstNameExist[k] = 'ok';
        }
        accountFirstName[k].addEventListener('input', e => {
            // Si input vide, on ajoute 'error' dans le tableau errorFirstNameExist[k], sinon 'ok'
            if (accountFirstName[k].value.length == 0) {
                errorFirstNameExist[k] = 'error';
            } else {
                errorFirstNameExist[k] = 'ok';
            }
            // Si il existe une erreur dans un des tableaux de vérification, désactive le bouton submit
            if (errorPasswordExist.includes('error') || errorPasswordNew.includes('error') || errorEmailExist.includes('error') || errorEmailNew.includes('error') || errorFirstNameExist.includes('error') || errorFirstNameNew.includes('error') || errorLastNameExist.includes('error') || errorLastNameNew.includes('error')) {
                accountSubmit.disabled = true;
            } else {
                accountSubmit.disabled = false;
            }
        })

        // Vérification inputs accountLastName, même principe que précédemment
        if (accountLastName[k].value.length == 0) {
            errorLastNameExist[k] = 'error';
        } else {
            errorLastNameExist[k] = 'ok';
        }
        accountLastName[k].addEventListener('input', e => {
            // Si input vide, on ajoute 'error' dans le tableau errorLastNameExist[k], sinon 'ok'
            if (accountLastName[k].value.length == 0) {
                errorLastNameExist[k] = 'error';
            } else {
                errorLastNameExist[k] = 'ok';
            }
            // Si il existe une erreur dans un des tableaux de vérification, désactive le bouton submit
            if (errorPasswordExist.includes('error') || errorPasswordNew.includes('error') || errorEmailExist.includes('error') || errorEmailNew.includes('error') || errorFirstNameExist.includes('error') || errorFirstNameNew.includes('error') || errorLastNameExist.includes('error') || errorLastNameNew.includes('error')) {
                accountSubmit.disabled = true;
            } else {
                accountSubmit.disabled = false;
            }
        })

    };
    /* FIN VERIFICATION */



    /* Vérification REGEX pour les NOUVEAUX inputs (générés par JS avec le bouton "Ajouter un compte") de la page Comptes */

    // Bouton "ajouter un compte"
    let addAccountRow = document.querySelector('#comptes--add');

    addAccountRow.addEventListener('click', e => {

        // Ajouter un <tr> avec tout les inputs lorsque l'on clique sur "Ajouter un Compte" et désactive bouton "Sauvegarder"
        addAccountInputs();
        accountSubmit.disabled = true;
        let compteNewDeleteBtn = document.querySelectorAll('.compte-new-delete');
        compteNewDeleteBtn.forEach(element => {
            element.addEventListener('click', e => {
                let compteNewRow = document.querySelectorAll('.compte-new');

                errorEmailNew.pop();
                errorLastNameNew.pop();
                errorFirstNameNew.pop();

                if (compteNewRow.length == 0) {
                    if (errorPasswordExist.includes('error') || errorPasswordNew.includes('error') || errorEmailExist.includes('error') || errorEmailNew.includes('error') || errorFirstNameExist.includes('error') || errorFirstNameNew.includes('error') || errorLastNameExist.includes('error') || errorLastNameNew.includes('error')) {
                        accountSubmit.disabled = true;
                    } else {
                        accountSubmit.disabled = false;
                    }
                }
                compteNewRow.forEach(element => {
                    if (errorPasswordExist.includes('error') || errorPasswordNew.includes('error') || errorEmailExist.includes('error') || errorEmailNew.includes('error') || errorFirstNameExist.includes('error') || errorFirstNameNew.includes('error') || errorLastNameExist.includes('error') || errorLastNameNew.includes('error')) {
                        accountSubmit.disabled = true;
                    } else {
                        accountSubmit.disabled = false;
                    }
                });
                console.log(errorEmailNew);
            })
        })

        accountFirstName = document.querySelectorAll('[id^="userFirstName_"]');


        // Mise a jour des variables accountEmail et accountPassword (car on a rajouté une ligne)
        accountEmail = document.querySelectorAll('[id^="userMail_"]');
        accountPassword = document.querySelectorAll('[id^="userMdp_"]');


        /* Boucle qui ajoute un eventListener pour chaque input mail (verif regex), et qui stock le résultat (erreur ou ok) dans un tableau d'erreur */
        for (let k = 0; k < accountEmail.length; k++) {
            // VOIR ICI ICI ICI ICI
            if (k == 0) {
                errorFirstNameNew[k] = 'ok';
                errorEmailNew[k] = 'ok';
                errorLastNameNew[k] = 'ok';
            }
            if (accountFirstName[k].value.length == 0) {
                errorFirstNameNew[k] = 'error';
            } else {
                errorFirstNameNew[k] = 'ok';
            }

            accountFirstName[k].addEventListener('input', e => {
                if (accountFirstName[k].value.length == 0) {
                    errorFirstNameNew[k] = 'error';
                } else {
                    errorFirstNameNew[k] = 'ok';
                }
                if (errorPasswordExist.includes('error') || errorPasswordNew.includes('error') || errorEmailExist.includes('error') || errorEmailNew.includes('error') || errorFirstNameExist.includes('error') || errorFirstNameNew.includes('error') || errorLastNameExist.includes('error') || errorLastNameNew.includes('error')) {
                    accountSubmit.disabled = true;
                } else {
                    accountSubmit.disabled = false;
                }
            })


            accountLastName = document.querySelectorAll('[id^="userLastName_"]');

            if (accountLastName[k].value.length == 0) {
                errorLastNameNew[k] = 'error';
            } else {
                errorLastNameNew[k] = 'ok';
            }
            accountLastName[k].addEventListener('input', e => {
                if (accountLastName[k].value.length == 0) {
                    errorLastNameNew[k] = 'error';
                } else {
                    errorLastNameNew[k] = 'ok';
                }
                if (errorPasswordExist.includes('error') || errorPasswordNew.includes('error') || errorEmailExist.includes('error') || errorEmailNew.includes('error') || errorFirstNameExist.includes('error') || errorFirstNameNew.includes('error') || errorLastNameExist.includes('error') || errorLastNameNew.includes('error')) {
                    accountSubmit.disabled = true;
                } else {
                    accountSubmit.disabled = false;
                }
            });


            if (accountEmail[k].value.length == 0) {
                errorEmailNew[k] = 'error';
            } else {
                errorEmailNew[k] = 'ok';
            }
            accountEmail[k].addEventListener('input', e => {
                if (accountEmail[k].value.match(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,5})$/) && accountEmail[k].value.length <= 50) {
                    console.log('REGEX');
                    // Si email valide, on stock 'ok' dans le tableau et on enlève la classe 'input-error'
                    errorEmailNew[k] = 'ok';







                    accountEmail[k].classList.remove('input-error');
                } else {
                    // Si email invalide, on stock 'error' dans le tableau et on ajoute la classe 'input-error'
                    errorEmailNew[k] = 'error';
                    accountEmail[k].classList.add('input-error');

                }

                // Ajoute classe input-error si il n'y a pas d'adresse mail
                if (accountEmail[k].value.length == 0) {
                    errorEmailNew[k] = 'error';
                    accountEmail[k].classList.add('input-error');
                }

                // Vérification des tableaux d'erreur, si il y a un 'error', désactive le bouton submit
                if (errorPasswordExist.includes('error') || errorPasswordNew.includes('error') || errorEmailExist.includes('error') || errorEmailNew.includes('error') || errorFirstNameExist.includes('error') || errorFirstNameNew.includes('error') || errorLastNameExist.includes('error') || errorLastNameNew.includes('error')) {
                    accountSubmit.disabled = true;
                } else {
                    accountSubmit.disabled = false;
                }
                console.log(errorEmailNew);
            });


            // Même boucle que celle précedente, ici pour input password
            checkboxPswAccount = document.querySelectorAll('.account-checkbox');
            checkboxPswAccount[k].addEventListener('change', e => {
                accountPassword[k].value = '';
                if (accountPassword[k].value.length == 0) {
                    errorPasswordNew[k] = 'ok';
                }

                if (errorPasswordExist.includes('error') || errorPasswordNew.includes('error') || errorEmailExist.includes('error') || errorEmailNew.includes('error') || errorFirstNameExist.includes('error') || errorFirstNameNew.includes('error') || errorLastNameExist.includes('error') || errorLastNameNew.includes('error')) {
                    accountSubmit.disabled = true;
                } else {
                    accountPassword[k].classList.remove('input-error');
                    accountSubmit.disabled = false;
                }
            })

            accountPassword[k].addEventListener('input', e => {
                if (accountPassword[k].value.match(/(?=^.{8,16}$)((?=.*\d)(?=.*\w+))(?![.\n])(?=.*[A-Z])(?=.*[a-z])(?=.*[$&+,:;=?@#_*-]).*$/) && accountPassword[k].value.length <= 16) {
                    errorPasswordNew[k] = 'ok';
                    accountPassword[k].classList.remove('input-error');
                } else {
                    errorPasswordNew[k] = 'error';
                    accountPassword[k].classList.add('input-error');
                }

                if (accountPassword[k].value.length == 0) {
                    accountPassword[k].classList.remove('input-error');
                }

                if (errorPasswordExist.includes('error') || errorPasswordNew.includes('error') || errorEmailExist.includes('error') || errorEmailNew.includes('error') || errorFirstNameExist.includes('error') || errorFirstNameNew.includes('error') || errorLastNameExist.includes('error') || errorLastNameNew.includes('error')) {
                    accountSubmit.disabled = true;
                } else {
                    accountSubmit.disabled = false;
                }
            });
        }
    });
}


/* Event Listeners */
if (BURGERBTN) {
    BURGERBTN.addEventListener('click', (e) => {
        toggleMenu()
    });
}
if (SEARCHBTN) {
    SEARCHBTN.addEventListener('click', (e) => {
        toggleSearch();
    });
}

/* Vérifications Regex */
// Vérification regex mail (page connexion)
if (loginEmail) {
    loginEmail.addEventListener('input', e => {
        if (loginEmail.value.match(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,5})$/) && loginEmail.value.length <= 50) {
            connectBtn.removeAttribute('disabled');
        } else {
            connectBtn.setAttribute('disabled', '');
        }
    })
}

// Vérification regex mail (page connexion)
if (loginPassword) {
    loginPassword.addEventListener('input', e => {
        if (loginPassword.value.match(/(?=^.{8,16}$)((?=.*\d)(?=.*\w+))(?![.\n])(?=.*[A-Z])(?=.*[a-z])(?=.*[$&+,:;=?@#_*-]).*$/) && loginPassword.value.length <= 16) {
            connectBtn.removeAttribute('disabled');
        } else {
            connectBtn.setAttribute('disabled', '');
        }
    })
    showHidePassword.addEventListener('click', e => {
        if (loginPassword.getAttribute('type') == 'password') {
            showIconPassword.classList.add('toggle-display');
            hideIconPassword.classList.remove('toggle-display');
            loginPassword.setAttribute('type', 'text');
        }
        else {
            showIconPassword.classList.remove('toggle-display');
            hideIconPassword.classList.add('toggle-display');
            loginPassword.setAttribute('type', 'password');
        }
    })
}

if (profilEmail) {
    let profilPsw = document.querySelector('.profil__input--password');
    let profilBtn = document.querySelector('.profil__btn');
    let profilFirstName = document.querySelector('#prenom');
    let profilLastName = document.querySelector('#nom');
    let error = [];

    profilFirstName.addEventListener('input', e => {
        if (profilFirstName.value.length == 0) {
            error[0] = 'error';
            profilFirstName.classList.add('input-error');
        } else {
            error[0] = 'ok';
            profilFirstName.classList.remove('input-error');
        }
        if (error.includes('error') || error.includes('error')) {
            profilBtn.disabled = true;
        } else {
            profilBtn.disabled = false;
        }
    })
    profilLastName.addEventListener('input', e => {
        if (profilLastName.value.length == 0) {
            error[1] = 'error';
            profilLastName.classList.add('input-error');
        } else {
            error[1] = 'ok';
            profilLastName.classList.remove('input-error');
        }
        if (error.includes('error') || error.includes('error')) {
            profilBtn.disabled = true;
        } else {
            profilBtn.disabled = false;
        }
    })


    /* Vérifications Regex pour input Mail & Password dans la page Profil */

    profilEmail.addEventListener('input', e => {
        if (profilEmail.value.match(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,5})$/) && profilEmail.value.length <= 50) {
            // Si mail valide, active le bouton "Modifier" et enlève la classe input-error
            error[2] = 'ok';
            profilBtn.disabled = false;
            profilEmail.classList.remove('input-error');
        } else {
            // Si mail invalide, désactive le bouton "Modifier" et ajoute la classe input-error
            error[2] = 'error';
            profilBtn.disabled = true;
            profilEmail.classList.add('input-error');
        }
        if (error.includes('error') || error.includes('error')) {
            profilBtn.disabled = true;
        } else {
            profilBtn.disabled = false;
        }
    })

    profilPsw.addEventListener('input', e => {
        if (profilPsw.value.match(/(?=^.{8,16}$)((?=.*\d)(?=.*\w+))(?![.\n])(?=.*[A-Z])(?=.*[a-z])(?=.*[$&+,:;=?@#_*-]).*$/) && profilPsw.value.length <= 16) {
            // Si psw valide, active le bouton "Modifier" et enlève la classe input-error
            error[3] = 'ok';
            profilBtn.disabled = false;
            profilPsw.classList.remove('input-error');
        } else {
            // Si psw invalide, désactive le bouton "Modifier" et ajoute la classe input-error
            error[3] = 'error';
            profilBtn.disabled = true;
            profilPsw.classList.add('input-error');
        }
        if (error.includes('error') || error.includes('error')) {
            profilBtn.disabled = true;
        } else {
            profilBtn.disabled = false;
        }
    })

    // Vérification de tout le formulaire, si une erreur on désactive le bouton

}

if (textUploadList) {
    uploadForm.insertBefore(textUploadList, uploadSubmit);

    uploadInput.addEventListener('change', e => {
        let filesArray = Array.from(uploadInput.files);
        uploadForm.insertBefore(titleUploadList, textUploadList);
        textUploadList.innerHTML = "";
        uploadSubmit.removeAttribute('disabled');

        /**
         * Retire un élement de l'objet FileList par index
         * @param {int} index 
         */
        function removeFile(index) {
            let testF = uploadInput.files;
            const dt = new DataTransfer();
            for (j = 0; j < testF.length; j++) {
                if (index != j) {
                    dt.items.add(testF[j]);
                }
            }
            uploadInput.files = dt.files;
        }

        // crée une <li> avec un bouton pour chaque fichier dans l'input file
        for (i = 0; i < filesArray.length; i++) {
            let textUploadItem = document.createElement('li');
            textUploadItem.id = 'upload_' + i;
            let btn = document.createElement('button');
            btn.classList.add('btn--delete');
            btn.classList.add('upload__btn');
            btn.textContent = "Supprimer";
            btn.setAttribute('type', 'button');
            btn.id = i;
            textUploadItem.textContent = filesArray[i]['name'];
            textUploadList.appendChild(textUploadItem);
            textUploadItem.appendChild(btn);

            btn.addEventListener('click', e => {
                let id = parseInt(btn.id);
                // supprime le fichier dans FileList (id)
                removeFile(id);
                // supprime la ligne HTML contenant le bouton (id)
                let liDelete = document.querySelectorAll('[id^="upload_"]');
                liDelete[id].remove();
                liDelete = document.querySelectorAll('[id^="upload_"]');
                for (x = 0; x < liDelete.length; x++) {
                    liDelete[x].id = "upload_" + x;
                }
                if (liDelete.length == 0) {
                    titleUploadList.remove();
                }
            })
            btn.addEventListener('click', e => {
                // ré-organise les IDs des boutons dans HTML
                let uploadBtn = document.querySelectorAll('.upload__btn');
                for (k = 0; k < uploadBtn.length; k++) {
                    uploadBtn[k].id = k;
                }
            })

            btn.addEventListener('click', e => {
                let filesA = Array.from(uploadInput.files);
                if (filesA.length === 0) {
                    uploadSubmit.setAttribute('disabled', '');
                }
            })
        }
    })
}

if (inputPswProfile && checkboxPswProfile) {
    checkboxPswProfile.addEventListener('change', e => {
        inputPswProfile.toggleAttribute('disabled');
    })
}

if (inputPswAccount && checkboxPswAccount) {
    for (i = 0; i < checkboxPswAccount.length; i++) {
        let id = i;
        checkboxPswAccount[i].addEventListener('change', e => {
            inputPswAccount[id].toggleAttribute('disabled');
        })
    }
}

let dropDownMenu = document.querySelectorAll('ul');
let mainList = document.querySelector('.drop-down');
let folderList = document.querySelectorAll('.drop-down');
let mainItem = document.querySelectorAll('.btn-folder-js');
let foldersLi = document.querySelectorAll('.folder-js');
let folderMoveBtn = document.querySelectorAll('.folder-move-js');
let folderStatus = document.querySelectorAll('.folder-status');

if (mainList) {
    mainList.classList.remove('drop-down--none');
    let x = mainList.childNodes;

    for (i = 0; i < mainItem.length; i++) {
        let x = folderList[i + 1];
        let y = folderStatus[i];
        mainItem[i].addEventListener('click', e => {
            x.classList.toggle('drop-down--none');
            y.classList.toggle('folder-status--opened');
        })
    }

    for (i = 0; i < foldersLi.length; i++) {
        let target = foldersLi[i];
        mainItem[i].addEventListener('click', e => {
            foldersLi.forEach(element => {
                element.classList.remove('selected-folder');
            })
            target.classList.add('selected-folder');
        })
    }
}

let filesRecentName = document.querySelectorAll('.filename__text');

if (fileSection) {
    // // Variables création modal pour déplacer un Fichier récent
    let modalFileRecentContainer = document.createElement('div');
    let modalFileRecent = document.createElement('div');
    let modalFileRecentBtnClose = document.createElement('button');
    let modalFileRecentForm = document.createElement('form');
    let modalFileRecentTitle = document.createElement('p');
    let modalFileRecentLabel = document.createElement('label');
    let modalFileRecentSelect = document.createElement('select');
    let modalFileRecentId = document.createElement('input');
    let modalFileRecentBtnSubmit = document.createElement('input');

    // Ajout des attributs pour modal Fichier récent
    modalFileRecentContainer.classList.add('modal-container');
    modalFileRecent.classList.add('modal');
    modalFileRecent.classList.add('file-move');
    modalFileRecentBtnClose.setAttribute('type', 'button');
    modalFileRecentBtnClose.textContent = 'X';
    modalFileRecentBtnClose.classList.add('close-modal');
    modalFileRecentForm.setAttribute('action', '/www/cloud/app/views/files/traitement/move_file_recent.php');
    modalFileRecentForm.setAttribute('method', 'POST');
    modalFileRecentTitle.textContent = 'Déplacer un fichier';
    modalFileRecentLabel.setAttribute('for', 'destination_path');
    modalFileRecentLabel.textContent = 'Choisissez le chemin souhaité :';
    modalFileRecentSelect.setAttribute('name', 'destination_path');
    modalFileRecentSelect.setAttribute('id', 'destination_path');
    modalFileRecentId.setAttribute('type', 'hidden');

    modalFileRecentBtnSubmit.setAttribute('type', 'submit');
    modalFileRecentBtnSubmit.setAttribute('class', 'btn btn--primary');
    modalFileRecentBtnSubmit.setAttribute('value', 'Déplacer');
    modalFileRecentOptionRoot = document.createElement('option');
    modalFileRecentOptionRoot.setAttribute('value', 'racine');
    modalFileRecentOptionRoot.setAttribute('style', 'color:red');
    modalFileRecentOptionRoot.innerHTML = 'Racine';

    //Options pour Fichiers
    let optionsFileRecent = [];
    optionsFileRecent.push(modalFileRecentOptionRoot);
    folderPath.forEach(element => {
        let modalFileRecentOption = document.createElement('option');
        modalFileRecentOption.setAttribute('value', element.dataset.pathFolder);
        modalFileRecentOption.textContent = element.dataset.pathFolder;
        optionsFileRecent.push(modalFileRecentOption);
    })

    // EventListener affichage modal déplacer un fichier

    for (let i = 0; i < fileTableBtnMove.length; i++) {
        fileTableBtnMove[i].addEventListener('click', e => {
            modalFileRecentId.setAttribute('name', "file_id");
            modalFileRecentId.setAttribute('value', fileTableBtnMove[i].id.replace(/\D/g, ''));
            // modalFileRecentId.setAttribute('value', fileBtnWrapper[i].childNodes[1].attributes['data-path-file'].nodeValue);
            fileSection.insertAdjacentElement('afterend', modalFileRecentContainer);
            modalFileRecentContainer.append(modalFileRecent);
            modalFileRecent.append(modalFileRecentBtnClose, modalFileRecentForm);
            modalFileRecentTitle.textContent = 'Déplacer ' + filesRecentName[i].textContent;
            modalFileRecentForm.append(modalFileRecentId, modalFileRecentTitle, modalFileRecentLabel, modalFileRecentSelect, modalFileRecentBtnSubmit);
            optionsFileRecent.forEach(element => {
                modalFileRecentSelect.append(element);
            });
            modalFileRecentBtnClose.addEventListener('click', e => {
                modalFileRecentContainer.remove();
            });
        })
    }



    // Variables création modal pour renommer un Fichier
    let modalContainer = document.createElement('div');
    let modal = document.createElement('div');
    let modalBtnClose = document.createElement('button');
    let modalForm = document.createElement('form');
    let modalFilesForm = document.createElement('form');
    let modalTitle = document.createElement('p');
    let modalLabel = document.createElement('label');
    let modalInput = document.createElement('input');
    let modalWrapper = document.createElement('div');
    let modalBtnReset = document.createElement('input');
    let modalBtnSubmit = document.createElement('input');
    // Variables création modal pour renommer un Dossier
    let modalContainerFolder = document.createElement('div');
    let modalFolder = document.createElement('div');
    let modalBtnCloseFolder = document.createElement('button');
    let modalFormFolder = document.createElement('form');
    let modalTitleFolder = document.createElement('p');
    let modalLabelFolder = document.createElement('label');
    let modalInputFolder = document.createElement('input');
    let modalWrapperFolder = document.createElement('div');
    let modalBtnResetFolder = document.createElement('input');
    let modalBtnSubmitFolder = document.createElement('input');



    // Ajout des attributs pour modal Fichier
    modalContainer.classList.add('modal-container');
    modal.classList.add('modal');
    modalBtnClose.setAttribute('type', 'button');
    modalBtnClose.textContent = 'X';
    modalBtnClose.classList.add('close-modal');
    modalForm.setAttribute('action', '/www/cloud/app/views/files/traitement/rename.php');
    modalFilesForm.setAttribute('action', '/www/cloud/app/views/files/traitement/rename_file.php');
    modalForm.setAttribute('method', 'POST');
    modalFilesForm.setAttribute('method', 'POST');
    modalTitle.textContent = 'Renommer un fichier';
    modalLabel.setAttribute('for', 'file_rename');
    modalLabel.textContent = 'Veuillez entrer le nom souhaité :';
    modalInput.setAttribute('type', 'text');
    modalInput.setAttribute('maxlength', '255');
    modalInput.setAttribute('id', 'file_rename');
    modalWrapper.classList.add('modal-wrapper');
    modalBtnReset.setAttribute('type', 'reset');
    modalBtnReset.setAttribute('value', 'Effacer');
    modalBtnSubmit.setAttribute('type', 'submit');
    modalBtnSubmit.setAttribute('value', 'OK');

    // Ajout des attributs pour modal Dossier
    modalContainerFolder.classList.add('modal-container');
    modalFolder.classList.add('modal');
    modalBtnCloseFolder.setAttribute('type', 'button');
    modalBtnCloseFolder.textContent = 'X';
    modalBtnCloseFolder.classList.add('close-modal');
    modalFormFolder.setAttribute('action', '/www/cloud/app/views/files/traitement/rename_folder.php');
    modalFormFolder.setAttribute('method', 'POST');
    modalTitleFolder.textContent = 'Renommer un dossier';
    modalInputFolder.setAttribute('type', 'text');
    modalInputFolder.setAttribute('maxlength', '255');
    modalInputFolder.setAttribute('id', 'folder_rename');
    modalInputFolder.setAttribute('name', 'folder_rename');
    modalLabelFolder.setAttribute('for', 'file_rename');
    modalLabelFolder.textContent = 'Veuillez entrer le nom souhaité :';
    modalWrapperFolder.classList.add('modal-wrapper');
    modalBtnResetFolder.setAttribute('type', 'reset');
    modalBtnResetFolder.setAttribute('value', 'Effacer');
    modalBtnSubmitFolder.setAttribute('type', 'submit');
    modalBtnSubmitFolder.setAttribute('value', 'OK');

    // EventListener affichage modal renommer un fichier (section 'tous les fichiers')
    filesBtnRename.forEach(element => {
        element.addEventListener('click', e => {
            for (let i = 0; i < filesListItem.length; i++) {
                filesListItem[i].classList.add('display-none-action-list');
            }
            fileSection.insertAdjacentElement('afterend', modalContainer);
            modalContainer.append(modal);
            modal.append(modalBtnClose, modalFilesForm);
            modalFilesForm.append(modalTitle, modalLabel, modalInput, modalWrapper);
            modalWrapper.append(modalBtnReset, modalBtnSubmit);
            modalInput.name = element.id;

            modalBtnClose.addEventListener('click', e => {
                modalContainer.remove();
            })
        })
    })

    // EventListener affichage modal renommer un fichier
    fileBtnRename.forEach(element => {
        element.addEventListener('click', e => {
            for (let i = 0; i < folderListItem.length; i++) {
                folderListItem[i].classList.add('display-none-action-list');
            }
            fileSection.insertAdjacentElement('afterend', modalContainer);
            modalContainer.append(modal);
            modal.append(modalBtnClose, modalForm);
            modalForm.append(modalTitle, modalLabel, modalInput, modalWrapper);
            modalWrapper.append(modalBtnReset, modalBtnSubmit);
            modalInput.name = element.id.replace(/\D/g, '');

            modalBtnClose.addEventListener('click', e => {
                modalContainer.remove();
            })
        })
    })


    // EventListener affichage modal renommer un dossier
    folderBtnRename.forEach(element => {
        element.addEventListener('click', e => {
            for (let i = 0; i < folderListItem.length; i++) {
                folderListItem[i].classList.add('display-none-action-list');
            }
            fileSection.insertAdjacentElement('afterend', modalContainerFolder);
            modalContainerFolder.append(modalFolder);
            modalFolder.append(modalBtnCloseFolder, modalFormFolder);
            modalFormFolder.append(modalTitleFolder, modalLabelFolder, modalInputFolder, modalWrapperFolder);
            modalWrapperFolder.append(modalBtnResetFolder, modalBtnSubmitFolder);
            modalInputFolder.name = element.id;

            modalBtnCloseFolder.addEventListener('click', e => {
                modalContainerFolder.remove();
            })
        })
    })
}

let folderBtnWrapper = document.querySelectorAll('.folder-button-wrapper');
let fileBtnWrapper = document.querySelectorAll('.file-button-wrapper');
let folderSection = document.querySelector('.folders');
let folderListBtn = document.querySelectorAll('.folder-move-js');
let fileListBtn = document.querySelectorAll('.files-move-js');
let folderRemoveBtn = document.querySelectorAll('.folder-move-js2');
let fileMoveBtn = document.querySelectorAll('.files-move-js2');


if (fileMoveBtn) {

    fileListBtn.forEach(element => {
        element.addEventListener('click', e => {
            // vérifie si le menu est caché.
            if (element.nextElementSibling.classList.contains('display-none-action-list')) {
                // si il est caché alors : cache tout les menus
                for (let j = 0; j < fileListBtn.length; j++) {
                    if (filesListItem[j] !== element) {
                        filesListItem[j].classList.add('display-none-action-list');
                    }
                }
                // affiche le menu cliqué
                toggleActionMenu(element.nextElementSibling);
            } else {
                // si le menu n'est pas caché alors :
                // cache le menu
                toggleActionMenu(element.nextElementSibling);
            }
        });
    })

    window.addEventListener('click', (e) => {
        if (!e.target.classList.contains('files-move-js')) {
            for (let i = 0; i < filesListItem.length; i++) {
                filesListItem[i].classList.add('display-none-action-list');
            }
        };
    });

    // // Variables création modal pour déplacer un Fichier
    let modalFileContainer = document.createElement('div');
    let modalFile = document.createElement('div');
    let modalFileBtnClose = document.createElement('button');
    let modalFileForm = document.createElement('form');
    let modalFileTitle = document.createElement('p');
    let modalFileLabel = document.createElement('label');
    let modalFileSelect = document.createElement('select');
    let modalFileName = document.createElement('input');
    let modalFileBtnSubmit = document.createElement('input');

    // Ajout des attributs pour modal Fichier
    modalFileContainer.classList.add('modal-container');
    modalFile.classList.add('modal');
    modalFile.classList.add('file-move');
    modalFileBtnClose.setAttribute('type', 'button');
    modalFileBtnClose.textContent = 'X';
    modalFileBtnClose.classList.add('close-modal');
    modalFileForm.setAttribute('action', '/www/cloud/app/views/files/traitement/move_file.php');
    modalFileForm.setAttribute('method', 'POST');
    modalFileTitle.textContent = 'Déplacer un fichier';
    modalFileLabel.setAttribute('for', 'file-new-path');
    modalFileLabel.textContent = 'Choisissez le chemin souhaité :';
    modalFileSelect.setAttribute('name', 'file-new-path');
    modalFileSelect.setAttribute('id', 'file-new-path');
    modalFileName.setAttribute('type', 'hidden');
    modalFileName.setAttribute('name', 'file-path');
    modalFileBtnSubmit.setAttribute('type', 'submit');
    modalFileBtnSubmit.setAttribute('class', 'btn btn--primary');
    modalFileBtnSubmit.setAttribute('value', 'Déplacer');
    modalFileOptionRoot = document.createElement('option');
    modalFileOptionRoot.setAttribute('value', 'racine');
    modalFileOptionRoot.setAttribute('style', 'color:red');
    modalFileOptionRoot.innerHTML = 'Racine';

    // Options pour Fichiers
    let options = [];
    options.push(modalFileOptionRoot);
    folderPath.forEach(element => {
        let modalFileOption = document.createElement('option');
        modalFileOption.setAttribute('value', element.dataset.pathFolder);
        modalFileOption.textContent = element.dataset.pathFolder;
        options.push(modalFileOption);
    })


    // EventListener affichage modal déplacer un fichier
    for (let i = 0; i < filesListItem.length; i++) {
        fileMoveBtn[i].addEventListener('click', e => {
            for (let i = 0; i < filesListItem.length; i++) {
                filesListItem[i].classList.add('display-none-action-list');
            }
            modalFileName.setAttribute('value', fileBtnWrapper[i].childNodes[1].attributes['data-path-file'].nodeValue);
            fileSection.insertAdjacentElement('afterend', modalFileContainer);
            modalFileContainer.append(modalFile);
            modalFile.append(modalFileBtnClose, modalFileForm);
            modalFileTitle.textContent = 'Déplacer ' + fileBtnWrapper[i].childNodes[1].textContent;
            modalFileForm.append(modalFileName, modalFileTitle, modalFileLabel, modalFileSelect, modalFileBtnSubmit);
            options.forEach(element => {
                modalFileSelect.append(element);
            });
            modalFileBtnClose.addEventListener('click', e => {
                modalFileContainer.remove();
            });
        })
    }

}
if (folderAddBtn) {
    folderAddBtn.addEventListener('click', e => {
        folderAddForm.classList.toggle('toggle-display');
        folderAddInput.value = '';
    })
}


if (folderRemoveBtn) {
    folderListBtn.forEach(element => {
        element.addEventListener('click', e => {
            // vérifie si le menu est caché.
            if (element.nextElementSibling.classList.contains('display-none-action-list')) {
                // si il est caché alors : cache tout les menus
                for (let j = 0; j < folderListBtn.length; j++) {
                    if (folderListItem[j] !== element) {
                        folderListItem[j].classList.add('display-none-action-list');
                    }
                }
                // affiche le menu cliqué
                toggleActionMenu(element.nextElementSibling);
            } else {
                // si le menu n'est pas caché alors :
                // cache le menu
                toggleActionMenu(element.nextElementSibling);
            }
        });
    })


    window.addEventListener('click', (e) => {
        if (!e.target.classList.contains('folder-move-js')) {
            for (let i = 0; i < folderListItem.length; i++) {
                folderListItem[i].classList.add('display-none-action-list');
            };
        };
    });

    // Variables création modal pour déplacer un Dossier
    let modalFolderContainer = document.createElement('div');
    let modalFolder = document.createElement('div');
    let modalFolderBtnClose = document.createElement('button');
    let modalFolderForm = document.createElement('form');
    let modalFolderTitle = document.createElement('p');
    let modalFolderLabel = document.createElement('label');
    let modalFolderSelect = document.createElement('select');
    let modalFolderName = document.createElement('input');
    let modalFolderBtnSubmit = document.createElement('input');

    // Ajout des attributs pour modal Dossier
    modalFolderContainer.classList.add('modal-container');
    modalFolder.classList.add('modal');
    modalFolder.classList.add('folder-move');
    modalFolderBtnClose.setAttribute('type', 'button');
    modalFolderBtnClose.textContent = 'X';
    modalFolderBtnClose.classList.add('close-modal');
    modalFolderForm.setAttribute('action', '/www/cloud/app/views/files/traitement/move_folder.php');
    modalFolderForm.setAttribute('method', 'POST');
    modalFolderTitle.textContent = 'Déplacer un dossier';
    modalFolderLabel.setAttribute('for', 'folder-new-path');
    modalFolderLabel.textContent = 'Choisissez le chemin souhaité :';
    modalFolderSelect.setAttribute('name', 'folder-new-path');
    modalFolderSelect.setAttribute('id', 'folder-new-path');
    modalFolderName.setAttribute('type', 'hidden');
    modalFolderName.setAttribute('name', 'folder-path');
    modalFolderBtnSubmit.setAttribute('type', 'submit');
    modalFolderBtnSubmit.setAttribute('class', 'btn btn--primary');
    modalFolderBtnSubmit.setAttribute('value', 'Déplacer');
    modalFolderOptionRoot = document.createElement('option');
    modalFolderOptionRoot.setAttribute('value', 'racine');
    modalFolderOptionRoot.setAttribute('style', 'color:red');
    modalFolderOptionRoot.innerHTML = 'Racine';

    // Options pour Dossiers
    let options = [];
    options.push(modalFolderOptionRoot);
    folderPath.forEach(element => {
        let modalFolderOption = document.createElement('option');
        modalFolderOption.setAttribute('value', element.dataset.pathFolder);
        modalFolderOption.textContent = element.dataset.pathFolder;
        options.push(modalFolderOption);
    })


    // EventListener affichage modal déplacer un dossier
    for (let i = 0; i < folderListItem.length; i++) {
        folderRemoveBtn[i].addEventListener('click', e => {
            for (let i = 0; i < folderListItem.length; i++) {
                folderListItem[i].classList.add('display-none-action-list');
            }
            modalFolderName.setAttribute('value', folderBtnWrapper[i].childNodes[2].attributes['data-path-folder'].nodeValue);
            fileSection.insertAdjacentElement('afterend', modalFolderContainer);
            modalFolderContainer.append(modalFolder);
            modalFolder.append(modalFolderBtnClose, modalFolderForm);
            modalFolderTitle.textContent = 'Déplacer ' + folderBtnWrapper[i].childNodes[2].textContent;
            modalFolderForm.append(modalFolderName, modalFolderTitle, modalFolderLabel, modalFolderSelect, modalFolderBtnSubmit);
            options.forEach(element => {
                modalFolderSelect.append(element);
            });
            modalFolderBtnClose.addEventListener('click', e => {
                modalFolderContainer.remove();
            });
        })
    }
}

let suggestion = document.querySelector('.suggestion');
let suggestionMobile = document.querySelector('.suggestion-mobile');
let searchInputDesktop = document.querySelector('#desktop_search');
let searchInputMobile = document.querySelector('#nav_search');

if (suggestion) {
    document.addEventListener('DOMContentLoaded', e => {
        searchInputMobile.addEventListener('keyup', e => {
            let query = searchInputMobile.value;

            if (query != '') {
                fetch('/www/cloud/app/views/home/traitement/search.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'query=' + encodeURIComponent(query)
                })
                    .then(function (response) {
                        if (response.ok) {
                            return response.text();
                        }
                        throw new Error('Erreur');
                    })
                    .then(function (data) {
                        suggestionMobile.innerHTML = data;
                    })
                    .catch(function (error) {
                        console.log('Erreur', error);
                    })
            } else {
                suggestionMobile.innerHTML = '';
            }
        })


        searchInputDesktop.addEventListener('keyup', e => {
            let query = searchInputDesktop.value;

            if (query != '') {
                fetch('/www/cloud/app/views/home/traitement/search.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'query=' + encodeURIComponent(query)
                })
                    .then(function (response) {
                        if (response.ok) {
                            return response.text();
                        }
                        throw new Error('Erreur');
                    })
                    .then(function (data) {
                        suggestion.innerHTML = data;
                    })
                    .catch(function (error) {
                        console.log('Erreur', error);
                    })
            } else {
                suggestion.innerHTML = '';
            }
        })
    })
}