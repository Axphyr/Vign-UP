document.addEventListener('DOMContentLoaded', onLoad);

function onLoad() {
    const allNote = document.querySelectorAll("div.note");
    allNote.forEach((note) => {
        const sidebar = note.querySelector('.sidebar_note');
        const data = note.querySelector('span#data').innerHTML;
        const pourcent =
            parseInt(data.substring(0,data.indexOf('/')))
            * 100
            / parseInt(data.substring(data.indexOf('/') + 1,data.length));
        if (note.getAttribute('id') === 'note_total') {
            sidebar
                .setAttribute('style',
                    `background: linear-gradient(90deg, rgb(0, 162, 45) 0%, rgb(3, 253, 0) ${pourcent}%, rgb(203, 203, 203) ${pourcent}%);`
                );
        } else {
            sidebar
                .setAttribute('style',
                    `background: linear-gradient(90deg, rgba(0,0,255,1) 0%, rgba(0,212,255,1) ${pourcent}%, rgb(203, 203, 203) ${pourcent}%);`
                );
        }
    });
}
