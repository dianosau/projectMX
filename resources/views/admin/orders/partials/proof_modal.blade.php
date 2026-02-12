<div class="modal fade" id="proofModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">หลักฐานการชำระเงิน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="bg-light rounded-4 p-2 mb-3">
                    <img id="proofImage" src="" class="img-fluid rounded-3 shadow-sm" alt="Payment Proof"
                        style="max-height: 500px;">
                </div>
                <div class="mt-3 p-3 bg-primary bg-opacity-10 rounded-4">
                    <p class="mb-1 text-muted small">ยอดเงินที่ต้องชำระตามออเดอร์</p>
                    <h3 class="fw-bold text-primary mb-0">฿ <span id="proofAmount">0.00</span></h3>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        function showProof(imageUrl, amount) {
            // 1. เปลี่ยนรูปภาพใน Modal
            const proofImg = document.getElementById('proofImage');
            proofImg.src = imageUrl;

            // 2. เปลี่ยนยอดเงิน
            document.getElementById('proofAmount').innerText = amount;

            // 3. เรียกใช้ Modal ของ Bootstrap
            var modalElement = document.getElementById('proofModal');
            var myModal = new bootstrap.Modal(modalElement);
            myModal.show();
        }
    </script>
@endpush