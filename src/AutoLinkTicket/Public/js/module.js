/**
 * FreeScout setzt in processLinks() alle Thread-Links auf target="_blank".
 * Ticket-Links behalten über data-target ihr gewünschtes target.
 */
function processAutoLinkTicketTargets() {
    $('.thread-content a[data-target]').each(function () {
        $(this).attr('target', $(this).data('target'));
    });
}

$(function () {
    if (typeof window.processLinks === 'function' && !window.processLinks._autolinkWrapped) {
        var originalProcessLinks = window.processLinks;
        window.processLinks = function () {
            originalProcessLinks.apply(this, arguments);
            processAutoLinkTicketTargets();
        };
        window.processLinks._autolinkWrapped = true;
    }

    processAutoLinkTicketTargets();
});
