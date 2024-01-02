"use strict"

/** Remove useless months table */
const isMobile = (function () {
    return Math.min(window.screen.width, window.screen.height) < 768 || navigator.userAgent.indexOf("Mobi") > -1
})();

let tableToRemove;
if (isMobile) {
    tableToRemove = document.querySelector('.desktop_tbl');
} else {
    tableToRemove = document.querySelector('.mobile_tbl');
}
if (tableToRemove !== null) tableToRemove.remove();
