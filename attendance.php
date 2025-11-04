<?php
require_once __DIR__ . '/partials/header.php';

?>

<section class="section">
    <div class="section-header">
        <h2>Scan Attendance</h2>
    </div>

    <div class="scan-container">
        <p>Allow camera access and present a student QR code to the scanner below. Each successful scan will record attendance instantly.</p>
        <div id="reader"></div>
        <div id="scan-result" class="scan-result"></div>
    </div>
</section>

<script src="https://unpkg.com/html5-qrcode@2.3.10/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const resultBox = document.getElementById('scan-result');
    let isProcessing = false;
    let lastToken = '';

    function showResult(message, type = 'success') {
        resultBox.textContent = message;
        resultBox.classList.remove('success', 'error');
        resultBox.classList.add(type);
    }

    async function recordAttendance(token) {
        isProcessing = true;
        try {
            const response = await fetch('record_attendance.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ token })
            });

            const data = await response.json();

            if (data.success) {
                showResult(`Attendance captured for ${data.data.full_name} (${data.data.student_identifier}) at ${data.data.recorded_at_readable}.`, 'success');
            } else {
                showResult(data.message || 'Unable to record attendance.', 'error');
            }
        } catch (error) {
            console.error(error);
            showResult('An unexpected error occurred while recording attendance.', 'error');
        } finally {
            setTimeout(() => {
                isProcessing = false;
            }, 1200);
        }
    }

    function onScanSuccess(decodedText) {
        const token = decodedText.trim();

        if (!token) {
            showResult('Invalid QR token.', 'error');
            return;
        }

        if (isProcessing || token === lastToken) {
            return;
        }

        lastToken = token;
        recordAttendance(token);
    }

    function onScanFailure(error) {
        console.debug('QR scan error', error);
    }

    const html5QrcodeScanner = new Html5QrcodeScanner('reader', {
        fps: 10,
        qrbox: 250,
        rememberLastUsedCamera: true,
        formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE]
    });

    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
});
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

