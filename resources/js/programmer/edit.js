
document.addEventListener("DOMContentLoaded",()=>{

    document.getElementById('experienceContainer').addEventListener('click', function(e) {
        if(e.target.classList.contains('removeTag')) {
            e.preventDefault();
            let row = e.target.closest('tr');
            let text = row.querySelector('input').value.trim();

            if(text){
                if(confirm(this.dataset.translateRemoveTag.replace(/\${text}/g, text))){    
                    row.remove();
                }
            }else{
                row.remove();
            }
        }
    });

    document.getElementById('additionalLanguageBtn').addEventListener('click', function(e) {
        let table = this.closest('table');
        table.querySelector('tbody').insertAdjacentHTML("beforeend", table.dataset.template);
    });

    if(document.querySelector('#additionalLanguages > tbody').children.length == 0){
        document.getElementById('additionalLanguageBtn').click();
    }

    function addExperience(){
        let container = document.getElementById('experienceContainer');
        let nextKey = container.dataset.nextkey;
        container.insertAdjacentHTML('beforeend', container.dataset.template.replace(/__index1__/g, nextKey));
        container.dataset.nextkey = parseInt(nextKey) + 1;
        container.querySelector('.experience:last-child .additionalFrameworkBtn').click();
    }

    document.getElementById('addExperienceBtn').addEventListener('click', function(e) {
        addExperience();
    });

    document.getElementById('experienceContainer').addEventListener('click', function(e) {
        if(e.target.classList.contains('removeJob')) {
            e.preventDefault();
            let container = e.target.closest('.experience');

            if(confirm(this.dataset.translateRemoveJob)){
                container.remove();
            }
        }
    });

    document.getElementById('experienceContainer').addEventListener('click', function(e) {
        if(e.target.classList.contains('additionalFrameworkBtn')) {
            e.preventDefault();
            let table = e.target.closest('table');
            table.querySelector('tbody').insertAdjacentHTML("beforeend", table.dataset.template);
        }
    });

    if(document.querySelector('#experienceContainer .experience') === null){
        addExperience();
    }

});
