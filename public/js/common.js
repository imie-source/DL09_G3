$(document).ready(function() {
    $('[data-rel="tooltip"]').tooltip({html: true});
    // French
    jQuery.timeago.settings.strings = {
        prefixAgo: "il y a",
        prefixFromNow: "d'ici",
        seconds: "moins d'une minute",
        minute: "une minute",
        minutes: "%d minutes",
        hour: "une heure",
        hours: "%d heures",
        day: "un jour",
        days: "%d jours",
        month: "un mois",
        months: "%d mois",
        year: "un an",
        years: "%d ans"
    };
    $('.timeago').timeago();
});