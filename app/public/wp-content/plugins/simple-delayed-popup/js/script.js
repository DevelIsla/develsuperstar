document.addEventListener('DOMContentLoaded', function () {
    const overlay = document.getElementById('sdp-overlay');
    const closeBtn = document.getElementById('sdp-close');

    if (!overlay || !closeBtn) {
        return;
    }

    const delay = parseInt(sdpSettings.delay) || 5000;

    setTimeout(function () {
        overlay.classList.remove('sdp-hidden');
    }, delay);

    closeBtn.addEventListener('click', function () {
        overlay.classList.add('sdp-hidden');
    });

    // Close on click outside
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) {
            overlay.classList.add('sdp-hidden');
        }
    });
});
