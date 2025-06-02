import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import 'styles/app.scss';
import 'bootstrap/dist/css/bootstrap.min.css';
import { Toast, Tooltip } from "bootstrap";

let tooltip = function (mutationsList, observer) {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new Tooltip(tooltipTriggerEl));
    tooltipObserver.disconnect();
};

let tooltipObserver = new MutationObserver(tooltip);
tooltipObserver.observe(document, {attributes: false, childList: true, subtree: true});

const toastEl = document.getElementById('toast');
if (toastEl) {
    const toastBody = toastEl.querySelector('.toast-body');
    let toast;
    let toastFunction = function (mutationsList, observer) {
        toast = new Toast(toastEl);

        if (toastBody && toastBody.innerText.length > 0) {
            toast.show();
        }
        toastEl.addEventListener('hidden.bs.toast', () => {
            toastBody.innerHTML = '';
        })
    };

    let toastObserver = new MutationObserver(toastFunction);
    toastObserver.observe(document, {attributes: false, childList: true, subtree: true});
}

const toaster = document.getElementById("toaster") ?? null;
if (toaster) {
    toast.classList.add('text-bg-'+toaster.dataset.type);
    toast.querySelector('i').classList.add(toaster.dataset.icon);
    toast.querySelector('.toast-body').innerText = toaster.innerHTML;
}
