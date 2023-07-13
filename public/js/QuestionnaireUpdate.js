const deleteSpan = "<span class=\"material-icons\">delete</span>";
const addSpan = "<span class=\"material-icons\">add_circle</span>";

document.addEventListener('DOMContentLoaded', onLoad);

function onLoad() {
    const buttonQuestionConseil = document.querySelectorAll("div#questionnaire_Questions button, div#questionnaire_Conseils button");
    buttonQuestionConseil.forEach((button) => {
        button.innerHTML += button.className === 'button_add' ? addSpan : deleteSpan;
    });
}

function onChangePartieConnecte() {
    const role_connecte = document.querySelector("div.role_connecte");
    const partieConnecte = document.querySelector("input#questionnaire_partieConnecte");
    const conseil_partieConnecte = document.querySelectorAll("div.conseil_partieConnecte");
    const conseil_categorieQuestion = document.querySelectorAll("div.conseil_categorieQuestion");
    if (partieConnecte.value <= 0) {
        role_connecte.setAttribute("style","visibility: hidden;");
        conseil_partieConnecte.forEach((div) => {
            div.setAttribute("style", "visibility: hidden;");
        });
    } else {
        let partCo = false
        if (partieConnecte.value == 1) {
            partCo = true;
        }
        role_connecte.setAttribute("style","visibility: initial;");
        conseil_partieConnecte.forEach((div) => {
            div.setAttribute("style", "visibility: initial;");
            if (partCo) {
                div.querySelector("input").checked = true;
            }
        });
        if (partCo) {
            conseil_categorieQuestion.forEach((div) => {
                div.querySelector("label").setAttribute("style", "visibility: initial;");
                div.querySelector("select").setAttribute("style", "visibility: initial;");
            });
        }
    }
}

function onChangeRoleConnecte() {
    const role_user = document.querySelector("input#questionnaire_roleConnecte_0");
    const role_prestataire = document.querySelector("input#questionnaire_roleConnecte_1");
    const role_vigneron = document.querySelector("input#questionnaire_roleConnecte_2");
    const role_fournisseur = document.querySelector("input#questionnaire_roleConnecte_3");
    if (role_user.checked) {
        role_prestataire.checked = false;
        role_vigneron.checked = false;
        role_fournisseur.checked = false;
        role_prestataire.disabled = true;
        role_vigneron.disabled = true;
        role_fournisseur.disabled = true;
    } else {
        role_prestataire.disabled = false;
        role_vigneron.disabled = false;
        role_fournisseur.disabled = false;
    }
}

function onChangeConseilPartieConnecte(event) {
    onChangeConseil(event);
    const idInputPartieConnecte = event.target.getAttribute('id').replace('_partieConnecte','');
    const selectConseilCat = document.querySelector("select#" + idInputPartieConnecte + "_categorieQuestion");
    const labelConseilCat = document.querySelector("label[for='" + idInputPartieConnecte + "_categorieQuestion']");
    const partieConnecte = document.querySelector("input#questionnaire_partieConnecte");
    if (partieConnecte.value == 1) {
        event.target.checked = true;
    }
    if (!event.target.checked) {
        selectConseilCat.setAttribute('style', 'visibility: hidden;');
        labelConseilCat.setAttribute('style', 'visibility: hidden;');
    } else {
        selectConseilCat.setAttribute('style', 'visibility: initial;');
        labelConseilCat.setAttribute('style', 'visibility: initial;');
    }
}

