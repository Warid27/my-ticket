<script type="module">
import QrScanner from './app/assets/qr-scanner.min.js';
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkinForm');
    const ticketCodeInput = document.getElementById('ticket_code');
    const modalId = 'confirmCheckinModal';
    const modal = new bootstrap.Modal(document.getElementById(modalId));
    const loadingDiv = document.getElementById(modalId + 'Loading');
    const contentDiv = document.getElementById(modalId + 'Content');
    const alertDiv = document.getElementById(modalId + 'Alert');
    const confirmBtn = document.getElementById(modalId + 'Confirm');
    const cancelBtn = document.getElementById(modalId + 'Cancel');

    // QR Scanner elements
    const video = document.getElementById('qr-video');
    const cameraOverlay = document.getElementById('camera-overlay');
    const cameraError = document.getElementById('camera-error');
    const fileInput = document.getElementById('qr-file-input');
    const fileError = document.getElementById('file-error');
    const manualTicketCode = document.getElementById('manual_ticket_code');
    const manualSubmitBtn = document.getElementById('manualSubmitBtn');

    let qrScanner = null;
    let isScanning = false;

    const cameraPermissionOverlay = document.getElementById('camera-permission-overlay');
    const requestCameraPermissionBtn = document.getElementById('requestCameraPermission');

    // Format currency
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    }

    // Reset modal state
    function resetModal() {
        loadingDiv.style.display = 'block';
        contentDiv.style.display = 'none';
        alertDiv.style.display = 'none';
        alertDiv.className = 'alert';
        alertDiv.innerHTML = '';
        confirmBtn.disabled = false;
    }

    // Show error in modal
    function showError(message) {
        loadingDiv.style.display = 'none';
        contentDiv.style.display = 'block';
        alertDiv.style.display = 'block';
        alertDiv.className = 'alert alert-danger';
        alertDiv.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i>' + message;
        confirmBtn.disabled = true;
    }

    // Process ticket code - shared function for all methods
    function processTicketCode(code) {
        if (!code || !code.trim()) {
            return;
        }
        const trimmedCode = code.trim();
        ticketCodeInput.value = trimmedCode;
        resetModal();
        modal.show();

        // Fetch ticket info
        fetch('index.php?page=attendee&action=getTicketInfo&ticket_code=' + encodeURIComponent(trimmedCode))
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    showError(data.message);
                    return;
                }

                // Populate modal fields
                document.getElementById(modalId + 'KodeTiket').textContent = data.data.ticketCode;
                document.getElementById(modalId + 'Event').textContent = data.data.eventName;
                document.getElementById(modalId + 'Tanggal').textContent = data.data.eventDate;
                document.getElementById(modalId + 'Pengunjung').textContent = data.data.userName;
                document.getElementById(modalId + 'Email').textContent = data.data.userEmail;
                document.getElementById(modalId + 'TipeTiket').textContent = data.data.ticketName;
                document.getElementById(modalId + 'Harga').textContent = formatCurrency(data.data.ticketPrice);
                document.getElementById(modalId + 'Qty').textContent = data.data.qty;
                document.getElementById(modalId + 'Subtotal').textContent = formatCurrency(data.data.subtotal);

                loadingDiv.style.display = 'none';
                contentDiv.style.display = 'block';
            })
            .catch(error => {
                showError('Terjadi kesalahan saat memuat data tiket');
                console.error('Error:', error);
            });
    }

    // Initialize QR Scanner for camera
    function initQrScanner() {
        if (qrScanner) {
            return qrScanner;
        }
        qrScanner = new QrScanner(video, result => {
            if (result && result.data) {
                qrScanner.stop();
                isScanning = false;
                processTicketCode(result.data);
            }
        }, {
            highlightScanRegion: true,
            highlightCodeOutline: true,
        });
        return qrScanner;
    }

    // Request camera permission explicitly
    async function requestCameraPermission() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
            stream.getTracks().forEach(track => track.stop());
            return true;
        } catch (err) {
            console.error('Permission denied:', err);
            return false;
        }
    }

    // Check camera permission status
    async function checkCameraPermission() {
        try {
            if (navigator.permissions && navigator.permissions.query) {
                const result = await navigator.permissions.query({ name: 'camera' });
                return result.state;
            }
            return 'prompt';
        } catch (err) {
            return 'prompt';
        }
    }

    // Start camera scanning
    async function startCamera() {
        cameraError.style.display = 'none';
        cameraPermissionOverlay.style.display = 'none';
        
        // Check permission first
        const permissionState = await checkCameraPermission();
        
        if (permissionState === 'prompt') {
            cameraPermissionOverlay.style.display = 'block';
            return;
        }
        
        if (permissionState === 'denied') {
            cameraError.style.display = 'block';
            cameraError.textContent = 'Camera access was denied. Please enable camera permissions in your browser settings or use another method.';
            return;
        }

        cameraOverlay.style.display = 'block';
        
        try {
            const scanner = initQrScanner();
            await scanner.start();
            isScanning = true;
            cameraOverlay.style.display = 'none';
        } catch (err) {
            cameraOverlay.style.display = 'none';
            cameraError.style.display = 'block';
            cameraError.textContent = 'Could not access camera. Please allow camera permissions or use another method.';
            console.error('Camera error:', err);
        }
    }

    // Handle permission request button
    requestCameraPermissionBtn.addEventListener('click', async function() {
        const granted = await requestCameraPermission();
        if (granted) {
            startCamera();
        } else {
            cameraError.style.display = 'block';
            cameraError.textContent = 'Camera access was denied. Please enable camera permissions in your browser settings or use another method.';
        }
    });

    // Stop camera scanning
    function stopCamera() {
        if (qrScanner && isScanning) {
            qrScanner.stop();
            isScanning = false;
        }
    }

    // Manual input handler
    manualSubmitBtn.addEventListener('click', function() {
        processTicketCode(manualTicketCode.value);
    });

    // Allow Enter key on manual input
    manualTicketCode.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            processTicketCode(manualTicketCode.value);
        }
    });

    // Tab switching - manage camera state
    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(e) {
            const targetId = e.target.getAttribute('data-bs-target');
            if (targetId === '#camera-pane') {
                startCamera();
            } else {
                stopCamera();
            }
        });
    });

    // File upload handler
    fileInput.addEventListener('change', async function(e) {
        const file = e.target.files[0];
        if (!file) return;

        fileError.style.display = 'none';

        try {
            const result = await QrScanner.scanImage(file, { returnDetailedScanResult: true });
            if (result && result.data) {
                processTicketCode(result.data);
            } else {
                throw new Error('No QR code found in image');
            }
        } catch (err) {
            fileError.style.display = 'block';
            fileError.textContent = 'Could not read QR code from image. Please try a clearer image.';
            console.error('File scan error:', err);
        }
    });

    // Handle confirm button
    confirmBtn.addEventListener('click', function() {
        form.submit();
    });

    // Handle cancel button
    cancelBtn.addEventListener('click', function() {
        modal.hide();
    });

    // Reset when modal is closed
    document.getElementById(modalId).addEventListener('hidden.bs.modal', function() {
        resetModal();
    });

    // Stop camera when leaving page
    window.addEventListener('beforeunload', function() {
        stopCamera();
    });
});
</script>