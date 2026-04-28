<!-- Camera Scan Tab Partial -->
<div class="tab-pane fade" id="camera-pane" role="tabpanel">
    <div class="text-center">
        <div id="camera-container" class="position-relative d-inline-block border rounded" style="max-width: 100%; overflow: hidden;">
            <video id="qr-video" class="d-block" style="max-width: 100%; height: auto; min-height: 300px; background: #000;"></video>
            <div id="camera-overlay" class="position-absolute top-50 start-50 translate-middle text-white" style="display: none;">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 mb-0">Starting camera...</p>
            </div>
            <div id="camera-permission-overlay" class="position-absolute top-50 start-50 translate-middle text-center" style="display: none;">
                <div class="p-4">
                    <i class="bi bi-camera-fill fs-1 text-muted mb-3"></i>
                    <p class="text-muted mb-3">Camera access is required to scan QR codes.</p>
                    <button type="button" class="btn btn-primary" id="requestCameraPermission">
                        <i class="bi bi-camera"></i> Allow Camera Access
                    </button>
                </div>
            </div>
        </div>
        <div id="camera-error" class="alert alert-danger mt-3" style="display: none;"></div>
        <p class="text-muted mt-2">
            <i class="bi bi-info-circle"></i> Point camera at QR code to scan
        </p>
    </div>
</div>
