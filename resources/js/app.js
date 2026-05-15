/* =========================================================
   LARAVEL BOOTSTRAP FILE
========================================================= */
import './bootstrap';

/* =========================================================
   ALPINE JS
========================================================= */
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/* =========================================================
   BOOTSTRAP 5
========================================================= */
import 'bootstrap';

/* =========================================================
   OPTIONAL GLOBAL HELPERS
========================================================= */

window.showAlert = function(message, type = 'success') {

    const alertBox = document.createElement('div');

    alertBox.className =
        `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow`;

    alertBox.style.zIndex = '9999';

    alertBox.innerHTML = `
        ${message}
        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>
    `;

    document.body.appendChild(alertBox);

    setTimeout(() => {
        alertBox.remove();
    }, 4000);
};

/* =========================================================
   CONFIRM DELETE
========================================================= */

document.addEventListener('DOMContentLoaded', () => {

    const deleteForms = document.querySelectorAll('.delete-form');

    deleteForms.forEach(form => {

        form.addEventListener('submit', function(e) {

            const confirmDelete = confirm(
                'Are you sure you want to delete this record?'
            );

            if(!confirmDelete){
                e.preventDefault();
            }

        });

    });

});

/* =========================================================
   ACTIVE SIDEBAR LINK
========================================================= */

document.addEventListener('DOMContentLoaded', () => {

    const currentUrl = window.location.href;

    const navLinks = document.querySelectorAll('.sidebar .nav-link');

    navLinks.forEach(link => {

        if(currentUrl === link.href){
            link.classList.add('active');
        }

    });

});

/* =========================================================
   TOOLTIP INITIALIZATION
========================================================= */

document.addEventListener('DOMContentLoaded', () => {

    const tooltipTriggerList =
        [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));

    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

});

/* =========================================================
   POPOVER INITIALIZATION
========================================================= */

document.addEventListener('DOMContentLoaded', () => {

    const popoverTriggerList =
        [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));

    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

});

/* =========================================================
   AUTO HIDE ALERTS
========================================================= */

document.addEventListener('DOMContentLoaded', () => {

    const alerts = document.querySelectorAll('.alert-auto-hide');

    alerts.forEach(alert => {

        setTimeout(() => {

            const bsAlert = new bootstrap.Alert(alert);

            bsAlert.close();

        }, 4000);

    });

});

/* =========================================================
   IMAGE PREVIEW
========================================================= */

window.previewImage = function(input, previewId) {

    const preview = document.getElementById(previewId);

    if(input.files && input.files[0]){

        const reader = new FileReader();

        reader.onload = function(e){

            preview.src = e.target.result;

            preview.style.display = 'block';
        };

        reader.readAsDataURL(input.files[0]);
    }
};

/* =========================================================
   LOADING BUTTONS
========================================================= */

document.addEventListener('DOMContentLoaded', () => {

    const loadingButtons = document.querySelectorAll('.btn-loading');

    loadingButtons.forEach(button => {

        button.addEventListener('click', function(){

            this.disabled = true;

            this.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"></span>
                Processing...
            `;
        });

    });

});