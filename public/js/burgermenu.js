document.addEventListener('DOMContentLoaded', onLoad);

function onLoad(){
    const hamburger = document.querySelector(".hamburger");
    const navMenu = document.querySelector("ul.accueil__menu")
    hamburger.addEventListener("click", () => {

        hamburger.classList.toggle("active");
        if(navMenu.className === 'accueil__menu_active'){
            navMenu.className = 'accueil__menu';
        }else{
            navMenu.className = 'accueil__menu_active';
        }

    })

    /*document.querySelectorAll(".nav-link").forEach(n => n.
    addEventListener("click",() => {
        hamburger.classList.remove("active");
        navMenu.classList.remove("active")
    }))*/
}