function onChangeConseil(event) {
    let txtAide = document.createElement("p");
    txtAide.className = "txtAide";
    const partieConnecte = document.querySelector("input#questionnaire_partieConnecte");
    const conseil = event.currentTarget.closest("div.questionnaire_conseil");
    const noteMinConseil = conseil.querySelector("input[type='number']").value;
    const partieCoConseil = conseil.querySelector("input[type='checkbox']").checked;
    let categorieConseil = conseil.querySelector("select");
    let txtAideConseil = conseil.querySelector("p.txtAide");
    if (!(txtAideConseil != null)) {
        conseil.appendChild(txtAide);
        txtAideConseil = conseil.querySelector("p.txtAide");
    }
    txtAide = "Ce conseil n'est pas valide.";
    if (noteMinConseil !== '' && !isNaN(parseInt(noteMinConseil))) {
        txtAide = "Ce conseil ce déclanchera lorsque : ";
        if (partieCoConseil || partieConnecte.value <= 1) {
            if (partieConnecte.value <= 1) {
                if (partieConnecte.value == 1) {
                    txtAide += `L'utilisateur ayant accès au questionnaire aura obtenu au moins ${noteMinConseil} point(s) `;
                } else {
                    txtAide += `L'utilisateur aura obtenu au moins ${noteMinConseil} point(s) `;
                }
            } else {
                txtAide += `L'utilisateur est sur la partie connecté du questionnaire (ayant les droits pour y accéder) et qu'il aura obtenu au moins ${noteMinConseil} point(s) `;
            }
            if (categorieConseil.value === "") {
                txtAide += "sur sa note global (la somme des points obtenu de toutes les questions.)."
            } else {
                categorieConseil = categorieConseil.querySelector(`option[value="${categorieConseil.value}"]`).innerHTML;
                txtAide += `sur toutes les questions de la catégorie ${categorieConseil}.`;
            }
        } else {
            txtAide += `L'utilisateur est sur la partie NON connecté du questionnaire et qu'il aura obtenu au moins ${noteMinConseil} point(s) sur la totalité des questions de cette partie.`;
        }
    }
    txtAideConseil.innerHTML = txtAide;
}

let indexQuestion = {'index':0,'numero':0};
let indexConseil = {'index':0,'numero':0};
let indexReponse = {};

function clickAddItem(collectionId) {
    let collectionHolder;
    let collectionHolderLast;
    let simpleCollection = true;
    let isQuestion;
    let idQuestion;
    if (collectionId instanceof Event) {
        collectionHolder = document.querySelector('div#' + collectionId.currentTarget.getAttribute('id').replace('_Add','_Reponses'));
        idQuestion = collectionId.currentTarget.getAttribute('id').replace('questionnaire_Questions_','');
        idQuestion = parseInt(idQuestion.replace('_Add',''));
        simpleCollection = false;
    } else {
        collectionHolder = document.querySelector('div' + collectionId);
        isQuestion = collectionId === '#questionnaire_Questions';
    }
    if (indexQuestion['numero'] === 0 || indexQuestion['index'] === 0) {
        indexQuestion['numero'] = collectionHolder.querySelectorAll("div.questionnaire_question").length;
        indexQuestion['index'] = collectionHolder.querySelectorAll("div.questionnaire_question").length;
    }
    if (indexConseil['numero'] === 0 || indexQuestion['index'] === 0) {
        indexConseil['numero'] = collectionHolder.querySelectorAll("div.questionnaire_conseil").length;
        indexConseil['index'] = collectionHolder.querySelectorAll("div.questionnaire_conseil").length;
    }
    let newItem;
    if (simpleCollection) {
        newItem = collectionHolder.dataset.prototype.replace(/__name__/g, isQuestion ? indexQuestion['index'] : indexConseil['index']);
        if (isQuestion) {
            if (indexQuestion['index'] in indexReponse) {
                indexReponse[indexQuestion['index']]['numero'] = 0;
            }
            indexQuestion['numero']++;
            indexQuestion['index']++;
            collectionHolder.innerHTML += newItem.substring(0,75)
                + " "
                + indexQuestion['numero']
                + newItem.substring(75,126)
                + `  value="${indexQuestion['numero']}"  `
                + newItem.substring(126,newItem.length);
            collectionHolderLast = collectionHolder.querySelector(".questionnaire_question:last-child");
            collectionHolderLast.querySelector("button.button_remove").innerHTML += deleteSpan;
            collectionHolderLast.querySelector("button.button_add").innerHTML += addSpan;
        } else {
            indexConseil['numero']++;
            indexConseil['index']++;
            const partieConnecte = document.querySelector('input#questionnaire_partieConnecte').value;
            let endItem;
            if (partieConnecte == 0) {
                endItem = newItem.substring(newItem.indexOf('class="conseil_partieConnecte"') + 50,newItem.length);
            } else {
                endItem = 'initial;"' + newItem.substring(newItem.indexOf('class="conseil_partieConnecte"') + 58,newItem.indexOf('type="checkbox"')) + "checked='checked' " + newItem.substring(newItem.indexOf('type="checkbox"'),newItem.length);
            }
            collectionHolder.innerHTML += newItem.substring(0,69)
                + indexConseil['numero']
                + newItem.substring(69,newItem.indexOf('class="conseil_partieConnecte"') + 50)
                + endItem;
            collectionHolderLast = collectionHolder.querySelector(".questionnaire_conseil:last-child");
            collectionHolderLast.querySelector("button.button_remove").innerHTML += deleteSpan;
        }
    } else {
        if (!(idQuestion in indexReponse)) {
            indexReponse[idQuestion] = {
                'numero': collectionHolder.querySelectorAll("div.questionnaire_reponse").length,
                'index': collectionHolder.querySelectorAll("div.questionnaire_reponse").length,
            };
        }
        newItem = collectionHolder.dataset.prototype.replace(/__name2__/g, indexReponse[idQuestion]['index']);
        indexReponse[idQuestion]['numero']++;
        indexReponse[idQuestion]['index']++;
        collectionHolder.innerHTML += newItem.substring(0,69)
            + indexReponse[idQuestion]['numero']
            + newItem.substring(69,newItem.length);
        collectionHolderLast = collectionHolder.querySelector(".questionnaire_reponse:last-child");
        collectionHolderLast.querySelector("button.button_remove").innerHTML += deleteSpan;
    }
}

function removeItem(event) {
    let collectionId = '#questionnaire_Conseils';
    let questionIndex = null;
    if (event.currentTarget.closest('.questionnaire_question') === null) {
        event.currentTarget.closest('.questionnaire_conseil').remove();
    } else if (event.currentTarget.closest('.questionnaire_reponse') === null) {
        event.currentTarget.closest('.questionnaire_question').remove();
        collectionId = '#questionnaire_Questions';
    } else {
        event.currentTarget.closest('.questionnaire_reponse').remove();
        questionIndex = event.currentTarget.getAttribute('id').replace('questionnaire_Questions_','');
        questionIndex = questionIndex.substring(0,questionIndex.indexOf('_Reponses'));
        collectionId = `#questionnaire_Questions_${questionIndex}_Reponses`;
    }
    reIndex(collectionId,questionIndex);
}

function reIndex(collectionId,questionIndex = null) {
    const collectionHolder = document.querySelector('div' + collectionId);
    let classItem = 'conseil';
    if (collectionId.includes('Questions')) {
        if (collectionId.includes('Reponses')) {
            classItem = 'reponse';
        } else {
            classItem = 'question';
        }
    }
    const item = collectionHolder.querySelectorAll("div.questionnaire_" + classItem);
    if (classItem === 'question') {
        indexQuestion['numero'] = item.length;
    } else if (classItem === 'conseil') {
        indexConseil['numero'] = item.length;
    } else {
        indexReponse[questionIndex]['numero'] = item.length;
    }
    let index = 1;
    item.forEach(item => {
        if (classItem === 'question') {
            item.querySelector('label').innerHTML = "Question numéro " + index;
            item.querySelector('input[type="hidden"]').value = index;
        } else if (classItem === 'conseil') {
            item.querySelector('label').innerHTML = "Conseil n°" + index;
        } else {
            item.querySelector('label').innerHTML = "Réponse n°" + index;
        }
        index++;
    });
}
